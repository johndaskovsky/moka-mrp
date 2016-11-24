<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>

<?php
	if (!isset($_GET['class_id']) || intval($_GET['class_id']) == 0) {
		//If no class is selected, escape.
		redirect_to("admin.php?page=mokamrp_home");
	} 
	else 
	{ 
		$class_id = $_GET['class_id']; 
	}
	if(get_row_by_id($class_id, "groups") == NULL) {
		//If class selected does not exist, escape.
		redirect_to("admin.php?page=mokamrp_home");
	}
	if (isset($_POST['submit'])) {
		check_admin_referer( 'mokamrp_edit_class','mokamrp_edit_class_nonce' );
			
		$errors = array();

		//Form Validation (this should be a function - duplicate on create class)
		/*
		$required_fields = array('public_id', 'title', 'teacher');
		foreach($required_fields as $fieldname) {
			if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
				$errors[] = $fieldname;
			}
		}
		*/

		if (!empty($errors)) {
			redirect_to("admin.php?page=mokamrp_home");
		} else {
			// Perform Update
			$name = stripslashes_deep($_POST['name']);

			$classes = get_table_name("groups");
			
			$result = $wpdb->update( 
				$classes, 
				array( 					
					'name' => $name
				), 
				array( 'id' => $class_id ), 
				array( 
					'%s' //name
				), 
				array( '%d' ) //class_id
			);

						
			if ($result == 1) {
				// Success
				$message = "<div class=\"alert alert-success\">The update was successful. WOOHOO!</div>";
			} else {
				// Failed
				$message = "<div class=\"alert alert-error\">The update failed, or no changes were made.</div>";
			}
			
		}
	} // end: if (isset($_POST['submit']))

?>

<?php 
	$class_id = $_GET['class_id']; 
	$class = get_row_by_id($class_id, 'groups');

?>
<?php include("includes/header.php"); ?>

<h2>Edit Group: <?php echo $class['name']; ?></h2>

<?php if (!empty($message)) {
	echo "<p>" . $message . "</p>";
} ?>

<form action="admin.php?page=mokamrp_edit_class&amp;class_id=<?php echo urlencode($class['id']); ?>" method="post">
	<?php wp_nonce_field( 'mokamrp_edit_class','mokamrp_edit_class_nonce' );  ?>
	<?php 
		$edit = true;
		include("includes/class_form.php"); 
	?>	
				
	<div class="form-actions">
	  <input type="submit" name="submit" id="submit" value="Save Changes" class="btn btn-primary">
	  <a href="admin.php?page=mokamrp_edit_class&amp;class_id=<?php echo urlencode($class['id']); ?>" class="btn">Cancel</a>
	</div>	

</form>

<legend>Groups</legend>

<?php                
 	display_table_list("groups");
?>


<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>