<?php
/**
 * @package    PurpleBeanie.PBEvents
 * @link http://www.purplebeanie.com
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');

 
class PbeventsController extends JController
{
	
	function __construct()
	{
		parent::__construct();

	}
	
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {	
    	
    }
    
    /**
     * 
     * saves the appointment to the pending_events table and routes validation emails
     * 
     */
    	
	function save()
	{

		
	}
	
	
	function error() {
		//$this->setLayout('fail');
		JRequest::setVar('layout','fail');
		parent::display();
	}
	
		
	
	
	
	

}