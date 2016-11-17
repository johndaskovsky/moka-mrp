<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php	display_admin_navigation("clear"); ?>

<legend>Clear a Database Table</legend>
<div class="alert">
<strong>Warning:</strong> This function is strictly for administrator use.
</div>

<form action="admin.php?page=mokamrp_clear_table&amp;noheader=true" method="POST">
	<?php wp_nonce_field( 'mokamrp_clear_table','mokamrp_clear_table_nonce' ); ?>
	<select name="table_name" required="required">
	  <option value="customers">Customers</option>
	  <option value="classes">Classes</option>
	  <option value="registrations">Registrations</option>
	  <option value="payments">Payments</option>
	</select>
 
    <div class="form-actions">
     <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure? This action cannot be undone.')">Clear Database Table</button>
    </div>
</form>
	
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>