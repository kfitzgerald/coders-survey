<?php
/**
 * INIT
 */

#ini_set( 'display_errors', true );

require_once 'config.php';
require_once 'lib/CodersSurvey/autoload.php';
require_once 'lib/CodersSurveyApp/autoload.php';
require_once 'vendor/autoload.php';


/**
 * SETUP
 */
$app = new Slim( [
    'templates.path' => 'lib/CodersSurveyApp/Views'
]);


$app->add( new Slim_Middleware_SessionCookie( [
    'expires' => '10080 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'coders_survey',
    'secret' => 'CHANGE_ME',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
] ) );

$survey = null;


/**
 * ROUTES
 */

$app->get( '/', function() {
    ( new \CodersSurveyApp\Controller\Home() )->start();
} );
$app->get( '/load-result/:id', function( $id ) {
    ( new \CodersSurveyApp\Controller\Home() )->loadResults( $id );
} );
$app->post( '/complete', function() {
    ( new \CodersSurveyApp\Controller\Home() )->complete();
} );

/* admin */
$admin_controller = new \CodersSurveyApp\Controller\Admin();
$app->map( '/admin', function() use ( $admin_controller ) {
    $admin_controller->start();
} )->via( 'GET', 'POST' );

// admin check function
$admin_only = function() use ( $admin_controller, $app ) {
    if ( ! $admin_controller->check() )
        return $app->redirect( '/admin' );
};

/* survey */
$app->post( '/admin/survey/switch', $admin_only, function() use ( $admin_controller ) {
    $admin_controller->switchSurvey();
} );
$app->map( '/admin/survey/generate', $admin_only, function() use ( $admin_controller ) {
    $admin_controller->generateResults();
} )->via( 'GET', 'POST' );
$app->post( '/admin/survey/save', $admin_only, function() use ( $admin_controller ) {
    $admin_controller->saveSurvey();
} );
$app->post( '/admin/survey/togglestate', $admin_only, function() use ( $admin_controller ) {
    $admin_controller->toggleState();
} );

/* page */
$app->get( '/admin/page/:id', $admin_only, function( $id ) use ( $admin_controller ) {
    $admin_controller->editPage( $id );
} );
$app->post( '/admin/page/:id', $admin_only, function( $id ) use ( $admin_controller ) {
    $admin_controller->savePage( $id );
} );
$app->get( '/admin/page/delete/:id', $admin_only, function( $id ) use ( $admin_controller ) {
    $admin_controller->deletePage( $id );
} );

/* topic */
$app->get( '/admin/topic/:id', $admin_only, function( $id ) use ( $admin_controller ) {
    $admin_controller->editTopic( $id );
} );
$app->post( '/admin/topic/:id', $admin_only, function( $id ) use ( $admin_controller ) {
    $admin_controller->saveTopic( $id );
} );
$app->get( '/admin/topic/delete/:id', $admin_only, function( $id ) use ( $admin_controller ) {
    $admin_controller->deleteTopic( $id );
} );
$app->get( '/admin/topic/create/:id', $admin_only, function( $id ) use ( $admin_controller ) {
    $admin_controller->editTopic( null, $id );
} );

/* misc */
$app->map( '/admin/change-password', $admin_only, function() use ( $admin_controller ) {
    $admin_controller->changePassword();
} )->via( 'GET', 'POST' );
$app->get( '/logout', $admin_only, function() use ( $admin_controller ) {
    $admin_controller->logout();
} );

/**
 * RUN
 */
$app->run();
