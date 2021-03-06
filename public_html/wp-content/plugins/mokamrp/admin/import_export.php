<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php	display_admin_navigation("import"); ?>

<legend>Export Database Table to CSV</legend>
<form action="admin.php?page=mokamrp_export_table&amp;noheader=true" method="POST">
	<?php wp_nonce_field( 'mokamrp_export_table','mokamrp_export_table_nonce' ); ?>
	<select name="table_name" required="required">
	  <option value="groups">Groups</option>
	  <option value="recipes">Recipes</option>
	  <option value="materials">Materials</option>
	  <option value="lines">Lines</option>
    <option value="logs">Logs</option>
	</select>
 
    <div class="form-actions">
     <button type="submit" class="btn btn-primary">Export</button>
    </div>
</form>
	
<br>


<legend>Import Database Table from CSV</legend>

<?php if(isset($_GET['check'])) {
	if ($_GET['check'] == true) {
		// Success
		$message = "<div class=\"alert alert-success\">The import was successful.</div>";
	} else {
		// Failed
		$message = "<div class=\"alert alert-error\">The import failed.</div>";
	}
	echo $message;
}
?>

<form enctype="multipart/form-data" action="admin.php?page=mokamrp_import_table&amp;noheader=true" method="POST">
	<?php wp_nonce_field( 'mokamrp_import_table','mokamrp_import_table_nonce' ); ?>
	<select name="table_name" required="required">
	  <option value="groups">Groups</option>
    <option value="recipes">Recipes</option>
    <option value="materials">Materials</option>
    <option value="lines">Lines</option>
    <option value="logs">Logs</option>
	</select>
     <br>
     <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
     <input id="filename" name="filename" type="file" accept="text/csv" required="required">
 
    <div class="form-actions">
     <button type="submit" class="btn btn-primary">Import</button>
     <a class="btn" href="admin.php?page=mokamrp_import_export">Cancel</a>
    </div>
</form>

<div class="alert alert-info">
<strong>Note:</strong> To replace/edit database data, CSV must include an 'id' column. To batch add new rows, do not include an 'id' column.
</div>
	
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>