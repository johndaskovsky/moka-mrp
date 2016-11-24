<?php check_admin_referer( 'mokamrp_create_class','mokamrp_create_class_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php

	/* Error Handling ------
	$errors = array();
	$required_fields = array('public_id', 'title', 'teacher');
	foreach($required_fields as $fieldname) {
		if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
			$errors[] = $fieldname;
		}
	}
	if (!empty($errors)) {
		redirect_to("admin.php?page=mokamrp_new_class");
	}
	*/

	$name = stripslashes_deep($_POST['name']);


	$classes = get_table_name("groups");

	$result = $wpdb->insert( 
			$classes, 
			array( 
				'name' => $name  
			), 
			array( 
				'%s' //name
			) 
		);
	
	//Create Cart66 Product
	$new_id = $wpdb->insert_id;
		
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