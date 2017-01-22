<div class="row-fluid">
	<div class="span3">  
		<label class="control-label" for="name">Name</label>
		<input class="span12" placeholder="Name" type="text" name="name" value="<?php if($edit){echo $row['name'];} ?>" id="name" required="required">
	</div>	
</div>

<div class="row-fluid">
  <div class="span3">  
    <label class="control-label" for="sort">Order</label>
    <input class="span12" placeholder="Order" type="number" name="sort" value="<?php if($edit){echo $row['sort'];} ?>" id="sort" required="required">
  </div>  
</div>

<div class="row-fluid">
  <div class="span3"> 
    <div class="control-group">  
      <label class="control-label" for="groups">Groups</label>  
      <div class="controls">  
        <select multiple="multiple" id="groups" class="input-large" name="groups[]">
          <?php
            $results = get_all_table_rows("groups");
            $groups_string = get_recipe_groups($id);
            $groups_array = explode(",", $groups_string);
            if($edit){
              echo array_to_option_multi_list($results, "id", "name", $groups_array);
            } else {
              echo array_to_option_list($results, "id", "name", NULL, NULL, false);
            }
          ?>
        </select>
      </div>
    </div>
  </div>
</div>
