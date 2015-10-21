<?php
/*
Plugin Name: Wp Instagram Bank
Plugin URI: http://tech-banker.com
Description: WP Instagram Bank is an ultimate WordPress Plugin to showcase your latest Instagram pics.
Author: Tech Banker
Version: 1.0.23
Author URI: http://tech-banker.com
License: GPLv2 or later
*/


/////////////////////////////////////  Define  WP Instagam Bank  Constants  ////////////////////////////////////////

if (!defined("INSTAGRAM_BK_PLUGIN_DIR")) define("INSTAGRAM_BK_PLUGIN_DIR",  plugin_dir_path( __FILE__ ));
if (!defined("INSTAGRAM_BK_PLUGIN_DIRNAME")) define("INSTAGRAM_BK_PLUGIN_DIRNAME", plugin_basename(dirname(__FILE__)));
if (!defined("instagram_bank")) define("instagram_bank", "instagram-bank");
if (!defined("tech_bank")) define("tech_bank", "tech-banker");
if (!defined("INSTAGRAM_FILE")) define("INSTAGRAM_FILE","wp-instagram-bank/wp-instagram-bank.php");


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
	wp_enqueue_style("framework.css", plugins_url("/assets/css/framework.css",__FILE__));
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
if(!function_exists("plugin_install_script_for_instagram_bank"))
{
	function plugin_install_script_for_instagram_bank()
	{
		global $wpdb;
		if (is_multisite())
		{
			$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach($blog_ids as $blog_id)
			{
				switch_to_blog($blog_id);
				include INSTAGRAM_BK_PLUGIN_DIR . "/lib/wpib-install-script.php";
				restore_current_blog();
			}
		}
		else
		{
			include_once INSTAGRAM_BK_PLUGIN_DIR . "/lib/wpib-install-script.php";
		}
	}
}

////////////////////////////////////  Call Uninstall Script on Plugin Uninstall  ////////////////////////////////////////
if(!function_exists("plugin_uninstall_script_for_instagram_bank"))
{
	function plugin_uninstall_script_for_instagram_bank()
	{
		delete_option("instagram-bank-automatic-update");
		wp_clear_scheduled_hook("instagram_bank_auto_update");
	}
}


/////////////////////////////////////  Call Languages for Multi-Lingual ////////////////////////////////////////

function instagram_bank_plugin_load_text_domain()
{
	if (function_exists("load_plugin_textdomain"))
	{
		load_plugin_textdomain(instagram_bank, false, INSTAGRAM_BK_PLUGIN_DIRNAME . "/lang");
	}
}

/////////////////////////////////////  admin menu////////////////////////////////////////

