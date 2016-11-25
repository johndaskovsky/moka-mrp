<?php check_admin_referer( 'mokamrp_create_materials','mokamrp_create_materials_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php

	$name = stripslashes_deep($_POST['name']);
	$group_id = stripslashes_deep($_POST['group_id']);
	$measure_type = stripslashes_deep($_POST['measure_type']);
	$source = stripslashes_deep($_POST['source']);
	$destination = stripslashes_deep($_POST['destination']);

	$table = get_table_name("materials");

	$result = $wpdb->insert( 
			$table, 
			array( 
				'name' => $name,
				'group_id' => $group_id,
				'measure_type' => $measure_type,
				'source' => $source,
				'destination' => $destination  
			), 
			array( 
				'%s', //name
				'%d', //group_id
				'%d', //measure_type
				'%d', //source
				'%d' //destination
			) 
		);
			
	if ($result != false) {
		// Success!
		redirect_to("admin.php?page=mokamrp_new_materials");
	} 
	else {
		// Display error message.
		echo "<p>Creation failed.</p>";
		echo "<p>" . $wpdb->print_error() . "</p>";
	}
?>
