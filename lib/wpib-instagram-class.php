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
			$import_type = intval($_REQUEST["import_type"]);
			$album_id = intval($_REQUEST["album_id"]);
			
			if($import_type == 1)
			{
				$instagram_user_data = array();
				$instagram_user_data = get_instagram_userid($user_name,"1000");
				if(!empty($instagram_user_data->data))
				{
					$user_id = $instagram_user_data->data[0]->id;
					$insta_array = wp_get_instagram_images($user_id,"1000");
				}
			}
			else
			{
				$insta_array = get_hashtag_files($user_name,"1000");
			}
			
			if(!is_wp_error($insta_array) && !empty($insta_array))
			{
				foreach ($insta_array->data as $content)
				{
					$file_name_exct = explode("/", esc_url($content->images->thumbnail->url));
					$file_name = $file_name_exct[count($file_name_exct) - 1];
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO " . wpib_album_pics() .
							" (album_id,title,description,thumbnail_url,image_url,tags,album_cover,enable_redirect,url,video,pic_name,date)
							VALUES(%d,%s,%s,%s,%s,%s,%d,%d,%s,%d,%s,CURDATE())",
							$album_id,
							isset($content->caption) ? esc_attr($content->caption->text) : "",
							"",
							esc_url($content->images->thumbnail->url),
							esc_url($content->images->standard_resolution->url),
							"",
							0,
							0,
							esc_url($content->link),
							($content->type == "image" ? 0 : 1),
							$file_name
						)
					);
					$pic_id = $wpdb->insert_id;
					?>
					<tr>
						<td><input type="checkbox" id="ux_chk_select_items" name="ux_chk_select_items" value="<?php echo $pic_id;?>"/></td>
						<td>
							<?php echo esc_attr($file_name);?><br>
							<?php echo date("F j, Y");?><br>
							<?php echo intval($content->images->thumbnail->width)." x ".intval($content->images->thumbnail->height); ?><br>
							<input type="radio" name="ux_rdl_album_cover" id="ux_rdl_album_cover<?php echo $pic_id;?>" ><?php  _e( "Set as album cover", instagram_bank );?><br>
							<a onclick="delete_pic(this)" control_id="<?php echo $pic_id;?>" style="cursor: pointer;"><?php _e("Delete", instagram_bank );?></a>
							<br><input type="checkbox" name="ux_chk_insta_redirect" id="ux_chk_insta_redirect" /> <?php _e( "Enable redirect on Instagram", instagram_bank );?>
						</td>
						<td>
							<img src="<?php echo esc_url($content->images->thumbnail->url);?>" image_id = "<?php echo $pic_id;?>" type="<?php echo $content->type;?>" image_link="<?php echo esc_url($content->link);?>" style="border:2px solid #000000;" />
						</td>
						<td>
							<input type="text" id="ux_txt_insta_title" name="ux_txt_insta_title" placeholder="<?php _e("Enter your Title", instagram_bank );?>" value="<?php echo isset($content->caption) ? esc_attr($content->caption->text) : ""; ?>" style="margin-bottom: 10px;"><br/>
							<textarea name="ux_txt_insta_desc" id="ux_txt_insta_desc" rows="4" cols="20" placeholder="<?php _e("Enter your Description", instagram_bank );?>"></textarea>
						</td>
						<td>
							<textarea name="ux_txt_insta_tags" id="ux_txt_insta_tags" rows="6" cols="20" placeholder="<?php _e("Enter your Tags", instagram_bank );?>"/></textarea>
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
					"aaSorting": [[ 1, "asc" ]],
					"aoColumnDefs": [{ "bSortable": false, "aTargets": [0] }]
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
			$import_type = intval($_REQUEST["import_type"]);
			$description = html_entity_decode(stripslashes($_REQUEST["album_desc"]));
			$wpdb->query
			(
				$wpdb->prepare
				(
					"UPDATE " . wpib_albums() . " SET album_name = %s, description = %s, user_name = %s, import_method = %d WHERE album_id = %d",
					$title,
					$description,
					$user_name,
					$import_type,
					$albumId
				)
			);
			die();
		}
		elseif ($_REQUEST["param"] == "update_insta_pics")
		{
			$album_pics = json_decode(stripcslashes($_REQUEST["album_pics"]));
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
		elseif($_REQUEST["param"] == "instagram_bank_plugin_updates")
		{
			$instagram_bank_updates = intval($_REQUEST["instagram_bank_updates"]);
			update_option("instagram-bank-automatic-update", $instagram_bank_updates);
			die();
		}
	}
}

function get_instagram_userid($name, $limit)
{
	return get_instagram_images_files('users/search', false, array('q' => $name, 'count' => $limit));
}

function get_hashtag_files($name, $limit)
{
	return get_instagram_images_files('tags/' . $name . '/media/recent', false, array('count' => $limit));
}

function wp_get_instagram_images($id, $limit)
{
	return get_instagram_images_files('users/' . $id . '/media/recent', ($id === 'self'), array('count' => $limit));
}

function get_instagram_images_files($function, $auth = false, $params = null, $method = 'GET')
{
	if (false === $auth)
	{
		// if the call doesn't requires authentication
		$authMethod = '?client_id=6271cc18471d429e9d2ba1895023a0f2';
	}
	else
	{
		// if the call needs an authenticated user
		if (true === isset($this->_accesstoken))
		{
			$authMethod = '?access_token=' . $this->getAccessToken();
		}
		else
		{
			throw new Exception("Error: _makeCall() | $function - This method requires an authenticated users access token.");
		}
	}

	if (isset($params) && is_array($params))
	{
		$paramString = "&" . http_build_query($params);
	}
	else
	{
		$paramString = null;
	}
	$apiCall = "https://api.instagram.com/v1/" . $function . $authMethod . (("GET" === $method) ? $paramString : null);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $apiCall);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	if ("POST" === $method)
	{
		curl_setopt($ch, CURLOPT_POST, count($params));
		curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($paramString, "&"));
	}
	else if ("DELETE" === $method)
	{
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	}

	$jsonData = curl_exec($ch);
	if (false === $jsonData)
	{
		throw new Exception("Error: _makeCall() - cURL error: " . curl_error($ch));
	}
	curl_close($ch);
	return json_decode($jsonData);
}
?>