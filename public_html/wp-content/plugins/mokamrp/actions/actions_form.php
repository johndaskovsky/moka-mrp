<?php if($_GET['id'] == 0 || $_GET['id'] == -1): ?>
<!-- PURCHASE OR LOSS -->
  <h1>
    <?php
      if($_GET['id'] == 0) {
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
              if($edit){ echo array_to_option_list($results, "id", "name", $row['id'], "*Variable*"); }
              else{ echo array_to_option_list($results, "id", "name", NULL, "*Variable*"); }
            ?>
          </select>
        </div>
      </div>
    </div>
    <div class="span3">  
      <label class="control-label" for="units">Weight/Units</label>
      <input class="span12" placeholder="Weight/Units" type="number" min="0" step="any" name="line[0][units]" value="<?php if($edit){echo $row['units'];} ?>" id="units">
    </div> 
    <?php
      if($_GET['id'] == 0) {
        echo "<div class=\"span3\">  
            <label class=\"control-label\" for=\"cost\">Cost</label>
            <input class=\"span12\" placeholder=\"Cost\" type=\"number\" min=\"0\" step=\"any\" name=\"line[0][cost]\" value=\"";
        if($edit){ echo $row['cost']; }
        echo "\" id=\"cost\">
          </div>"; 
      }
    ?>
    <div class="span3"> 
      <label class="control-label" for="notes">Notes</label>
      <textarea class="span12" rows="1" placeholder="Notes" type="text" name="line[0][notes]" id="notes"><?php if($edit){echo $row['notes'];} ?></textarea>
    </div>
  </div>
  <?php
    if($_GET['id'] == 0) {
      echo "<input type=\"hidden\" name=\"line[0][recipe_id]\" value=\"0\" id=\"recipe_id\" required=\"required\">
        <input type=\"hidden\" name=\"line[0][type]\" value=\"1\" id=\"type\" required=\"required\">";
    } else {
      echo "<input type=\"hidden\" name=\"line[0][recipe_id]\" value=\"-1\" id=\"recipe_id\" required=\"required\">
        <input type=\"hidden\" name=\"line[0][type]\" value=\"-1\" id=\"type\" required=\"required\">";
    }
  ?>

<?php else: ?>
<!-- OTHER ACTIONS -->
  <div class="row-fluid">
    <div class="span3">  
      <label class="control-label" for="name">Name</label>
      <input class="span12" placeholder="Name" type="text" name="name" value="<?php if($edit){echo $row['name'];} ?>" id="name" required="required">
    </div>  
  </div>

<?php endif; ?>
