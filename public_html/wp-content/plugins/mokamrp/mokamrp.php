<?php
/*
Plugin Name: MokaMRP
Plugin URI: http://mokaorigins.com
Description: Material requirements planning for Moka. 
Author: John Daskovsky	
Version: 0.2.0
Author URI: https://mokaorigins.com

Copyright 2016 John Daskovsky

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/

// Discover plugin path even if symlinked
if(!defined('MOKAMRP_PATH')) {
	$this_plugin_file = __FILE__;
	if (isset($plugin)) {
	  $this_plugin_file = $plugin;
	}
	elseif (isset($mu_plugin)) {
	  $this_plugin_file = $mu_plugin;
	}
	elseif (isset($network_plugin)) {
	  $this_plugin_file = $network_plugin;
	}
	define('MOKAMRP_PATH', WP_PLUGIN_DIR . '/' . basename(dirname($this_plugin_file)));
	define('MOKAMRP_URL', plugin_dir_url(MOKAMRP_PATH) . basename(dirname($this_plugin_file)));
}
 
require_once(MOKAMRP_PATH . "/includes/functions.php");
require_once(MOKAMRP_PATH . "/includes/shortcodes.php"); 
 
 //Install
function mokamrp_install() {
	global $wpdb;
	$tablename = get_table_name('materials');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`group_id` int(11) NOT NULL,
		`name` varchar(255) NOT NULL,
		`measure_type` tinyint(1) NOT NULL,
		`source` int(11) NOT NULL,
		`destination` int(11) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);

	$tablename = get_table_name('groups');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);

	$tablename = get_table_name('recipes');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		`sort` int(11) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);

	$tablename = get_table_name('lines');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`recipe_id` int(11) NOT NULL,
		`material_type` tinyint(1) NOT NULL,
		`material_id` int(11) NOT NULL,
		`units` decimal(10,2) NOT NULL,
		`cost_responsibility` decimal(5,2) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);

	$tablename = get_table_name('logs');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`action_id` int(11) NOT NULL,
		`material_id` int(11) NOT NULL,
		`recipe_id` int(11) NOT NULL,
		`units` decimal(10,2) NOT NULL,
		`type` tinyint(1) NOT NULL,
		`cost` decimal(10,2) NOT NULL,
		`user` varchar(255) NOT NULL,
		`notes` text NOT NULL,
		`lots` text NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);

	$tablename = get_table_name('recipes');
	$query = "alter table `{$tablename}` add column `groups` varchar(255) not null";
	$wpdb -> query($query);
}
register_activation_hook(__FILE__, 'mokamrp_install');

if(function_exists('register_update_hook')) {
	register_update_hook(__FILE__, 'mokamrp_install');
}


//Create plugin Options page
function mokamrp_init()
{
	register_setting('mokamrp_options','mokamrp_weight_unit');
}
add_action('admin_init','mokamrp_init');


//Create Admin Dashboard Widgets
function mokamrp_today_dashboard_widget_function() {
	$today = today();
	$classes = get_table_name("classes");
	
	$query = "SELECT * FROM {$classes} ";
	$query .= "WHERE end_date >= '{$today}' AND start_date <= '{$today}' ";
	$query .= "AND DAYOFWEEK(start_date) = DAYOFWEEK('{$today}') ";
	$query .= "ORDER BY title ASC";

	display_classes_for_widget($query);
} 

function mokamrp_today_add_dashboard_widgets() {
	wp_add_dashboard_widget('mokamrp_today_dashboard_widget', "MokaMRP: Today's Classes", 'mokamrp_today_dashboard_widget_function');	
} 
add_action('wp_dashboard_setup', 'mokamrp_today_add_dashboard_widgets' );

function mokamrp_search_dashboard_widget_function() {
	?>
	<form action="admin.php?page=mokamrp_search" method="post">
			<?php wp_nonce_field( 'mokamrp_search','mokamrp_search_nonce' ); ?>
			<input type="text" name="keyword" value="" id="keyword" placeholder="Search Students">
			<input type="submit" name="submit" id="submit" value="Search" class="btn">
	</form>
	<?php
} 

function mokamrp_search_add_dashboard_widgets() {
	wp_add_dashboard_widget('mokamrp_search_dashboard_widget', "MokaMRP: Search Students", 'mokamrp_search_dashboard_widget_function');	
} 
add_action('wp_dashboard_setup', 'mokamrp_search_add_dashboard_widgets' );



//Create plugin pages
function mokamrp_home_page()
{
	require_once(MOKAMRP_PATH . "/includes/functions.php");
	include(MOKAMRP_PATH . "/includes/header.php"); 
	include(MOKAMRP_PATH . "/actions/mokamrp_home.php"); 
	include(MOKAMRP_PATH . "/includes/footer.php");
}

//GROUPS
function mokamrp_new_groups_page() { include(MOKAMRP_PATH . "/groups/new_groups.php"); }
function mokamrp_create_groups_page() { include(MOKAMRP_PATH . "/groups/create_groups.php"); }
function mokamrp_edit_groups_page() { include(MOKAMRP_PATH . "/groups/edit_groups.php"); }

//recipes
function mokamrp_new_recipes_page() { include(MOKAMRP_PATH . "/recipes/new_recipes.php"); }
function mokamrp_create_recipes_page() { include(MOKAMRP_PATH . "/recipes/create_recipes.php"); }
function mokamrp_edit_recipes_page() { include(MOKAMRP_PATH . "/recipes/edit_recipes.php"); }

//materials
function mokamrp_new_materials_page() { include(MOKAMRP_PATH . "/materials/new_materials.php"); }
function mokamrp_create_materials_page() { include(MOKAMRP_PATH . "/materials/create_materials.php"); }
function mokamrp_edit_materials_page() { include(MOKAMRP_PATH . "/materials/edit_materials.php"); }

//lines
function mokamrp_new_lines_page() { include(MOKAMRP_PATH . "/lines/new_lines.php"); }
function mokamrp_create_lines_page() { include(MOKAMRP_PATH . "/lines/create_lines.php"); }
function mokamrp_edit_lines_page() { include(MOKAMRP_PATH . "/lines/edit_lines.php"); }

//logs
function mokamrp_new_logs_page() { include(MOKAMRP_PATH . "/logs/new_logs.php"); }
function mokamrp_create_logs_page() { include(MOKAMRP_PATH . "/logs/create_logs.php"); }
function mokamrp_edit_logs_page() { include(MOKAMRP_PATH . "/logs/edit_logs.php"); }

//actions
function mokamrp_new_actions_page() { include(MOKAMRP_PATH . "/actions/new_actions.php"); }
function mokamrp_create_actions_page() { include(MOKAMRP_PATH . "/actions/create_actions.php"); }
function mokamrp_edit_actions_page() { include(MOKAMRP_PATH . "/actions/edit_actions.php"); }

//admin
function mokamrp_settings_page() { include(MOKAMRP_PATH . "/admin/settings.php"); }
function mokamrp_delete_table_item_page() { include(MOKAMRP_PATH . "/admin/delete_table_item.php"); }
function mokamrp_export_table_page() { include(MOKAMRP_PATH . "/admin/export_table.php"); }
function mokamrp_import_export_page() { include(MOKAMRP_PATH . "/admin/import_export.php"); }
function mokamrp_import_table_page() { include(MOKAMRP_PATH . "/admin/import_table.php"); }

//reports
function mokamrp_reports_home_page() { include(MOKAMRP_PATH . "/reports/reports_home.php"); }
function mokamrp_reports_actions_page() { include(MOKAMRP_PATH . "/reports/reports_actions.php"); }
function mokamrp_reports_inventory_page() { include(MOKAMRP_PATH . "/reports/reports_inventory.php"); }
function mokamrp_reports_historical_page() { include(MOKAMRP_PATH . "/reports/reports_historical.php"); }
function mokamrp_reports_losses_page() { include(MOKAMRP_PATH . "/reports/reports_losses.php"); }
function mokamrp_reports_purchases_page() { include(MOKAMRP_PATH . "/reports/reports_purchases.php"); }
function mokamrp_reports_finished_page() { include(MOKAMRP_PATH . "/reports/reports_finished.php"); }
function mokamrp_reports_low_inventory_page() { include(MOKAMRP_PATH . "/reports/reports_low_inventory.php"); }
function mokamrp_reports_recipe_warning_page() { include(MOKAMRP_PATH . "/reports/reports_recipe_warning.php"); }


function mokamrp_plugin_menu()
{    
  $my_pages[] = add_menu_page('MokaMRP', 'MokaMRP', 'read', 'mokamrp_home');
  $my_pages[] = add_submenu_page('mokamrp_home', 'MokaMRP', 'MokaMRP', 'read', 'mokamrp_home', 'mokamrp_home_page');
	$my_pages[] = add_submenu_page('mokamrp_home', 'Settings', 'Settings', 'manage_options', 'mokamrp_settings', 'mokamrp_settings_page');
	
	//GROUPS
	$my_pages[] = add_submenu_page('mokamrp_home', 'Groups', 'Admin', 'manage_options', 'mokamrp_new_groups', 'mokamrp_new_groups_page');
	$my_pages[] = add_submenu_page(null, 'Edit Groups', 'Edit Groups', 'manage_options', 'mokamrp_edit_groups', 'mokamrp_edit_groups_page');	
	$my_pages[] = add_submenu_page(null, 'Create Groups', 'Create Groups', 'manage_options', 'mokamrp_create_groups', 'mokamrp_create_groups_page');

	//RECIPES
	$my_pages[] = add_submenu_page(null, 'recipes', 'Admin', 'manage_options', 'mokamrp_new_recipes', 'mokamrp_new_recipes_page');
	$my_pages[] = add_submenu_page(null, 'Edit recipes', 'Edit recipes', 'manage_options', 'mokamrp_edit_recipes', 'mokamrp_edit_recipes_page');	
	$my_pages[] = add_submenu_page(null, 'Create recipes', 'Create recipes', 'manage_options', 'mokamrp_create_recipes', 'mokamrp_create_recipes_page');

	//materials
	$my_pages[] = add_submenu_page(null, 'materials', 'Admin', 'manage_options', 'mokamrp_new_materials', 'mokamrp_new_materials_page');
	$my_pages[] = add_submenu_page(null, 'Edit materials', 'Edit materials', 'manage_options', 'mokamrp_edit_materials', 'mokamrp_edit_materials_page');	
	$my_pages[] = add_submenu_page(null, 'Create materials', 'Create materials', 'manage_options', 'mokamrp_create_materials', 'mokamrp_create_materials_page');

	//lines
	$my_pages[] = add_submenu_page(null, 'lines', 'Admin', 'manage_options', 'mokamrp_new_lines', 'mokamrp_new_lines_page');
	$my_pages[] = add_submenu_page(null, 'Edit lines', 'Edit lines', 'manage_options', 'mokamrp_edit_lines', 'mokamrp_edit_lines_page');	
	$my_pages[] = add_submenu_page(null, 'Create lines', 'Create lines', 'manage_options', 'mokamrp_create_lines', 'mokamrp_create_lines_page');

	//logs
	$my_pages[] = add_submenu_page(null, 'logs', 'Admin', 'manage_options', 'mokamrp_new_logs', 'mokamrp_new_logs_page');
	$my_pages[] = add_submenu_page(null, 'Edit logs', 'Edit logs', 'manage_options', 'mokamrp_edit_logs', 'mokamrp_edit_logs_page');	
	$my_pages[] = add_submenu_page(null, 'Create logs', 'Create logs', 'manage_options', 'mokamrp_create_logs', 'mokamrp_create_logs_page');

	//actions
	$my_pages[] = add_submenu_page(null, 'actions', 'Admin', 'read', 'mokamrp_new_actions', 'mokamrp_new_actions_page');
	$my_pages[] = add_submenu_page(null, 'Edit actions', 'Edit actions', 'read', 'mokamrp_edit_actions', 'mokamrp_edit_actions_page');	
	$my_pages[] = add_submenu_page(null, 'Create actions', 'Create actions', 'read', 'mokamrp_create_actions', 'mokamrp_create_actions_page');

	//admin
	$my_pages[] = add_submenu_page(null, 'Delete Table Item', 'Delete Table Item', 'read', 'mokamrp_delete_table_item', 'mokamrp_delete_table_item_page');
	$my_pages[] = add_submenu_page(null, 'Export Table', 'Export Table', 'manage_options', 'mokamrp_export_table', 'mokamrp_export_table_page');
	$my_pages[] = add_submenu_page(null, 'Import/Export', 'Import/Export', 'manage_options', 'mokamrp_import_export', 'mokamrp_import_export_page');
	$my_pages[] = add_submenu_page(null, 'Import Table', 'Import Table', 'manage_options', 'mokamrp_import_table', 'mokamrp_import_table_page');

	//reports
	$my_pages[] = add_submenu_page(null, 'Reports Home', 'Reports Home', 'manage_options', 'mokamrp_reports_home', 'mokamrp_reports_home_page');
	$my_pages[] = add_submenu_page(null, 'Reports actions', 'Reports actions', 'manage_options', 'mokamrp_reports_actions', 'mokamrp_reports_actions_page');
	$my_pages[] = add_submenu_page(null, 'Reports historical', 'Reports historical', 'manage_options', 'mokamrp_reports_historical', 'mokamrp_reports_historical_page');
	$my_pages[] = add_submenu_page(null, 'Reports inventory', 'Reports inventory', 'manage_options', 'mokamrp_reports_inventory', 'mokamrp_reports_inventory_page');
	$my_pages[] = add_submenu_page(null, 'Reports losses', 'Reports losses', 'manage_options', 'mokamrp_reports_losses', 'mokamrp_reports_losses_page');
	$my_pages[] = add_submenu_page(null, 'Reports low_inventory', 'Reports low_inventory', 'manage_options', 'mokamrp_reports_low_inventory', 'mokamrp_reports_low_inventory_page');
	$my_pages[] = add_submenu_page(null, 'Reports purchases', 'Reports purchases', 'manage_options', 'mokamrp_reports_purchases', 'mokamrp_reports_purchases_page');
	$my_pages[] = add_submenu_page(null, 'Reports finished', 'Reports finished', 'manage_options', 'mokamrp_reports_finished', 'mokamrp_reports_finished_page');
	$my_pages[] = add_submenu_page(null, 'Reports recipe_warning', 'Reports recipe_warning', 'manage_options', 'mokamrp_reports_recipe_warning', 'mokamrp_reports_recipe_warning_page');
	

 
	foreach($my_pages as $my_page) {
		add_action( 'load-' . $my_page, 'mokamrp_load_admin_custom_css' ); 
	}
}
add_action('admin_menu', 'mokamrp_plugin_menu');

//Add custom CSS to plugin pages
function load_custom_mokamrp_style() {
  wp_register_style( 'custom_wp_admin_css1', MOKAMRP_URL . '/css/bootstrap.css', false, '2.2.2' );
    wp_enqueue_style( 'custom_wp_admin_css1' );
	wp_register_style( 'custom_wp_admin_css2', MOKAMRP_URL . '/css/bootstrap-responsive.css', false, '2.2.2' );
    wp_enqueue_style( 'custom_wp_admin_css2' );
	wp_register_style( 'custom_wp_admin_css3', MOKAMRP_URL . '/css/jquery.dataTables.css', false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css3' );
  wp_register_script('datatables_script', MOKAMRP_URL . '/js/jquery.dataTables.min.js' );
    wp_enqueue_script('datatables_script');
	wp_register_script('bootstrap_script', MOKAMRP_URL . '/js/bootstrap.min.js' );
    wp_enqueue_script('bootstrap_script');
	
}

function mokamrp_load_admin_custom_css(){
  add_action( 'admin_enqueue_scripts', 'load_custom_mokamrp_style' );
}

function mokamrp_enqueue_style() {
	wp_register_style( 'mokamrp_public_css', MOKAMRP_URL . '/css/classdex.css');
    wp_enqueue_style( 'mokamrp_public_css' );
}
add_action( 'wp_enqueue_scripts', 'mokamrp_enqueue_style' );
?>
