window.addEvent('domready',function(){
	$('pbevents-submit').addEvent('click',function(e){
		error = validate_form();
		if (error) {
			e.stop();
		}
	});
})

function validate_form()
{
	var error = false;
	fields.each(function(el,idx){
		var field_value = undefined;
		var field = undefined;
		switch (el.type) {
			case 'select':
				field = $$('select[name=' + el['var'] + ']');
				field_value = field.getLast().getProperty('value');
				break;
			case 'checkbox':
				field = $$('input[name='+el['var']+'[]]');
				if ($$('input[name='+el['var']+'[]]:checked').length == 0) {
					field_value='';
				} else {
					field_value=$$('input[name='+el['var']+'[]]:checked').getLast().getProperty('value')
				}
				break;
			case 'radio':
				field = $$('input[name='+el['var']+']');
				if ($$('input[name='+el['var']+']:checked').length == 0) {
					field_value='';
				} else {
					field_value=$$('input[name='+el['var']+']:checked').getLast().getProperty('value')
				}
				break;
			case 'textarea':
				field = $$('textarea[name='+el['var']+']');
				field_value = $$('textarea[name='+el['var']+']').getLast().getProperty('value');
				break;

			default:
				field = $$('input[name='+el['var']+']');
				field_value = $$('input[name='+el['var']+']').getLast().getProperty('value');
				break;
		}
		if (el.required == 1 && field_value == '') {
			field.addClass('pbevents-error-field');
			error = true;
		} else {
			field.removeClass('pbevents-error-field');
		}

		if (el.is_email == 1) {
			re = /.*\@.*\..*/
			if (!re.test(field.getProperty('value'))) {
				field.addClass('pbevents-error-field');
				error = true;
			}
		}

	})

	return error;
}