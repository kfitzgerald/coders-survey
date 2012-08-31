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
 * Survey Topic
 * 
 * Represents a topic of a page.
 *
 * Each title has many options. Each page has many titles. Each survey has many pages.
 */
class SurveyTopic extends _SurveyBase {
	use _SurveyIterable, _SurveyOtherValue;
	
	/**
	 * @var Type of topic (checkbox, radio)
	 **/
	private $_type;
	
	/**
	 * @var Whether topic is mandatory
	 **/
	private $_mandatory = false;
	
	/**
	 * @var Whether topic has other field (ALPHA)
	 **/
	private $_has_other = false;
	
	/**
	 * @var Whether is grouped by this topic
	 **/
	private $_group_by = false;
	
	/**
	 * @var In result context: Some error string
	 **/
	private $_error = null;
	
	
	/**
	 * Constructor
	 *
	 * @param   \CodersSurvey\SurveyPage   parent page
	 * @param   array   The database row
	 **/
	public function __construct( &$page, $db_row ) {
		parent::__construct( $page, $db_row );
		$config = json_decode( $db_row[ 'data' ], true );
		$this->_type = $config[ 'type' ];
		if ( isset( $config[ 'other' ] ) && $config[ 'other' ] )
			$this->_has_other = true;
		if ( isset( $config[ 'mandatory' ] ) && $config[ 'mandatory' ] )
			$this->_mandatory = true;
		if ( isset( $config[ 'grouped' ] ) && $config[ 'grouped' ] )
			$this->_group_by = true;
	}
	
	
	/**
	 * Whether has other field (ALPHA)
	 *
	 * @return   bool
	 **/
	public function hasOther() {
		return $this->_has_other;
	}
	
	
	/**
	 * Whether has error (in result context)
	 *
	 * @result   bool
	 **/
	public function hasError() {
		return ! is_null( $this->_error );
	}
	
	
	/**
	 * If has error, returns error string (result context)
	 *
	 * @return   string
	 **/
	public function getError() {
		return $this->_error;
	}
	
	
	/**
	 * Set result error
	 *
	 * @param   string   Some error
	 * @return   string
	 **/
	public function setError( $error ) {
		return $this->_error = $error;
	}
	
	
	/**
	 * Whether mandatory
	 *
	 * @return   bool
	 **/
	public function isMandatory() {
		return $this->_mandatory;
	}
	
	
	/**
	 * Whether group by
	 *
	 * @return   bool
	 **/
	public function groupBy() {
		return $this->_group_by;
	}
	
	
	/**
	 * Returns name for input in the form "data[<page-id>][<topic-id>]"
	 *
	 * @return   string
	 **/
	public function getReqName( $force_single = false ) {
		$page_id  = $this->getParent()->getId();
		$topic_id = $this->getId();
		return 'data['. $page_id. ']['. $topic_id. ']'
			. ( ! $force_single && method_exists( $this, 'getType' ) && $this->getType() != 'radio' ? '[]' : '' );
	}
	
	
	/**
	 * Returns name for other-field input in the form "data[<page-id>][<topic-id>:other]"
	 *
	 * @return   string
	 **/
	public function getReqOtherName() {
		$page_id  = $this->getParent()->getId();
		$topic_id = $this->getId();
		return 'data['. $page_id. ']['. $topic_id. ':other]'
			. ( method_exists( $this, 'getType' ) && $this->getType() != 'radio' ? '[]' : '' );
	}
	
	
	/**
	 * Returns type
	 *
	 * @return   string
	 **/
	public function getType() {
		return $this->_type;
	}
}