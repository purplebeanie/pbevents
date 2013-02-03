<?php
/**
 * @copyright	Eric Fernance - Purple Beanie
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Class for PBEvents PLugin
 *
 */
class plgContentPbevents extends JPlugin
{


	/**
	 * Plugin to render the form for registering for events using PBevents.
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	mixed	An object with a "text" property or the string to be cloaked.
	 * @param	array	Additional parameters.
	 * @param	int		Optional page number. Unused. Defaults to zero.
	 * @return	boolean	True on success.
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{		
		$article = $row->text;

		if (preg_match('/\{pbevents=(\d+)\}/',$article,$matches)) {
			//get the event details
			$db = &JFactory::getDbo();
			$event = $db->setQuery('select * from #__pbevents_events where id = '.(int)$db->escape($matches[1]))->loadObject();
			if ($event) {
				$query = $db->getQuery(true);
				$query->select('*')->from('#__pbevents_rsvps')->where('event_id = '.(int)$event->id);
				$attendees = $db->setQuery($query)->loadObjectList();
				$event->attendees = count($attendees);
				$event->attendeesList = $attendees;
				
				//do i need to inject? GET or POST
				if ($_SERVER['REQUEST_METHOD'] == "GET") {
					//build the form up
					if ($event->publish == 1 && ($event->max_people == 0 || count($attendees)<$event->max_people)) {
						$form = $this->_build_form($event);
					} else {
						if ($event->publish == 0)
							$form = $this->_displayEventClosed();
						else
							$form = $this->_display_busy_event();
					}

					//inject the form, javascript and, css
					$doc = &JFactory::getDocument();
					$doc->addScript(JURI::root(false).'plugins/content/pbevents/scripts/pbevents.js');
					$doc->addScriptDeclaration('var fields = '.$event->fields);
					$doc->addStyleSheet(JURI::root(false).'plugins/content/pbevents/styles/pbevents.css');
					$row->text = str_replace($matches[0],$form,$article);

					//push the form id into the users session to prevent tinkering....
					$session = &JFactory::getSession();
					$session->set('pbevents_event_id',(int)$matches[1]);

				} else {
					$success = $this->_process_rsvp();
					if ($success) {
						if ($event->email_admin_success>0)
							$this->_email_admin($event,'success');
						header("Location: $event->confirmation_page");
					} else {
						if ($event->email_admin_failure>0)
							$this->_email_admin($event,'fail');
						header("Location: $event->failed_page");
					}
					
				}
			} else {
				$row->text = str_replace($matches[0],'',$article);
			}

		}
	}

	/**
	* build up the form using the event id
	* @param object the event
	* @return string the form
	*/

	private function _build_form($event)
	{
		//load the language strings
		$lang = JFactory::getLanguage();
		$lang->load('com_pbevents', JPATH_ADMINISTRATOR);

		$form = '<form action="" method="POST">';
		$form .= '<table id="pbevents">';

		if ($event->show_counter == 1)
			$form .= '<tr><td colspan=2" class="hitCounter">'.sprintf(JText::_('COM_PBEVENTS_HIT_COUNTER_FORMAT'),($event->max_people - $event->attendees)).'</td></tr>';
		if ($event->show_attendees == 1)
			$form .= $this->_addAttendeesList($event);
		foreach (json_decode($event->fields,true) as $field) {
			switch($field['type']) {
				case 'text':
					$form .= sprintf('<tr><td><label>%s</label></td><td><input type="text" name="%s" value=""/></td></tr>',
							$field['label'],$field['var']);
					break;
				case 'radio':
					$form.='<tr><td><label>'.$field['label'].'</label></td><td>';
					foreach (explode('|',$field['values']) as $value) {
						$form.='<input type="radio" value="'.$value.'" name="'.$field['var'].'"/> <label>'.$value.'</label> ';
					}
					$form.='</td></tr>';
					break;
				case 'textarea':
					$form .='<tr><td><label>'.$field['label'].'</kabe></td><td><textarea name="'.$field['var'].'"></textarea></td></tr>';
					break;
				case 'checkbox':
					$form.='<tr><td><label>'.$field['label'].'</label></td><td>';
					foreach (explode('|',$field['values']) as $value) {
						$form.='<input type="checkbox" value="'.$value.'" name="'.$field['var'].'[]"/> <label>'.$value.'</label><br/>';
					}
					$form.='</td></tr>';
					break;
				case 'select':
					$form .= '<tr><td><label>'.$field['label'].'</label></td>';
					$form.= '<td><select name="'.$field['var'].'">';
					foreach (explode('|',$field['values']) as $value) {
						$form.='<option value="'.$value.'">'.$value.'</option>';
					}
					$form .='</select></td></tr>';
					break;
			}
			
		}
		$form .= '<tr><td colspan="2" align="center"><input type="submit" value="submit" id="pbevents-submit"/></td></tr>';
		$form .='</table>';
		$form .='<input type="hidden" name="event_id" value="'.$event->id.'"/>';
		$form .= '</form>';

		//return the form
		return $form;

	}

	/**
	* display the busy event box
	*/

	private function _display_busy_event()
	{
		//load the language strings
		$lang = JFactory::getLanguage();
		$lang->load('com_pbevents', JPATH_ADMINISTRATOR);

		$form = '<div class="pbevents-fully-booked">'.JText::_('COM_PBEVENTS_EVENT_FULL_ERROR').'</div>';
		return $form;
	}


