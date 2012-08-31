<?php
/**
 * Coder's Survey
 *
 * @author      Ulrich Kautz <uk@fortrabbit.de>
 * @copyright   2012 Ulrich Kautz
 * @link        http://coderssurvey.com
 * @version     1.0.0
 * @package     CodersSurvey
 *
 * Playing around with 5.4..
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace CodersSurveyApp\Controller;
use \CodersSurvey\SurveyException;

/**
 * Home Controller
 *
 * Implements controller logic for frontend
 */
class Home extends _Controller {
	
	
	/**
	 * Display:
	 *   Results, if survey closed
	 *   Thank-you page, if user finished survey
	 *   Survey, if none of the above
	 */
	public function start() {
		//$this->a()->view()->setData( 'survey', $this->s() );
		if ( $this->s()->isClosed() )
			$this->a()->render( 'Frontend/results.php' );
		else if ( $this->s()->isFinished() )
			$this->a()->render( 'Frontend/thank-you.php' );
		else
			$this->a()->render( 'Frontend/index.php' );
	}
	
	
	/**
	 * Completes survey by writing user's choices into database
	 */
	public function complete() {
		if ( ! $this->s()->isClosed() ) {
			try {
				$result = $this->parseRequest( $_POST[ 'data' ], true );
				$this->saveResult( $result, true );
			}
			catch ( \Exception $e ) {
				error_log( ' -- error: '. print_r( $e, true ) );
			}
		}
		$this->a()->redirect('/');
	}
	
	
	/**
	 * Render survey results (for AJAX load)
	 */
	public function loadResults( $result_id = 0 ) {
		$survey_result = $this->s()->initResult( $result_id );
		$this->a()->view()->setData( 'survey_result', $survey_result );
		$this->a()->render( 'Snippets/results.php' );
	}
	
	
	/**
	 * Parses survey request after survey has been finished by user.
	 *
	 * @param   array   $request   Eg $_POST[ 'data' ]
	 * @return  array   Result object, used for saveResult method
	 **/
	private function parseRequest( $request, $save_to_session = false ) {
		$results = [];
		$errors = [];
		$seen = [];
		$survey = $this->s();
		
		// parse request into results array
		foreach ( $request as $page_id => $topics ) {
			$page = $survey->indexedPage( $page_id );
			if ( ! $page ) continue;
			$seen[ $page_id ] = [];
			if ( ! isset( $results[ $page_id ] ) )
				$results[ $page_id ] = [];
			$pref = &$results[ $page_id ];
			
			foreach ( $topics as $topic_id => $option_ids ) {
				$topic = $page->indexedTopic( $topic_id );
				if ( ! $topic ) continue;
				$seen[ $page_id ][ $topic_id ] = [];
				if ( ! isset( $pref[ $topic_id ] ) )
					$pref[ $topic_id ] = [];
				$tref = &$pref[ $topic_id ];
				
				if ( ! is_array( $option_ids ) )
					$option_ids = [ $option_ids ];
				foreach ( $option_ids as $option_id ) {
					$option = $topic->indexedOption( $option_id );
					if ( ! $option ) continue;
					$seen[ $page_id ][ $topic_id ][ $option_id ] = true;
					$tref[ $option_id ] = '';
					/*$results []= [
						'page_id'     => $page_id,
						'topic_id'    => $topic_id,
						'option_id'   => $option_id,
						'other_value' => $option_id == '__other__' && !@empty( $topics[ $topic_id. ':other' ] )
							? trim( strip_tags( $topics[ $topic_id. ':other' ] ) )
							: null
					];*/
				}
			}
		}
		
		// check for option errors
		foreach ( $survey->getAllPage() as $page ) {
			foreach( $page->getAllTopic() as $topic ) {
				if ( $topic->isMandatory() && (
					! isset( $seen[ $page->getId() ] )
					|| ! isset( $seen[ $page->getId() ][ $topic->getId() ] )
					|| empty( $seen[ $page->getId() ][ $topic->getId() ] )
				) ) {
					$errors []= [
						'type'       => 'topic',
						'page_id'    => $page->getId(),
						'topic_id'   => $topic->getId(),
						'code'       => self::ERROR_MISSING_MANDATORY
					];
				}
			}
		}
		
		// check for denied errors
		if ( defined( 'SURVEY_MAX_PER_IP') ) {
			$count = $survey->dbCount(
				'survey_walkthrough', 'ip = ? AND survey_id = ? AND created + ? > CURRENT_TIMESTAMP',
				[ $this->_userIp(), $survey->getId(), SURVEY_IP_TIMEFRAME_SEC ]
			);
			if ( ! empty( $check_res ) && $check_res[ 0 ] >= SURVEY_MAX_PER_IP )
				$errors []= [
					'type' => 'survey',
					'code' => self::ERROR_DENIED
				];
		}
		
		// save to session?
		if ( $save_to_session ) {
			if ( ! isset( $_SESSION[ 'SURVEY:'. $survey->getId() ] ) )
				$_SESSION[ 'SURVEY:'. $survey->getId() ] = [ 'finished' => false ];
			$_SESSION[ 'SURVEY:'. $survey->getId() ][ 'results' ] = $results;
			$_SESSION[ 'SURVEY:'. $survey->getId() ][ 'errors' ] = $errors;
		}
		
		// got errors -> done
		if ( ! empty( $errors ) )
			throw new SurveyException( $errors );
		
		return $results;
	}
	
	
	/**
	 * Save results into database
	 *
	 * @param   array   $results
	 * @param   bool    $save_to_session Whether finish state should be set in session
	 **/
	private function saveResult( array $results, $save_to_session = false ) {
		
		// write user walkthrough
		$this->s()->dbQuery(
			'INSERT INTO survey_walkthrough ( survey_id, data, ip ) VALUES ( ?, ?, ? )',
			[ $this->s()->getId(), serialize( $results ), $this->_userIp() ] );
		
		// save finish status
		if ( $save_to_session )
			$_SESSION[ 'SURVEY:'. $this->s()->getId() ][ 'finished' ] = true;
	}
	
	
	/**
	 * Returns IP (or MD5 if disguise enabled) of the current user.
	 *
	 * @return  string   IP or MD5
	 **/
	private function _userIp() {
		return defined( 'SURVEY_DISGUISE_USER_IP' ) && SURVEY_DISGUISE_USER_IP
			? md5( $_SERVER[ 'REMOTE_ADDR'] )
			: $_SERVER[ 'REMOTE_ADDR'];
	}
}