<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

<?php	
  $recipe_id = $_GET['recipe_id'];
  $recipe_name = get_name_by_id($recipe_id, "recipes");

  display_create_page("lines", "Add lines to recipe: {$recipe_name}"); 
  
  display_recipe_lines($recipe_id);
?>

<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>