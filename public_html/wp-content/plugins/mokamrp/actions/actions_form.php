<?php if(isset($_GET['id']) && $_GET['id'] == 0): ?>
<!-- PURCHASE -->
<div class="row-fluid">
  <div class="span3">  
    <label class="control-label" for="name">Name</label>
    <input class="span12" placeholder="Name" type="text" name="name" value="<?php if($edit){echo $row['name'];} ?>" id="name" required="required">
  </div>  
</div>
<?php else: ?>
<!-- NOT PURCHASE -->
<div class="row-fluid">
  <div class="span3">  
    <label class="control-label" for="name">Name</label>
    <input class="span12" placeholder="Name" type="text" name="name" value="<?php if($edit){echo $row['name'];} ?>" id="name" required="required">
  </div>  
</div>
<?php endif; ?>
