<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>

<?php
	if (!isset($_GET['id']) || intval($_GET['id']) == 0) {
		//If no class is selected, escape.
		redirect_to("admin.php?page=mokamrp_home");
	} 
	else 
	{ 
		$id = $_GET['id']; 
	}
	if(get_row_by_id($id, "logs") == NULL) {
		//If class selected does not exist, escape.
		redirect_to("admin.php?page=mokamrp_home");
	}
	if (isset($_POST['submit'])) {
		check_admin_referer( 'mokamrp_edit_logs','mokamrp_edit_logs_nonce' );
			
		//$action_id = stripslashes_deep($_POST['action_id']);
		$material_id = stripslashes_deep($_POST['material_id']);
		$recipe_id = stripslashes_deep($_POST['recipe_id']);
		$units = stripslashes_deep($_POST['units']);
		$type = stripslashes_deep($_POST['type']);
		$cost = stripslashes_deep($_POST['cost']);
		$user = stripslashes_deep($_POST['user']);
		$notes = stripslashes_deep($_POST['notes']);

		$table = get_table_name("logs");
		
		$result = $wpdb->update( 
			$table, 
			array( 					
				//'action_id' => $action_id,
				'material_id' => $material_id,
				'recipe_id' => $recipe_id,
				'units' => $units,
				'type' => $type,
				'cost' => $cost,
				'user' => $user,
				'notes' => $notes  
			), 
			array( 'id' => $id ), 
			array( 
				//'%d', //action_id
				'%d', //material_id
				'%d', //recipe_id
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
			$message = "<div class=\"alert alert-success\">The update was successful. WOOHOO!</div>";
		} else {
			// Failed
			$message = "<div class=\"alert alert-error\">The update failed, or no changes were made.</div>";
		}
	} // end: if (isset($_POST['submit']))

?>

<?php	display_edit_page("logs", $message); ?>


<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>