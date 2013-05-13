<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or la<ter; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access
 
defined('_JEXEC') or die('Restricted access'); 

?> 
<script>

PurpleBeanie.jquery(document).ready(function($){

	$('.pbevents-submit').click(function(e){
		//get the form and validate it.
		var form = $(this).parents('form')
		var error = validate_form(form);
		if (error)
			e.preventDefault();
	})
});


//modified to try and scale to multiple forms on one page.
function validate_form(form)
{
	var event_id = PurpleBeanie.jquery(form).find('input[name="event_id"]').val();
	var error = false;

	fields[event_id.toString()].each(function(el,idx){
		var field_value = '';
		var field = undefined;

		switch (el.type) {
			case 'select':
				field = PurpleBeanie.jquery(form).find('select[name="'+el['var']+'"]');
				field_value = field.val();
				break;
			case 'textarea':
				field = PurpleBeanie.jquery(form).find('textarea[name="'+el['var']+'"]');
				field_value = field.val();	
				break;			
			case 'text':
				field = PurpleBeanie.jquery(form).find('input[name="'+el['var']+'"]');
				field_value = field.val();
				break;
			case 'checkbox':
				field_name  = el['var']+'[]';
				field = PurpleBeanie.jquery(form).find('input[name="'+el['var']+'[]"]');
				field_checked = PurpleBeanie.jquery(form).find('input[name="'+el['var']+'[]"]:checked');
				if (field_checked.length>0)
					field_value = 'yes';
				break;
			case 'radio':
				field = PurpleBeanie.jquery(form).find('input[name="'+el['var']+'"]');
				field_value = '';
				field_checked = PurpleBeanie.jquery(form).find('input[name="'+el['var']+'"]:checked');
				if (field_checked.length>0)
					field_value='yes';
				break;
		}

		if (el.required == 1 && field_value == '') {
			try {
				field.addClass('pbevents-error-field');
			} catch (e) {
				//nothing to do.....
			}
			error = true;
		} else {
			try {
				field.removeClass('pbevents-error-field');
			} catch (e) {
				//nothing to be done with error...
			}
		}

		if (el.is_email == 1) {
			re = /.*\@.*\..*/
			if (!re.test(field_value)) {
				field.addClass('pbevents-error-field');
				error = true;
			}
		}

	})

	return error;
}
</script>