<?php 

if ( current_user_can('manage_options') ) {
  echo "<legend>Accounting</legend>";

  //Purchase
  echo "<form action=\"admin.php?page=mokamrp_new_actions&amp;recipe_id=0\" method=\"post\">";
  echo "<div class=\"form-actions\">
      <input type=\"submit\" name=\"submit\" id=\"submit\" class=\"btn btn-primary\" value=\"Purchase\">
      </div>";
  echo "</form>";

  //Loss
  echo "<form action=\"admin.php?page=mokamrp_new_actions&amp;recipe_id=-1\" method=\"post\">";
  echo "<div class=\"form-actions\">
      <input type=\"submit\" name=\"submit\" id=\"submit\" class=\"btn btn-primary\" value=\"Loss\">
      </div>";
  echo "</form>";

  //Sale
  echo "<form action=\"admin.php?page=mokamrp_new_actions&amp;recipe_id=-2\" method=\"post\">";
  echo "<div class=\"form-actions\">
      <input type=\"submit\" name=\"submit\" id=\"submit\" class=\"btn btn-primary\" value=\"Sale\">
      </div>";
  echo "</form>";
}

  echo "<legend>Recipes</legend>";

  //Other recipes
  $recipes = get_all_table_rows("recipes");
  $all_groups = get_all_table_rows("groups");
  foreach ($recipes as $row) {
    echo "<form action=\"admin.php?page=mokamrp_new_actions&amp;recipe_id={$row['id']}\" method=\"post\">";
    echo "<div class=\"form-actions\">";
    echo "<select id=\"group_id\" style=\"margin-bottom:0;\" class=\"input-large\" name=\"group_id\" required>";
    
    $groups_string = get_recipe_groups($row['id']);
    if($groups_string == "") {
      $groups = $all_groups;
    } else {
      $groups_array = explode(",", $groups_string);
      $groups = [];
      foreach($all_groups as $group_row)
      {
        if(in_array($group_row['id'], $groups_array)){
          array_push($groups,$group_row);
        }
      }
    }

    echo array_to_option_list($groups, "id", "name", NULL, NULL, TRUE, ""); 

    echo "</select>";
    echo "&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"submit\" id=\"submit\" class=\"btn btn-primary\" value=\"{$row['name']}\">";
    echo "</div>";
    echo "</form>";
  }
?>
