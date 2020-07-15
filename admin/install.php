<?php
function franchise_install()
{
	global $wpdb , $wp_roles, $wp_version;
	/*$business = $wpdb->prefix . 'prf_business';
	$category = $wpdb->prefix . 'prf_category';*/
	
	$profilecf = 'zc_custom_fields';
	$profilecfopt = 'zc_custom_field_options';
	$profilezip = 'zc_zip';
	$profile = 'zc_profile';
	// upgrade function changed in WordPress 2.3
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	// add charset & collate like wp core
	$charset_collate = '';

	if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
		if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";
	}
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$profilecf." (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) NOT NULL,
  `profile_column` varchar(255) NOT NULL,
  `shortcode` varchar(255) NOT NULL,
  	profile_type enum('B','FE','FO') NOT NULL DEFAULT 'B',
  `field_type` enum('T','TA','C','S','R') NOT NULL DEFAULT 'T',
  `displayorder` int(11) NOT NULL,
  PRIMARY KEY (`field_id`)
)".$charset_collate."";

dbDelta($sql);
$sql = "CREATE TABLE IF NOT EXISTS ".$profilecfopt." (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `option_value` varchar(255) NOT NULL,
  `option_display` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`option_id`)
)".$charset_collate."";

dbDelta($sql);

$sql = "CREATE TABLE IF NOT EXISTS ".$profilezip." (
  `zipid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`zipid`)
)".$charset_collate."";

dbDelta($sql);

$sql = "CREATE TABLE IF NOT EXISTS ".$profile." (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `parentId` int(11) NOT NULL,
  `profile_type` enum('FO','FE') NOT NULL DEFAULT 'FE' COMMENT 'FO-Franchisor.FE-Franchisee',
  `status` enum('Y','N','P') NOT NULL DEFAULT 'P' COMMENT 'Y=Active',
  `theme_status` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y=can choose, N=not choose',
  `theme_id` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  `addfield` varchar(255) NOT NULL,
  PRIMARY KEY (`profile_id`)
)".$charset_collate."";

dbDelta($sql);
$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}development (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagetext` varchar(255) NOT NULL,
   PRIMARY KEY (`id`)
)".$charset_collate."";	
dbDelta($sql);

					add_role('franchise',__( 'Franchise' ),
						array(
							'read'         => true,  // true allows this capability
							'edit_posts'   => true,
							'delete_posts' => true, // Use false to explicitly deny
						)
					);
					add_role('franchisor',__( 'Franchisor' ),
						array(
							'read'         => true,  // true allows this capability
							'edit_posts'   => true,
							'delete_posts' => true, // Use false to explicitly deny
						)
					);
					
					
					
					$role = get_role( 'franchisor' );
					$wp_roles->add_cap( 'franchisor', 'manage_galleries' );
					$wp_roles->add_cap( 'franchisor', 'switch_themes' );
					$wp_roles->add_cap( 'franchisor', 'edit_themes' );
					$wp_roles->add_cap( 'franchisor', 'activate_plugins' );
					$wp_roles->add_cap( 'franchisor', 'edit_plugins' );
					$wp_roles->add_cap( 'franchisor', 'edit_users' );
					$wp_roles->add_cap( 'franchisor', 'edit_files' );
					$wp_roles->add_cap( 'franchisor', 'manage_options' );
					$wp_roles->add_cap( 'franchisor', 'moderate_comments' );
					$wp_roles->add_cap( 'franchisor', 'manage_categories' );
					$wp_roles->add_cap( 'franchisor', 'manage_links' );
					$wp_roles->add_cap( 'franchisor', 'upload_files' );
					$wp_roles->add_cap( 'franchisor', 'import' );
					$wp_roles->add_cap( 'franchisor', 'unfiltered_html' );
					$wp_roles->add_cap( 'franchisor', 'edit_posts' );
					$wp_roles->add_cap( 'franchisor', 'edit_others_posts' );
					$wp_roles->add_cap( 'franchisor', 'edit_published_posts' );
					$wp_roles->add_cap( 'franchisor', 'publish_posts' );
					$wp_roles->add_cap( 'franchisor', 'edit_pages' );
					$wp_roles->add_cap( 'franchisor', 'read' );
					$wp_roles->add_cap( 'franchisor', 'level_10' );
					$wp_roles->add_cap( 'franchisor', 'level_9' );
					$wp_roles->add_cap( 'franchisor', 'level_8' );
					$wp_roles->add_cap( 'franchisor', 'level_7' );
					$wp_roles->add_cap( 'franchisor', 'level_6' );
					$wp_roles->add_cap( 'franchisor', 'level_5' );
					$wp_roles->add_cap( 'franchisor', 'level_4' );
					$wp_roles->add_cap( 'franchisor', 'level_3' );
					$wp_roles->add_cap( 'franchisor', 'level_2' );
					$wp_roles->add_cap( 'franchisor', 'level_1' );
					$wp_roles->add_cap( 'franchisor', 'level_0' );
					$wp_roles->add_cap( 'franchisor', 'edit_others_pages' );
					$wp_roles->add_cap( 'franchisor', 'edit_published_pages' );
					$wp_roles->add_cap( 'franchisor', 'publish_pages' );
					$wp_roles->add_cap( 'franchisor', 'delete_pages' );
					$wp_roles->add_cap( 'franchisor', 'delete_others_pages' );
					$wp_roles->add_cap( 'franchisor', 'delete_published_pages' );
					$wp_roles->add_cap( 'franchisor', 'edit_dashboard' );
					$wp_roles->add_cap( 'franchisor', 'delete_posts' );
					$wp_roles->add_cap( 'franchisor', 'delete_others_posts' );
					$wp_roles->add_cap( 'franchisor', 'delete_published_posts' );
					$wp_roles->add_cap( 'franchisor', 'delete_private_posts' );
					$wp_roles->add_cap( 'franchisor', 'edit_private_posts' );
					$wp_roles->add_cap( 'franchisor', 'read_private_posts' );
					$wp_roles->add_cap( 'franchisor', 'delete_private_pages' );
					$wp_roles->add_cap( 'franchisor', 'edit_private_pages' );
					$wp_roles->add_cap( 'franchisor', 'read_private_pages' );
					$wp_roles->add_cap( 'franchisor', 'delete_users' );
					$wp_roles->add_cap( 'franchisor', 'create_users' );
					$wp_roles->add_cap( 'franchisor', 'unfiltered_upload' );
					$wp_roles->add_cap( 'franchisor', 'update_plugins' );
					$wp_roles->add_cap( 'franchisor', 'delete_plugins' );
					$wp_roles->add_cap( 'franchisor', 'update_plugins' );
					$wp_roles->add_cap( 'franchisor', 'install_plugins' );
					$wp_roles->add_cap( 'franchisor', 'update_plugins' );
					$wp_roles->add_cap( 'franchisor', 'update_themes' );
					$wp_roles->add_cap( 'franchisor', 'update_plugins' );
					$wp_roles->add_cap( 'franchisor', 'install_themes' );
					$wp_roles->add_cap( 'franchisor', 'update_core' );
					$wp_roles->add_cap( 'franchisor', 'list_users' );
					$wp_roles->add_cap( 'franchisor', 'remove_users' );
					$wp_roles->add_cap( 'franchisor', 'add_users' );
					$wp_roles->add_cap( 'franchisor', 'promote_users' );
					$wp_roles->add_cap( 'franchisor', 'edit_theme_options' );
					$wp_roles->add_cap( 'franchisor', 'delete_themes' );
					$wp_roles->add_cap( 'franchisor', 'promote_users' );
					$wp_roles->add_cap( 'franchisor', 'manage_galleries' );
					$wp_roles->add_cap( 'franchisor', 'promote_users' );
					$wp_roles->add_cap( 'franchisor', 'administrator' );
					$wp_roles->add_cap( 'franchisor', 'can_edit_posts' );
					
					
					$role = get_role( 'franchise' );
					$wp_roles->add_cap( 'franchise', 'manage_galleries' );
					$wp_roles->add_cap( 'franchise', 'switch_themes' );
					$wp_roles->add_cap( 'franchise', 'edit_themes' );
					$wp_roles->add_cap( 'franchise', 'activate_plugins' );
					$wp_roles->add_cap( 'franchise', 'edit_plugins' );
					$wp_roles->add_cap( 'franchise', 'edit_users' );
					$wp_roles->add_cap( 'franchise', 'edit_files' );
					$wp_roles->add_cap( 'franchise', 'manage_options' );
					$wp_roles->add_cap( 'franchise', 'moderate_comments' );
					$wp_roles->add_cap( 'franchise', 'manage_categories' );
					$wp_roles->add_cap( 'franchise', 'manage_links' );
					$wp_roles->add_cap( 'franchise', 'upload_files' );
					$wp_roles->add_cap( 'franchise', 'import' );
					$wp_roles->add_cap( 'franchise', 'unfiltered_html' );
					$wp_roles->add_cap( 'franchise', 'edit_posts' );
					$wp_roles->add_cap( 'franchise', 'edit_others_posts' );
					$wp_roles->add_cap( 'franchise', 'edit_published_posts' );
					$wp_roles->add_cap( 'franchise', 'publish_posts' );
					$wp_roles->add_cap( 'franchise', 'edit_pages' );
					$wp_roles->add_cap( 'franchise', 'read' );
					$wp_roles->add_cap( 'franchise', 'level_10' );
					$wp_roles->add_cap( 'franchise', 'level_9' );
					$wp_roles->add_cap( 'franchise', 'level_8' );
					$wp_roles->add_cap( 'franchise', 'level_7' );
					$wp_roles->add_cap( 'franchise', 'level_6' );
					$wp_roles->add_cap( 'franchise', 'level_5' );
					$wp_roles->add_cap( 'franchise', 'level_4' );
					$wp_roles->add_cap( 'franchise', 'level_3' );
					$wp_roles->add_cap( 'franchise', 'level_2' );
					$wp_roles->add_cap( 'franchise', 'level_1' );
					$wp_roles->add_cap( 'franchise', 'level_0' );
					$wp_roles->add_cap( 'franchise', 'edit_others_pages' );
					$wp_roles->add_cap( 'franchise', 'edit_published_pages' );
					$wp_roles->add_cap( 'franchise', 'publish_pages' );
					$wp_roles->add_cap( 'franchise', 'delete_pages' );
					$wp_roles->add_cap( 'franchise', 'delete_others_pages' );
					$wp_roles->add_cap( 'franchise', 'delete_published_pages' );
					$wp_roles->add_cap( 'franchise', 'edit_dashboard' );
					$wp_roles->add_cap( 'franchise', 'delete_posts' );
					$wp_roles->add_cap( 'franchise', 'delete_others_posts' );
					$wp_roles->add_cap( 'franchise', 'delete_published_posts' );
					$wp_roles->add_cap( 'franchise', 'delete_private_posts' );
					$wp_roles->add_cap( 'franchise', 'edit_private_posts' );
					$wp_roles->add_cap( 'franchise', 'read_private_posts' );
					$wp_roles->add_cap( 'franchise', 'delete_private_pages' );
					$wp_roles->add_cap( 'franchise', 'edit_private_pages' );
					$wp_roles->add_cap( 'franchise', 'read_private_pages' );
					$wp_roles->add_cap( 'franchise', 'delete_users' );
					$wp_roles->add_cap( 'franchise', 'create_users' );
					$wp_roles->add_cap( 'franchise', 'unfiltered_upload' );
					$wp_roles->add_cap( 'franchise', 'update_plugins' );
					$wp_roles->add_cap( 'franchise', 'delete_plugins' );
					$wp_roles->add_cap( 'franchise', 'update_plugins' );
					$wp_roles->add_cap( 'franchise', 'install_plugins' );
					$wp_roles->add_cap( 'franchise', 'update_plugins' );
					$wp_roles->add_cap( 'franchise', 'update_themes' );
					$wp_roles->add_cap( 'franchise', 'update_plugins' );
					$wp_roles->add_cap( 'franchise', 'install_themes' );
					$wp_roles->add_cap( 'franchise', 'update_core' );
					$wp_roles->add_cap( 'franchise', 'list_users' );
					$wp_roles->add_cap( 'franchise', 'remove_users' );
					$wp_roles->add_cap( 'franchise', 'add_users' );
					$wp_roles->add_cap( 'franchise', 'promote_users' );
					$wp_roles->add_cap( 'franchise', 'edit_theme_options' );
					$wp_roles->add_cap( 'franchise', 'delete_themes' );
					$wp_roles->add_cap( 'franchise', 'promote_users' );
					$wp_roles->add_cap( 'franchise', 'manage_galleries' );
					$wp_roles->add_cap( 'franchise', 'promote_users' );
					$wp_roles->add_cap( 'franchise', 'administrator' );
					$wp_roles->add_cap( 'franchise', 'can_edit_posts' );
					
}
/*
*	Delete / uninstall plugin
*/
function franchise_uninstall()
{
 global $wpdb;
 $wpdb->query( "DROP TABLE IF EXISTS zc_custom_fields" );
 $wpdb->query( "DROP TABLE IF EXISTS zc_custom_field_options" );
 $wpdb->query( "DROP TABLE IF EXISTS zc_profile" );
 $wpdb->query( "DROP TABLE IF EXISTS zc_zip" );
 $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}development" );
 remove_role( 'franchise' ); 
 remove_role( 'franchisor' ); 
}
?>