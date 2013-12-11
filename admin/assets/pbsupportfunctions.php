<?php

// No direct access
 
defined('_JEXEC') or die('Restricted access'); 

/**
* returns an array of day names min for use in jQuery UI date pickers - the day names are always started from sunday!
* @return array
*/

function get_day_names_min()
{
	$config = JFactory::getConfig();
	$bow = date_create("this sunday",new DateTimeZone($config->get('offset')));
	$day_names_min = array();
	for ($i=0;$i<7;$i++){
		$day_names_min[] = JHTML::_('date',$bow->format(DATE_ATOM),"D");
		$bow->modify("+1 day");
	}
	return $day_names_min;
}



?>