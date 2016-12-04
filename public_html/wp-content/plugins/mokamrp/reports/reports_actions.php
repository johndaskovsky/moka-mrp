<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php	display_admin_navigation("reports"); ?>

<?php display_report_navigation("actions"); ?>

<legend>Edit Actions</legend>

<?php display_actions(); ?>
	
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>