<?php

//No direct access to this file

defined('_JEXEC') or die('Restricted access');

/**
* com_pbevents installer script
*/

class com_pbeventsInstallerScript
{
	function postflight($type,$parent) {
		//get dir.... some installs don't support __DIR__ constant....
		$dir = preg_replace('/\/script.php/','',__FILE__);

		//now install.
		$installer = new JInstaller();
		$installer->install($dir.'/plugin');
	}
}