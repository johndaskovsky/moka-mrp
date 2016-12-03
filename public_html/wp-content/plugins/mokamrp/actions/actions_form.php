<?php 
  $recipe_id = stripslashes_deep($_GET['recipe_id']);
  $group_id = stripslashes_deep($_POST['group_id']);

  if($recipe_id == 0 || $recipe_id == -1 || $recipe_id == -2): 
?>
<!-- PURCHASE OR LOSS -->
  <h1>
    <?php
      if($recipe_id == 0) {
        echo "Purchase";
      } elseif($recipe_id == -1) {
        echo "Loss";
      } else {
        echo "Sale";
      }
    ?>
  </h1>
  <div class="row-fluid">
    <div class="span3"> 
      <div class="control-group">  
        <label class="control-label" for="material_id">Material</label>  
        <div class="controls">  
          <select id="material_id" class="input-large" name="line[0][material_id]">
            <?php
              if ($recipe_id == 0) {
                //Purchase
                $results = get_all_table_rows("materials", "WHERE source = 0 ");
              } elseif($recipe_id == -1) {
                //Loss
                $results = get_all_table_rows("materials");
              } else {
                //Sale
                $results = get_all_table_rows("materials", "WHERE destination = -2 ");
              }
              
              echo array_to_option_list($results, "id", "name", NULL, NULL, false);
            ?>
          </select>
        </div>
      </div>
    </div>
    <div class="span3">  
      <label class="control-label" for="units">Weight/Units</label>
      <input class="span12" placeholder="Weight/Units" type="number" min="0" step="any" name="line[0][units]" value="" id="units">
    </div> 
    <?php
      if($recipe_id == 0) {
        echo "<div class=\"span3\">  
            <label class=\"control-label\" for=\"cost\">Cost</label>
            <input class=\"span12\" placeholder=\"Cost\" type=\"number\" min=\"0\" step=\"any\" name=\"line[0][cost]\" value=\"\" id=\"cost\">
          </div>"; 
      }
    ?>
    <div class="span3"> 
      <label class="control-label" for="notes">Notes</label>
      <textarea class="span12" rows="1" placeholder="Notes" type="text" name="line[0][notes]" id="notes"></textarea>
    </div>
  </div>
  <?php
    if($recipe_id == 0) {
      //Purchase
      echo "<input type=\"hidden\" name=\"line[0][recipe_id]\" value=\"0\" id=\"recipe_id\">
        <input type=\"hidden\" name=\"line[0][type]\" value=\"1\" id=\"type\">
        <input type=\"hidden\" name=\"line[0][cost_responsibility]\" value=\"0\" id=\"type\">";
    } elseif($recipe_id == -1) {
      //Loss
      echo "<input type=\"hidden\" name=\"line[0][recipe_id]\" value=\"-1\" id=\"recipe_id\">
        <input type=\"hidden\" name=\"line[0][type]\" value=\"-1\" id=\"type\">
        <input type=\"hidden\" name=\"line[0][cost_responsibility]\" value=\"0\" id=\"type\">";
    } else {
      //Sale
      echo "<input type=\"hidden\" name=\"line[0][recipe_id]\" value=\"-2\" id=\"recipe_id\">
        <input type=\"hidden\" name=\"line[0][type]\" value=\"-1\" id=\"type\">
        <input type=\"hidden\" name=\"line[0][cost_responsibility]\" value=\"0\" id=\"type\">";
    }
  ?>

<?php else: ?>
<!-- OTHER ACTIONS -->
<?php 
  $output_not_reached = true;
  $recipe_name = get_name_by_id($recipe_id, "recipes");
  echo "<h1>{$recipe_name}</h1>";
  echo "<legend style=\"font-size:18px\">Inputs</legend>";

  $recipe_lines = get_recipe_lines($recipe_id);

  foreach($recipe_lines as $key=>$line) {
    if($line['material_type'] == 1 && $output_not_reached){
        echo "<legend style=\"font-size:18px\">Outputs</legend>";
        $output_not_reached = false;
    }

    echo "<div class=\"row-fluid\">
        <div class=\"span3\"> 
          <div class=\"control-group\">  
            <label class=\"control-label\" for=\"material_id\">Material</label>  
            <div class=\"controls\">  
              <select id=\"material_id\" class=\"input-large\" name=\"line[{$key}][material_id]\">";
    
    $results = array();
    if($line['material_id'] == 0) {
      //Variable
      $results = get_variable_options($line['recipe_id'],$line['material_type'],$group_id);
      if($results == NULL) {
        $results = get_all_table_rows("materials");
      }
    } else {
      //Fixed
      $results[] = get_row_by_id($line['material_id'], "materials");
    }
    

    echo array_to_option_list($results, "id", "name", NULL, NULL, false);
              
    echo "</select>
            </div>
          </div>
        </div>
        <div class=\"span3\">  
          <label class=\"control-label\" for=\"units\">Weight/Units</label>
          <input class=\"span12\" placeholder=\"Weight/Units\" type=\"number\" min=\"0\" step=\"any\" name=\"line[{$key}][units]\" value=\"{$line['units']}\" id=\"units\">
        </div> 
        <div class=\"span3\"> 
          <label class=\"control-label\" for=\"notes\">Notes</label>
          <textarea class=\"span12\" rows=\"1\" placeholder=\"Notes\" type=\"text\" name=\"line[{$key}][notes]\" id=\"notes\">{$row['notes']}</textarea>
          </div>
        </div>";
    echo "<input type=\"hidden\" name=\"line[{$key}][recipe_id]\" value=\"{$line['recipe_id']}\" id=\"recipe_id\">
        <input type=\"hidden\" name=\"line[{$key}][type]\" value=\"{$line['material_type']}\" id=\"type\">
        <input type=\"hidden\" name=\"line[{$key}][cost_responsibility]\" value=\"{$line['cost_responsibility']}\" id=\"type\">";

  }
?>

<?php endif; ?>