function add_instagram_icon($meta = TRUE)
{
	global $wp_admin_bar, $wpdb, $current_user;
	if (!is_user_logged_in()) {
		return;
	}
	else 
	{
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
		switch ($role)
		{
			case "administrator":
				$wp_admin_bar->add_menu(array(
				"id" => "instagram_bank",
				"title" => __("<img src=\"" . plugins_url("/assets/images/instagram.png",__FILE__)."\" width=\"25\"
				height=\"25\" style=\"vertical-align:text-top; margin-right:5px;\" />Instagram Bank"),
				"href" => __(site_url() . "/wp-admin/admin.php?page=instagram_bank"),
				));
					
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "dashboard",
						"href" => site_url() . "/wp-admin/admin.php?page=instagram_bank",
						"title" => __("Dashboard", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "add_new_album",
						"href" => site_url() . "/wp-admin/admin.php?page=add_album",
						"title" => __("Add New Album", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "instagram_plugin_update_link",
						"href" => site_url() . "/wp-admin/admin.php?page=wp_instagram_auto_plugin_update",
						"title" => __("Plugin Update", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "short_code",
						"href" => site_url() . "/wp-admin/admin.php?page=short_code",
						"title" => __("Short Codes", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "recommendation_instagram",
						"href" => site_url() . "/wp-admin/admin.php?page=recommended_plugins_instagram",
						"title" => __("Recommendations", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "other_services_instagram",
						"href" => site_url() . "/wp-admin/admin.php?page=other_services_instagram",
						"title" => __("Our Other Services", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "wp_system_status_instagram",
						"href" => site_url() . "/wp-admin/admin.php?page=wp_system_status",
						"title" => __("System Status", instagram_bank))
				);
			break;
			case "editor":
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "dashboard",
						"href" => site_url() . "/wp-admin/admin.php?page=instagram_bank",
						"title" => __("Dashboard", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "add_new_album",
						"href" => site_url() . "/wp-admin/admin.php?page=add_album",
						"title" => __("Add New Album", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "short_code",
						"href" => site_url() . "/wp-admin/admin.php?page=short_code",
						"title" => __("Short Codes", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "recommendation_instagram",
						"href" => site_url() . "/wp-admin/admin.php?page=recommended_plugins_instagram",
						"title" => __("Recommendations", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "other_services_instagram",
						"href" => site_url() . "/wp-admin/admin.php?page=other_services_instagram",
						"title" => __("Our Other Services", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "wp_system_status_instagram",
						"href" => site_url() . "/wp-admin/admin.php?page=wp_system_status",
						"title" => __("System Status", instagram_bank))
				);
			break;
			case "author":
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "dashboard",
						"href" => site_url() . "/wp-admin/admin.php?page=instagram_bank",
						"title" => __("Dashboard", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "add_new_album",
						"href" => site_url() . "/wp-admin/admin.php?page=add_album",
						"title" => __("Add New Album", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "short_code",
						"href" => site_url() . "/wp-admin/admin.php?page=short_code",
						"title" => __("Short Codes", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "recommendation_instagram",
						"href" => site_url() . "/wp-admin/admin.php?page=recommended_plugins_instagram",
						"title" => __("Recommendations", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "other_services_instagram",
						"href" => site_url() . "/wp-admin/admin.php?page=other_services_instagram",
						"title" => __("Our Other Services", instagram_bank))
				);
				$wp_admin_bar->add_menu(array(
						"parent" => "instagram_bank",
						"id" => "wp_system_status_instagram",
						"href" => site_url() . "/wp-admin/admin.php?page=wp_system_status",
						"title" => __("System Status", instagram_bank))
				);
			break;
		}
	}
}

///////////////////////////////////  Call Shortcodes for Front End ////////////////////////////////////////
function instagram_bank_short_code($atts)
{
	extract(shortcode_atts(array(
	"albumid" => '',
	"display_images" => '',
	"no_of_images" => '',
	"sort_by" => '',
	"title" => '',
	"desc" => '',
	), $atts));
	return extract_short_code_for_instagram_images($albumid,$display_images,$no_of_images,$sort_by,$title,$desc);
}

/////////////////////////////////////  Extract Shortcodes called by Front End Function ////////////////////////////////////////
function extract_short_code_for_instagram_images($album_id,$display_images,$no_of_images,$sort_by,$title,$desc)
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
	global $wpdb,$current_user;
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
	include INSTAGRAM_BK_PLUGIN_DIR . "/lib/wpib-include-menus.php";
}

///////////////////////////////////// Shortcodes Generator Functions /////////////////////////////////////
if(!function_exists("add_instagram_shortcode_button"))
{
	function add_instagram_shortcode_button($context) {
		add_thickbox();
		$context .= "<a href=\"#TB_inline?width=300&height=400&inlineId=instagram_bank\"  class=\"button thickbox\"  title=\"" . __("Add Instagram Bank Shortcode", instagram_bank) . "\">
		<span class=\"contact_icon\"></span> Add Instagram Bank Shortcode</a>";
		return $context;
	}
}
if(!function_exists("add_instagram_mce_popup"))
{
	function add_instagram_mce_popup()
	{
		add_thickbox();
		global $wpdb,$current_user,$user_role_permission;
		$role = $wpdb->prefix . "capabilities";
		$current_user->role = array_keys($current_user->$role);
		$role = $current_user->role[0];
		if(file_exists(INSTAGRAM_BK_PLUGIN_DIR ."/views/shortcode.php"))
		{
			include INSTAGRAM_BK_PLUGIN_DIR."/views/shortcode.php";
		}
	}
}

///////////////////////////////////// Register Ajax Based Functions /////////////////////////////////////

if (isset($_REQUEST["action"])) {
	switch ($_REQUEST["action"]) {
		case "instagram_library":
		add_action("admin_init", "instagram_library");
		function instagram_library()
		{
			global $wpdb,$current_user;
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
			include INSTAGRAM_BK_PLUGIN_DIR . "/lib/wpib-instagram-class.php";
		}
		break;
	}
}

function instagram_bank_plugin_update_message($args)
{
	$response = wp_remote_get( 'https://plugins.svn.wordpress.org/wp-instagram-bank/trunk/readme.txt' );
	if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) )
	{
		// Output Upgrade Notice
		$matches        = null;
		$regexp         = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote($args['Version']) . '\s*=|$)~Uis';
		$upgrade_notice = '';
		if ( preg_match( $regexp, $response['body'], $matches ) ) {
			$changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));
			$upgrade_notice .= '<div class="framework_plugin_message">';
			foreach ( $changelog as $index => $line ) {
				$upgrade_notice .= "<p>".$line."</p>";
			}
			$upgrade_notice .= '</div> ';
			echo $upgrade_notice;
		}
	}
}

function instagram_bank_textdomain_for_tech_serices()
{
	if(function_exists( "load_plugin_textdomain" ))
	{
		load_plugin_textdomain(tech_bank, false, INSTAGRAM_BK_PLUGIN_DIRNAME ."/tech-banker-services");
	}
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR PLUGIN AUTOMATIC UPDATE
//--------------------------------------------------------------------------------------------------------------//
$is_instagram_auto_update_option = get_option("instagram-bank-automatic-update");

if($is_instagram_auto_update_option == "" || $is_instagram_auto_update_option == "1")
{
	if (!wp_next_scheduled("instagram_bank_auto_update"))
	{
		wp_schedule_event(time(), "daily", "instagram_bank_auto_update");
	}
	add_action("instagram_bank_auto_update", "wp_instagram_bank_autoUpdate");
}
else
{
	wp_clear_scheduled_hook("instagram_bank_auto_update");
}

function wp_instagram_bank_autoUpdate()
{
	try
	{
		require_once(ABSPATH . "wp-admin/includes/class-wp-upgrader.php");
		require_once(ABSPATH . "wp-admin/includes/misc.php");
		define("FS_METHOD", "direct");
		require_once(ABSPATH . "wp-includes/update.php");
		require_once(ABSPATH . "wp-admin/includes/file.php");
		wp_update_plugins();
		ob_start();
		$instagram_bank_upgrader = new Plugin_Upgrader();
		$instagram_bank_upgrader->upgrade("wp-instagram-bank/wp-instagram-bank.php");
		$output = @ob_get_contents();
		@ob_end_clean();
	}
	catch(Exception $e)
	{
	}
}


$wp_instagram_version = get_option("instagram-bank-pro-edition");

if($wp_instagram_version == "" || $wp_instagram_version == "1.0")
{
	add_action("admin_init", "plugin_install_script_for_instagram_bank");
}
///////////////////////////////////  Call Hooks   /////////////////////////////////////////////////////
register_activation_hook(__FILE__, "plugin_install_script_for_instagram_bank");
register_uninstall_hook(__FILE__, "plugin_uninstall_script_for_instagram_bank");
add_action("plugins_loaded", "instagram_bank_textdomain_for_tech_serices");
add_action("network_admin_menu", "create_global_menus_for_instagram_bank" );
add_action("admin_bar_menu", "add_instagram_icon",100);
add_shortcode("wp_instagram_bank", "instagram_bank_short_code");
add_action("plugins_loaded", "instagram_bank_plugin_load_text_domain");
add_action("admin_menu", "create_global_menus_for_instagram_bank");
add_shortcode("instagram_bank", "instagram_bank_short_code");
add_action("admin_init", "admin_panel_css_calls");
add_action("admin_init", "admin_panel_js_calls");
add_action("init", "front_end_js_calls");
add_action("init", "front_end_css_calls");
add_action("in_plugin_update_message-".INSTAGRAM_FILE,"instagram_bank_plugin_update_message" );
add_action( "media_buttons_context", "add_instagram_shortcode_button", 1);
add_action("admin_footer","add_instagram_mce_popup");

?>