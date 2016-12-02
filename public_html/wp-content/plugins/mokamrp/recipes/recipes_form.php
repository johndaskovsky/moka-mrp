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
