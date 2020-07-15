<?php
/*
	Plugin Name: profile-management
	Plugin URI:
	Description: Display fields information as well as add, edit
	Author: Kusdevelopers
	Version: 1.0
	Author URI:

*/
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) 
{ 
	die('You are not allowed to call this page directly.'); 
}
include "admin/formfields-details.php";
/**
 * Loads the Form fields plugin
 */
	function franchisor_add_dashboard_widgets() {
$current_user = wp_get_current_user();
 
if($current_user->roles[0]=="franchisor")
{
	wp_add_dashboard_widget(
                 'example_dashboard_widget',         // Widget slug.
                 'Franchisor Details',         // Title.
                 'franchisor_details' // Display function.
        );	
}
}
function franchise_add_dashboard_widgets() {
$current_user = wp_get_current_user();

 
if($current_user->roles[0]=="franchise")
{
		
		wp_add_dashboard_widget(
                 'example_dashboard_widget',         // Widget slug.
                 'Franchise Details',         // Title.
                 'franchise_details' // Display function.
        );	
}
}
//add_action( 'wp_dashboard_setup', 'franchisor_add_dashboard_widgets' );
add_action( 'wp_dashboard_setup', 'franchise_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function franchisor_details() {

	// Display whatever it is you want to show.
	$current_user = wp_get_current_user();
	//echo "<pre>";
	//print_r($current_user);
	$id = $current_user->ID;
	if($current_user->roles[0]=="franchisor")
	{
		global $wp_meta_boxes;
		//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		//unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
		
 		//remove_menu_page( 'themes.php' ); 
		//remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		//remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		//franchisor_fields($id);
	}
	
	//echo plugins_url();
}
function franchise_details() {
	$current_user = wp_get_current_user();
	$id = $current_user->ID;
	if($current_user->roles[0]=="franchise")
	{
		global $wp_meta_boxes;
	
		// wp..
		 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
		 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		 // bbpress
		 unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
		 // yoast seo
		 unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
		 // gravity forms
		 unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);
		franchise_fields($id);
	}
}
function custom_remove_dashboard_widgets() {
	global $wp_meta_boxes;
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] ); # Allows for basic post entry
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] ); # Shows you who is linking to you
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] ); # Displays new, updated, and popular WordPress plugins on WordPress.org
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] ); # Highlights entries from the WordPress team on WordPress.org
	# unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] ); # Displays stats about your blog
	# unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] ); # Displays the most recent comments on your blog
	 unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] ); # Displays your most recent drafts
	# unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] ); # Displays the WordPress Planet feed, which includes blog entries from WordPress.org
}
 class ProfileLoader
 {
	var $lcount;
	function ProfileLoader()
	{
		$this->define_tables();
		$this->load_dependencies();
		
		$this->plugin_name = basename(dirname(__FILE__)).'/'.basename(__FILE__);

		// Init options & tables during activation & deregister init option
		register_activation_hook( $this->plugin_name, array(&$this, 'activate') );
		register_deactivation_hook( $this->plugin_name, array(&$this, 'deactivate') );

		// Register a uninstall hook to remove all tables & option automatic
		register_uninstall_hook( __FILE__, array(__CLASS__, 'uninstall') );

			// Start this plugin once all other plugins are fully loaded
		add_action( 'plugins_loaded', array(&$this, 'start_plugin') );
	}

	function define_tables() 
	{
		global $wpdb;

		// add database pointer
		$wpdb->franchisefields = $wpdb->prefix . 'fr_formfields';
		
	}
	function deactivate()
	{
	}
	function uninstall()
	{
		include_once (dirname (__FILE__) . '/admin/install.php');
		franchise_uninstall();
	}
	function activate()
	{
		include_once (dirname (__FILE__) . '/admin/install.php');
		franchise_install();
	}
	function start_plugin()
	{

	}
	function load_dependencies()
	{
		if ( is_admin() ) 
		{
			require_once (dirname (__FILE__) . '/admin/admin.php');
			$this->ProfileAdminPanel = new ProfileAdminPanel();
		}
	}
	
 }
global $profile;
if(is_admin())
	$profile = new ProfileLoader();
?>