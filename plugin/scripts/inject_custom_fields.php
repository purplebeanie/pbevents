<?php

	//error_log('inject_custom_fields');

?>
<script>
	if (typeof fields === 'undefined')
		fields = [];
	//fields exist now ... so let's add the custom fields to the existing fields array
	fields["<?php echo $event->id;?>"] = <?php echo $event->fields;?>;
</script>