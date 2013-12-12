<?php

/**
* @package		PurpleBeanie.PBevents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('cms.html.html');

$version = new JVersion();
$input = JFactory::getApplication()->input;
$task = $input->get('task',null,'string');


define('PBEVENTS_MODE','debug');
define('JOOMLA_VERSION',$version->RELEASE);
if ($version->RELEASE != '2.5')
	define('DS',DIRECTORY_SEPARATOR);

 
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
if ($version->RELEASE != '2.5') {
	JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_DASHBOARD'),'index.php?option=com_pbevents',($task == null || $task=='display'));
	JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_CONFIGURATION'),'index.php?option=com_pbevents&task=editconfiguration',($task == 'editconfiguration'));
	JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_ADMIN_LIST_EVENTS'), 'index.php?option=com_pbevents&task=listevents', ($task == 'listevents'));
} else {
	JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_DASHBOARD'),'index.php?option=com_pbevents');
	JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_CONFIGURATION'),'index.php?option=com_pbevents&task=editconfiguration');
	JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_ADMIN_LIST_EVENTS'), 'index.php?option=com_pbevents&task=listevents');

}


// Require the base controller
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php' );
 
// Create the controller
$classname = 'PbeventsController'.$controller;
$controller = new $classname( );
 
// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();