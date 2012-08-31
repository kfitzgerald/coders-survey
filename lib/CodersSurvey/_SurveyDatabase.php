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
 * Survey Database trait
 * 
 * Database access methods.
 */
trait _SurveyDatabase {
	
	/**
	 * @var PDO
	 */
	private $_db;
	
	/**
	 * Performs database query and returns handle
	 *
	 * @param	string	SQL
	 * @param	array	SQL parameters
	 * @return	\PDOStatement
	 **/
	public function dbQuery( $sql, $args = [] ) {
		$sth = $this->_db()->prepare( $sql );
		$sth->execute( $args );
		return $sth;
	}
	
	/**
	 * Fetches a single database row as associative array
	 *
	 * @param	string	table name
	 * @param	string	SQL condition
	 * @param	array	SQL parameters
	 * @return	array
	 **/
	public function dbFetch( $table, $where = '1=1', $args = [] ) {
		return $this->dbQuery( "SELECT * FROM $table WHERE $where", $args )->fetch( \PDO::FETCH_ASSOC );
	}
	
	/**
	 * Fetches multiple database rows as array of associative arrays
	 *
	 * @param	string	table name
	 * @param	string	SQL condition
	 * @param	array	SQL parameters
	 * @return	array
	 **/
	public function dbFetchAll( $table, $where = '1=1', $args = [] ) {
		return $this->dbQuery( "SELECT * FROM $table WHERE $where", $args )->fetchAll( \PDO::FETCH_ASSOC );
	}
	
	/**
	 * Fetches count of database rows
	 *
	 * @param	string	table name
	 * @param	string	SQL condition
	 * @param	array	SQL parameters
	 * @return	array
	 **/
	public function dbCount( $table, $where = '1=1', $args = [] ) {
		$res = $this->dbQuery( "SELECT COUNT(*) FROM $table WHERE $where", $args )->fetch( \PDO::FETCH_NUM );
		return empty( $res ) ? 0 : $res[0];
	}
	
	/**
	 * Return last inserted ID
	 *
	 * @return	int
	 **/
	public function dbLastId() {
		return $this->_db()->lastInsertId();
	}
	
	/**
	 * Access to \PDO instance
	 *
	 * @return   \PDO
	 **/
	protected function _db() {
		return $this->hasParent() ? $this->_dbHolder()->_db() : $this->_db;
	}
	
	/**
	 * Access to \PDO instance
	 **/
	protected function _dbInit( $dns = SURVEY_DB_DNS, $user = SURVEY_DB_USER, $password = SURVEY_DB_PASSWORD ) {
		if ( $this->hasParent() )
			return $this->_dbHolder()->_dbInit( $dns, $user, $password );
		$this->_db = new \PDO( $dns, $user, $password );
	}
	
	/**
	 * Recursive access to survey object having the PDO object
	 *
	 * @return   \Survey
	 **/
	protected function _dbHolder() {
		if ( $this->hasParent() )
			return $this->getParent()->_dbHolder();
		return $this;
	}
}