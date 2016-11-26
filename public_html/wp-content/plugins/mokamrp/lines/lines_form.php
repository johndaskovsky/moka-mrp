<?php 
  if(isset($_GET['recipe_id'])) {
    $recipe_id = $_GET['recipe_id'];
  } 
?>
<?php if(!isset($_GET['recipe_id'])): ?>
  <div class="control-group">  
    <label class="control-label" for="recipe_id">Recipe</label>  
    <div class="controls">  
      <select id="recipe_id" class="input-large" name="recipe_id">
        <?php 
          $results = get_all_table_rows("recipes");
          if($edit){ echo array_to_option_list($results, "id", "name", $row['recipe_id']); }
          else{ echo array_to_option_list($results, "id", "name"); }
        ?>
      </select>
    </div>
  </div>
<?php else: ?>
  <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>" id="recipe_id" required="required">
<?php endif; ?>

<div class="control-group">  
  <label class="control-label" for="material_type">Line Type</label>  
  <div class="controls">  
    <select id="material_type" class="input-large" name="material_type" required>
      <option value ="1"<?php if($edit && $row['material_type'] == 1) { echo "selected"; } ?>>Input</option>
      <option value ="2"<?php if($edit && $row['material_type'] == 2) { echo "selected"; } ?>>Output</option>
    </select>
  </div>
</div>

<div class="control-group">  
  <label class="control-label" for="material_id">Material</label>  
  <div class="controls">  
    <select id="material_id" class="input-large" name="material_id">
      <?php 
        $results = get_all_table_rows("materials");
        if($edit){ echo array_to_option_list($results, "id", "name", $row['id'], "*Variable*"); }
        else{ echo array_to_option_list($results, "id", "name", NULL, "*Variable*"); }
      ?>
    </select>
  </div>
</div>

<div class="row-fluid">
  <div class="span3">  
    <label class="control-label" for="units">Weight/Units</label>
    <input class="span12" placeholder="Weight/Units" type="number" min="0" step="any" name="units" value="<?php if($edit){echo $row['units'];} ?>" id="units">
  </div>  
</div>

<div class="row-fluid">
  <div class="span3">  
    <label class="control-label" for="cost_responsibility">Cost Responsibility %</label>
    <input class="span12" placeholder="Cost Responsibility %" type="number" min="0" max="100" step="any" name="cost_responsibility" value="<?php if($edit){echo $row['cost_responsibility'];} else { echo 100; } ?>" id="cost_responsibility">
  </div>  
</div>
