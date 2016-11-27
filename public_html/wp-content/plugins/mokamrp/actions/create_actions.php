<?php check_admin_referer( 'mokamrp_create_actions','mokamrp_create_actions_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php
/*
	$table = get_table_name("logs");

	//General values used for all lines
	$action_id = 4; //get_new_action_id();
	$user = "user"; //stripslashes_deep($_POST['user']);

	//foreach($_POST['line'] as $row) {
		$result = $wpdb->insert( 
			$table, 
			array( 
				'action_id' => $action_id,
				'material_id' => 4;//$row['material_id'],
				'recipe_id' => 5;//$row['recipe_id']),
				'units' => 7; //$row['units']),
				'type' => 1;//$row['type']),
				'cost' => 22;//$row['cost']),
				'user' => $user,
				'notes' => "test";//$row['notes'])  
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
		if ($result == false) {
			// Display error message.
			echo "<p>Creation failed.</p>";
			echo "<p>" . $wpdb->print_error() . "</p>";
		}
	//}		

	redirect_to("admin.php?page=mokamrp_home");		
*/

	$lines = $_POST['line'];

	//General variables that apply to all lines
	$action_id = get_next_action_id();
	$current_user = wp_get_current_user();
	$user = $current_user->display_name;

	$table = get_table_name("logs");

	foreach($lines as $row) {
		$material_id = stripslashes_deep($row['material_id']);
		$recipe_id = stripslashes_deep($row['recipe_id']);
		$units = stripslashes_deep($row['units']);
		$type = stripslashes_deep($row['type']);
		$notes = stripslashes_deep($row['notes']);

		if($recipe_id == 0) {
			$cost = stripslashes_deep($row['cost']);
		} else {
			if($type == -1) {
				$cost = get_cost_of_input($material_id,$units);
			} else {
				$cost = 100; //TODO: get_cost_of_output($material_id,$units);
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
