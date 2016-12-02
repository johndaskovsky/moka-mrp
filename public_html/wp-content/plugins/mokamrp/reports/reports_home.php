<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php	display_admin_navigation("reports"); ?>

<legend>Reports</legend>
<ul>
  <li style="margin-bottom:10px;"><a class="btn btn-default" href="admin.php?page=mokamrp_reports_actions">Actions</a></li>
  <li style="margin-bottom:10px;"><a class="btn btn-default" href="admin.php?page=mokamrp_reports_inventory">Current Inventory and Costs (with total value of current inventory)</a></li>
  <li style="margin-bottom:10px;"><a class="btn btn-default" href="admin.php?page=mokamrp_reports_historical">Historical Inventory and Cost (with total value of inventory at that date)</a></li>
  <li style="margin-bottom:10px;"><a class="btn btn-default" href="admin.php?page=mokamrp_reports_losses">Losses and cost of losses</a></li>
  <li style="margin-bottom:10px;"><a class="btn btn-default" href="admin.php?page=mokamrp_reports_purchases">Purchases</a></li>
  <li style="margin-bottom:10px;"><a class="btn btn-default" href="admin.php?page=mokamrp_reports_low_inventory">Low inventory warning</a></li>
  <li style="margin-bottom:10px;"><a class="btn btn-default" href="admin.php?page=mokamrp_reports_recipe_warning">Warn if recipes outputs do not add up to 100</a></li>
</ul>
	
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>