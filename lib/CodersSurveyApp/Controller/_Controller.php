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
 * Base Controller
 *
 * Abstract super class of controllers
 */
abstract class _Controller {
	
	/**
	 * @var name of the session key
	 **/
	public static $SESSION_KEY = 'SURVEYAPP';
	
	public function __construct() {
		global $survey, $app;
		
		$survey = $this->session( 'admin' ) && $this->session( 'survey_id' )
			? new \CodersSurvey\Survey( $this->session( 'survey_id' ) )
			: new \CodersSurvey\Survey();
		$app->view()->setData( 'survey', $survey );
	}
	
	/**
	 * Shorthand for Slim app object
	 */
	protected function a() {
		global $app;
		return $app;
	}
	
	
	/**
	 * Shorthand for CodersSurvey\Survey object
	 */
	protected function s() {
		global $survey;
		return $survey;
	}
	
	
	/**
	 * Write / read session parameters
	 *
	 * @param   string   $name name of the session parameter
	 * @param   mixed    $value value to be persisted into session
	 * @return  mixed    null, if $name not set
	 */
	public function session( $name, $value = null ) {
		if ( ! isset( $_SESSION[ self::$SESSION_KEY ] ) )
			$_SESSION[ self::$SESSION_KEY ] = [];
		if ( ! is_null( $value ) )
			$_SESSION[ self::$SESSION_KEY ][ $name ] = $value;
		return isset( $_SESSION[ self::$SESSION_KEY ][ $name ] )
			? $_SESSION[ self::$SESSION_KEY ][ $name ]
			: null;
	}
}