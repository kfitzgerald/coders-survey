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
 * Survey Meta trait
 *
 * Meta data shared in each survey obejct
 **/
trait _SurveyMeta {
	
	/**
	 * @var   string   The title
	 **/
	private $_title = null;
	
	/**
	 * @var   int   The database ID
	 **/
	private $_id = null;
	
	/**
	 * @var   int   The position among peers
	 **/
	private $_position = null;
	
	/**
	 * @var   object   Ref to parent
	 **/
	private $_parent = null;
	
	
	/**
	 * Set parent 
	 *
	 * @param   object   The parent
	 **/
	protected function setParent( &$obj ) {
		$this->_parent = $obj;
	}
	
	
	/**
	 * Get parent 
	 *
	 * @return   object   The parent
	 **/
	public function getParent() {
		return $this->_parent;
	}
	
	
	/**
	 * Whether has parent 
	 *
	 * @return   bool
	 **/
	public function hasParent() {
		return ! is_null( $this->_parent );
	}
	
	
	/**
	 * Set title 
	 *
	 * @param   string   The title
	 **/
	protected function setTitle( $title ) {
		$this->_title = $title;
	}
	
	
	/**
	 * Get title
	 *
	 * @param   string   The title
	 **/
	public function getTitle() {
		return $this->_title;
	}
	
	
	/**
	 * Set position
	 *
	 * @param   int   The position
	 **/
	protected function setPosition( $position ) {
		$this->_position = $position;
	}
	
	
	/**
	 * Get the position
	 *
	 * @return   int   The position
	 **/
	public function getPosition() {
		return $this->_position;
	}
	
	
	/**
	 * Set the database ID
	 *
	 * @param   int   The ID
	 **/
	public function setId( $id ) {
		$this->_id = $id;
	}
	
	
	/**
	 * Get the database ID
	 *
	 * @return   int   The ID
	 **/
	public function getId() {
		return $this->_id;
	}
}