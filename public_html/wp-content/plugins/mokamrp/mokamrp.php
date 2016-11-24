<?php
/*
Plugin Name: MokaMRP
Plugin URI: http://mokaorigins.com
Description: Material requirements planning for Moka. 
Author: John Daskovsky	
Version: 0.1.0
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
		`name` varchar(255) NOT NULL,
		`measure_type` tinyint(1) NOT NULL,
		`source` int(11) NOT NULL,
		`group_id` int(11) NOT NULL,
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
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);

	$tablename = get_table_name('lines');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`recipe_id` int(11) NOT NULL,
		`material_type` tinyint(1) NOT NULL,
		`material_id` int(11) NOT NULL,
		`source` int(11) NOT NULL,
		`amount` decimal(6,2) NOT NULL,
		`cost_responsibility` tinyint(3) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);

	$tablename = get_table_name('logs');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`action_id` int(11) NOT NULL,
		`datetime` datetime NOT NULL,
		`material_id` int(11) NOT NULL,
		`recipe_id` int(11) NOT NULL,
		`in` decimal(6,2) NOT NULL,
		`out` decimal(6,2) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);
}
register_activation_hook(__FILE__, 'mokamrp_install');

if(function_exists('register_update_hook')) {
	register_update_hook(__FILE__, 'mokamrp_install');
}


//Create plugin Options page
function mokamrp_init()
{
	register_setting('mokamrp_options','mokamrp_weight_units');
	register_setting('mokamrp_options_hidden','mokamrp_zout_register_date');
	register_setting('mokamrp_options_hidden','mokamrp_zout_register_time');
	register_setting('mokamrp_options_hidden','mokamrp_zout_paypal_date');
	register_setting('mokamrp_options_hidden','mokamrp_zout_paypal_time');
	register_setting('mokamrp_options','mokamrp_zout_email');
	register_setting('mokamrp_options','mokamrp_mailchimp_apikey');
	register_setting('mokamrp_options','mokamrp_mailchimp_listid');
	register_setting('mokamrp_options','mokamrp_zip_start');
	register_setting('mokamrp_options','mokamrp_zip_stop');
	register_setting('mokamrp_options','mokamrp_month_01');
	register_setting('mokamrp_options','mokamrp_month_02');
	register_setting('mokamrp_options','mokamrp_month_03');
	register_setting('mokamrp_options','mokamrp_month_04');
	register_setting('mokamrp_options','mokamrp_month_05');
	register_setting('mokamrp_options','mokamrp_month_06');
	register_setting('mokamrp_options','mokamrp_month_07');
	register_setting('mokamrp_options','mokamrp_month_08');
	register_setting('mokamrp_options','mokamrp_month_09');
	register_setting('mokamrp_options','mokamrp_month_10');
	register_setting('mokamrp_options','mokamrp_month_11');
	register_setting('mokamrp_options','mokamrp_month_12');
	register_setting('mokamrp_options','mokamrp_waiver');
	register_setting('mokamrp_options','mokamrp_list_shortcode');
	register_setting('mokamrp_options','mokamrp_detail_shortcode');
	register_setting('mokamrp_options','mokamrp_detail1_shortcode');
	register_setting('mokamrp_options','mokamrp_detail2_shortcode');
	register_setting('mokamrp_options','mokamrp_detail3_shortcode');
	register_setting('mokamrp_options','mokamrp_account_types');
	register_setting('mokamrp_options','mokamrp_discount_member');
	register_setting('mokamrp_options','mokamrp_discount_senior');
	register_setting('mokamrp_options','mokamrp_discount_student');
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
	?>
	  <legend>Today's Classes</legend>
	<?php
		$today = today();
		$classes = get_table_name("classes");
		
		$query = "SELECT * FROM {$classes} ";
		$query .= "WHERE end_date >= '{$today}' AND start_date <= '{$today}' ";
		$query .= "AND DAYOFWEEK(start_date) = DAYOFWEEK('{$today}') ";
		$query .= "ORDER BY title ASC";
	
		display_classes($query);
	
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

//OTHER
function mokamrp_settings_page() { include(MOKAMRP_PATH . "/settings.php"); }
function mokamrp_classes_page() { include(MOKAMRP_PATH . "/classes.php"); }
function mokamrp_clear_database_page() { include(MOKAMRP_PATH . "/clear_database.php"); }
function mokamrp_clear_table_page() { include(MOKAMRP_PATH . "/clear_table.php"); }
function mokamrp_delete_table_item_page() { include(MOKAMRP_PATH . "/delete_table_item.php"); }


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
	$my_pages[] = add_submenu_page('mokamrp_home', 'recipes', 'Admin', 'manage_options', 'mokamrp_new_recipes', 'mokamrp_new_recipes_page');
	$my_pages[] = add_submenu_page(null, 'Edit recipes', 'Edit recipes', 'manage_options', 'mokamrp_edit_recipes', 'mokamrp_edit_recipes_page');	
	$my_pages[] = add_submenu_page(null, 'Create recipes', 'Create recipes', 'manage_options', 'mokamrp_create_recipes', 'mokamrp_create_recipes_page');



	$my_pages[] = add_submenu_page(null, 'Classes', 'Classes', 'read', 'mokamrp_classes', 'mokamrp_classes_page');
	$my_pages[] = add_submenu_page(null, 'Clear Database', 'Clear Database', 'manage_options', 'mokamrp_clear_database', 'mokamrp_clear_database_page'); 
	$my_pages[] = add_submenu_page(null, 'Clear Table', 'Clear Table', 'manage_options', 'mokamrp_clear_table', 'mokamrp_clear_table_page');
	$my_pages[] = add_submenu_page(null, 'Delete Table Item', 'Delete Table Item', 'read', 'mokamrp_delete_table_item', 'mokamrp_delete_table_item_page');
	

 
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
	wp_register_script('jquery_script', '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js' );
    wp_enqueue_script('jquery_script');
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