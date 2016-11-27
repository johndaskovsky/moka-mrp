<?php check_admin_referer( 'mokamrp_create_actions','mokamrp_create_actions_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php
	$lines = $_POST['line'];

	//General variables that apply to all lines
	$action_id = get_next_action_id();
	$current_user = wp_get_current_user();
	$user = $current_user->display_name;
	$total_cost_of_inputs = 0;

	$table = get_table_name("logs");

	foreach($lines as $row) {
		$material_id = stripslashes_deep($row['material_id']);
		$recipe_id = stripslashes_deep($row['recipe_id']);
		$units = stripslashes_deep($row['units']);
		$type = stripslashes_deep($row['type']);
		$notes = stripslashes_deep($row['notes']);
		$cost_responsibility = stripslashes_deep($row['cost_responsibility']);

		if($recipe_id == 0) {
			//Purchases
			$cost = stripslashes_deep($row['cost']);
		} else {
			if($type == -1) {
				//Inputs and Losses
				$cost = get_cost_of_input($material_id,$units);
				$total_cost_of_inputs += $cost;
			} else {
				//Outputs
				$cost = $total_cost_of_inputs * ($cost_responsibility / 100); 
			}
		}
		
		$result = $wpdb->insert( 
				$table, 
				array( 
					'action_id' => $action_id,
					'material_id' => $material_id,
					'recipe_id' => $recipe_id,
					'units' => $units,
					'type' => $type,
					'cost' => $cost,
					'user' => $user,
					'notes' => $notes  
				), 
				array( 
					'%d', //action_id
					'%d', //material_id
					'%d', //recipe_id
					'%f', //units
					'%d', //type
					'%f', //cost
					'%s', //user
					'%s' //notes
				) 
			);
	}
			
	redirect_to("admin.php?page=mokamrp_home");
?>
