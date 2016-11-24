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

			$result = $wpdb->insert( 
					$classes, 
					array( 
						'name' => $name  
					), 
					array( 
						'%s' //name
					) 
				);

			$classes = get_table_name("classes");
			
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
				$message = "<div class=\"alert alert-success\">The class was successfully updated.</div>";
			} else {
				// Failed
				$message = "<div class=\"alert alert-error\">The class update failed, or no changes were made.</div>";
				$message .= "<br />". $wpdb->print_error();
			}
			
		}
	} // end: if (isset($_POST['submit']))

?>

<?php 
	$class_id = $_GET['class_id']; 
	$class = get_row_by_id($class_id, 'groups');

?>
<?php include("includes/header.php"); 
	echo $class['name'];
?>

<h2>Edit Group: <?php echo $class['name']; ?></h2>

<?php if (!empty($message)) {
	echo "<p>" . $message . "</p>";
} ?>
<?php
// output a list of the fields that had errors
if (!empty($errors)) {
	echo "<p class=\"errors\">";
	echo "Please review the following fields:<br />";
	foreach($errors as $error) {
		echo " - " . $error . "<br />";
	}
	echo "</p>";
}
?>
<form action="admin.php?page=mokamrp_edit_class&amp;class_id=<?php echo urlencode($class['id']); ?>" method="post">
	<?php wp_nonce_field( 'mokamrp_edit_class','mokamrp_edit_class_nonce' );  ?>
	<?php 
		$edit = true;
		include("includes/class_form.php"); 
	?>	
				
	<div class="form-actions">
	  <input type="submit" name="submit" id="submit" value="Save Changes" class="btn btn-primary">
	  <a href="admin.php?page=mokamrp_edit_class&amp;class_id=<?php echo urlencode($class['id']); ?>" class="btn">Cancel</a>
	  <?php if ( current_user_can('manage_options') ) { ?>
	  	<a href="#deleteClass" role="button" class="btn btn-small btn-danger pull-right" data-toggle="modal">Delete Class</a>
      <?php }  ?> 
	</div>	

</form>


<!-- Modal -->
<div id="deleteClass" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="myModalLabel">Delete Class: <?php echo $class['title'] . " with " . $class['teacher']; ?></h3>
  </div>
  <div class="modal-body">
    <div class="alert alert-block">
  		<h4>Warning!</h4>
  		This action cannot be undone.
	</div>
  </div>
  <div class="modal-footer">
  	<?php $form_action_url = "admin.php?page=mokamrp_delete_table_item&amp;noheader=true&amp;t=classes&amp;i=" . urlencode($class['class_id']); ?>	
	<form action="<?php echo $form_action_url; ?>" method="post">
		<?php wp_nonce_field('mokamrp_delete_table_item'); ?>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
		<input type="submit" name="submit" id="submit" value="Delete Class" class="btn btn-danger">
	</form>	
  </div>
</div>

<legend>Class List</legend>

<?php                
 	display_student_list($class_id);
?>


<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>