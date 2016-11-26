<?php
  if( !isset($_GET['id']) ) {
    wp_redirect("admin.php?page=mokamrp_home");
    exit;
  }
?>
<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>
<?php	display_create_page("actions"); ?>
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>
