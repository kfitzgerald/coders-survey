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
 * Survey Base class
 * 
 * Base class of all survey components (survey, page, topic, option)
 */
abstract class _SurveyBase {
	use _SurveyMeta, _SurveySumCount, _SurveyDatabase;
	
	
	/**
	 * General constructor
	 *
	 * @param   object   survey, page or topic
	 * @param   array   database row
	 **/
	public function __construct( &$parent, $db_row ) {
		$this->_parent = $parent;
		$this->setTitle( $db_row[ 'title' ] );
		$this->setId( $db_row[ 'id' ] );
		$this->setPosition( $db_row[ 'position' ] );
		
		if ( $this->_getChildType() )
			$this->_initChilds();
	}
	
	
	/**
	 * Returns the type of the object as "survey", "page", "topic" or "option"
	 *
	 * @param string
	 **/
	protected function _getMyType() {
		$type = strtolower( preg_replace( '/^.+Survey/', '', get_class( $this ) ) );
		return $type ? $type : 'survey';
	}
	
	
	/**
	 * Returns the type of children objects ("page", "topic" or "option") or null
	 *
	 * @param string
	 **/
	protected function _getChildType() {
		switch( $this->_getMyType() ) {
			case 'survey':
				return 'page';
			case 'page':
				return 'topic';
			case 'topic':
				return 'option';
			default:
				return null;
		}
	}
	
	
	/**
	 * Reads childerens from database
	 **/
	protected function _initChilds() {
		$child_type = $this->_getChildType();
		$childs = $this->dbQuery(
			'SELECT * FROM survey_item WHERE stype = ? AND parent_id = ? ORDER BY position ASC',
			[ $child_type, $this->getId() ]
		)->fetchAll( \PDO::FETCH_ASSOC );
		$child_class = '\\CodersSurvey\\Survey'. ucfirst( $child_type );
		$add_method = 'add'. ucfirst( $child_type );
		$add_grouped_method = 'addGrouped'. ucfirst( $child_type );
		foreach ( $childs as $child )  {
			$obj = new $child_class( $this, $child );
			$this->$add_method( $obj, $obj->getId() );
			if ( method_exists( $obj, 'groupBy' ) && $obj->groupBy() )
				$this->$add_grouped_method( $obj );
		}
	}
}