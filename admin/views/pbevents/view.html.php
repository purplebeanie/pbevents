<?php
/**
* @package		PurpleBeanie.PBEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
require_once(JPATH_BASE.DS.'components'.DS.'com_pbevents'.DS.'assets'.DS.'pbcustom.php');

 
 
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
				JFactory::getApplication()->input->set('hidemainmenu', true);				
				break;
			case 'viewattendees':
				$bar=JToolBar::getInstance( 'toolbar' );
				$bar->appendButton( 'PBCustom', 'plus',JText::_('COM_PBEVENTS_ATTENDEE_ADD'));
				$bar->appendButton('Link','download',JText::_('COM_PBEVENTS_ATTENDEE_EXPORT'),'index.php?option=com_pbevents&task=export&format=raw&event_id='.$this->event->id);
				break;

		}

        // display...... do I need to modify the layout?????
        if (JOOMLA_VERSION != '2.5' || 1==1)
        	$this->setLayout($this->getLayout().'v3');

		parent::display($tpl);
    }
}