<div class="row-fluid">
	<div class="span3">  
		<label class="control-label" for="name">Name</label>
		<input class="span12" placeholder="Name" type="text" name="name" value="<?php if($edit){echo $row['name'];} ?>" id="name" required="required">
	</div>	
</div>

<div class="control-group">  
  <label class="control-label" for="group_id">Group</label>  
  <div class="controls">  
    <select id="group_id" class="input-large" name="group_id">
      <?php 
        $results = get_all_table_rows("groups");
        if($edit){ echo array_to_option_list($results, "id", "name", $row['group_id']); }
        else{ echo array_to_option_list($results, "id", "name"); }
      ?>
    </select>
  </div>
</div>

<div class="control-group">  
  <label class="control-label" for="measure_type">Measure Type</label>  
  <div class="controls">  
    <select id="measure_type" class="input-large" name="measure_type" required>
      <option value ="1"<?php if($edit && $row['measure_type'] == 1) { echo "selected"; } ?>>Weight</option>
      <option value ="2"<?php if($edit && $row['measure_type'] == 2) { echo "selected"; } ?>>Units</option>
    </select>
  </div>
</div>

<div class="control-group">  
  <label class="control-label" for="source">Source</label>  
  <div class="controls">  
    <select id="source" class="input-large" name="source">
      <?php 
        $results = get_all_table_rows("recipes");
        if($edit){ echo array_to_option_list($results, "id", "name", $row['source'], "Purchase"); }
        else{ echo array_to_option_list($results, "id", "name", NULL, "Purchase"); }
      ?>
    </select>
  </div>
</div>

<div class="control-group">  
  <label class="control-label" for="destination">Destination</label>  
  <div class="controls">  
    <select id="destination" class="input-large" name="destination">
      <?php 
        $results = get_all_table_rows("recipes");
        if($edit){ echo array_to_option_list($results, "id", "name", $row['destination'], NULL, TRUE, "-1", TRUE); }
        else{ echo array_to_option_list($results, "id", "name", NULL, NULL, TRUE, "-1", TRUE); }
      ?>
    </select>
  </div>
</div>
