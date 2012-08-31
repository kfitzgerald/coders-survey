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
 * Survey Iterable trait
 *
 * Implements iterable methods for survey objects with children (Survey, SurveyPage, SurveyTopic)
 **/
trait _SurveyIterable {
	
	/**
	 * @var array The child objects
	 **/
	private $_childs;
	
	/**
	 * @var array Mapping childs by index for fast access
	 **/
	private $_child_maps;
	
	/**
	 * @var array Amount of children per type
	 **/
	private $_child_cnts;
	
	/**
	 * @var array Current iteration index per type
	 **/
	private $_child_idxs;
	
	
	/**
	 * Inits child index, count and type arrays
	 *
	 * @param   string   type of the child
	 **/
	private function _initChild( $child_name ) {
		if ( ! isset( $this->_childs[ $child_name ] ) ) {
			$this->_childs[ $child_name ] = [];
			$this->_child_maps[ $child_name ] = [];
			$this->_child_cnts[ $child_name ] = 0;
			$this->_child_idxs[ $child_name ] = 0;
		}
	}
	
	
	/**
	 * Inits child index, count and type arrays
	 *
	 * @param   string   Type of the child
	 * @param   object   Reference to child object
	 * @param   string   Unique index name of child
	 **/
	public function addIterable( $child_name, &$item, $index_name = null ) {
		$this->_initChild( $child_name );
		$this->_childs[ $child_name ][]= $item;
		if ( ! is_null( $index_name ) )
			$this->_child_maps[ $child_name ][ $index_name ] = $item;
		$this->_child_cnts[ $child_name ] ++;
	}
	
	
	/**
	 * Returns next child of given name
	 *
	 * @param   string   Type-name of the child
	 **/
	public function nextIterable( $child_name ) {
		$this->_initChild( $child_name );
		if ( $this->_child_idxs[ $child_name ] < $this->_child_cnts[ $child_name ] )
			return $this->_childs[ $child_name ][ $this->_child_idxs[ $child_name ]++ ];
		return null;
	}
	
	
	/**
	 * Reset itearator for child to start
	 *
	 * @param   string   Type-name of the child
	 **/
	public function resetIterable( $child_name ) {
		$this->_initChild( $child_name );
		$this->_child_idxs[ $child_name ] = 0;
		return null;
	}
	
	
	/**
	 * Return child with index name
	 *
	 * @param   string   Type-name of the child
	 * @param   string   Index-name of the child
	 * @return   object   The child
	 **/
	public function indexedIterable( $child_name, $index_name ) {
		$this->_initChild( $child_name );
		return isset( $this->_child_maps[ $child_name ][ $index_name ] )
			? $this->_child_maps[ $child_name ][ $index_name ]
			: null;
	}
	
	
	/**
	 * Returns array of all children of a type
	 *
	 * @param   string   Type-name of the child
	 * @return   array   The children
	 **/
	public function getAllIterable( $child_name ) {
		$this->_initChild( $child_name );
		return $this->_childs[ $child_name ];
	}
	
	
	/**
	 * Overloading of all methods beginning with:
	 *   * next
	 *   * add
	 *   * reset
	 *   * indexed
	 *   * getAll
	 * 
	 * So adding and accessing a "foo" child would be:
	 * <code>
	 $obj->addFoo( $foo_object, $foo_object->id );
	 foreach ( $obj->getAllFoo() as $foo_child ) { .. }
	 $obj->indexedFoo( "123" );
	 * </code>
	 *
	 * @param   string   Type-name of the child
	 * @return   array   The children
	 **/
	public function __call( $req_method, $arguments ) {
		if ( preg_match( '/^(next|add|reset|indexed|getAll)(.+?)$/', $req_method, $match ) ) {
			$req_method = $match[1]. 'Iterable';
			array_unshift( $arguments, strtolower( $match[2] ) );
			return self::_ref_callback( $this, $req_method, $arguments );
		}
		return parent::__call( $req_method, $arguments );
	}
	
	
	/**
	 * Stolen from php.net
	 **/
	private static function _ref_callback( $obj, $method, $orig_args ) {
		if (! method_exists( $obj, $method ) ) {
			trigger_error( 'No such method "'.$method. '" for "'. get_class( $obj ). '"', E_USER_ERROR);
			return NULL;
		}
		$reflect = new \ReflectionMethod( $obj, $method );
		$pass_args = [];
		foreach ($reflect->getParameters() as $i => $param) {
			$pname = $param->getName();
			$pass_args []= &$orig_args[ $i ];
		}
		return call_user_func_array( array( $obj, $method ), $pass_args);
	}
}