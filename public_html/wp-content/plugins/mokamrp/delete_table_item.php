<?php check_admin_referer( 'mokamrp_delete_table_item'); ?>
<?php global $wpdb; ?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php
	if (!isset($_GET['t']) && !isset($_GET['i']) || intval($_GET['i']) == 0) {
		redirect_to("admin.php?page=mokamrp_home");
		exit;
	} else {
		$id = $_GET['i'];	
		$table_name = $_GET['t'];
		$table = get_table_name($table_name);
		$redirect_link = "admin.php?page=mokamrp_home";
	}
			
	$query = $wpdb->prepare("DELETE FROM {$table} WHERE id = %d", 
			$id);
			
	$result = $wpdb->query($query);
	
	if ($result != 0) {
		redirect_to( $redirect_link );
	} else {
		// Deletion Failed
		echo "<p>Item deletion failed.</p>";
		echo "<p>" . $wpdb->print_error() . "</p>";
		echo "<a href=\"admin.php?page=mokamrp_home\">Return to Main Page</a>";
	}

?>