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
	if(get_row_by_id($id, "groups") == NULL) {
		//If class selected does not exist, escape.
		redirect_to("admin.php?page=mokamrp_home");
	}
	if (isset($_POST['submit'])) {
		check_admin_referer( 'mokamrp_edit_groups','mokamrp_edit_groups_nonce' );
			
		$name = stripslashes_deep($_POST['name']);

		$table = get_table_name("groups");
		
		$result = $wpdb->update( 
			$table, 
			array( 					
				'name' => $name
			), 
			array( 'id' => $id ), 
			array( 
				'%s' //name
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

<?php	display_edit_page("groups", $message); ?>


<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>