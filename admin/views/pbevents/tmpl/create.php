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
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Locale.'.$this->config->date_picker_locale.'.DatePicker.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Picker.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Picker.Attach.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Picker.Date.js');
//$doc->addStyleSheet(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/style.css');
$doc->addStyleSheet(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/datepicker_dashboard/datepicker_dashboard.css');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com_pbevents.administrator.create.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com.purplebeanie.general.js');
?>



<script>

window.addEvent('domready', function(){
	new Picker.Date($$('input[name=dtstart]'), {
		timePicker: true,
		positionOffset: {x: 5, y: 0},
		pickerClass: 'datepicker_dashboard',
		useFadeInOut: !Browser.ie,
		format: '%Y-%m-%d %H:%M:00'
	});
		new Picker.Date($$('input[name=dtend]'), {
		timePicker: true,
		positionOffset: {x: 5, y: 0},
		pickerClass: 'datepicker_dashboard',
		useFadeInOut: !Browser.ie,
		format: '%Y-%m-%d %H:%M:00'
	});
});

</script>

<form action="<?php echo JRoute::_('index.php?option=com_pbevents&layout=add');?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PBEVENTS_CREATE_EVENT');?></legend>
			<table>
				<tr>
					<td><label><?php echo JText::_('COM_PBEVENTS_EVENT_NAME');?></label></td>
					<td><input type="text" name="event_name" value="<?php echo (isset($this->event->event_name)) ? $this->event->event_name : null;?>" size="80"/></td>
				</tr>
				<tr>
					<td><label><?php echo JText::_('COM_PBEVENTS_EVENT_DESCRIPTION');?></label></td>
					<td><textarea name="description" rows="10" cols="40"><?php echo (isset($this->event->description)) ? $this->event->description : null;?></textarea></td>
				</tr>
				<tr>
					<td><label><?php echo JText::_('COM_PBEVENTS_START');?></label></td>
					<td><input type="text" name="dtstart" id="dtstart" value="<?php echo (isset($this->event->dtstart)) ? date_create($this->event->dtstart)->format('Y-m-d H:i:s') : null;?>"/></td>
				</tr>
				<tr>
					<td><label><?php echo JText::_('COM_PBEVENTS_END');?></label></td>
					<td><input type="text" name="dtend" id="dtend" value="<?php echo (isset($this->event->dtend)) ? date_create($this->event->dtend)->format('Y-m-d H:i:s') : null;?>"/></td>
				</tr>
				<tr>
					<td><label><?php echo JText::_('COM_PBEVENTS_MAX_PEOPLE');?></label></td>
					<td><input type="text" name="max_people" value="<?php echo (isset($this->event->max_people)) ? $this->event->max_people : 0;?>"/><i>0 = unlimited</i></td>
				</tr>
				<tr>
					<td><label><?php echo JText::_('COM_PBEVENTS_SUCCESS_URL');?></label></td>
					<td><input type="text" name="confirmation_page" value="<?php echo (isset($this->event->confirmation_page)) ? $this->event->confirmation_page : null;?>" size="80"/></td>
				</tr>
				<tr>
					<td><label><?php echo JText::_('COM_PBEVENTS_FAIL_URL');?></label></td>
					<td><input type="text" name="failed_page" value="<?php echo (isset($this->event->failed_page)) ? $this->event->failed_page : null;?>" size="80"/></td>
				</tr>
			</table>
			
		</fieldset>
	</div>

	<div class="width-50 fltrt">
		<?php echo JHtml::_('sliders.start', 'content-sliders-1', array('useCookie'=>1)); ?>
		<?php // Do not show the publishing options if the edit form is configured not to. ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_PBEVENTS_FIELDS'), 'publishing-details'); ?>
			<fieldset class="panelform">
				<table id="event-fields">
					<tr>
						<th><?php echo JText::_('COM_PBEVENTS_FIELD_LABEL');?></th>
						<th><?php echo JText::_('COM_PBEVENTS_FIELD_VAR');?></th>
						<th><?php echo JText::_('COM_PBEVENTS_FIELD_REQUIRED');?></th>
						<th><?php echo JText::_('COM_PBEVENTS_FIELD_TYPE');?></th>
						<th><?php echo JText::_('COM_PBEVENTS_FIELD_VALUES');?></th>
						<th><?php echo Jtext::_('COM_PBEVENTS_FIELD_VALIDATE_AS_EMAIL');?></th>
						<th></th>
					</tr>
					<?php if (!isset($this->event->fields) || $this->event->fields == '') :?>
						<tr>
							<td><input type="text" name="label[0]" value=""/></td>
							<td><input type="text" name="var[0]" value=""/></td>
							<td align="center"><input type="checkbox" name="required[]" value="1"/></td>
							<td>
								<select name="type[0]">
									<option value=""><?php echo JText::_('COM_PBEVENTS_SELECT_PROMPT');?></option>
									<?php foreach (array('select','checkbox','text','textarea','radio') as $type) :?>
										<option value="<?php echo $type;?>"><?php echo JText::_('COM_PBEVENTS_FIELD_TYPE_'.strtoupper($type));?></option>
									<?php endforeach;?> 
								</select>
							</td>
							<td><input type="text" name="values[0]" value=""/></td>
							<td align="center"><input type="checkbox" name="is_email[0]" value="1"/></td>
							<td>
								<img src="<?php echo JURI::root(false);?>administrator/components/com_pbevents/images/add.png" onclick="add_table_row('#event-fields')"/>
								<img src="<?php echo JURI::root(false);?>administrator/components/com_pbevents/images/delete.png" onclick="del_table_row(this)" />
							</td>
						</tr>
					<?php else:?>
						<?php $i=0;?>
						<?php foreach (json_decode($this->event->fields,true) as $field) :?>
							<tr>
								<td><input type="text" name="label[<?php echo $i;?>]" value="<?php echo $field['label'];?>"/></td>
								<td><input type="text" name="var[<?php echo $i;?>]" value="<?php echo $field['var'];?>"/></td>
								<td align="center"><input type="checkbox" name="required[<?php echo $i;?>]" value="1" <?php echo (isset($field['required']) && $field['required'] == 1) ? 'checked' : null;?> /></td>
								<td>
									<select name="type[<?php echo $i;?>]">
										<option value=""><?php echo JText::_('COM_PBEVENTS_SELECT_PROMPT');?></option>
										<?php foreach (array('select','checkbox','text','textarea','radio') as $type) :?>
											<option value="<?php echo $type;?>"  
												<?php echo ($field['type'] == $type) ? 'selected="true"' : null;?>
											><?php echo JText::_('COM_PBEVENTS_FIELD_TYPE_'.strtoupper($type));?></option>
										<?php endforeach;?> 
									</select>
								</td>
								<td><input type="text" name="values[<?php echo $i;?>]" value="<?php echo ($field['values']);?>"/></td>
								<td align="center"><input type="checkbox" name="is_email[<?php echo $i;?>]" value="1" <?php echo (isset($field['is_email']) && $field['is_email'] == 1) ? 'checked' : null;?>/></td>
								<td>
									<img src="<?php echo JURI::root(false);?>administrator/components/com_pbevents/images/add.png" onclick="add_table_row('#event-fields')"/>
									<img src="<?php echo JURI::root(false);?>administrator/components/com_pbevents/images/delete.png" onclick="del_table_row(this)" />
								</td>
							</tr>
							<?php $i++;?>
						<?php endforeach;?>
					<?php endif;?>

				</table>
			</fieldset>
			
			<?php echo JHtml::_('sliders.panel', JText::_('COM_PBEVENTS_NOTIFICATIONS'), 'notification-details'); ?>
			<fieldset class="panelform" width="100%">
				<table id="notification-settings" width="100%">
					<tr>
						<th><?php echo JText::_('COM_PBEVENTS_NOTIFY_FAILURE');?></th>
						<td><input type="checkbox" name="email_admin_failure" value="1" <?php echo (isset($this->event->email_admin_failure) && $this->event->email_admin_failure > 0 ) ? 'checked' : null;?>/></td>
					</tr>
					<tr>
						<th><?php echo JText::_('COM_PBEVENTS_NOTIFY_SUCCESS');?></th>
						<td><input type="checkbox" name="email_admin_success" value="1" <?php echo (isset($this->event->email_admin_success) && $this->event->email_admin_success > 0 ) ? 'checked' : null;?>/></td>
					</tr>
					<tr>
						<th><?php echo JText::_('COM_PBEVENTS_SEND_NOTIFICATIONS_TO');?></th>
						<td><input type="text" name="send_notifications_to" value="<?php echo (isset($this->event->send_notifications_to)) ? $this->event->send_notifications_to : null;?>" size="80"/></td>
					</tr>
				</table>
			</fieldset>

		<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<div class="clr"></div>
	
	<div>
		<input type="hidden" name="id" value="<?php echo (isset($this->event->id)) ? $this->event->id : 0;?>"/>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
