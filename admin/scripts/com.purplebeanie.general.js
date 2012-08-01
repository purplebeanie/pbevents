/*
---
name: com.purplebeanie.general.js
description: Provides some general functions that I use a lot.
authors: Eric Fernance
requires: [Picker, Core/Element.Event]
...
*/

function add_table_row(table)
{
	var last_row = $$(table+' tr').getLast();
	var new_row = $(last_row).clone();
	$$(table).adopt(new_row);

	new_row.getElements('input').each(function(el,idx){
		var re = /.*\[\d+\]/;
		if (re.test(el.getProperty('name'))) {
			var cur_row = el.getProperty('name').replace(/.*\[(\d+)\]/,'$1');
			var new_row = parseInt(cur_row) + 1;
			var new_name = el.getProperty('name').replace(/(.*\[)(\d+)(\])/,'$1'+new_row+'$3');
			el.setProperty('name',new_name);

		} 

	});

	new_row.getElements('select').each(function(el,idx){
		var re = /.*\[\d+\]/;
		if (re.test(el.getProperty('name'))) {
			var cur_row = el.getProperty('name').replace(/.*\[(\d+)\]/,'$1');
			var new_row = parseInt(cur_row) + 1;
			var new_name = el.getProperty('name').replace(/(.*\[)(\d+)(\])/,'$1'+new_row+'$3');
			el.setProperty('name',new_name);
		} 
	});
}

function del_table_row(element)
{
	var tr = element.parentNode.parentNode;
	tr.parentNode.removeChild(tr);
}
