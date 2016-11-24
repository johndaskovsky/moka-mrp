<?php check_admin_referer( 'mokamrp_export_table','mokamrp_export_table_nonce' ); ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php
	if(isset($_POST["table_name"]))
	{
		$table_name = $_POST["table_name"];	
		
		if($table_name == "customers") {
			$table = get_table_name("customers");
		}
		elseif ($table_name == "classes") {
			$table = get_table_name("classes");
		}
		elseif ($table_name == "registrations") {
			$table = get_table_name("registrations");
		}
		elseif ($table_name == "payments") {
			$table = get_table_name("payments");
		}
		else {
			//No proper table was selected, so escape.
			redirect_to("admin.php?page=mokamrp_home");
		}
			
		$query = "SELECT * FROM " . $table;
		query_to_csv($query, $table_name . "_backup_" . today() . ".csv", true);
	}
	exit;	
?>