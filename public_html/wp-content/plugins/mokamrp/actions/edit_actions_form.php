<?php 
  $action_id = stripslashes_deep($_GET['id']);
  $logs = get_logs_by_action_id($action_id);

  $recipe_id = $logs[0]['recipe_id'];

  if($recipe_id == 0 || $recipe_id == -1): 
?>
<!-- PURCHASE OR LOSS -->
  <h1>
    <?php
      if($recipe_id == 0) {
        echo "Purchase";
      } else {
        echo "Loss";
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
              $results = get_all_table_rows("materials");
              echo array_to_option_list($results, "id", "name", $logs[0]['material_id'], NULL, false);
            ?>
          </select>
        </div>
      </div>
    </div>
    <div class="span3">  
      <label class="control-label" for="units">Weight/Units</label>
      <input class="span12" placeholder="Weight/Units" type="number" min="0" step="any" name="line[0][units]" value="<?php echo "{$logs[0]['units']}" ?>" id="units">
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
      <textarea class="span12" rows="1" placeholder="Notes" type="text" name="line[0][notes]" id="notes"><?php echo "{$logs[0]['notes']}" ?></textarea>
    </div>
  </div>
  <?php
    if($recipe_id == 0) {
      echo "<input type=\"hidden\" name=\"line[0][recipe_id]\" value=\"0\" id=\"recipe_id\">
        <input type=\"hidden\" name=\"line[0][type]\" value=\"1\" id=\"type\">";
    } else {
      echo "<input type=\"hidden\" name=\"line[0][recipe_id]\" value=\"-1\" id=\"recipe_id\">
        <input type=\"hidden\" name=\"line[0][type]\" value=\"-1\" id=\"type\">";
    }
    echo "<input type=\"hidden\" name=\"line[0][cost_responsibility]\" value=\"0\" id=\"type\">";
    echo "<input type=\"hidden\" name=\"line[0][log_id]\" value=\"{$logs[0]['id']}\" id=\"type\">";
  ?>

<?php else: ?>
<!-- OTHER ACTIONS -->
<?php 
 // $output_not_reached = true;
 // $recipe_name = get_name_by_id($recipe_id, "recipes");
 // echo "<h1>{$recipe_name}</h1>";
 // echo "<legend style=\"font-size:18px\">Inputs</legend>";

/*
  foreach($logs as $key=>$log) {
    if($log[$key]['material_type'] == 1 && $output_not_reached){
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
    if($log[$key]['material_id'] == 0) {
      //Variable
      $results = get_variable_options($log[$key]['recipe_id'],$log[$key]['material_type'],$group_id);
      if($results == NULL) {
        $results = get_all_table_rows("materials");
      }
    } else {
      //Fixed
      $results[] = get_row_by_id($log[$key]['material_id'], "materials");
    }
    

    echo array_to_option_list($results, "id", "name", NULL, NULL, false);
              
    echo "</select>
            </div>
          </div>
        </div>
        <div class=\"span3\">  
          <label class=\"control-label\" for=\"units\">Weight/Units</label>
          <input class=\"span12\" placeholder=\"Weight/Units\" type=\"number\" min=\"0\" step=\"any\" name=\"line[{$key}][units]\" value=\"{$log[$key]['units']}\" id=\"units\">
        </div> 
        <div class=\"span3\"> 
          <label class=\"control-label\" for=\"notes\">Notes</label>
          <textarea class=\"span12\" rows=\"1\" placeholder=\"Notes\" type=\"text\" name=\"line[{$key}][notes]\" id=\"notes\">{$log[$key]['notes']}</textarea>
          </div>
        </div>";
    echo "<input type=\"hidden\" name=\"line[{$key}][recipe_id]\" value=\"{$log[$key]['recipe_id']}\" id=\"recipe_id\">
        <input type=\"hidden\" name=\"line[{$key}][type]\" value=\"{$log[$key]['material_type']}\" id=\"type\">
        <input type=\"hidden\" name=\"line[{$key}][cost_responsibility]\" value=\"{$log[$key]['cost_responsibility']}\" id=\"type\">";
    echo "<input type=\"hidden\" name=\"line[{$key}][log_id]\" value=\"{$log[$key]['id']}\" id=\"type\">";

  }
*/

?>

<?php endif; ?>
