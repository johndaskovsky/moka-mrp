<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

<h2>Add Student</h2>

<form action="admin.php?page=mokamrp_create_customer&amp;noheader=true" method="post">
	<?php wp_nonce_field( 'mokamrp_create_customer','mokamrp_create_customer_nonce' );  ?>

<?php 
	$edit = false;
	include(MOKAMRP_PATH . "/includes/customer_form.php"); 
?>	
						
	<div class="form-actions">
	  <button type="submit" class="btn btn-primary">Add Student</button>
	  <a href="admin.php?page=mokamrp_new_customer" class="btn">Cancel</a>
	</div>	
</form>


<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>