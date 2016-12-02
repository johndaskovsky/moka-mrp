<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

  <!--Admin Navigation-->
<?php	display_admin_navigation("reports"); ?>

<legend>Reports</legend>
<ul>
  <li>Actions</li>
  <li>Current Inventory and Costs (with total value of current inventory)</li>
  <li>Historical Inventory and Cost (with total value of inventory at that date)</li>
  <li>Losses and cost of losses</li>
  <li>Purchases</li>
  <li>Low inventory warning</li>
  <li>Warn if recipes outputs do not add up to 100</li>
</ul>
	
<?php require(MOKAMRP_PATH . "/includes/footer.php"); ?>