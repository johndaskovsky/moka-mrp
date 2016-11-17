<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>
<?php include(MOKAMRP_PATH . "/includes/class_pagination.php"); ?>
	
<?php 

if($time_range == 3) {
	display_classes($query, false, true); 
} else {
	display_classes($query);
}

?>
	
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>