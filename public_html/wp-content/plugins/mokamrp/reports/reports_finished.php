<?php
  if (!isset($_GET['id']) || intval($_GET['id']) == 0) {
    //If no class is selected, escape.
    wp_redirect("admin.php?page=mokamrp_home");
    exit;
  } 
  else 
  { 
    $id = $_GET['id']; 
  }
?>
<?php require_once(MOKAMRP_PATH . "/includes/functions.php"); ?>
<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php display_admin_navigation("reports"); ?>

<?php display_report_navigation("finished"); ?>

<legend>Finished Goods Lots Report</legend>

<?php display_finished($id); ?>
  
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>