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


<form action="<?php echo JRoute::_('index.php?option=com_pbevents&task=viewattendees');?>" method="post" name="adminForm" id="adminForm">  
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<!-- draw header rows-->
				<th width="1%">	
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					ID
				</th>

				<?php foreach (json_decode($this->event->fields,true) as $field) :?>
					<th>
						<?php echo $field['label'];?>
					</th>
				<?php endforeach;?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?php echo count(json_decode($this->event->fields,true))+2;?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php if (isset($this->attendees) && count($this->attendees)>0) :?>
			<?php foreach ($this->attendees as $i => $attendee) :?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $attendee->id); ?>
					</td>
					<td align="center" width="5%">
						<?php echo $attendee->id;?>
					</td>
					<?php foreach (json_decode($this->event->fields,true) as $field) :?>
						<?php $data = json_decode($attendee->data,true);?>
						<td align="center">
							<?php echo (isset($data[$field['var']])) ? $data[$field['var']] :null ;?>
						</td>
					<?php endforeach;?>
				</tr>
			<?php endforeach;?>
		<?php else :?>
			<tr>
				<td colspan="<?php echo count(json_decode($this->event->fields,true))+2;?>" align="center"><?php echo JText::_('COM_PBEVENTS_NO_ATTENDEES');?></td>
			</tr>
		<?php endif;?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="viewattendees" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo $this->event->id;?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>