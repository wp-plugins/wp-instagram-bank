<?php
//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING MENUS
//---------------------------------------------------------------------------------------------------------------//
global $wpdb,$current_user;
$role = $wpdb->prefix . "capabilities";
$current_user->role = array_keys($current_user->$role);
$role = $current_user->role[0];

switch($role)
{
	case "administrator":
		add_menu_page("Instagram Bank", __("Instagram Bank", instagram_bank), "read", "instagram_bank", "", INSTAGRAM_BK_PLUGIN_URL . "/assets/images/instagram.png");
		add_submenu_page("instagram_bank", "Dashboard", __("Dashboard", instagram_bank), "read", "instagram_bank",  "instagram_bank");
		add_submenu_page("", "", "", "read", "add_album",  "add_album");
		add_submenu_page("instagram_bank", "System Status", __("System Status",instagram_bank), "read", "wp_system_status",  "wp_system_status");
		break;
	case "editor":
		add_menu_page("Instagram Bank", __("Instagram Bank", instagram_bank), "read", "instagram_bank", "", INSTAGRAM_BK_PLUGIN_URL . "/assets/images/instagram.png");
		add_submenu_page("instagram_bank", "Dashboard", __("Dashboard", instagram_bank), "read", "instagram_bank",  "instagram_bank");
		add_submenu_page("", "", "", "read", "add_album",  "add_album");
		add_submenu_page("instagram_bank", "System Status", __("System Status",instagram_bank), "read", "wp_system_status",  "wp_system_status");
		break;
	case "author":
		add_menu_page("Instagram Bank", __("Instagram Bank", instagram_bank), "read", "instagram_bank", "", INSTAGRAM_BK_PLUGIN_URL . "/assets/images/instagram.png");
		add_submenu_page("instagram_bank", "Dashboard", __("Dashboard", instagram_bank), "read", "instagram_bank",  "instagram_bank");
		add_submenu_page("", "", "", "read", "add_album",  "add_album");
		add_submenu_page("instagram_bank", "System Status", __("System Status",instagram_bank), "read", "wp_system_status",  "wp_system_status");
		break;
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING PAGES
//---------------------------------------------------------------------------------------------------------------//

function instagram_bank()
{
	global $wpdb,$current_user,$user_role_permission;
	$role = $wpdb->prefix . "capabilities";
	$current_user->role = array_keys($current_user->$role);
	$role = $current_user->role[0];
	include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/wpib-dashboard.php";
}

function add_album()
{
	global $wpdb,$current_user,$user_role_permission;
	$role = $wpdb->prefix . "capabilities";
	$current_user->role = array_keys($current_user->$role);
	$role = $current_user->role[0];
	include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/wpib-instagram-view.php";
}

function wp_system_status()
{
	global $wpdb,$current_user,$user_role_permission,$wp_version;
	$role = $wpdb->prefix . "capabilities";
	$current_user->role = array_keys($current_user->$role);
	$role = $current_user->role[0];
	include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/wpib-system-status.php";
}

?>