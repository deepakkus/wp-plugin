<?php
class ProfileAdminPanel
{
	public function __construct()
	{
		add_action('admin_menu',array($this,'franchise_details'));
	}

	public function franchise_details()
	{
		$current_user = wp_get_current_user();
		
		if($current_user->roles[0]=="administrator" || $current_user->roles[0]=="franchisor")
		{
		add_menu_page( _n( 'Profile Manager', 'Profile Manager', 1, 'Profile Manager' ), _n( 'Profile Manager', 'Profile Management', 1, 'Profile Manager' ), 'Profile Management', 'Profile Manager' );
		}
		/*add_submenu_page('Profile Manager', 'Profile Management', 'Profile Management', 1,'manage_profile', array($this,'manage_profile'));*/
		if($current_user->roles[0]=="administrator" || $current_user->roles[0]=="franchisor"){
		add_submenu_page('Profile Manager', 'Form Fields', 'Form Fields', 1,'manage_formfields', array($this,'manage_formfields'));
		}
		if($current_user->roles[0]=="administrator" || $current_user->roles[0]=="franchisor")
		{
		add_submenu_page('Profile Manager', 'Franchise', 'Franchise', 1,'manage_franchise', array($this,'manage_franchise'));
		}
		if($current_user->roles[0]=="administrator"){
		add_submenu_page('Profile Manager', 'Franchisor', 'Franchisor', 1,'manage_franchisor', array($this,'manage_franchisor'));
		add_submenu_page('Profile Manager', 'Zipcode', 'Zipcode', 1,'manage_zip', array($this,'manage_zip'));
		}
		//add_submenu_page('Profile Manager', 'Development', 'Development', 1,'developer_settings', array($this,'developer_settings'));
	}
	
	public function add_franchise_fields()
	{
		include(dirname (__FILE__)."/addfranchisefields.php");
	}
	/*
	*	Profile Management
	*/
	public function manage_profile()
	{
		include(dirname (__FILE__)."/profilemanagement.php");
	}
	/*
	*	Manage Form Fields
	*/
	public function manage_formfields()
	{
		include(dirname (__FILE__)."/fieldmanagement.php");
	}
	/*
	*	Manage Franchisor
	*/
	public function manage_franchisor()
	{
		include(dirname (__FILE__)."/franchisormanagement.php");
	}
	/*
	*	Manage Franchise
	*/
	public function manage_franchise()
	{
		include(dirname (__FILE__)."/franchisemanagement.php");
	}
	/*
	*	Manage Zip
	*/
	public function manage_zip()
	{
		include(dirname (__FILE__)."/zipmanagement.php");
	}
	/*
	*	Developer Settings
	*/
	public function developer_settings()
	{
		include(dirname (__FILE__)."/settings.php");
	}
}
?>