<div class="wrap">
<div class="navbar">
  <div class="navbar-inner" style="padding-right: 0px; padding-left: 0px;">
		<div class="container-fluid">
		  <a class="brand" href="admin.php?page=mokamrp_home">MokaMRP</a>
		  	<ul class="nav">
			  <li><a href="admin.php?page=mokamrp_home"><i class="icon-home"></i></a></li>
			  <?php if ( current_user_can('manage_options') ) { ?>
	 	 			<li><a href="admin.php?page=mokamrp_new_groups"><i class="icon-lock"></i> Admin</a></li>
	 		  <?php }  ?> 
			</ul>
		</div>
  </div>
</div>
<div class="container-fluid" style="padding-right: 0px; padding-left: 0px;">
  <div class="row-fluid">
	<div class="span12">