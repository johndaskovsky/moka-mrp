<?php check_admin_referer( 'mokamrp_export_table','mokamrp_export_table_nonce' ); ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php
	if(isset($_POST["table_name"]))
	{
		$table_name = $_POST["table_name"];	
		$table = get_table_name($table_name);
			
		$query = "SELECT * FROM " . $table;
		query_to_csv($query, $table_name . "_backup_" . today() . ".csv", true);
	}
	exit;	
?>