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
include(JPATH_BASE.DS.'components'.DS.'com_pbevents'.DS.'assets'.DS.'pbsupportfunctions.php');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');


$doc = JFactory::getDocument();
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com_pbevents.administrator.create.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com.purplebeanie.general.js');
$doc->addStyleSheet(JURI::root(false).'administrator/components/com_pbevents/css/pbevents.css');


?>

<?php require_once(JPATH_BASE.DS.'components'.DS.'com_pbevents'.DS.'assets'.DS.'checkbootstrap.php');?>
<script src="<?php echo JURI::root(false);?>administrator/components/com_pbevents/scripts/com_pbevents.administrator.viewattendees.js"></script>



<style>
textarea {width:90%;}
.fields-header > div {font-weight: bold;}
.fields-checkbox {text-align:center;}
</style>



<script>


jQuery(document).ready(function($){

	jQuery('.date').datetimepicker({timeFormat:'hh:mm:ss', dateFormat: 'yy-mm-dd'});

	//listen for the add record click	
	$('.btn-add-field').click(function(event)
		{
			event.preventDefault();
			var last_record = $('.fields-data').last();
			var field_id = $('.fields-data').last().attr('id').replace(/.*-(\d+)/,'$1').toInt();
			var old_field_id = field_id;

			//tidy to re-inject the base record back in
			field_id++; 									//bump the field id
			var new_record = last_record.clone();
			$(new_record).attr('id','fields-row-'+field_id);
			$(new_record).find('input[name="cid[]"]').val(field_id);
			$(new_record).find('input[name="label['+old_field_id+']"]').attr('name','label['+field_id+']');
			$(new_record).find('input[name="var['+old_field_id+']"]').attr('name','var['+field_id+']');
			$(new_record).find('input[name="required['+old_field_id+']"]').attr('name','required['+field_id+']');
			$(new_record).find('select[name="type['+old_field_id+']"]').attr('name','type['+field_id+']');
			$(new_record).find('input[name="values['+old_field_id+']"]').attr('name','values['+field_id+']');
			$(new_record).find('input[name="is_email['+old_field_id+']"]').attr('name','is_email['+field_id+']');
			$(new_record).find('input[name="display_in_list['+old_field_id+']"]').attr('name','display_in_list['+field_id+']');
			$(new_record).find('input[name="ordering['+old_field_id+']"]').attr('name','ordering['+field_id+']');

			//inject back into the dom and flash...
			$('.fields-container').append('<hr/>');
			$(new_record).appendTo('.fields-container');
			jQuery('#fields-row-'+field_id).effect('highlight',{},1500);
		}
	);

	//listen for the delete custom field click
	$('.btn-del-field').click(function(event){
		event.preventDefault();

		var cids = $('input[name="cid[]"]:checked');
		if (cids.length == 0)
			alert('**Please select customfields to delete**');
		else {
			for (var i=0;i<cids.length;i++) {
				var cid = cids[i];
				$('#fields-row-'+$(cid).val()).remove();
			}
		}

		
	});
})

</script>


