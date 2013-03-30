<?php

//No direct access to this file

defined('_JEXEC') or die('Restricted access');

/**
* com_pbevents installer script
*/

class com_pbeventsInstallerScript
{
	function postflight($type,$parent) {
		//get dir.... some installs don't support __DIR__ constant...
		$version = new JVersion();
		if ($version->RELEASE == '3.0')
			define('DS',DIRECTORY_SEPARATOR);

		$dir_arr = explode(DS,__FILE__);
		$dir_arr = array_slice($dir_arr, 0,(count($dir_arr)-1));
		
		//now install.
		$installer = new JInstaller();
		$installer->install(implode(DS,$dir_arr).DS.'plugin');
	}
}