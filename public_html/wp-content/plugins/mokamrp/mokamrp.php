<?php
/*
Plugin Name: MokaMRP
Plugin URI: http://mokaorigins.com
Description: Material resource planning for Moka. 
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
	$tablename = get_table_name('classes');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`class_id` int(11) NOT NULL AUTO_INCREMENT,
		`public_id` varchar(10) NOT NULL,
		`title` varchar(255) NOT NULL,
		`teacher` varchar(255) NOT NULL,
		`start_date` date NOT NULL,
		`end_date` date NOT NULL,
		`time` varchar(255) NOT NULL,
		`description` text NOT NULL,
		`fee` int(5) NOT NULL,
		`discounts` tinyint(1) NOT NULL,
		`max_size` int(3) NOT NULL,
		`yoga_class` tinyint(1) NOT NULL,
		`seminar` tinyint(1) NOT NULL,
		`meditation` tinyint(1) NOT NULL,
		`wellness` tinyint(1) NOT NULL,
		`philosophy` tinyint(1) NOT NULL,
		`canceled` tinyint(1) NOT NULL,
		`custom_html` text NOT NULL,
		`image` varchar(255) NOT NULL,
		`image_justified` tinyint(1) NOT NULL,
		`acct_type` int(2) NOT NULL,
		PRIMARY KEY (`class_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);
	$tablename = get_table_name('customers');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`cust_id` int(11) NOT NULL AUTO_INCREMENT,
		`first_name` varchar(35) NOT NULL,
		`last_name` varchar(35) NOT NULL,
		`address` varchar(255) NOT NULL,
		`city` varchar(255) NOT NULL,
		`state` varchar(3) NOT NULL,
		`zip` int(5) NOT NULL,
		`phone` varchar(20) NOT NULL,
		`email` varchar(100) NOT NULL,
		`active_date` date NOT NULL,
		`signed_waiver` date NOT NULL,
		`class_credit` int(5) NOT NULL,
		`green` tinyint(1) NOT NULL,
		`senior` tinyint(1) NOT NULL,
		`student` tinyint(1) NOT NULL,
		`member` tinyint(1) NOT NULL,
		`member_expiration` date NOT NULL,
		PRIMARY KEY (`cust_id`),
		FULLTEXT KEY `full_name_index` (`first_name`,`last_name`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);
	$tablename = get_table_name('payments');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`pay_id` int(11) NOT NULL AUTO_INCREMENT,
		`cust_id` int(11) NOT NULL,
		`reg_id` int(11) NOT NULL,
		`amount` decimal(6,2) NOT NULL,
		`pay_type` int(2) NOT NULL,
		`acct_type` int(2) NOT NULL,
		`date_time` datetime NOT NULL,
		PRIMARY KEY (`pay_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);
	$tablename = get_table_name('registrations');
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`reg_id` int(11) NOT NULL AUTO_INCREMENT,
		`class_id` int(11) NOT NULL,
		`cust_id` int(11) NOT NULL,
		`paid` tinyint(1) NOT NULL,
		PRIMARY KEY (`reg_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb -> query($query);
	
	//Added notes to customers table in version 1.1.8
	$tablename = get_table_name('customers');
	$query = "alter table `{$tablename}` add column `notes` text not null";
	$wpdb -> query($query);
}
register_activation_hook(__FILE__, 'mokamrp_install');

if(function_exists('register_update_hook')) {
	register_update_hook(__FILE__, 'mokamrp_install');
}


//Create plugin Options page
function mokamrp_init()
{
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
	wp_add_dashboard_widget('mokamrp_today_dashboard_widget', "ClassDex: Today's Classes", 'mokamrp_today_dashboard_widget_function');	
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
	wp_add_dashboard_widget('mokamrp_search_dashboard_widget', "ClassDex: Search Students", 'mokamrp_search_dashboard_widget_function');	
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

function mokamrp_browse_page() { include(MOKAMRP_PATH . "/browse.php"); }
function mokamrp_classes_page() { include(MOKAMRP_PATH . "/classes.php"); }
function mokamrp_clear_database_page() { include(MOKAMRP_PATH . "/clear_database.php"); }
function mokamrp_clear_table_page() { include(MOKAMRP_PATH . "/clear_table.php"); }
function mokamrp_clone_class_page() { include(MOKAMRP_PATH . "/clone_class.php"); }
function mokamrp_create_cart66_product_page() { include(MOKAMRP_PATH . "/create_cart66_product.php"); }
function mokamrp_create_class_page() { include(MOKAMRP_PATH . "/create_class.php"); }
function mokamrp_create_customer_page() { include(MOKAMRP_PATH . "/create_customer.php"); }
function mokamrp_delete_table_item_page() { include(MOKAMRP_PATH . "/delete_table_item.php"); }
function mokamrp_edit_class_page() { include(MOKAMRP_PATH . "/edit_class.php"); }
function mokamrp_edit_customer_page() { include(MOKAMRP_PATH . "/edit_customer.php"); }
function mokamrp_expired_page() { include(MOKAMRP_PATH . "/expired.php"); }
function mokamrp_export_mailing_list_page() { include(MOKAMRP_PATH . "/export_mailing_list.php"); }
function mokamrp_export_table_page() { include(MOKAMRP_PATH . "/export_table.php"); }
function mokamrp_full_payment_page() { include(MOKAMRP_PATH . "/full_payment.php"); }
function mokamrp_import_export_page() { include(MOKAMRP_PATH . "/import_export.php"); }
function mokamrp_import_table_page() { include(MOKAMRP_PATH . "/import_table.php"); }
function mokamrp_mailing_list_page() { include(MOKAMRP_PATH . "/mailing_list.php"); }
function mokamrp_mark_not_paid_page() { include(MOKAMRP_PATH . "/mark_not_paid.php"); }
function mokamrp_new_class_page() { include(MOKAMRP_PATH . "/new_class.php"); }
function mokamrp_new_customer_page() { include(MOKAMRP_PATH . "/new_customer.php"); }
function mokamrp_non_class_payment_page() { include(MOKAMRP_PATH . "/non_class_payment.php"); }
function mokamrp_partial_payment_page() { include(MOKAMRP_PATH . "/partial_payment.php"); }
function mokamrp_payment_page() { include(MOKAMRP_PATH . "/payment.php"); }
function mokamrp_print_email_z_out_page() { include(MOKAMRP_PATH . "/print_email_z_out.php"); }
function mokamrp_print_sign_up_list_page() { include(MOKAMRP_PATH . "/print_sign_up_list.php"); }
function mokamrp_print_student_list_page() { include(MOKAMRP_PATH . "/print_student_list.php"); }
function mokamrp_print_unpaid_page() { include(MOKAMRP_PATH . "/print_unpaid.php"); }
function mokamrp_print_waiver_page() { include(MOKAMRP_PATH . "/print_waiver.php"); }
function mokamrp_registration_page() { include(MOKAMRP_PATH . "/registration.php"); }
function mokamrp_search_page() { include(MOKAMRP_PATH . "/search.php"); }
function mokamrp_settings_page() { include(MOKAMRP_PATH . "/settings.php"); }
function mokamrp_student_list_page() { include(MOKAMRP_PATH . "/student_list.php"); }
function mokamrp_unpaid_page() { include(MOKAMRP_PATH . "/unpaid.php"); }
function mokamrp_zout_page() { include(MOKAMRP_PATH . "/z_out_classes.php"); }

function mokamrp_plugin_menu()
{    
    $my_pages[] = add_menu_page('ClassDex', 'ClassDex', 'read', 'mokamrp_home');
    $my_pages[] = add_submenu_page('mokamrp_home', 'ClassDex', 'ClassDex', 'read', 'mokamrp_home', 'mokamrp_home_page');
	$my_pages[] = add_submenu_page(null, 'Browse', 'Browse', 'read', 'mokamrp_browse', 'mokamrp_browse_page'); 
	$my_pages[] = add_submenu_page(null, 'Classes', 'Classes', 'read', 'mokamrp_classes', 'mokamrp_classes_page');
	$my_pages[] = add_submenu_page(null, 'Clear Database', 'Clear Database', 'manage_options', 'mokamrp_clear_database', 'mokamrp_clear_database_page'); 
	$my_pages[] = add_submenu_page(null, 'Clear Table', 'Clear Table', 'manage_options', 'mokamrp_clear_table', 'mokamrp_clear_table_page');
	$my_pages[] = add_submenu_page(null, 'Clone Class', 'Clone Class', 'manage_options', 'mokamrp_clone_class', 'mokamrp_clone_class_page');
	$my_pages[] = add_submenu_page(null, 'Create Cart66 Product', 'Create Cart66 Product', 'manage_options', 'mokamrp_create_cart66_product', 'mokamrp_create_cart66_product_page');
	$my_pages[] = add_submenu_page(null, 'Create Class', 'Create Class', 'manage_options', 'mokamrp_create_class', 'mokamrp_create_class_page');
	$my_pages[] = add_submenu_page(null, 'Create Customer', 'Create Customer', 'read', 'mokamrp_create_customer', 'mokamrp_create_customer_page');
	$my_pages[] = add_submenu_page(null, 'Delete Table Item', 'Delete Table Item', 'read', 'mokamrp_delete_table_item', 'mokamrp_delete_table_item_page');
	$my_pages[] = add_submenu_page(null, 'Edit Class', 'Edit Class', 'manage_options', 'mokamrp_edit_class', 'mokamrp_edit_class_page');	
	$my_pages[] = add_submenu_page(null, 'Edit Customer', 'Edit Customer', 'read', 'mokamrp_edit_customer', 'mokamrp_edit_customer_page');
	$my_pages[] = add_submenu_page(null, 'Expired Members', 'Expired Members', 'manage_options', 'mokamrp_expired', 'mokamrp_expired_page');
	$my_pages[] = add_submenu_page(null, 'Export Mailing List', 'Export Mailing List', 'manage_options', 'mokamrp_export_mailing_list', 'mokamrp_export_mailing_list_page');
	$my_pages[] = add_submenu_page(null, 'Export Table', 'Export Table', 'manage_options', 'mokamrp_export_table', 'mokamrp_export_table_page');
	$my_pages[] = add_submenu_page(null, 'Full Payment', 'Full Payment', 'read', 'mokamrp_full_payment', 'mokamrp_full_payment_page');
	$my_pages[] = add_submenu_page(null, 'Import/Export', 'Import/Export', 'manage_options', 'mokamrp_import_export', 'mokamrp_import_export_page');
	$my_pages[] = add_submenu_page(null, 'Import Table', 'Import Table', 'manage_options', 'mokamrp_import_table', 'mokamrp_import_table_page');
	$my_pages[] = add_submenu_page(null, 'Mailing List', 'Mailing List', 'manage_options', 'mokamrp_mailing_list', 'mokamrp_mailing_list_page');  
	$my_pages[] = add_submenu_page(null, 'Mark Not Paid', 'Mark Not Paid', 'read', 'mokamrp_mark_not_paid', 'mokamrp_mark_not_paid_page');
    $my_pages[] = add_submenu_page('mokamrp_home', 'Add Classes', 'Admin', 'manage_options', 'mokamrp_new_class', 'mokamrp_new_class_page');
	$my_pages[] = add_submenu_page(null, 'New Customer', 'New Customer', 'read', 'mokamrp_new_customer', 'mokamrp_new_customer_page');
	$my_pages[] = add_submenu_page(null, 'Non Class Payment', 'Non Class Payment', 'read', 'mokamrp_non_class_payment', 'mokamrp_non_class_payment_page');
	$my_pages[] = add_submenu_page(null, 'Partial Payment', 'Partial Payment', 'read', 'mokamrp_partial_payment', 'mokamrp_partial_payment_page');
	$my_pages[] = add_submenu_page(null, 'Payment', 'Payment', 'read', 'mokamrp_payment', 'mokamrp_payment_page');
	$my_pages[] = add_submenu_page(null, 'Print/Email Z-Out', 'Print/Email Z-Out', 'manage_options', 'mokamrp_print_email_z_out', 'mokamrp_print_email_z_out_page');
	$my_pages[] = add_submenu_page(null, 'Print Sign Up List', 'Print Sign Up List', 'read', 'mokamrp_print_sign_up_list', 'mokamrp_print_sign_up_list_page');
	$my_pages[] = add_submenu_page(null, 'Print Student List', 'Print Student List', 'read', 'mokamrp_print_student_list', 'mokamrp_print_student_list_page');
	$my_pages[] = add_submenu_page(null, 'Print Unpaid', 'Print Unpaid', 'manage_options', 'mokamrp_print_unpaid', 'mokamrp_print_unpaid_page');	
	$my_pages[] = add_submenu_page(null, 'Print Waiver', 'Print Waiver', 'read', 'mokamrp_print_waiver', 'mokamrp_print_waiver_page');	
	$my_pages[] = add_submenu_page(null, 'Registration', 'Registration', 'read', 'mokamrp_registration', 'mokamrp_registration_page');
	$my_pages[] = add_submenu_page(null, 'Search', 'Search', 'read', 'mokamrp_search', 'mokamrp_search_page');
	$my_pages[] = add_submenu_page('mokamrp_home', 'Settings', 'Settings', 'manage_options', 'mokamrp_settings', 'mokamrp_settings_page');
	$my_pages[] = add_submenu_page(null, 'Student List', 'Student List', 'read', 'mokamrp_student_list', 'mokamrp_student_list_page');
	$my_pages[] = add_submenu_page(null, 'Unpaid', 'Unpaid', 'manage_options', 'mokamrp_unpaid', 'mokamrp_unpaid_page');
	$my_pages[] = add_submenu_page(null, 'Z-Out Classes', 'Z-Out Classes', 'manage_options', 'mokamrp_zout', 'mokamrp_zout_page');
 
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