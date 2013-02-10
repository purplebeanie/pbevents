<?php
/**
* @package		PurpleBeanie.PBEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
 
class PbeventsViewPbevents extends JViewLegacy
{
    function display($tpl = null)
    {
		$input = JFactory::getApplication()->input;
		$task = JRequest::getVar('task');

		switch ($task) {
			case 'editconfiguration':
				$input->set('hidemainmenu',true);
        		JToolBarHelper::cancel('cancel');
				break;
			case 'listevents':
				break;
			case 'add':
				JToolBarHelper::cancel('cancel');
				break;

		}

        // display...... do I need to modify the layout?????
        if (JOOMLA_VERSION == '3.0')
        	$this->setLayout($this->getLayout().'v3');

		parent::display($tpl);
    }
}