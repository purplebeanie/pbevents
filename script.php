<?php

//No direct access to this file

defined('_JEXEC') or die('Restricted access');

/**
* com_pbevents installer script
*/

class com_pbeventsInstallerScript
{
	function postflight($type,$parent) {
		$installer = new JInstaller();

		//first check to see if the plugin is already installed
		$db = JFactory::getDbo();
		$extension = $db->setQuery('select * from #__extensions where `type` = "plugin" and `element` = "pbevents" and `folder` = "content"')->loadObject();
		if ($extension)
		    $installer->uninstall('plugin',$extension->extension_id);



		//get dir.... some installs don't support __DIR__ constant...
		$version = new JVersion();
		if ($version->RELEASE == '3.0')
			define('DS',DIRECTORY_SEPARATOR);

		$dir_arr = explode(DS,__FILE__);
		$dir_arr = array_slice($dir_arr, 0,(count($dir_arr)-1));
		
		//now install.
		$installer->install(implode(DS,$dir_arr).DS.'plugin');
	}
}