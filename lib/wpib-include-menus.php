<?php
//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING MENUS
//---------------------------------------------------------------------------------------------------------------//
switch($role)
{
	case "administrator":
		add_menu_page("Instagram Bank", __("Instagram Bank", instagram_bank), "read", "instagram_bank", "",plugins_url("/assets/images/instagram.png" , dirname(__FILE__)));
		add_submenu_page("instagram_bank", "Dashboard", __("Dashboard", instagram_bank), "read", "instagram_bank",  "instagram_bank");
		add_submenu_page("instagram_bank", "Add New Album", __("Add New Album", instagram_bank), "read", "add_album",  "add_album");
		add_submenu_page("instagram_bank", "Plugin Update", __("Plugin Update", instagram_bank), "read", "wp_instagram_auto_plugin_update", "wp_instagram_auto_plugin_update");
		add_submenu_page("instagram_bank", "Short Codes", __("Short Codes", instagram_bank), "read", "short_code",  "short_code");
		add_submenu_page("instagram_bank", "Recommendations", __("Recommendations", instagram_bank), "read", "recommended_plugins_instagram", "recommended_plugins_instagram" );
		add_submenu_page("instagram_bank", "Our Other Services", __("Our Other Services", instagram_bank), "read", "other_services_instagram", "other_services_instagram" );
		add_submenu_page("instagram_bank", "System Status", __("System Status",instagram_bank), "read", "wp_system_status",  "wp_system_status");
	break;
	case "editor":
		add_menu_page("Instagram Bank", __("Instagram Bank", instagram_bank), "read", "instagram_bank", "",plugins_url("/assets/images/instagram.png" , dirname(__FILE__)));
		add_submenu_page("instagram_bank", "Dashboard", __("Dashboard", instagram_bank), "read", "instagram_bank",  "instagram_bank");
		add_submenu_page("instagram_bank", "Add New Album", __("Add New Album", instagram_bank), "read", "add_album",  "add_album");
		add_submenu_page("instagram_bank", "Plugin Update", __("Plugin Update", instagram_bank), "read", "wp_instagram_auto_plugin_update", "wp_instagram_auto_plugin_update");
		add_submenu_page("instagram_bank", "Short Codes", __("Short Codes", instagram_bank), "read", "short_code",  "short_code");
		add_submenu_page("instagram_bank", "Recommendations", __("Recommendations", instagram_bank), "read", "recommended_plugins_instagram", "recommended_plugins_instagram" );
		add_submenu_page("instagram_bank", "Our Other Services", __("Our Other Services", instagram_bank), "read", "other_services_instagram", "other_services_instagram" );
		add_submenu_page("instagram_bank", "System Status", __("System Status",instagram_bank), "read", "wp_system_status",  "wp_system_status");
	break;
	case "author":
		add_menu_page("Instagram Bank", __("Instagram Bank", instagram_bank), "read", "instagram_bank", "",plugins_url("/assets/images/instagram.png" , dirname(__FILE__)));
		add_submenu_page("instagram_bank", "Dashboard", __("Dashboard", instagram_bank), "read", "instagram_bank",  "instagram_bank");
		add_submenu_page("instagram_bank", "Add New Album", __("Add New Album", instagram_bank), "read", "add_album",  "add_album");
		add_submenu_page("instagram_bank", "Plugin Update", __("Plugin Update", instagram_bank), "read", "wp_instagram_auto_plugin_update", "wp_instagram_auto_plugin_update");
		add_submenu_page("instagram_bank", "Short Codes", __("Short Codes", instagram_bank), "read", "short_code",  "short_code");
		add_submenu_page("instagram_bank", "Recommendations", __("Recommendations", instagram_bank), "read", "recommended_plugins_instagram", "recommended_plugins_instagram" );
		add_submenu_page("instagram_bank", "Our Other Services", __("Our Other Services", instagram_bank), "read", "other_services_instagram", "other_services_instagram" );
		add_submenu_page("instagram_bank", "System Status", __("System Status",instagram_bank), "read", "wp_system_status",  "wp_system_status");
	break;
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING PAGES
//---------------------------------------------------------------------------------------------------------------//
if(!function_exists( "instagram_bank" ))
{
	function instagram_bank()
	{
		global $wpdb,$current_user,$user_role_permission;
		if(is_super_admin())
		{
			$role = "administrator";
		}
		else
		{
			$role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$role);
			$role = $current_user->role[0];
		}
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/instagram_header.php";
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/wpib-dashboard.php";
	}
}
if(!function_exists( "add_album" ))
{
	function add_album()
	{
		global $wpdb,$current_user,$user_role_permission;
		if(is_super_admin())
		{
			$role = "administrator";
		}
		else
		{
			$role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$role);
			$role = $current_user->role[0];
		}
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/instagram_header.php";
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/wpib-instagram-view.php";
	}
}
if(!function_exists( "wp_system_status" ))
{
	function wp_system_status()
	{
		global $wpdb,$current_user,$user_role_permission;
		if(is_super_admin())
		{
			$role = "administrator";
		}
		else
		{
			$role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$role);
			$role = $current_user->role[0];
		}
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/instagram_header.php";
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/wpib-system-status.php";
	}
}
if(!function_exists( "recommended_plugins_instagram" ))
{
	function recommended_plugins_instagram()
	{
		global $wpdb,$current_user,$user_role_permission,$wp_version;
		if(is_super_admin())
		{
			$role = "administrator";
		}
		else
		{
			$role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$role);
			$role = $current_user->role[0];
		}
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/instagram_header.php";
		if (file_exists(INSTAGRAM_BK_PLUGIN_DIR ."/views/recommended-plugins.php"))
		{
			include_once INSTAGRAM_BK_PLUGIN_DIR ."/views/recommended-plugins.php";
		}
	}
}
if(!function_exists( "other_services_instagram" ))
{
	function other_services_instagram()
	{
		global $wpdb,$current_user,$user_role_permission,$wp_version;
		if(is_super_admin())
		{
			$role = "administrator";
		}
		else
		{
			$role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$role);
			$role = $current_user->role[0];
		}
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/instagram_header.php";
		if (file_exists(INSTAGRAM_BK_PLUGIN_DIR ."/views/other-services.php"))
		{
			include_once INSTAGRAM_BK_PLUGIN_DIR ."/views/other-services.php";
		}
	}
}

if(!function_exists( "short_code" ))
{
	function short_code()
	{
		global $wpdb,$current_user,$user_role_permission,$wp_version;
		if(is_super_admin())
		{
			$role = "administrator";
		}
		else
		{
			$role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$role);
			$role = $current_user->role[0];
		}
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/instagram_header.php";
		if (file_exists(INSTAGRAM_BK_PLUGIN_DIR ."/views/short-code-tab.php"))
		{
			include_once INSTAGRAM_BK_PLUGIN_DIR ."/views/short-code-tab.php";
		}
	}
}
if(!function_exists( "wp_instagram_auto_plugin_update" ))
{
	function wp_instagram_auto_plugin_update()
	{
		global $wpdb,$current_user,$user_role_permission,$wp_version;
		if(is_super_admin())
		{
			$role = "administrator";
		}
		else
		{
			$role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$role);
			$role = $current_user->role[0];
		}
		include_once INSTAGRAM_BK_PLUGIN_DIR . "/views/instagram_header.php";
		if (file_exists(INSTAGRAM_BK_PLUGIN_DIR ."/views/automatic-plugin-update.php"))
		{
			include_once INSTAGRAM_BK_PLUGIN_DIR ."/views/automatic-plugin-update.php";
		}
	}
}

?>