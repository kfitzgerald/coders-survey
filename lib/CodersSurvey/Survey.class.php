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

namespace CodersSurvey;

/**
 * Survey
 *
 * Implementation of a survey. This is the core module of CodersSurvey.
 */
class Survey extends _SurveyBase {
	use _SurveyIterable;
	
	/**
	 * @var bool
	 */
	private $_has_errors = false;
	
	/**
	 * @var array
	 */
	private $_errors = null;
	
	/**
	 * @var bool
	 */
	private $_closed = null;
	
	
	/**
	 * Error code: missing a mandatory option
	 */
	const ERROR_MISSING_MANDATORY = 1;
	
	/**
	 * Error code: user IP not allowed to take part anymore
	 */
	const ERROR_DENIED = 2;
	
	/**
	 * Constructor
	 *
	 * @param   string   $title   Optional Title or ID. Uses SURVEY_TITLE constant or "Default" if not given
	 * @return  Survey
	 **/
	public function __construct( $survey_title = null ) {
		
		// init db
		$this->_dbInit( SURVEY_DB_DNS, SURVEY_DB_USER, SURVEY_DB_PASSWORD );
		
		// determine title
		if ( is_null( $survey_title ) ) {
			if ( defined( 'SURVEY_TITLE' ) ) $survey_title = SURVEY_TITLE;
			else $survey_title = 'Default';
		}
		else if ( is_numeric( $survey_title ) ) {
			$survey_res = $this->dbFetch(
				'survey_item', 'stype = "survey" AND id = :id', [ ':id' => $survey_title ] );
			if ( @empty( $survey_res ) ) 
				throw new \Exception( "Could not determine from id '$survey_title'" );
			$survey_title = $survey_res[ 'title' ];
		}
		$this->setTitle( $survey_title );
		
		// get survey from db
		$survey_res = $this->dbFetch(
			'survey_item', 'stype = "survey" AND title = :title', [ ':title' => $survey_title ] );
		if ( @empty( $survey_res ) ) {
			$this->dbQuery(
				'INSERT INTO survey_item ( stype, title, position ) '
				. 'VALUES( "survey", ?, ( '
					.'SELECT IFNULL( MAX( position ), 0 ) + 1 FROM survey_item AS si WHERE si.stype = "survey"'
				. ') )',
				[ $survey_title ]
			);
			$this->setId( $this->_db->lastInsertId() );
		}
		else
			$this->setId( $survey_res[ 'id' ] );
		
		// init children
		$this->_initChilds();
		
		// update from session
		if ( !@empty( $_SESSION[ 'SURVEY:'. $this->getId() ] ) )
			$this->updateFromSession();
	}
	
	/**
	 * Updates selected survey data from session
	 *
	 * @param	$_SESSION
	 **/
	public function updateFromSession( $session = null ) {
		if ( is_null( $session ) && ! @empty( $_SESSION[ 'SURVEY:'. $this->getId() ] ) )
			$session = $_SESSION[ 'SURVEY:'. $this->getId() ];
		if ( is_null( $session ) )
			return;
		
		// set selected options
		foreach ( $session[ 'results' ] as $page_id => $topics ) {
			$page = $this->indexedPage( $page_id );
			if ( ! $page ) continue;
			foreach ( $topics as $topic_id => $options ) {
				$topic = $page->indexedTopic( $topic_id );
				if ( ! $topic ) continue;
				foreach ( $options as $option_id => $option_value ) {
					$option = $topic->indexedOption( $option_id );
					if ( ! $option ) continue;
					$option->isSelected( true );
				}
			}
		}
		
		// set errors
		$error_codes = [];
		foreach ( $session[ 'errors' ] as $error_item ) {
			if ( $error_item[ 'type' ] == 'topic' ) {
				$page = $this->indexedPage( $error_item[ 'page_id' ] );
				if ( ! $page ) continue;
				$topic = $page->indexedTopic( $error_item[ 'topic_id' ] );
				if ( ! $topic ) continue;
				$topic->setError( $error_item[ 'code' ] );
			}
			$error_codes[ $error_item[ 'code' ] ] = true;
		}
		
		$this->_has_errors = ! empty( $session[ 'errors' ] );
		$this->_errors = array_keys( $error_codes );
	}
	
