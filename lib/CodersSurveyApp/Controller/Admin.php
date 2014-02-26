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

/**
 * Admin Controller
 *
 * Implements controller logic for admin
 */
class Admin extends _Controller {
	
	
	/**
	 * Cecks whether logged in as admin, inits surveys
	 */
	public function check() {
		if ( ! $this->session( 'admin' ) )
			return false;
		$surveys = $this->s()->dbFetchAll( 'survey_item', 'stype = "survey" ORDER BY title ASC', [] );
		$this->a()->view()->setData( 'surveys', $surveys );
		return true;
	}
	
	
	/**
	 * GET: display login or admin screen
	 * POST: login
	 */
	public function start() {
		if ( !@empty( $_POST ) ) {
			$username = $_POST[ 'data' ][ 'username' ];
			$password = $_POST[ 'data' ][ 'password' ];
			$crypted = crypt( $password, '$6$'. md5( $username ). '$' );
			$res = $this->s()->dbFetch(
				'survey_config', 'stype = "auth" AND name = ? AND data = ?', [ $username, $crypted ] );
			if ( !@empty( $res ) ) {
				$this->session( 'admin', $username );
				$this->a()->flash( 'success', 'Welcome' );
			}
			else {
				$this->a()->flash( 'error', 'Login failed' );
			}
			return  $this->a()->redirect('/pregzilla/polls/admin');
		}
		
		if ( $this->check() ) {
			$this->a()->render( 'Admin/admin.php' );
		}
		else
			$this->a()->render( 'Admin/login.php' );
	}
	
