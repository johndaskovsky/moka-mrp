<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php display_admin_navigation("reports"); ?>

<?php display_report_navigation("purchases"); ?>

<legend>Purchases Report</legend>

<?php display_purchases(); ?>
  
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>