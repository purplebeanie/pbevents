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

	<div id="filter-bar">
		<!--<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->filter_search); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>-->
		<div class="btn-group pull-left">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->filter_published, true);?>
			</select>

		</div>
	</div>
	<div style="clear:both;"> </div>
		
		
	<table class="table table-striped">
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
				<th><?php echo JText::_('COM_PBEVENTS_EVENT_ARCHIVED');?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
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
				<td class="center"><?php echo JHtml::_('jgrid.published', $event->publish, $i); ?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="listevents" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>