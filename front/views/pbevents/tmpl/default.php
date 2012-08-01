<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access
 
defined('_JEXEC') or die('Restricted access'); 

  
echo '<h1>'.JTEXT::_('COM_PBBOOKING_CALENDAR_HEADING').'</h1>';

?>

<div id="pbbooking-notifications"></div>

<?php 

//for testing purposes.... don't forget to change this eric

include('individual_freeflow_view.php');



if (isset($this->calendar_message)) {
	echo $this->calendar_message;
}
 
if ($this->config->show_link) {
	echo '<p>Powered by <a href="http://www.purplebeanie.com">PBBooking - Online Booking for Joomla</a>.</p>';
}

?>