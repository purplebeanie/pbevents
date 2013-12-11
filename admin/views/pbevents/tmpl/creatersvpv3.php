<?php
?>


<h3><?php echo JText::_('COM_PBEVENTS_CREATE_RSVP');?></h3>
<form method="POST" action="<?php echo JURI::root(false);?>administrator/index.php?option=com_pbevents&task=creatersvp" id="new-attendee">
	<table>
		<?php foreach ($this->fields as $field):?>
			<tr>
				<td><?php echo $field['label'];?></td>
				<td>
					<?php
					$form = '';
					switch($field['type']){
						case 'text':
							$form .= sprintf('<input type="text" name="%s" value=""/>',$field['var']);
							break;
						case 'radio':
							foreach (explode('|',$field['values']) as $value) {
								$form.='<input type="radio" value="'.$value.'" name="'.$field['var'].'"/> <label>'.$value.'</label> ';
							}
							$form.='</td></tr>';
							break;
						case 'textarea':
							$form .='<textarea name="'.$field['var'].'"></textarea>';
							break;
						case 'checkbox':
							$form.='';
							foreach (explode('|',$field['values']) as $value) {
								$form.='<input type="checkbox" value="'.$value.'" name="'.$field['var'].'[]"/> <label>'.$value.'</label><br/>';
							}
							break;
						case 'select':
							$form.= '<select name="'.$field['var'].'">';
							foreach (explode('|',$field['values']) as $value) {
								$form.='<option value="'.$value.'">'.$value.'</option>';
							}
							$form .='</select>';
							break;
						}
					?>
					<?php echo $form;?>
				</td>
			</tr>
		<?php endforeach;?>
	</table>
	<input type="hidden" name="event_id" value="<?php echo $this->event->id;?>"/>
	<button type="button" class="btn btn-success"><?php echo JText::_('COM_PBEVENTS_ADD_CUSTOMFIELD');?></button>
</form>
<div style="clear:both;"></div>

