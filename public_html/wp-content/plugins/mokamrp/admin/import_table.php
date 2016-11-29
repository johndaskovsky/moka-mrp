<?php check_admin_referer( 'mokamrp_import_table','mokamrp_import_table_nonce' ); ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php
	if(isset($_FILES["filename"]['tmp_name']) && isset($_POST["table_name"]))
	{	
		$file = $_FILES["filename"]['tmp_name'];
		$table_name = $_POST["table_name"];

		$table = get_table_name($table_name);
		
		if ($_FILES["filename"]["error"] > 0 || $_FILES["filename"]["type"] != "text/csv") 
		{
            echo "Error uploading or wrong file type. File must be a CSV.";
			$check = false;
        }
        else
        {
			csv_file_to_mysql_table($_FILES["filename"]['tmp_name'], $table);
			$check = true;
		}	
	}
	$redirect_to = admin_url( "admin.php?page=mokamrp_import_export&check={$check}" );
	wp_safe_redirect( $redirect_to );
	exit;	
?>