	/**
	 * Toggle closed-state for survey
	 */
	public function toggleState() {
		$existing = $this->s()->dbFetch( 'survey_config', 'stype = "setting" AND name = ?',
			[ "closed:". $this->s()->getId() ] );
		if ( $existing ) {
			$this->a()->flash( 'success', 'Survey "'. $this->s()->getTitle(). '" now running.' );
			$this->s()->dbQuery(
				'DELETE FROM survey_config WHERE stype = "setting" AND name = ?',
				[ "closed:". $this->s()->getId() ] );
		}
		else {
			$this->a()->flash( 'success', 'Survey "'. $this->s()->getTitle(). '" now closed.' );
			$this->s()->dbQuery(
				'INSERT INTO survey_config ( stype, name, data ) VALUES ( "setting", ?, "1" )',
				[ "closed:". $this->s()->getId() ] );
		}
		return  $this->a()->redirect('/pregzilla/polls/admin');
	}
	
	
	/**
	 * GET: display password form
	 * POST: update password
	 */
	public function changePassword() {
		
		if ( ! @empty( $_REQUEST[ 'data' ] ) ) {
			$username = @trim( $_REQUEST[ 'data' ][ 'username_new' ] );
			$password = @trim( $_REQUEST[ 'data' ][ 'password_new' ] );
			if ( empty( $username ) || empty( $password ) ) {
				$this->a()->flash( 'error', 'Username and password required' );
				return  $this->a()->redirect( '/pregzilla/polls/admin/change-password' );
			}
			if ( $password != $_REQUEST[ 'data' ][ 'password_repeat' ] ) {
				$this->a()->flash( 'error', 'Password mismatch' );
				return  $this->a()->redirect( '/pregzilla/polls/admin/change-password' );
			}
			
			$this->s()->dbQuery(
				'UPDATE survey_config SET name = ?, data = ? WHERE stype = "auth" AND name = ?',
				[ $username, crypt( $password, '$6$'. md5( $username ). '$' ), $this->session( 'admin' ) ]
			);
			$this->a()->flash( 'success', 'Username and password updated' );
			return  $this->a()->redirect( '/pregzilla/polls/admin' );
		}
		
		$surveys = $this->s()->dbFetchAll( 'survey_item', 'stype = "survey" ORDER BY title ASC', [] );
		$this->a()->view()->setData( 'surveys', $surveys );
		$this->a()->view()->setData( 'username', $this->session( 'admin' ) );
		$this->a()->render( 'Admin/change_password.php' );
	}
	
	
	/**
	 * Generate results survey results, writes into DB
	 */
	public function generateResults() {
		// get grouped
		$all_result = [
			'main'	=> [
				'results'	=> [],
				'position'	=> '0',
				'count'		=> 0
			],
		];
		if ( ! @empty( $_REQUEST[ 'data' ] ) )
			foreach ( $_REQUEST[ 'data' ] as $page_id => $topics ) {
				$page = $this->s()->indexedPage( $page_id );
				if ( ! $page ) continue;
				foreach ( $topics as $topic_id => $ok ) {
					$topic = $page->indexedTopic( $topic_id );
					if ( ! $topic ) continue;
					foreach ( $topic->getAllOption() as $option )
						$all_result[ 'grouped:'. $page_id. ':'. $topic_id. ':'. $option->getId() ] = [
							'results'	=> [],
							'position'	=> sprintf( '%06d:%06d:%06d',
								$page->getPosition(), $topic->getPosition(), $option->getPosition() ),
							'count'		=> 0
						];
				}
			}
		
		// read all walkthroughs
		$count = $this->s()->dbCount( 'survey_walkthrough', 'survey_id = ?', [ $this->s()->getId() ] );
		$start = isset( $_REQUEST[ 'start' ] ) && is_numeric( $_REQUEST[ 'start' ] ) > 0
			? (int)$_REQUEST[ 'start' ]
			: 0;
		$walks = $this->s()->dbFetchAll( 'survey_walkthrough',
			'survey_id = ? ORDER BY id ASC LIMIT '. $start. ', 99999', [ $this->s()->getId() ] );
		
		// build results
		$seen_result = [];
		foreach ( $walks as $item ) {
			$user_results = unserialize( $item[ 'data' ] );
			
			foreach ( $all_result as $result_name => &$result ) {
				if ( $result_name != 'main' ) {
					list( $x, $req_page_id, $req_topic_id, $req_option_id ) = preg_split( '/:/', $result_name );
					if ( ! isset( $user_results[ $req_page_id ] )
						|| ! isset( $user_results[ $req_page_id ][ $req_topic_id ] )
						|| ! isset( $user_results[ $req_page_id ][ $req_topic_id ][ $req_option_id ] )
					) continue;
				}
				
				$pref = &$result[ 'results' ];
				foreach ( $user_results as $page_id => $topics ) {
					if ( ! isset( $pref[ $page_id ] ) )
						$pref[ $page_id ] = [
							'topics'	=> [],
							'count'		=> 0
						];
					$tref = &$pref[ $page_id ][ 'topics' ];
					
					foreach ( $topics as $topic_id => $option_ref ) {
						if ( @empty( $option_ref ) )
							continue;
						if ( ! isset( $tref[ $topic_id ] ) )
							$tref[ $topic_id ] = [
								'options'	=> [],
								'count'		=> 0
							];
						
						$tref[ $topic_id ][ 'count' ]++;
						$pref[ $page_id ][ 'count' ]++;
						$result[ 'count' ]++;
						$oref = &$tref[ $topic_id ][ 'options' ];
						
						foreach ( $option_ref as $option_id => $other_value ) {
							if ( ! isset( $oref[ $option_id ] ) )
								$oref[ $option_id ] = 0;
							$oref[ $option_id ]++;
						}
					}
				}
			}
		}
		
		// remove existing ..
		$this->s()->dbQuery( 'DELETE FROM survey_result WHERE survey_id = ?', [ $this->s()->getId() ] );
		
		// write results
		foreach ( $all_result as $result_name => &$result ) {
			
			// create new
			$this->s()->dbQuery(
				'INSERT INTO survey_result ( survey_id, ref, position, data, count ) '
				. 'VALUES ( :survey_id, :ref, :position, :data, :count )',
				[
					':survey_id'	=> $this->s()->getId(),
					':ref'			=> $result_name,
					':position'		=> $result[ 'position' ],
					':data'			=> serialize( $result[ 'results' ] ),
					':count'		=> $result[ 'count' ],
				]
			);
		}
		
		// write results
		$response = $this->a()->response();
		$response[ 'Content-Type' ] = 'text/plain';
		print json_encode( [ 'ok' => 1 ] );
	}
	
	
	/**
	 * Modify page position in survey
	 */
	public function saveSurvey() {
		$pages = $this->s()->dbFetchAll( 'survey_item', 'stype = "page" and parent_id = ?', [ $this->s()->getId() ] );
		foreach ( $pages as $page ) {
			$new_pos = @isset( $_POST[ 'data' ][ 'page' ][ $page[ 'id' ] ][ 'position' ] )
				? $_POST[ 'data' ][ 'page' ][ $page[ 'id' ] ][ 'position' ]
				: $page[ 'position' ];
			if ( $new_pos != $page[ 'position' ] )
				$this->s()->dbQuery( 'UPDATE survey_item SET position = ? WHERE id = ?', [ $new_pos, $page[ 'id' ] ] );
		}
		$this->a()->flash( 'success', 'Page order has been saved' );
		$this->a()->redirect('/pregzilla/polls/admin');
	}
	
	
	/**
	 * Display page edit / create form
	 */
	public function editPage( $page_id ) {
		$page = null;
		if ( is_numeric( $page_id ) )
			$page = $this->s()->dbFetch( 'survey_item', 'stype = "page" AND id = ?', [ $page_id ] );
		if ( @empty( $page ) ) {
			$page = [
				'id'        => 'new',
				'title'     => '',
				'parent_id' => $this->s()->getId(),
				'stype'     => 'page',
				'position'  => 0
			];
		}
		$this->a()->view()->setData( 'survey_page', $page );
		$this->a()->render( 'Admin/edit_page.php' );
	}
	
	
	/**
	 * Save modified / new page
	 */
	public function savePage( $page_id ) {
		$page = null;
		if ( ! is_null( $page_id ) && is_numeric( $page_id ) )
			$page = $this->s()->dbFetch( 'survey_item', 'stype = "page" AND id = ?', [ $page_id ] );
		
		/// create
		$created = 0;
		if ( @empty( $page ) ) {
			$created = 1;
			$sth = $this->s()->dbQuery(
				'INSERT INTO survey_item ( stype, parent_id, title, position )'
				. 'VALUES ( "page", :parent_id, :title, ( '
					. 'SELECT IFNULL( MAX( position ), 0 ) + 1 FROM survey_item AS si WHERE si.stype = "page" AND si.parent_id = :parent_id'
				. ' ) )',
				[
					':parent_id' => $_POST[ 'data' ][ 'page' ][ 'parent_id' ],
					':title'     => trim( strip_tags( $_POST[ 'data' ][ 'page' ][ 'title' ] ) )
				]
			);
			$page_id = $this->s()->dbLastId();
			$page = $this->s()->dbFetch( 'survey_item', 'id = ?', [ $page_id ] );
		}
		
		// update
		else {
			$sth = $this->s()->dbQuery(
				'UPDATE survey_item SET title = :title WHERE id = :id',
				[
					':id'    => $page_id,
					':title' => $_POST[ 'data' ][ 'page' ][ 'title' ]
				]
			);
			
			// write existing topic positions
			$topics = $this->s()->dbFetchAll( 'survey_item', 'parent_id = ? AND stype = "topic"', [ $page_id ] );
			foreach ( $topics as $topic ) {
				if ( ! isset( $_POST[ 'data' ][ 'topic' ][ $topic[ 'id' ] ] )
					|| @empty( $_POST[ 'data' ][ 'topic' ][ $topic[ 'id' ] ][ 'position' ] ) ) 
					continue;
				else {
					$this->s()->dbQuery(
						'UPDATE survey_item SET position = ? WHERE id = ?',
						[
							$_POST[ 'data' ][ 'topic' ][ $topic[ 'id' ] ][ 'position' ],
							$topic[ 'id' ]
						]
					);
				}
			}
		}
		
		$this->a()->flash( 'success', 'Page "'. htmlentities( $page[ 'title' ] )
			. '" has been '. ( $created ? 'created' : 'saved' ) );
		return $this->a()->redirect( '/pregzilla/polls/admin' );
	}
	
	
	/**
	 * Delete page and topics (and options) within
	 */
	public function deletePage( $id ) {
		$this->s()->dbQuery(
			'DELETE FROM survey_item WHERE stype = "page" AND id = ?', [ $id ] );
		$topics = $this->s()->dbFetchAll( 'survey_item', 'stype = "topic" AND parent_id = ? ', [ $id ] );
		$this->s()->dbQuery( 'DELETE FROM survey_item WHERE stype = "topic" AND parent_id = ?', [ $id ] );
		foreach ( $topics as $topic ) {
			$this->s()->dbQuery( 'DELETE FROM survey_item WHERE stype = "option" AND parent_id = ?', [ $topic[ 'id' ] ] );
		}
		$this->a()->flash( 'success', 'Page deleted' );
		$this->a()->redirect( '/pregzilla/polls/admin' );
	}
	
	
	/**
	 * Display topic edit / create form
	 */
	public function editTopic( $topic_id = null, $page_id = null ) {
		$topic = null;
		if ( ! is_null( $topic_id ) && is_numeric( $topic_id ) )
			$topic = $this->s()->dbFetch( 'survey_item', 'stype = "topic" AND id = ?', [ $topic_id ] );
		if ( @empty( $topic ) ) {
			$topic = [
				'id'        => 'new',
				'title'     => '',
				'parent_id' => $page_id,
				'stype'     => 'topic',
				'position'  => 0,
				'data'      => [
					'mandatory' => 0,
					'other'     => 0,
					'type'      => 'radio'
				]
			];
		}
		else {
			$topic[ 'data' ] = array_merge( [
				'mandatory' => 0,
				'other'     => 0,
				'type'      => 'radio'
			], json_decode( $topic[ 'data' ], true ) );
		}
		$this->a()->view()->setData( 'survey_topic', $topic );
		$this->a()->render( 'Admin/edit_topic.php' );
	}
	
	
	/**
	 * Save modified / new topic into DB
	 */
	public function saveTopic( $topic_id ) {
		$topic = null;
		if ( ! is_null( $topic_id ) && is_numeric( $topic_id ) )
			$topic = $this->s()->dbFetch( 'survey_item', 'stype = "topic" AND id = ?', [ $topic_id ] );
		
		/// create
		$created = 0;
		if ( @empty( $topic ) ) {
			$create = 1;
			$sth = $this->s()->dbQuery(
				'INSERT INTO survey_item ( stype, parent_id, title, data, position )'
				. 'VALUES ( "topic", :parent_id, :title, :data, ( '
					. 'SELECT IFNULL( MAX( position ), 0 ) + 1 FROM survey_item AS si WHERE si.stype = "topic" AND si.parent_id = :parent_id'
				. ' ) )',
				[
					':parent_id' => $_POST[ 'data' ][ 'topic' ][ 'parent_id' ],
					':title'     => trim( strip_tags( $_POST[ 'data' ][ 'topic' ][ 'title' ] ) ),
					':data'      => json_encode( array_merge(
						[
							'mandatory' => 0,
							'other'     => 0,
							'type'      => 'radio'
						],
						$_POST[ 'data' ][ 'topic' ][ 'data' ]
					) )
				]
			);
			$topic_id = $this->s()->dbLastId();
			$topic = $this->s()->dbFetch( 'survey_item', 'id = ?', [ $topic_id ] );
		}
		
		// update
		else {
			$sth = $this->s()->dbQuery(
				'UPDATE survey_item SET title = :title, data = :data WHERE id = :id',
				[
					':id'    => $topic_id,
					':title' => $_POST[ 'data' ][ 'topic' ][ 'title' ],
					':data'  => json_encode( array_merge(
						[
							'mandatory' => 0,
							'other'     => 0,
							'type'      => 'radio'
						],
						$_POST[ 'data' ][ 'topic' ][ 'data' ]
					) )
				]
			);
			
			// write existing
			$options = $this->s()->dbFetchAll( 'survey_item', 'parent_id = ? AND stype = "option"', [ $topic_id ] );
			foreach ( $options as $option ) {
				if ( ! isset( $_POST[ 'data' ][ 'option' ][ $option[ 'id' ] ] )
					|| @empty( $_POST[ 'data' ][ 'option' ][ $option[ 'id' ] ][ 'title' ] ) ) {
					$this->s()->dbQuery( 'DELETE FROM survey_item WHERE id = ?', [ $option[ 'id' ] ] );
				}
				else {
					$this->s()->dbQuery(
						'UPDATE survey_item SET title = ?, position = ? WHERE id = ?',
						[
							$_POST[ 'data' ][ 'option' ][ $option[ 'id' ] ][ 'title' ],
							$_POST[ 'data' ][ 'option' ][ $option[ 'id' ] ][ 'position' ],
							$option[ 'id' ]
						]
					);
				}
			}
		}
		
		
		foreach ( range( 1, 20 ) as $idx ) {
			if ( ! empty( $_POST[ 'data' ][ 'option' ][ 'new:'. $idx ] )
				&& ! empty( $_POST[ 'data' ][ 'option' ][ 'new:'. $idx ][ 'title' ] ) ) {
				$title = $_POST[ 'data' ][ 'option' ][ 'new:'. $idx ][ 'title' ];
				$position = $_POST[ 'data' ][ 'option' ][ 'new:'. $idx ][ 'position' ];
				$this->s()->dbQuery(
					'INSERT INTO survey_item ( stype, parent_id, title, position )'
					. 'VALUES ( "option", :parent_id, :title, :position )',
					[
						':parent_id' => $topic_id,
						':title'     => trim( strip_tags( $title ) ),
						':position'  => $position,
					]
				);
			}
		}
		
		$this->a()->flash( 'success', 'Topic "'. htmlentities( $topic[ 'title' ] )
			. '" has been '. ( $created ? 'created' : 'saved' ) );
		return $this->a()->redirect( '/pregzilla/polls/admin/page/'. $topic[ 'parent_id' ] );
	}
	
	
	/**
	 * Remove topic and child options from DB
	 */
	public function deleteTopic( $id ) {
		$topic = $this->s()->dbFetch( 'survey_item', 'stype = "topic" AND id = ?', [ $id ] );
		$this->s()->dbQuery( 'DELETE FROM survey_item WHERE stype = "topic" AND id = ?', [ $id ] );
		$this->s()->dbQuery( 'DELETE FROM survey_item WHERE stype = "option" AND parent_id = ?', [ $id ] );
		$this->a()->flash( 'success', 'Topic "'. htmlentities( $topic[ 'title' ] ). '" deleted' );
		$this->a()->redirect( '/pregzilla/polls/admin/page/'. $topic[ 'parent_id' ] );
	}
	
	
	/**
	 * Switch to given Survey 
	 */
	public function switchSurvey() {
		$id = $_POST[ 'data' ][ 'survey' ][ 'id' ];
		try {
			if ( $id == '__NEW__' )
				$id = $_POST[ 'data' ][ 'survey' ][ 'title' ];
			$test_survey = new \CodersSurvey\Survey( $id );
			$this->session( 'survey_id', $test_survey->getId() );
			$this->a()->flash( 'success', 'Switch to "'. htmlentities( $test_survey->getTitle() ). '"' );
		}
		catch ( \Exception $e ) {
			error_log( "Error switching survey: '$e'" );
			$this->a()->flash( 'error', 'Cannot switch to non existing survey' );
		}
		$this->a()->redirect('/pregzilla/polls/admin');
	}
	
	
	/**
	 * Logout from admin
	 */
	public function logout() {
		$this->session( 'admin', 0 );
		$this->a()->flash( 'success', 'Good bye' );
		$this->a()->redirect('/pregzilla/polls/admin');
	}
}
