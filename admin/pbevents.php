<?php

/**
* @package		PurpleBeanie.PBevents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );

 
// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

if (!$controller) {
	$controller = '';
}

//setup submenu items
JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_CONFIGURATION'),'index.php?option=com_pbevents&task=editconfiguration');
JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_ADMIN_LIST_EVENTS'), 'index.php?option=com_pbevents&task=listevents');


// Require the base controller
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php' );
 
// Create the controller
$classname = 'PbeventsController'.$controller;
$controller = new $classname( );
 
// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();