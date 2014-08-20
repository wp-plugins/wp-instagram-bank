<?php
/*
Plugin Name: Wp Instagram Bank
Plugin URI: http://tech-banker.com
Description: WP Instagram Bank is an ultimate WordPress Plugin to showcase your latest Instagram pics.
Author: Tech Banker
Version: 1.0.2
Author URI: http://tech-banker.com
*/


/////////////////////////////////////  Define  WP Instagam Bank  Constants  ////////////////////////////////////////

if (!defined("INSTAGRAM_BK_PLUGIN_DIR")) define("INSTAGRAM_BK_PLUGIN_DIR",  plugin_dir_path( __FILE__ ));
if (!defined("INSTAGRAM_BK_PLUGIN_DIRNAME")) define("INSTAGRAM_BK_PLUGIN_DIRNAME", plugin_basename(dirname(__FILE__)));
if (!defined("instagram_bank")) define("instagram_bank", "instagram-bank");


/////////////////////////////////////  Call CSS & JS Scripts - Front End ////////////////////////////////////////

function front_end_js_calls()
{
	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery.prettyPhoto.js", plugins_url("/assets/js/jquery.prettyPhoto.js",__FILE__));
}

function front_end_css_calls()
{
	wp_enqueue_style("prettyPhoto.css", plugins_url("/assets/css/prettyPhoto.css",__FILE__));
}

/////////////////////////////////////  Call CSS & JS Scripts - Back End ////////////////////////////////////////

function admin_panel_js_calls()
{
	wp_enqueue_script("jquery");
	wp_enqueue_script("farbtastic");
	wp_enqueue_script("jquery.dataTables.min.js", plugins_url("/assets/js/jquery.dataTables.min.js",__FILE__));
	wp_enqueue_script("jquery.validate.min.js", plugins_url("/assets/js/jquery.validate.min.js",__FILE__));
}

function admin_panel_css_calls()
{
	wp_enqueue_style("farbtastic");
	wp_enqueue_style("stylesheet.css", plugins_url("/assets/css/stylesheet.css",__FILE__));
	wp_enqueue_style("system-message.css", plugins_url("/assets/css/system-message.css",__FILE__));
}


/////////////////////////////////////  Functions for Returing Table Names  ////////////////////////////////////////
function wpib_albums()
{
	global $wpdb;
	return $wpdb->prefix . "instagram_bank_albums";
}

function wpib_album_pics()
{
	global $wpdb;
	return $wpdb->prefix . "instagram_bank_pics";
}

/////////////////////////////////////  Call Install Script on Plugin Activation ////////////////////////////////////////

function plugin_install_script_for_instagram_bank()
{
	include_once INSTAGRAM_BK_PLUGIN_DIR . "/lib/wpib-install-script.php";
}

/////////////////////////////////////  Call Languages for Multi-Lingual ////////////////////////////////////////
function instagram_bank_plugin_load_text_domain()
{
	if (function_exists("load_plugin_textdomain"))
	{
		load_plugin_textdomain(instagram_bank, false, INSTAGRAM_BK_PLUGIN_DIRNAME . "/lang");
	}
}
//
///////////////////////////////////  Call Shortcodes for Front End ////////////////////////////////////////
function instagram_bank_short_code($atts)
{
	extract(shortcode_atts(array(
	"album_id" => '',
	"title" => '',
	"desc" => '',
	), $atts));
	return extract_short_code_for_instagram_images($album_id,$title,$desc);
}

/////////////////////////////////////  Extract Shortcodes called by Front End Function ////////////////////////////////////////
function extract_short_code_for_instagram_images($album_id,$title,$desc)
{
	ob_start();
	
	global $wpdb;

	include INSTAGRAM_BK_PLUGIN_DIR . "/front_views/wpib-include-comon-before.php";
	include INSTAGRAM_BK_PLUGIN_DIR . "/front_views/wpib-show-album.php";
	include INSTAGRAM_BK_PLUGIN_DIR . "/front_views/wpib-include-common-after.php";
	
	$instagram_bank_output_album = ob_get_clean();
	wp_reset_query();

	return $instagram_bank_output_album;
}

/////////////////////////////////////  Global Function to Convert String to Upper ////////////////////////////////////////
function array_iunique_instagram($array)
{
	return array_intersect_key($array,array_unique(array_map("StrToUpper", $array)));
}

/////////////////////////////////////  Include Menus on Dashboard ////////////////////////////////////////
function create_global_menus_for_instagram_bank()
{
	include_once INSTAGRAM_BK_PLUGIN_DIR . "/lib/wpib-include-menus.php";
}


///////////////////////////////////// Register Ajax Based Functions /////////////////////////////////////

if (isset($_REQUEST["action"])) {
	switch ($_REQUEST["action"]) {
		case "instagram_library":
		add_action("admin_init", "instagram_library");
		function instagram_library()
		{
			
			global $wpdb,$current_user,$user_role_permission;
			$role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$role);
			$role = $current_user->role[0];
			include_once INSTAGRAM_BK_PLUGIN_DIR . "/lib/wpib-instagram-class.php";
		}
		break;
	}
}

///////////////////////////////////  Call Hooks   /////////////////////////////////////////////////////

add_shortcode("wp_instagram_bank", "instagram_bank_short_code");
add_action("plugins_loaded", "instagram_bank_plugin_load_text_domain");
add_action("admin_menu", "create_global_menus_for_instagram_bank");
add_action("admin_init", "admin_panel_css_calls");
add_action("admin_init", "admin_panel_js_calls");
add_action("init", "front_end_js_calls");
add_action("init", "front_end_css_calls");
register_activation_hook(__FILE__, "plugin_install_script_for_instagram_bank");
?>