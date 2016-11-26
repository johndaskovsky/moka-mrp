<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

<?php	
  display_create_page("lines"); 

  $recipe_id = $_GET['recipe_id'];
  display_recipe_lines($recipe_id);
?>

<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>