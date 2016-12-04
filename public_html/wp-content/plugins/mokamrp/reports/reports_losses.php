<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php	display_admin_navigation("reports"); ?>

<?php display_report_navigation("losses"); ?>

<legend>Losses Report</legend>

<?php display_losses(); ?>
	
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>