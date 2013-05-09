<?php

/**
* @package		PurpleBeanie.PBEEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access
 
defined('_JEXEC') or die('Restricted access'); 

$config = JFactory::getConfig();

require_once(JPATH_BASE.DS.'components'.DS.'com_pbevents'.DS.'assets'.DS.'checkbootstrap.php');
  
echo '<h1>'.JTEXT::_('COM_PBEVENTS_HEADING').'</h1>';


?>

<div class="container-fluid">
<!-- Begin Content -->
	<div class="row-fluid">
		<div class="span6">
			<div class="well well-small">
				<div class="module-title nav-header"><?php echo JText::_('COM_PBEVENTS_LATEST_REGISTRATIONS');?></div>
				<div class="row-striped">
					<?php if (count($this->rsvps)>0) :?>
						<?php foreach ($this->rsvps as $rsvp) :?>
							<div class="row-fluid">
								<div class="span9"><strong class="row-title"><?php echo $rsvp->event_name;?></strong></div>
								<div class="span3">
									<?php
										$eventFields = json_decode($rsvp->fields,true);
										$rsvpData = json_decode($rsvp->data,true);
										if (isset($eventFields) && count($eventFields) > 0)
											foreach ($eventFields as $field)
												echo $rsvpData[$field['var']];
									?>
								</div>
							</div>
						<?php endforeach;?>
					<?php else:?>
						<div class="row-fluid">
							<div class="span12"><strong class="row-title"><?php echo JText::_('COM_PBBOOKING_DASHBOARD_NOTHING_FOUND');?></strong></div>
						</div>	
					<?php endif;?>
				</div>
			</div>
		</div>
		
		<div class="span6">
			<div class="well well-small">
				<div class="module-title nav-header"><?php echo JText::_('COM_PBEVENTS_UPCOMING_EVENTS');?></div>
				<div class="row-striped">
					<?php foreach ($this->events as $event) :?>
						<div class="row-fluid">
							<div class="span9">
								<a href="<?php echo JRoute::_('index.php?option=com_pbevents&task=editevent&id='.(int)$event->id);?>">
									<strong class="row-title"><?php echo $event->event_name;?></strong>
								</a>
							</div>
							<div class="span3">
								<i class="icon-calendar"></i>
								<?php echo JHtml::_('date',date_create($event->dtstart,new DateTimeZone($config->get('offset')))->format(DATE_ATOM),JText::_('COM_PBEVENTS_FULL_DATE_FORMAT'));?>
							</div>
						</div>
					<?php endforeach;?>
				</div>
				
			</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span12">
			<div class="well well-small">
				<div class="module-title nav-header"><?php echo JText::_('COM_PBEVENTS_LATEST_ACCOUNCEMENTS');?></div>
					<div class="row-striped">
					<?php foreach ($this->announcements as $announcement) :?>
					<div class="row-fluid">
						<div class="span12">
							<strong><?php echo $announcement->get_title();?></strong>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<?php echo $announcement->get_description();?>
							<p>
								<hr style="width:90%;margin:0px auto;color:#F0F0EE;"/>
							</p>
							<i><?php echo $announcement->get_date();?></i>
						</div>
					</div>

					<?php endforeach;?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once(JPATH_BASE.DS.'components'.DS.'com_pbevents'.DS.'assets'.DS.'closebootstrap.php');?>