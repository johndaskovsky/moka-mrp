<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>


  <!--Admin Navigation-->
<?php	display_admin_navigation("add"); ?>


<legend>Add a Material Group</legend>

<form action="admin.php?page=mokamrp_create_class&amp;noheader=true" method="post">		
	<?php wp_nonce_field( 'mokamrp_create_class','mokamrp_create_class_nonce' );  ?>

	<?php 
		$edit = false;
		include(MOKAMRP_PATH . "/includes/class_form.php"); 
	?>				
					
	<div class="form-actions">
	  <button type="submit" class="btn btn-primary">Add Group</button>
	  <a href="admin.php?page=mokamrp_new_class" class="btn">Cancel</a>
	</div>	
</form>

<legend>Groups</legend>

<?php                
 	display_table_list("groups");
?>


<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>