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
	if(get_row_by_id($id, "lines") == NULL) {
		//If class selected does not exist, escape.
		redirect_to("admin.php?page=mokamrp_home");
	}
	if (isset($_POST['submit'])) {
		check_admin_referer( 'mokamrp_edit_lines','mokamrp_edit_lines_nonce' );
			
		$recipe_id = stripslashes_deep($_POST['recipe_id']);
		$material_type = stripslashes_deep($_POST['material_type']);
		$material_id = stripslashes_deep($_POST['material_id']);
		$units = stripslashes_deep($_POST['units']);
		$cost_responsibility = stripslashes_deep($_POST['cost_responsibility']);

		$table = get_table_name("lines");
		
		$result = $wpdb->update( 
			$table, 
			array( 					
				'recipe_id' => $recipe_id,
				'material_type' => $material_type,
				'material_id' => $material_id,
				'units' => $units,
				'cost_responsibility' => $cost_responsibility 
			), 
			array( 'id' => $id ), 
			array( 
				'%d', // $recipe_id,
				'%d', // $material_type,
				'%d', // $material_id,
				'%f', // $units,
				'%f'  // $cost_responsibility 
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

<?php	
	display_edit_page("lines", $message); 
	
	$row = get_row_by_id($id, 'lines'); 
	$recipe_id = $row['recipe_id']; 
	display_recipe_lines($recipe_id);
?>


<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>