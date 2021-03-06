<div class="control-group">  
  <label class="control-label" for="material_id">Material</label>  
  <div class="controls">  
    <select id="material_id" class="input-large" name="material_id">
      <?php 
        $results = get_all_table_rows("materials");
        if($edit){ echo array_to_option_list($results, "id", "name", $row['material_id']); }
        else{ echo array_to_option_list($results, "id", "name"); }
      ?>
    </select>
  </div>
</div>

<div class="control-group">  
  <label class="control-label" for="recipe_id">Recipe</label>  
  <div class="controls">  
    <select id="recipe_id" class="input-large" name="recipe_id">
      <?php 
        $results = get_all_table_rows("recipes");
        if($edit){ echo array_to_option_list($results, "id", "name", $row['recipe_id'], "Purchase"); }
        else{ echo array_to_option_list($results, "id", "name", NULL, "Purchase"); }
      ?>
    </select>
  </div>
</div>

<div class="row-fluid">
  <div class="span3">  
    <label class="control-label" for="units">Weight/Units</label>
    <input class="span12" placeholder="Weight/Units" type="number" step="any" name="units" value="<?php if($edit){echo $row['units'];} ?>" id="units">
  </div>  
</div>

<div class="row-fluid">
  <div class="span3">  
    <label class="control-label" for="cost">Cost</label>
    <input class="span12" placeholder="Cost" type="number" step="any" name="cost" value="<?php if($edit){echo $row['cost'];} ?>" id="cost">
  </div>  
</div>

<div class="control-group">  
  <label class="control-label" for="type">Type</label>  
  <div class="controls">  
    <select id="type" class="input-large" name="type" required>
      <option value ="1"<?php if($edit && $row['type'] == 1) { echo "selected"; } ?>>Increase Material</option>
      <option value ="-1"<?php if($edit && $row['type'] == -1) { echo "selected"; } ?>>Decrease Material</option>
    </select>
  </div>
</div>

<div class="row-fluid">
  <div class="span3"> 
    <label class="control-label" for="notes">Notes</label>
    <textarea class="span12" rows="1" placeholder="Notes" type="text" name="notes" id="notes"><?php if($edit){echo $row['notes'];} ?></textarea>
  </div>
</div>
