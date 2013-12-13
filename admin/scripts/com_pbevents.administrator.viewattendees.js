jQuery(document).ready(function(){
	jQuery('#pbtoolbar-plus').on('click',addattendee);
	jQuery('#pbtoolbar-edit').on('click',editattendee);
});

function addattendee()
{

	var loadForm = jQuery.get('index.php?option=com_pbevents&task=creatersvp&format=raw&event_id='+jQuery('input[name="id"]').val()).done(displayForm);
}

function editattendee()
{
	var rsvpid = jQuery('input[name="cid[]"]:checked').last().val();
	if (rsvpid)
		var loadForm = jQuery.get('index.php?option=com_pbevents&task=editrsvp&format=raw&rsvpid='+rsvpid).done(displayForm);
	else
		alert(Joomla.JText._('COM_PBEVENTS_ATTENDEE_EDIT_PLS_CHOOSE'));
}

function saveattendee()
{
	jQuery('#new-attendee').submit();
}

function displayForm(data) {
	jQuery('body').append('<div id="pbevents-dialog"></div>');
	var dialog = jQuery('#pbevents-dialog').dialog();
	dialog.html(data);
	dialog.dialog('option','position',{my:"center",at:"center",of:window});
	dialog.find('.btn-success').on('click',saveattendee);
	dialog.find('.btn-warning').on('click',function(){
		dialog.html('');
		dialog.dialog("destroy");
	});
}