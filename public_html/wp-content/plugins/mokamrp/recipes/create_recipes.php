<?php check_admin_referer( 'mokamrp_create_recipes','mokamrp_create_recipes_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php

	$name = stripslashes_deep($_POST['name']);
	$order = stripslashes_deep($_POST['order']);

	$table = get_table_name("recipes");

	$result = $wpdb->insert( 
			$table, 
			array( 
				'name' => $name,
				'order' => $order   
			), 
			array( 
				'%s', //name
				'%d' //order
			) 
		);
			
	if ($result != false) {
		// Success!
		redirect_to("admin.php?page=mokamrp_new_recipes");
	} 
	else {
		// Display error message.
		echo "<p>Creation failed.</p>";
		echo "<p>" . $wpdb->print_error() . "</p>";
	}
?>
