<?php check_admin_referer( 'mokamrp_create_lines','mokamrp_create_lines_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php

	$recipe_id = stripslashes_deep($_POST['recipe_id']);
	$material_type = stripslashes_deep($_POST['material_type']);
	$material_id = stripslashes_deep($_POST['material_id']);
	$source = stripslashes_deep($_POST['source']);
	$units = stripslashes_deep($_POST['units']);
	$cost_responsibility = stripslashes_deep($_POST['cost_responsibility']);


	$table = get_table_name("lines");

	$result = $wpdb->insert( 
			$table, 
			array( 
				'recipe_id' => $recipe_id,
				'material_type' => $material_type,
				'material_id' => $material_id,
				'source' => $source,
				'units' => $units,
				'cost_responsibility' => $cost_responsibility  
			), 
			array( 
				'%d', // $recipe_id,
				'%d', // $material_type,
				'%d', // $material_id,
				'%d', // $source,
				'%d', // $units,
				'%d'  // $cost_responsibility  
			) 
		);
			
	if ($result != false) {
		// Success!
		redirect_to("admin.php?page=mokamrp_new_lines");
	} 
	else {
		// Display error message.
		echo "<p>Creation failed.</p>";
		echo "<p>" . $wpdb->print_error() . "</p>";
	}
?>
