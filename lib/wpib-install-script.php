<?php
global $wpdb;
require_once(ABSPATH . "wp-admin/includes/upgrade.php");
$instagram_version = get_option("instagram-bank-pro-edition");
if(!function_exists("create_table_insta_albums"))
{
	function create_table_insta_albums()
	{
		$sql = "CREATE TABLE " . wpib_albums() . "(
				album_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				album_name VARCHAR(100),
				user_name VARCHAR(100),
				album_date DATE,
				description TEXT,
				import_method INTEGER(2),
				PRIMARY KEY (album_id)
				) DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
		dbDelta($sql);
	}
}

if(!function_exists("create_table_insta_pics"))
{
	function create_table_insta_pics()
	{
		$sql = "CREATE TABLE " . wpib_album_pics() . "(
				pic_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				album_id INTEGER(10) UNSIGNED NOT NULL,
				title TEXT,
				description TEXT,
				thumbnail_url TEXT NOT NULL,
				image_url TEXT NOT NULL,
				tags TEXT,
				album_cover INTEGER(1),
				enable_redirect INTEGER(1),
				url VARCHAR(250),
				video INTEGER(10) NOT NULL,
				pic_name TEXT NOT NULL,
				date DATE,
				PRIMARY KEY(pic_id)
				) DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
		dbDelta($sql);
	}
}

if (count($wpdb->get_var("SHOW TABLES LIKE '" . wpib_albums() . "'")) == 0)
{
	create_table_insta_albums();
}
else
{
	$check_import_type = $wpdb->get_var
	(
		"SHOW COLUMNS FROM " . wpib_albums() . " LIKE 'import_method'"
	);
	
	if($check_import_type == "")
	{
		$wpdb->query
		(
			"ALTER TABLE " . wpib_albums() . " ADD import_method INTEGER(2) DEFAULT 1"
		);
	}
}

if (count($wpdb->get_var("SHOW TABLES LIKE '" . wpib_album_pics() . "'")) == 0)
{
	create_table_insta_pics();
}

if($instagram_version == "")
{
	update_option("instagram-bank-automatic-update", "1");
	update_option("instagram-bank-pro-edition", "1.0");
}
?>