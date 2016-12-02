<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php	display_admin_navigation("reports"); ?>

<legend>Current Inventory Report</legend>

<?php display_inventory(); ?>
	
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>