	/**
	* the event is now closed
	*/
	private function _displayEventClosed()
	{
		//load the language strings
		$lang = JFactory::getLanguage();
		$lang->load('com_pbevents',JPATH_ADMINISTRATOR);

		$form = '<div class="pbevents-fully-booked">'.JText::_('COM_PBEVENTS_EVENT_UNPUBLISHED_ERROR').'</div>';
		return $form;
	}

	/**
	* adds the attendees list to the form
	* @param object the event
	* @return string html list inside a table row
	*/

	private function _addAttendeesList($event)
	{
		//load the language strings
		$lang = JFactory::getLanguage();
		$lang->load('com_pbevents',JPATH_ADMINISTRATOR);

		$html = '<tr><td colspan="2" class="attendeesList"><p><strong>'.JText::_('COM_PBEVENTS_SHOW_ATTENDEES_INTRO').'</strong></p><ul>';
		foreach ($event->attendeesList as $attendee) {
			$attendeeData = json_decode($attendee->data,true);
			$dataFields = json_decode($event->fields,true);
			
			$html .= '<li>';
			foreach ($dataFields as $field)
				if (isset($field['display_in_list']) && $field['display_in_list'] == 1)
					$html .= $attendeeData[$field['var']].JText::_('COM_PBEVENTS_DISPLAY_IN_FRONT_END_ATTENDEE_LIST_SPACE');
			$html .= '</li>';
		}
		$html .= '</ul>';

		return $html;
	}


	/**
	* process the data back from the user and store in the event.
	* @return bool a success or failure.
	*/

	private function _process_rsvp()
	{

		//$session = &JFactory::getSession(); //session not needed - caused failed registrations on windows.
		//$event_id = $session->get('pbevents_event_id',0); //removed - as it generated errors on windows. now pulls from a hidden field.
		$input = &JFactory::getApplication()->input;
		$event_id = $input->get('event_id',null,'integer');

		if ($event_id) {
			$db = &JFactory::getDbo();
			$event = $db->setQuery('select * from #__pbevents_events where id = '.(int)$db->getEscaped($event_id))->loadObject();
			$data = array(); //contains the rsvp
			foreach (json_decode($event->fields,true) as $field) {
				$data[$field['var']] = ($field['type'] == 'checkbox') ? implode(',',$input->get($field['var'],null,'array')) : $input->get($field['var'],null,'string');
			}
			$rsvp = new JObject(array('event_id'=>$event->id,'data'=>json_encode($data)));
			if ($db->insertObject('#__pbevents_rsvps',$rsvp)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}

	}

	/**
	* email the admin of the event with notification
	* @param mixed the event object
	* @param string the status that is being sent
	*/

	private function _email_admin($event,$status)
	{
		$input = JFactory::getApplication()->input;

		//load the language strings
		$lang = JFactory::getLanguage();
		$lang->load('com_pbevents', JPATH_ADMINISTRATOR);

		//get the config both from pbevents and joomla config.
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__pbevents_config')->where('id = 1');
		$config = $db->setQuery($query)->loadObject();
		$joomla_config =& JFactory::getConfig();


		$email_body = ($status == 'success') ? $config->email_success_body : $config->email_failed_body;
		$email_subject = ($status == 'success') ? $config->email_success_subject : $config->email_failed_subject;

		$event_details = sprintf('<ul><li>%s = %s</li><li>%s = %s</li><li>%s = %s</li></ul>',JText::_('COM_PBEVENTS_EVENT_NAME'),$event->event_name,
								JText::_('COM_PBEVENTS_START'),JHTML::_('date',date_create($event->dtstart,new DateTimeZone($joomla_config->getValue('config.offset')))->format(DATE_ATOM),JText::_('COM_PBEVENTS_EMAIL_DATE_TIME_FORMAT')),
								JText::_('COM_PBEVENTS_END'),JHTML::_('date',date_create($event->dtend,new DateTimeZone($joomla_config->getValue('config.offset')))->format(DATE_ATOM),JText::_('COM_PBEVENTS_EMAIL_DATE_TIME_FORMAT')));
		$rsvp_details = '<ul>';
		foreach (json_decode($event->fields,true) as $field) {
			$rsvp_details .= '<li>'.$field['label'].' - ';
			$rsvp_details .= ($field['type'] == 'checkbox') ? implode(',',$input->get($field['var'],null,'array')) : $input->get($field['var'],null,'string');
			$rsvp_details .= '</li>';
		}
		$rsvp_details .= '</ul>';
	
		//push the event details and the rsvp details into the email body...
		$email_body = str_replace('|*event*|',$event_details,$email_body);
		$email_body = str_replace('|*user*|',$rsvp_details,$email_body);
		$email_body .= ($status == 'fail') ? '<p>'.$db->getErrorMsg().'<p>' : null;
		$email_body .= '<p>'.JText::_('COM_PBEVENTS_REMOTE_ADDR').' '.$_SERVER['REMOTE_ADDR'].'</p>';

		$mailer =& JFactory::getMailer();
		$mailer->setSender(array($joomla_config->getValue('config.mailfrom'),$joomla_config->getValue('config.fromname')));
		
		$mailer->addRecipient($event->send_notifications_to);
		$mailer->setSubject($email_subject);
		$mailer->isHTML(true);

		$mailer->setBody($email_body);
		$mailer->Send();

	}


}
