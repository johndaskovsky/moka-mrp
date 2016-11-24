<?php
	if (!isset($_GET['class_id']) || intval($_GET['class_id']) == 0) {
		//If no class is selected, escape.
		redirect_to("admin.php?page=mokamrp_home");
	} else { $class_id = $_GET['class_id']; }
	if(get_class_by_id($class_id) == NULL) {
		//If class selected does not exist, escape.
		redirect_to("admin.php?page=mokamrp_home");
	}
?>
<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>


  <!--Admin Navigation-->
	<ul class="nav nav-pills" style="padding-right: 0px; padding-left: 0px;">				  
	  <li class="active"><a href="admin.php?page=mokamrp_new_class">Add Classes</a></li>
	  <li><a href="admin.php?page=mokamrp_zout">Z-Out Classes</a></li>
	  <li><a href="admin.php?page=mokamrp_import_export">Import/Export</a></li>
	  <li><a href="admin.php?page=mokamrp_clear_database">Clear Database</a></li>
	  <li><a href="admin.php?page=mokamrp_mailing_list">Export Mailing List</a></li>
	</ul>


<legend>Clone a Class</legend>

<form action="admin.php?page=mokamrp_create_class&amp;noheader=true" method="post">		
	<?php wp_nonce_field( 'mokamrp_create_class','mokamrp_create_class_nonce' );  ?>

	<?php 
		$class = get_class_by_id($class_id);
		$edit = true;
		include(MOKAMRP_PATH . "/includes/class_form.php"); 
	?>				
					
	<div class="form-actions">
	  <button type="submit" class="btn btn-primary">Add Class</button>
	  <a href="admin.php?page=mokamrp_new_class" class="btn">Cancel</a>
	</div>	
</form>


<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>