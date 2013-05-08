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

<style>
	textarea {width:100%;}
</style>

<form action="<?php echo JRoute::_('index.php?option=com_pbevents');?>" method="post" name="adminForm" class="form-validate" id="adminForm">
	<div class="row-fluid">
		<div class="span12 form-horizontal">
			<fieldset>
				<legend><?php echo JText::_('COM_PBEVENTS_CONFIGURATION');?></legend>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PBBOOKING_FAILED_SUBJECT');?></div>
					<div class="controls"><input type="text" name="email_failed_subject" value="<?php echo (isset($this->config->email_failed_subject)) ? $this->config->email_failed_subject : null;?>" size="80"/></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PBBOOKING_FAILED_BODY');?></div>
					<div class="controls"><textarea name="email_failed_body" rows="10" cols="40"><?php echo (isset($this->config->email_failed_body)) ? $this->config->email_failed_body : null;?></textarea></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PBBOOKING_SUCCESS_SUBJECT');?></div>
					<div class="controls"><input type="text" name="email_success_subject" value="<?php echo (isset($this->config->email_success_subject)) ? $this->config->email_success_subject : null;?>" size="80"></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PBBOOKING_SUCCESS_BODY');?></div>
					<div class="controls"><textarea name="email_success_body" rows="10" cols="40"><?php echo (isset($this->config->email_success_body)) ? $this->config->email_success_body : null;?></textarea></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PBEVENTS_CONFIG_SUCCESS_URL');?></div>
					<div class="controls"><input type="text" name="default_success_URL" value="<?php echo (isset($this->config->default_success_URL)) ? $this->config->default_success_URL : null;?>" size="80"/></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PBEVENTS_CONFIG_FAILURE_URL');?></div>
					<div class="controls"><input type="text" name="default_failure_URL" value="<?php echo (isset($this->config->default_failure_URL)) ? $this->config->default_failure_URL : null;?>" size="80"/></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PBEVENTS_CONFIG_NOTIF_EMAIL');?></div>
					<div class="controls"><input type="text" name="default_notification_email" value="<?php echo (isset($this->config->default_notification_email)) ? $this->config->default_notification_email : null;?>" size="80"/></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PBEVENTS_REQUIRE_CAPTCHA');?></div>
					<div class="controls"><input type="checkbox" name="require_captcha" value="1" <?php echo (isset($this->config->require_captcha) && $this->config->require_captcha > 0) ? 'checked' : null;?> /></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PBEVENTS_DATE_LOCALE');?></div>
					<div class="controls">
						<select name="date_picker_locale">
							<?php foreach (array('en-US','cs-CZ','de-DE','es-ES','fr-FR','he-IL','it-IT','nl-NL','pl-PL','pt-BR','ru-RU') as $locale) :?>
								<option value="<?php echo $locale;?>"
								<?php echo (isset($this->config->date_picker_locale) && $this->config->date_picker_locale == $locale) ? 'selected="true"' : null;?> ><?php echo $locale;?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>

			</fieldset>
		</div>
	</div>

	<div class="clr"></div>
	
	<div>
		<input type="hidden" name="id" value="<?php echo (isset($this->config->id)) ? $this->config->id : 0;?>"/>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
