<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php
	if (!isset($_GET['id']) || intval($_GET['id']) == 0) {
		//If no class is selected, escape.
		wp_redirect("admin.php?page=mokamrp_home");
		exit;
	} 
	else 
	{ 
		$id = $_GET['id']; 
	}
	if (isset($_POST['submit'])) {
		check_admin_referer( 'mokamrp_edit_actions','mokamrp_edit_actions_nonce' );
			
		$lines = $_POST['line'];

		//General variables that apply to all lines
		$current_user = wp_get_current_user();
		$user = $current_user->display_name;
		$total_cost_of_inputs = 0;
		$success = 0;

		$table = get_table_name("logs");

		foreach($lines as $row) {
			$log_id = stripslashes_deep($row['log_id']);
			$material_id = stripslashes_deep($row['material_id']);
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
			
			$result = $wpdb->update( 
					$table, 
					array( 
						'material_id' => $material_id,
						'units' => $units,
						'type' => $type,
						'cost' => $cost,
						'user' => $user,
						'notes' => $notes  
					),
					array( 'id' => $log_id ),  
					array( 
						'%d', //material_id
						'%f', //units
						'%d', //type
						'%f', //cost
						'%s', //user
						'%s' //notes
					),
					array( '%d' ) //id 
				);

			if ($result == 1) {
				// Success
				$success++;
			}
		}
		
		if ($success >= 1) {
			// Success
			$message = "<div class=\"alert alert-success\">The update was successful. WOOHOO!</div>";
		} else {
			// Failed
			$message = "<div class=\"alert alert-error\">The update failed, or no changes were made.</div>";
		}
	} // end: if (isset($_POST['submit']))

?>

<?php	display_action_edit_page($message); ?>

<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>