	/**
	 * Reads topic by ID
	 *
	 * @param	int		ID of page
	 * @param	int		ID of topic
	 * @return	\CodersSurvey\SurveyTopic
	 **/
	public function indexedTopic( $page_id, $topic_id ) {
		$page = $this->indexedPage( $page_id );
		if ( ! $page ) return null;
		return $page->indexedTopic( $topic_id );
	}
	
	/**
	 * Reads option by ID
	 *
	 * @param	int		ID of page
	 * @param	int		ID of topic
	 * @param	int		ID of option
	 * @return	\CodersSurvey\SurveyOption
	 **/
	public function indexedOption( $page_id, $topic_id, $option_id ) {
		$topic = $this->indexedTopic( $page_id, $topic_id );
		if ( ! $topic ) return null;
		return $topic->indexedOption( $option_id );
	}
	
	/**
	 * Returns bool whether there was a form error or not
	 *
	 * @return	bool
	 **/
	public function hasErrors() {
		return $this->_has_errors;
	}
	
	/**
	 * Checks whether form has a given error code
	 *
	 * @param	int		the error code
	 * @return	bool
	 **/
	public function hasErrorCode( $error_code ) {
		return $this->_has_errors && in_array( $error_code, $this->_errors );
	}
	
	/**
	 * Returns array of error codes (can be empty)
	 *
	 * @return	array
	 **/
	public function getErrorCodes() {
		return $this->_errors;
	}
	
	/**
	 * Returns bool whether survey is closed (over, done)
	 *
	 * @return	bool
	 **/
	public function isClosed() {
		if ( is_null( $this->_closed ) ) {
			$row = $this->dbFetch( 'survey_config', 'stype = "setting" AND name = ?', [ "closed:". $this->getId() ] );
			$this->_closed = ! @empty( $row ) && $row[ 'data' ] == '1';
		}
		return $this->_closed;
	}
	
	/**
	 * Returns bool whether user has finised the survey already (determined from session)
	 *
	 * @return	bool
	 **/
	public function isFinished( $session = null ) {
		if ( is_null( $session ) && ! @empty( $_SESSION[ 'SURVEY:'. $this->getId() ] ) )
			$session = $_SESSION[ 'SURVEY:'. $this->getId() ];
		if ( is_null( $session ) )
			return false;
		return isset( $session[ 'finished' ] ) && $session[ 'finished' ];
	}
	
	/**
	 * Inits survey from given result (or the main result)
	 *
	 * @param	int		ID of survey_result
	 * @return	\CodersSurvey\SurveyResult
	 **/
	public function initResult( $result_id = 0 ) {
		$result;
		if ( $result_id == 0 )
			$result = $this->dbFetch( 'survey_result', 'survey_id = ? AND ref = "main"', [ $this->getId() ] );
		else
			$result = $this->dbFetch( 'survey_result', 'survey_id = ? AND id = ?', [ $this->getId(), $result_id ] );
		if ( empty( $result ) ) return null;
		$this->setCount( $result[ 'count' ] );
		foreach ( unserialize( $result[ 'data' ] ) as $page_id => $page_ref ) {
			$page = $this->indexedPage( $page_id );
			if ( ! $page ) continue;
			$page->setCount( $page_ref[ 'count' ] );
			foreach ( $page_ref[ 'topics' ] as $topic_id => $topic_ref ) {
				$topic = $page->indexedTopic( $topic_id );
				if ( ! $topic ) continue;
				$topic->setCount( $topic_ref[ 'count' ] );
				foreach ( $topic_ref[ 'options' ] as $option_id => $option_count ) {
					$option = $topic->indexedOption( $option_id );
					if ( ! $option ) continue;
					$option->setCount( $option_count );
				}
			}
		}
		return new SurveyResult( $this, $result );
	}
	
	/**
	 * Returns all Available results
	 *
	 * @param	int		ID of survey_result
	 * @return	\CodersSurvey\SurveyResult
	 **/
	public function getResults() {
		$db_results = $this->dbFetchAll( 'survey_result', 'survey_id = ? ORDER BY position ASC', [ $this->getId() ] );
		$results = [];
		foreach ( $db_results as $db_res )
			$results []= new SurveyResult( $this, $db_res );
		return $results;
	}
}

