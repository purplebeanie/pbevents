<?php
/**
* @package		PurpleBeanie.PBEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$doc = JFactory::getDocument();
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com.purplebeanie.general.js');
?>


<form action="<?php echo JRoute::_('index.php?option=com_pbevents');?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PBEVENTS_CONFIGURATION');?></legend>
			<table>
				<tr>
					<th><?php echo JText::_('COM_PBBOOKING_FAILED_SUBJECT');?></th>
					<td><input type="text" name="email_failed_subject" value="<?php echo (isset($this->config->email_failed_subject)) ? $this->config->email_failed_subject : null;?>" size="80"/></td>
				</tr>
				<tr>
					<th><?php echo JText::_('COM_PBBOOKING_FAILED_BODY');?></th>
					<td><textarea name="email_failed_body" rows="10" cols="40"><?php echo (isset($this->config->email_failed_body)) ? $this->config->email_failed_body : null;?></textarea></td>
				</tr>


				<tr>
					<th><?php echo JText::_('COM_PBBOOKING_SUCCESS_SUBJECT');?></th>
					<td><input type="text" name="email_success_subject" value="<?php echo (isset($this->config->email_success_subject)) ? $this->config->email_success_subject : null;?>" size="80"></td>
				</tr>

				<tr>
					<th><?php echo JText::_('COM_PBBOOKING_SUCCESS_BODY');?></th>
					<td><textarea name="email_success_body" rows="10" cols="40"><?php echo (isset($this->config->email_success_body)) ? $this->config->email_success_body : null;?></textarea></td>
				</tr>

				<tr>
					<th><?php echo JText::_('COM_PBEVENTS_CONFIG_SUCCESS_URL');?></th>
					<td><input type="text" name="default_success_URL" value="<?php echo (isset($this->config->default_success_URL)) ? $this->config->default_success_URL : null;?>" size="80"/></td>
				</tr>
				<tr>
					<th><?php echo JText::_('COM_PBEVENTS_CONFIG_FAILURE_URL');?></th>
					<td><input type="text" name="default_failure_URL" value="<?php echo (isset($this->config->default_failure_URL)) ? $this->config->default_failure_URL : null;?>" size="80"/></td>
				</tr>
				<tr>
					<th><?php echo JText::_('COM_PBEVENTS_CONFIG_NOTIF_EMAIL');?></th>
					<td><input type="text" name="default_notification_email" value="<?php echo (isset($this->config->default_notification_email)) ? $this->config->default_notification_email : null;?>" size="80"/></td>
				</tr>

				<tr>
					<th><?php echo JText::_('COM_PBEVENTS_DATE_LOCALE');?></th>
					<td>
						<select name="date_picker_locale">
							<?php foreach (array('en-US','cs-CZ','de-DE','es-ES','fr-FR','he-IL','it-IT','nl-NL','pl-PL','pt-BR','ru-RU') as $locale) :?>
								<option value="<?php echo $locale;?>"
								<?php echo (isset($this->config->date_picker_locale) && $this->config->date_picker_locale == $locale) ? 'selected="true"' : null;?> ><?php echo $locale;?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>



			</table>
			
		</fieldset>
	</div>

	<div class="clr"></div>
	
	<div>
		<input type="hidden" name="id" value="<?php echo (isset($this->config->id)) ? $this->config->id : 0;?>"/>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
