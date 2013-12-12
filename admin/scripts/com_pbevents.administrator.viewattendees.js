jQuery(document).ready(function(){
	jQuery('#pbtoolbar-plus').on('click',addattendee);
});

function addattendee()
{
	var displayForm = function(data) {
		jQuery('body').append('<div id="pbevents-dialog"></div>');
		var dialog = jQuery('#pbevents-dialog').dialog();
		dialog.html(data);
		dialog.dialog('option','position',{my:"center",at:"center",of:window});
		dialog.find('.btn-success').on('click',saveattendee);
		dialog.find('.btn-warning').on('click',function(){
			dialog.dialog("destroy");
		});
	};

	var loadForm = jQuery.get('index.php?option=com_pbevents&task=creatersvp&format=raw&event_id='+jQuery('input[name="id"]').val()).done(displayForm);
}

function saveattendee()
{
	jQuery('#new-attendee').submit();
}