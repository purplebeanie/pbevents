<?php

/**
* @package		PurpleBeanie.PBEEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
 
defined('_JEXEC') or die('Restricted access'); 
?>


<form action="<?php echo JRoute::_('index.php?option=com_pbevents&task=listevents');?>" method="post" name="adminForm" id="adminForm">  
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					ID
				</th>
				<th>
					<?php echo JText::_('COM_PBEVENTS_EVENT_NAME');?>
				</th>
				<th>
					<?php echo JText::_('COM_PBEVENTS_EVENT_DESCRIPTION');?>
				</th>
				<th>
					<?php echo JText::_('COM_PBEVENTS_START');?> 
				</th>
				<th>
					<?php echo JText::_('COM_PBEVENTS_END');?>
				</th>
				<th>
					<?php echo JText::_('COM_PBEVENTS_ATTENDEES');?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->events as $i => $event) :?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $event->id); ?>
				</td>
				<td width="5%" align="center" class="center">
					<?php echo $event->id;?>
				</td>
				<td class="center">
					<a href="<?php echo JURI::root(false);?>administrator/index.php?option=com_pbevents&task=editevent&id=<?php echo $event->id;?>">
						<?php echo $event->event_name;?>
					</a>
				</td>
				<td width="50%">
					<?php echo $event->description;?>
				</td>
				<td class="center">
					<?php echo JHtml::_('date',$event->dtstart->format(DATE_ATOM),JText::_('COM_PBEVENTS_FULL_DATE_FORMAT'));?>
				</td>

				<td class="center">
					<?php echo JHtml::_('date',$event->dtend->format(DATE_ATOM),JText::_('COM_PBEVENTS_FULL_DATE_FORMAT'));?>
				</td>
				<td class="center">
					<a href="<?php echo JURI::root(false);?>administrator/index.php?option=com_pbevents&task=viewattendees&id=<?php echo $event->id;?>"><?php echo count($event->attendees);?></a>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="listevents" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>