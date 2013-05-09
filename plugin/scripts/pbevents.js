window.addEvent('domready',function(){
	$$('.pbevents-submit').addEvent('click',function(e){
		error = validate_form(this.getParents('form').getLast());
		if (error) {
			e.stop();
		}
	});
})


//modified to try and scale to multiple forms on one page.
function validate_form(form)
{
	var event_id = form.getChildren('input[name=event_id]').getLast().getProperty('value');
	var error = false;

	fields[event_id.toString()].each(function(el,idx){
		var field_value = undefined;
		var field = undefined;
		switch (el.type) {
			case 'select':
				field = form.getElements('select[name=' + el['var'] + ']');
				field_value = field.getLast().getProperty('value');
				break;
			case 'checkbox':
				field = form.getElements('input[name='+el['var']+'[]]');
				if (form.getElements('input[name='+el['var']+'[]]:checked').length == 0) {
					field_value='';
				} else {
					//field_value=$$('input[name='+el['var']+'[]]:checked').getLast().getProperty('value')
					field_value = 'yes'; //doesn't matter just for validation!
				}
				break;
			case 'radio':
				field = form.getElements('input[name='+el['var']+']');
				if (form.getElements('input[name='+el['var']+']:checked').length == 0) {
					field_value='';
				} else {
					field_value=$$('input[name='+el['var']+']:checked').getLast().getProperty('value');
				}
				break;
			case 'textarea':
				field = form.getElements('textarea[name='+el['var']+']');
				field_value=form.getElements('textarea[name='+el['var']+']').getLast().getProperty('value')
				break;

			default:
				field = form.getElements('input[name='+el['var']+']');
				field_value = form.getElements('input[name='+el['var']+']').getLast().getProperty('value');
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