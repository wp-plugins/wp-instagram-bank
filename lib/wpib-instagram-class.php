<?php
switch($role)
{
	case "administrator":
		$user_role_permission = "manage_options";
		break;
	case "editor":
		$user_role_permission = "publish_pages";
		break;
	case "author":
		$user_role_permission = "publish_posts";
		break;
}

if (!current_user_can($user_role_permission))
{
	return;
}
else
{
	if (isset($_REQUEST["param"]))
	{
		if ($_REQUEST["param"] == "get_insta_gallery")
		{
			$user_name = esc_attr($_REQUEST["user_name"]);
			$album_id = intval($_REQUEST["album_id"]);
			$insta_array = wp_get_instagram_gallery($user_name);
			if(!is_wp_error($insta_array))
			{
				foreach ($insta_array as $content)
				{
					$file_name_exct = explode("/", esc_url($content["thumbnail"]["url"]));
					$file_name = $file_name_exct[count($file_name_exct) - 1];
					if ($content["type"] == "image")
					{
						$wpdb->query
						(
							$wpdb->prepare
							(
								"INSERT INTO " . wpib_album_pics() . 
								" (album_id,title,description,thumbnail_url,image_url,tags,album_cover,enable_redirect,url,video,pic_name,date)
									VALUES(%d,%s,%s,%s,%s,%s,%d,%d,%s,%d,%s,CURDATE())",
									$album_id,
									esc_attr($content["description"]),
									"",
									esc_url($content["thumbnail"]["url"]),
									esc_url($content["large"]["url"]),
									"",
									0,
									0,
									esc_url($content["link"]),
									0,
									$file_name
							)
						);
						echo $pic_id = $wpdb->insert_id;
					}
					else
					{
						$wpdb->query
						(
							$wpdb->prepare
							(
									"INSERT INTO " . wpib_album_pics() .
									" (album_id,title,description,thumbnail_url,image_url,tags,album_cover,enable_redirect,url,video,pic_name,date)
								VALUES(%d,%s,%s,%s,%s,%s,%d,%d,%s,%d,%s,CURDATE())",
									$album_id,
									esc_attr($content["description"]),
									"",
									esc_url($content["thumbnail"]["url"]),
									esc_url($content["large"]["url"]),
									"",
									0,
									0,
									esc_url($content["link"]),
									1,
									$file_name
							)
						);
						echo $pic_id = $wpdb->insert_id;
					}
					?>
					<tr>
						<td><input type="checkbox" id="ux_chk_select_items" name="ux_chk_select_items" value="<?php echo $pic_id;?>"/></td>
						<td>
							<?php echo esc_attr($file_name);?><br>
							<?php echo date("F j, Y");?><br>
							<?php echo intval($content["thumbnail"]["width"])." x ".intval($content["thumbnail"]["height"]); ?><br>
							<input type="radio" name="ux_rdl_album_cover" id="ux_rdl_album_cover<?php echo $pic_id;?>" ><?php  _e( "Set as album cover", instagram_bank );?><br>
							<a onclick="delete_pic(this)" control_id="<?php echo $pic_id;?>"><?php _e("Delete", instagram_bank );?></a>
							<br><input type="checkbox" name="ux_chk_insta_redirect" id="ux_chk_insta_redirect" /> <?php _e( "Enable redirect on Instagram", instagram_bank );?>
						</td>
						<td>
							<img src="<?php echo esc_url($content["thumbnail"]["url"]);?>" image_id = "<?php echo $pic_id;?>" type="<?php echo $content["type"];?>" image_link="<?php echo esc_url( $content["link"] );?>" />
						</td>
						<td>
							<input type="text" id="ux_txt_insta_title" name="ux_txt_insta_title" placeholder="Enter your Title" value="<?php echo  esc_attr($content["description"]); ?>" style="margin-bottom: 10px;"><br/>
							<textarea name="ux_txt_insta_desc" id="ux_txt_insta_desc" rows="4" cols="20" placeholder="Enter your Description"/ ></textarea>
						</td>
						<td>
							<textarea name="ux_txt_insta_tags" id="ux_txt_insta_tags" rows="6" cols="20" placeholder="Enter your Tags"/></textarea>
						</td>
					</tr>
					<?php
					
				}
			}
			?>
			<script type="text/javascript">
				oTable = jQuery("#ux_data-instagram-images").dataTable
				({
					"bJQueryUI": false,
					"bAutoWidth": true,
					"sPaginationType": "full_numbers",
					"sDom": "<\"datatable-header\"fl>t<\"datatable-footer\"ip>",
					"oLanguage": 
					{
						"sLengthMenu": "<span>Show entries:</span> _MENU_"
					},
					"aaSorting": [[ 0, "asc" ]]
				});
				jQuery(".datatable-header").css("float","right");
				jQuery(".datatable-header").css("margin-bottom","8px");
			</script>
			<?php 
			die();
		}
		elseif ($_REQUEST["param"] == "update_insta_album")
		{
			$albumId = intval($_REQUEST["album_id"]);
			$title = html_entity_decode($_REQUEST["album_title"]);
			$user_name = esc_attr($_REQUEST["user_name"]);
			$description = html_entity_decode(stripslashes($_REQUEST["album_desc"]));
			$wpdb->query
			(
				$wpdb->prepare
				(
					"UPDATE " . wpib_albums() . " SET album_name = %s, description = %s, user_name = %s WHERE album_id = %d",
					$title,
					$description,
					$user_name,
					$albumId
				)
			);
			die();
		}
		elseif ($_REQUEST["param"] == "update_insta_pics")
		{
			$album_pics = json_decode(stripcslashes($_REQUEST["album_pics"]));
			print_r($album_pics);
			foreach($album_pics as $value)
			{
				$iscoverset = $value[2] == "checked" ? "1" : "0";
				$enableredirect = $value[3] == "checked" ? "1" : "0";
				$wpdb->query
				(
					$wpdb->prepare
					(
						"UPDATE " . wpib_album_pics() . " SET title = %s, description = %s, tags = %s, album_cover = %d, enable_redirect =%d, date = CURDATE() WHERE pic_id = %d",
						htmlspecialchars($value[4]),
						htmlspecialchars($value[5]),
						$value[6],
						$iscoverset,
						$enableredirect,
						$value[1]
					)
				);
			}
			die();
		}
		elseif ($_REQUEST["param"] == "delete_album_pic")
		{
			$delete_array = (html_entity_decode($_REQUEST["delete_array"]));
			$albumId = intval($_REQUEST["album_id"]);
			
			$wpdb->query
			(
					"DELETE FROM " . wpib_album_pics() . " WHERE pic_id in ($delete_array)"
			);
			die();
		}
		elseif ($_REQUEST["param"] == "delete_album")
		{
			$id=intval($_REQUEST["id"]);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"DELETE FROM " .wpib_albums(). " WHERE album_id = %d",
					$id
				)
			);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"DELETE FROM " .wpib_album_pics(). " WHERE album_id = %d",
					$id
				)
			);
			die();
		}
	}
}

