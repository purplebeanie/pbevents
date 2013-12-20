<?php

//this just loads up the international options to be used for the datetime picker and outputs them to the current document. which can then be included in the 

/*

dateFormat
dayNames
dayNamesMin
dayNamesShort
nextText
prevText
currentText
closeText
isRTL
monthNames
monthNamesShort

*/
	
	$config = JFactory::getConfig();
	$lang = JLanguage::getInstance($config->get('language'));

	$dtLocalisation = array();
	
	//dateFormat
	$dtLocalisation['dateFormat'] = 'yy-mm-dd';

	//dayNames, dayNamesMin, dayNamesShort
	$dtLocalisation['dayNames'] = array();
	$dtLocalisation['dayNamesMin'] = array();
	$dtLocalisation['dayNamesShort'] = array();

	$bow = date_create("this sunday",new DateTimeZone($config->get('offset')));
	for($i=0;$i<7;$i++){
		$dtLocalisation['dayNames'][] = Jhtml::_('date',$bow->format(DATE_ATOM),'l');
		$dtLocalisation['dayNamesMin'][] = Jhtml::_('date',$bow->format(DATE_ATOM),'D');
		$dtLocalisation['dayNamesShort'][] = Jhtml::_('date',$bow->format(DATE_ATOM),'D');
		$bow->modify('+1 day');
	}

	//nextText,prevText,currentText,closeText
	foreach (array('nextText','prevText','currentText','closeText') as $str) {
		$dtLocalisation[$str] = JText::_('COM_PBEVENTS_'.strtoupper($str));
	}

	//isRTL
	$dtLocalisation['isRTL'] = ($lang->isRTL()) ? true : 0;

	//monthNames, monthNamesShort
	$dtLocalisation['monthNames'] = array();
	$dtLocalisation['monthNamesShort'] = array();
	$boy = date_create("first day of january",new DateTimeZone($config->get('offset')));
	for ($i=0;$i<12;$i++) {
		$dtLocalisation['monthNames'][] = JHtml::_('date',$boy->format(DATE_ATOM),'F');
		$dtLocalisation['monthNamesShort'][] = JHtml::_('date',$boy->format(DATE_ATOM),'M');
		$boy->modify('+1 month');
	}

	//let's set the defaults.
	echo '<script>jQuery.datepicker.setDefaults('.json_encode($dtLocalisation).');</script>';

	//lets push the localisation in the doc in case we want to use it elswhere
	$doc = JFactory::getDocument();
	$doc->addScriptDeclaration('var dtLocalisation = '.json_encode($dtLocalisation).';');

	/*now let's also do the localisation for dtpickcer

	timeOnlyTitle: 'Выберите время',
	timeText: 'Время',
	hourText: 'Часы',
	minuteText: 'Минуты',
	secondText: 'Секунды',
	millisecText: 'Миллисекунды',
	timezoneText: 'Часовой пояс',
	currentText: 'Сейчас',
	closeText: 'Закрыть',
	timeFormat: 'HH:mm',
	amNames: ['AM', 'A'],
	pmNames: ['PM', 'P'],
	isRTL: false

	*/

	$dtPickerLocalisation = array();

	//first set the strings
	foreach (array('timeOnlyTitle','timeText','hourText','minuteText','millisecText','timezoneText','currentText','closeText') as $str) {
		$dtPickerLocalisation[$str] = JText::_('COM_PBEVENTS_DTPICKER_'.strtoupper($str));
	}

	//now the time format
	$dtPickerLocalisation['timeFormat'] = 'HH:mm:ss';

	//now the AM & PM settings
	$bod = date_create("now",new DateTimeZone($config->get('offset')));
	$bod->setTime(0,0,0);
	$eod = date_create("now",new DateTimeZone($config->get('offset')));
	$eod->setTime(23,59,59);

	$dtPickerLocalisation['amNames'] = array(Jhtml::_('date',$bod->format(DATE_ATOM),'A'),Jhtml::_('date',$bod->format(DATE_ATOM),'a'));
	$dtPickerLocalisation['pmNames'] = array(Jhtml::_('date',$bod->format(DATE_ATOM),'A'),Jhtml::_('date',$bod->format(DATE_ATOM),'a'));

	//now the RTL
	$dtPickerLocalisation['isRTL'] = $dtLocalisation['isRTL'];

	//let's set the defaults...
	echo '<script>jQuery.setDefaults('.json_encode($dtPickerLocalisation).');</script>';


?>