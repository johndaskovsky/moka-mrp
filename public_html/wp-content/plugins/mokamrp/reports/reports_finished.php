<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php display_admin_navigation("reports"); ?>

<?php display_report_navigation("finished"); ?>

<legend>Finished Goods Report</legend>

<?php display_finished(); ?>
  
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>