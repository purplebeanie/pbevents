<?php
if (isset($this->rsvp))
	$data = json_decode($this->rsvp->data,true);
?>


<h3><?php echo (isset($this->rsvp)) ? JText::_('COM_PBEVENTS_EDIT_RSVP') : JText::_('COM_PBEVENTS_CREATE_RSVP');?></h3>
<form method="POST" action="<?php echo JURI::root(false);?>administrator/index.php?option=com_pbevents&task=<?php echo (isset($this->rsvp) && $this->rsvp->id != null) ? 'editrsvp' : 'creatersvp';?>" id="new-attendee">
	<table>
		<?php foreach ($this->fields as $field):?>
			<tr>
				<td><?php echo $field['label'];?></td>
				<td>
					<?php
					$form = '';
					switch($field['type']){
						case 'text':
							$form .= sprintf('<input type="text" name="%s" value="%s"/>',$field['var'],(isset($data[$field['var']])) ? $data[$field['var']] : '');
							break;
						case 'radio':
							foreach (explode('|',$field['values']) as $value) {
								$form.='<input type="radio" value="'.$value.'" name="'.$field['var'].'" '.((isset($data[$field['var']]) && $data[$field['var']] == $value) ? 'checked' : null) .' /> <label>'.$value.'</label> ';
							}
							$form.='</td></tr>';
							break;
						case 'textarea':
							$form .='<textarea name="'.$field['var'].'">'.((isset($data[$field['var']])) ? $data[$field['var']] : null).'</textarea>';
							break;
						case 'checkbox':
							$form.='';
							foreach (explode('|',$field['values']) as $value) {
								$form.='<input type="checkbox" value="'.$value.'" name="'.$field['var'].'[]"  '.((isset($data[$field['var']]) && $data[$field['var']] == $value) ? 'checked' : null).'/> <label>'.$value.'</label><br/>';
							}
							break;
						case 'select':
							$form.= '<select name="'.$field['var'].'">';
							foreach (explode('|',$field['values']) as $value) {
								$form.='<option value="'.$value.'" '.((isset($data[$field['var']]) && $data[$field['var']] == $value) ? 'selected=true' : null).' >'.$value.'</option>';
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
	<input type="hidden" name="rsvpid" value="<?php echo (isset($this->rsvp->id)) ? $this->rsvp->id : 0;?>"/>
	<button type="button" class="btn btn-success"><?php echo JText::_('COM_PBEVENTS_ADD_SAVE');?></button>
	<button type="button" class="btn btn-warning"><?php echo JText::_('COM_PBEVENTS_ADD_CANCELLED');?></button>
</form>
<div style="clear:both;"></div>

