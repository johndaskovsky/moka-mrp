<?php check_admin_referer( 'mokamrp_create_class','mokamrp_create_class_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php

	$name = stripslashes_deep($_POST['name']);

	$table = get_table_name("groups");

	$result = $wpdb->insert( 
			$table, 
			array( 
				'name' => $name  
			), 
			array( 
				'%s' //name
			) 
		);
			
	if ($result != false) {
		// Success!
		redirect_to("admin.php?page=mokamrp_new_class");
	} 
	else {
		// Display error message.
		echo "<p>Class creation failed.</p>";
		echo "<p>" . $wpdb->print_error() . "</p>";
	}
?>
