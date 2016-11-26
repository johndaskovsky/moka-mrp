<?php if($_GET['id'] == 0): ?>
<!-- PURCHASE -->
<h1>Purchase</h1>

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

<!-- Recipe ID -->
<input type="hidden" name="recipe_id" value="0" id="recipe_id" required="required">

<div class="row-fluid">
  <div class="span3">  
    <label class="control-label" for="units">Weight/Units</label>
    <input class="span12" placeholder="Weight/Units" type="number" min="0" step="any" name="units" value="<?php if($edit){echo $row['units'];} ?>" id="units">
  </div>  
</div>

<!-- Type -->
<input type="hidden" name="type" value="1" id="type" required="required">

<?php else: ?>
<!-- NOT PURCHASE -->
<div class="row-fluid">
  <div class="span3">  
    <label class="control-label" for="name">Name</label>
    <input class="span12" placeholder="Name" type="text" name="name" value="<?php if($edit){echo $row['name'];} ?>" id="name" required="required">
  </div>  
</div>
<?php endif; ?>
