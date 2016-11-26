<?php check_admin_referer( 'mokamrp_create_logs','mokamrp_create_logs_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php

	$action_id = 0;//stripslashes_deep($_POST['action_id']);
	$material_id = stripslashes_deep($_POST['material_id']);
	$recipe_id = stripslashes_deep($_POST['recipe_id']);
	$units = stripslashes_deep($_POST['units']);
	$type = 0; //stripslashes_deep($_POST['type']);
	$cost = 0; //stripslashes_deep($_POST['cost']);
	$user = 0; //stripslashes_deep($_POST['user']);

	$table = get_table_name("logs");

	$result = $wpdb->insert( 
			$table, 
			array( 
				'action_id' => $action_id,
				'material_id' => $material_id,
				'recipe_id' => $recipe_id,
				'units' => $units,
				'type' => $type,
				'cost' => $cost,
				'user' => $user  
			), 
			array( 
				'%d', //action_id
				'%d', //material_id
				'%d', //recipe_id
				'%d', //units
				'%d', //type
				'%d', //cost
				'%s' //user
			) 
		);
			
	if ($result != false) {
		// Success!
		redirect_to("admin.php?page=mokamrp_new_logs");
	} 
	else {
		// Display error message.
		echo "<p>Creation failed.</p>";
		echo "<p>" . $wpdb->print_error() . "</p>";
	}
?>
