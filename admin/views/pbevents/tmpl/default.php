<?php

/**
* @package		PurpleBeanie.PBEEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access

defined('_JEXEC') or die('Restricted access'); 

$config = JFactory::getConfig();

echo '<h1>'.JTEXT::_('COM_PBEVENTS_HEADING').'</h1>';


?>

<!-- Begin Content -->
<form name="adminForm" id="adminForm">
	<div class="width-40 fltlft">
		<fieldset>
			<legend><?php echo JText::_('COM_PBEVENTS_LATEST_REGISTRATIONS');?></legend>
			<table style="width:100%;">
				<?php if (count($this->rsvps)>0) :?>
					<?php foreach ($this->rsvps as $rsvp) :?>
						<tr>
							<td><strong class="row-title"><?php echo $rsvp->event_name;?></strong></td>
							<td>
								<?php
									$eventFields = json_decode($rsvp->fields,true);
									$rsvpData = json_decode($rsvp->data,true);
									if (isset($eventFields) && count($eventFields) > 0)
										foreach ($eventFields as $field)
											echo $rsvpData[$field['var']];
								?>
							</td>
						</tr>
					<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan="2"><strong class="row-title"><?php echo JText::_('COM_PBBOOKING_DASHBOARD_NOTHING_FOUND');?></strong></td>
					<tr>	
				<?php endif;?>
			</table>
		</fieldset>
	</div>
		
	<div class="width-40 fltrt">
		<fieldset>
			<legend><?php echo JText::_('COM_PBEVENTS_UPCOMING_EVENTS');?></legend>
			<table style="width:100%;">
				<?php foreach ($this->events as $event) :?>
					<tr>
						<td>
							<a href="<?php echo JRoute::_('index.php?option=com_pbevents&task=editevent&id='.(int)$event->id);?>">
								<strong class="row-title"><?php echo $event->event_name;?></strong>
							</a>
						</td>
						<td>
							<?php echo JHtml::_('date',date_create($event->dtstart,new DateTimeZone($config->get('offset')))->format(DATE_ATOM),JText::_('COM_PBEVENTS_FULL_DATE_FORMAT'));?>
						</td>
					</tr>
				<?php endforeach;?>
			</table>	
		</fieldset>
	</div>
	
	<div style="clear:both;"></div>

	<div class="">
		<fieldset>
			<legend><?php echo JText::_('COM_PBEVENTS_LATEST_ACCOUNCEMENTS');?></legend>
			<?php foreach ($this->announcements as $announcement) :?>
				<p><strong><?php echo $announcement->get_title();?></strong></p>
				<p><?php echo $announcement->get_description();?></p>
				<p>
					<hr style="width:90%;margin:0px auto;color:#F0F0EE;"/>
				</p>
				<i><?php echo $announcement->get_date();?></i>
			<?php endforeach;?>
		</fieldset>	
	</div>

	
	</div>
</form>