<form action="<?php echo JRoute::_('index.php?option=com_pbevents&layout=add');?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tabEventDetails" data-toggle="tab"><?php echo JText::_('COM_PBEVENTS_EVENT_DETAILS');?></a></li>
		<li><a href="#tabEventFields" data-toggle="tab"><?php echo JText::_('COM_PBEVENTS_EVENT_FIELDS');?></a></li>
		<li><a href="#tabEventEmails" data-toggle="tab"><?php echo JText::_('COM_PBEVENTS_EVENT_EMAILS');?></a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="tabEventDetails">

			<h2><?php echo JText::_('COM_PBEVENTS_CREATE_EVENT');?></h3>
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_EVENT_NAME');?></label>
						<div class="controls"><input type="text" class="input-xxlarge" name="event_name" value="<?php echo (isset($this->event->event_name)) ? $this->event->event_name : null;?>" size="80"/></div>
					</div>

					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_EVENT_DESCRIPTION');?></label>
						<div class="controls"><textarea name="description" class="input-xxlarge" rows="10" ><?php echo (isset($this->event->description)) ? $this->event->description : null;?></textarea></div>
					</div>

					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_START');?></label>
						<div class="controls"><input type="text" class="input-medium date" name="dtstart" id="dtstart" value="<?php echo (isset($this->event->dtstart)) ? date_create($this->event->dtstart)->format('Y-m-d H:i:s') : null;?>"/></div>
					</div>

					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_END');?></label>
						<div class="controls"><input type="text" class="input-medium date" name="dtend" id="dtend" value="<?php echo (isset($this->event->dtend)) ? date_create($this->event->dtend)->format('Y-m-d H:i:s') : null;?>"/></div>
					</div>

					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_MAX_PEOPLE');?></label>
						<div class="controls"><input type="text" class="input-small" name="max_people" value="<?php echo (isset($this->event->max_people)) ? $this->event->max_people : 0;?>"/><i><?php echo JText::_('COM_PBEVENTS_MAX_PEOPLE_UNLIMIT');?></i></div>
					</div>

					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_SUCCESS_URL');?></label>
						<div class="controls"><input type="text" class="input-xxlarge" name="confirmation_page" value="<?php echo (isset($this->event->confirmation_page)) ? $this->event->confirmation_page : $this->config->default_success_URL;?>" size="80"/></div>
					</div>

					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_FAIL_URL');?></label>
						<div class="controls"><input type="text" class="input-xxlarge" name="failed_page" value="<?php echo (isset($this->event->failed_page)) ? $this->event->failed_page : $this->config->default_failure_URL;?>" size="80"/></div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_SHOW_COUNTER');?></label>
						<div class="controls"><input type="hidden" name="show_counter" value="0"><input type="checkbox" name="show_counter" value="1" <?php echo (isset($this->event->show_counter) && $this->event->show_counter == 1) ? 'checked' : null;?>></div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_SHOW_ATTENDEES');?></label>
						<div class="controls"><input type="hidden" name="show_attendees" value="0"><input type="checkbox" name="show_attendees" value="1" <?php echo (isset($this->event->show_attendees) && $this->event->show_attendees == 1) ? 'checked' : null;?>></div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_REQUIRE_CAPTCHA');?></label>
						<div class="controls"><input type="hidden" name="require_captcha" value="0"><input type="checkbox" name="require_captcha" value="1" <?php echo (isset($this->event->require_captcha) && $this->event->require_captcha > 0) ? 'checked' : ((isset($this->config->require_captcha) && $this->config->require_captcha > 0) ? 'checked' : null);?> /></div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_NOTIFY_FAILURE');?></label>
						<div class="controls"><input type="checkbox" name="email_admin_failure" value="1" <?php echo (isset($this->event->email_admin_failure) && $this->event->email_admin_failure > 0 ) ? 'checked' : null;?>/></div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_NOTIFY_SUCCESS');?></label>
						<div class="controls"><input type="checkbox" name="email_admin_success" value="1" <?php echo (isset($this->event->email_admin_success) && $this->event->email_admin_success > 0 ) ? 'checked' : null;?>/></div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_PBEVENTS_SEND_NOTIFICATIONS_TO');?></label>
						<div class="controls"><input type="text" class="input-xxlarge" name="send_notifications_to" value="<?php echo (isset($this->event->send_notifications_to)) ? $this->event->send_notifications_to : $this->config->default_notification_email;?>" size="80"/></div>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane" id="tabEventFields"

			<div class="row-fluid">



				<div class="row-fluid fields-header">
					<div class="span1"></div>
					<div class="span3"><?php echo JText::_('COM_PBEVENTS_FIELD_LABEL');?></div>
					<div class="span2"><?php echo JText::_('COM_PBEVENTS_FIELD_VAR');?></div>
					<div class="span1"><?php echo JText::_('COM_PBEVENTS_FIELD_REQUIRED');?></div>
					<div class="span2"><?php echo JText::_('COM_PBEVENTS_FIELD_TYPE');?>/<br/><?php echo JText::_('COM_PBEVENTS_FIELD_VALUES');?></div>
					<div class="span1"><?php echo JText::_('COM_PBEVENTS_FIELD_VALIDATE_AS_EMAIL');?></div>
					<div class="span1"><?php echo JText::_('COM_PBEVENTS_DISPLAY_IN_FRONT_END_ATTENDEE_LIST');?></div>
					<div class="span1"><?php echo JText::_('COM_PBEVENTS_CUSTOMFIELD_ORDERING');?></div>
				</div>

				<div class="fields-container">

					<?php if (!isset($this->event->fields) || $this->event->fields == '' || $this->event->fields == '[]') :?>

						<div id="fields-row-0" class="row-fluid fields-data">
							<div class="span1 fields-checkbox"><input type="checkbox" name="cid[]" value="0"/></div>
							<div class="span3"><input type="text" name="label[0]" value="" class="input-large"/></div>
							<div class="span2"><input type="text" name="var[0]" value="" class="input-medium"/></div>
							<div class="span1 fields-checkbox"><input type="checkbox" name="required[]" value="1"/></div>
							<div class="span2">
								<select name="type[0]" class="input-medium">
									<option value=""><?php echo JText::_('COM_PBEVENTS_SELECT_PROMPT');?></option>
									<?php foreach (array('select','checkbox','text','textarea','radio') as $type) :?>
										<option value="<?php echo $type;?>"><?php echo JText::_('COM_PBEVENTS_FIELD_TYPE_'.strtoupper($type));?></option>
									<?php endforeach;?> 
								</select><br/><br/>
								<input type="text" name="values[0]" value="" class="input-medium"/>
							</div>
							<div class="span1 fields-checkbox"><input type="checkbox" name="is_email[0]" value="1"/></div>
							<div class="span1 fields-checkbox"><input type="checkbox" name="display_in_list[0]" value="1"/></div>
							<div class="span1"><input type="text" class="input-mini" name="ordering[0]" value="<?php echo isset($field['ordering']) ? $field['ordering'] : 1;?>"/></div>
						</div>

						<hr/>

					<?php else:?>

						<?php $i=0;?>
						<?php foreach (json_decode($this->event->fields,true) as $field):?>

							<div id="fields-row-<?php echo $i;?>" class="row-fluid fields-data">
								<div class="span1 fields-checkbox"><input type="checkbox" name="cid[]" value="<?php echo $i;?>"/></div>
								<div class="span3"><input type="text" name="label[<?php echo $i;?>]" value="<?php echo $field['label'];?>" class="input-large"/></div>
								<div class="span2"><input type="text" name="var[<?php echo $i;?>]" value="<?php echo $field['var'];?>" class="input-medium"/></div>
								<div class="span1 fields-checkbox"><input type="checkbox" name="required[<?php echo $i;?>]" value="1" <?php echo (isset($field['required']) && $field['required'] == 1) ? 'checked' : null;?> /></div>
								<div class="span2">
									<select name="type[<?php echo $i;?>]" class="input-medium">
										<option value=""><?php echo JText::_('COM_PBEVENTS_SELECT_PROMPT');?></option>
										<?php foreach (array('select','checkbox','text','textarea','radio') as $type) :?>
											<option value="<?php echo $type;?>"  
												<?php echo ($field['type'] == $type) ? 'selected="true"' : null;?>
											><?php echo JText::_('COM_PBEVENTS_FIELD_TYPE_'.strtoupper($type));?></option>
										<?php endforeach;?> 
									</select><br/><br/>
									<input type="text" name="values[<?php echo $i;?>]" value="<?php echo ($field['values']);?>" class="input-medium"/>
								</div>
								<div class="span1 fields-checkbox"><input type="checkbox" name="is_email[<?php echo $i;?>]" value="1" <?php echo (isset($field['is_email']) && $field['is_email'] == 1) ? 'checked' : null;?>/></div>
								<div class="span1 fields-checkbox"><input type="checkbox" name="display_in_list[<?php echo $i;?>]" value="1" <?php echo (isset($field['display_in_list']) && $field['display_in_list'] == 1) ? 'checked' : null;?>/></div>
								<div class="span1"><input type="text" size="4" class="input-mini" name="ordering[<?php echo $i;?>]" value="<?php echo isset($field['ordering']) ? $field['ordering'] : 1;?>"/></div>
							</div>

							<hr/>				

							<?php $i++;?>

						<?php endforeach;?>

					<?php endif;?>

				</div>


				<div class="clr"></div>

				<div class="row-fluid">
					<div class="span9"></div>
					<div class="span3" style="padding-top:10px;line-height:40px;">
						<a href="#" class="btn btn-success btn-add-field"><?php echo JText::_('COM_PBEVENTS_ADD_CUSTOMFIELD');?></a>&nbsp;
						<a href="#" class="btn btn-warning btn-del-field"><?php echo JText::_('COM_PBEVENTS_DELETE_CUSTOMFIELD');?></a>&nbsp;
					</div>
				</div>
			</div>
			<div class="tab-pane" id="tabEventEmails">
				<div>
					<div class="row-fluid">
						<div class="span12">
							<div class="alert alert-info">
								<p><?php echo JText::_('COM_PBEVENTS_EMAILS_NOTICE');?></p>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label"><?php echo Jtext::_('COM_PBEVENTS_REGISTRATION_SUCCESSFUL_CLIENT_EMAIL');?></label>
							<div class="controls">
								<textarea class="input-xxlarge" rows="10" name="client_confirmation_message"><?php echo (isset($this->event->client_confirmation_message)) ? $this->event->client_confirmation_message : null;?></textarea>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label"><?php echo Jtext::_('COM_PBEVENTS_REGISTRATION_SUCCESSFUL_CLIENT_SUBJECT');?></label>
							<div class="controls">
								<input type="text" name="client_confirmation_subject" class="input-xxlarge" value="<?php echo (isset($this->event->client_confirmation_subject)) ? $this->event->client_confirmation_subject : null;?>"/>
							</div>
						</div>

						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		</div>


	</div>
</div>
<input type="hidden" name="id" value="<?php echo (isset($this->event->id)) ? $this->event->id : 0;?>"/>
<input type="hidden" name="task" value="" />
<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
<?php echo JHtml::_('form.token'); ?>
</form>



<?php require_once(JPATH_BASE.DS.'components'.DS.'com_pbevents'.DS.'assets'.DS.'closebootstrap.php');?>

