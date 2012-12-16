<?php

//No direct access to this file

defined('_JEXEC') or die('Restricted access');

/**
* com_pbevents installer script
*/

class com_pbeventsInstallerScript
{
	function postflight($type,$parent) {



		error_log($parent->getParent()->getPath('source'));

		//now install.
		$installer = new JInstaller();
		$installer->install($parent->getParent()->getPath('source').DS.'plugin');
	}
}