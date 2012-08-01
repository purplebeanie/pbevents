<?php
/**
 * @link http://www.purplebeanie.com
 * @license    GNU/GPL
*/
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Require the base controller
 
require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'models'.DS.'calendar.php' );
require_once( JPATH_COMPONENT.DS.'models'.DS.'event.php' );
require_once( JPATH_COMPONENT.DS.'views'.DS.'pbbooking'.DS.'view.html.php' );
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'pbdebug.php');

 
// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
 
// Create the controller
$controller   = new PbbookingController();


$task = JRequest::getWord('task');

if(!$task) {
	$controller->display();
}

if($task == "view") {
	$controller->display();
}

if($task=="create") {
	$controller->display();
}

if($task=="save") {
	$controller->save();
}

if($task=="validate") {
	$controller->validate();
}

if($task == "view_day") {
	$controller->view_day();
}

if($task =="subscribe") {
	$controller->subscribe();
}

if($task=='load_slots_for_day') {
	$controller->load_slots_for_day();
}
 
// Perform the Request task
//$controller->execute( JRequest::getWord( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();