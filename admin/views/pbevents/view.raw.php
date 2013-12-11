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


        // display...... do I need to modify the layout?????
        if (JOOMLA_VERSION != '2.5' || 1==1)
        	$this->setLayout($this->getLayout().'v3');

		parent::display($tpl);
    }
}