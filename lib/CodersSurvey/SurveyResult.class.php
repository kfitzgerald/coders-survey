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
 * Survey Result
 * 
 * Represents the result of a specific survey. 
 *
 * Each title has many options. Each page has many titles. Each survey has many pages.
 */
class SurveyResult {
	
	/**
	 * @var Reference to \CodersSurvey\Survey object
	 **/
	private $_survey;
	
	/**
	 * @var Reference to \CodersSurvey\SurveyPage object (for grouped result)
	 **/
	private $_page;
	
	/**
	 * @var Reference to \CodersSurvey\SurveyTopic object (for grouped result)
	 **/
	private $_topic;
	
	/**
	 * @var Reference to \CodersSurvey\SurveyOption object (for grouped result)
	 **/
	private $_option;
	
	/**
	 * @var ID of result in database
	 **/
	private $_id;
	
	public function __construct( &$survey, $db_res ) {
		$this->_survey = $survey;
		$this->_id = $db_res[ 'id' ];
		if ( preg_match( '/^grouped:0*([1-9][0-9]*):0*([1-9][0-9]*):0*([1-9][0-9]*)$/', $db_res[ 'ref' ], $match ) ) {
			$this->_page = $survey->indexedPage( $match[1] );
			$this->_topic = $survey->indexedTopic( $match[1], $match[2] );
			$this->_option = $survey->indexedOption( $match[1], $match[2], $match[3] );
		}
	}
	
	
	/**
	 * Whether is main result (not grouped)
	 *
	 * @return   bool
	 **/
	public function isMain() {
		return $this->_page == null;
	}
	
	
	/**
	 * ID of the result
	 *
	 * @return   int
	 **/
	public function getId() {
		return $this->_id;
	}
	
	
	/**
	 * Return page for grouped result
	 *
	 * @return   \CodersSurvey\SurveyPage
	 **/
	public function getPage() {
		return $this->_page;
	}
	
	
	/**
	 * Return topic for grouped result
	 *
	 * @return   \CodersSurvey\SurveyTopic
	 **/
	public function getTopic() {
		return $this->_topic;
	}
	
	
	/**
	 * Return option for grouped result
	 *
	 * @return   \CodersSurvey\SurveyOption
	 **/
	public function getOption() {
		return $this->_option;
	}
	
}