function wp_get_instagram_gallery($username)
{
	if (false === ($instagram = get_transient("instagram-media-".sanitize_title_with_dashes($username)))) {

		$remote = wp_remote_get("http://instagram.com/".trim($username));

		if (is_wp_error($remote))
			return new WP_Error("site_down", __("Unable to communicate with Instagram.", instagram_bank));

		if ( 200 != wp_remote_retrieve_response_code( $remote ) )
			return new WP_Error("invalid_response", __("Instagram did not return a 200.", instagram_bank));

		$shards = explode("window._sharedData = ", $remote["body"]);
		$insta_json = explode(";</script>", $shards[1]);
		$insta_array = json_decode($insta_json[0], TRUE);

		if (!$insta_array)
			return new WP_Error("bad_json", __("Instagram has returned invalid data.", instagram_bank));

		$images = $insta_array["entry_data"]["UserProfile"][0]["userMedia"];

		$instagram = array();

		foreach ($images as $image) {

			if ($image["user"]["username"] == $username) {

				$image["link"]                          = preg_replace( "/^http:/i", "", $image["link"] );
				$image["images"]["thumbnail"]           = preg_replace( "/^http:/i", "", $image["images"]["thumbnail"] );
				$image["images"]["standard_resolution"] = preg_replace( "/^http:/i", "", $image["images"]["standard_resolution"] );

				$instagram[] = array(
						"description"   => $image["caption"]["text"],
						"link"          => $image["link"],
						"time"          => $image["created_time"],
						"comments"      => $image["comments"]["count"],
						"likes"         => $image["likes"]["count"],
						"thumbnail"     => $image["images"]["thumbnail"],
						"large"         => $image["images"]["standard_resolution"],
						"type"          => $image["type"]
				);
			}
		}

		$instagram = base64_encode( serialize( $instagram ) );
		set_transient("instagram-media-".sanitize_title_with_dashes($username), $instagram, apply_filters("null_instagram_cache_time", HOUR_IN_SECONDS*2));
	}

	$instagram = unserialize( base64_decode( $instagram ) );
	return $instagram;
}
?>