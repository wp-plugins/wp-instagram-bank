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
	$wpib_album_id = $wpdb->get_var
	(
			"SELECT album_id FROM " .wpib_albums(). " order by album_id desc limit 1"
	);
	$albumId = (count($wpib_album_id) == 0 ? "1" : (intval($wpib_album_id) + 1));
	if(isset($_REQUEST["albumId"]))
	{
		$albumId = intval($_REQUEST["albumId"]);
	}
	$albumId_exist = $wpdb->get_var
	(
		$wpdb->prepare
		(
			"SELECT album_id FROM " .wpib_albums(). " where album_id= %d",
			$albumId
		)
	);
	
	if($albumId_exist == 0)
	{
		$wpdb->query
		(
			$wpdb->prepare
			(
				"INSERT INTO " . wpib_albums() . "(album_id,album_name, user_name, album_date, description,import_method)
				VALUES(%d, %s, %s, CURDATE(), %s,%d)",
					$albumId,
					"Untitled Album",
					"",
					"",
					1
			)
		);
		
		$instagram_album = $wpdb->get_row
		(
			$wpdb->prepare
			(
				"SELECT * FROM " . wpib_albums() . " where album_id = %d",
				$albumId
			)
		);
	}
	else
	{
		$instagram_album = $wpdb->get_row
		(
			$wpdb->prepare
			(
				"SELECT * FROM " . wpib_albums() . " where album_id = %d",
				$albumId
			)
		);
	}
	
	$instagram_pics = $wpdb->get_results
	(
		$wpdb->prepare
		(
				"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d order by pic_id asc",
				$albumId
		)
	);
	
	?>
	
	<form id="ux_frm_instagram_album" class="layout-form wpib-page-width" style="width:1000px;">
		<div class="fluid-layout">
			<div class="layout-span12">
				<div class="widget-layout wpib-body-background">
					<div class="widget-layout-title">
						<h4>
							<?php _e("Add New Instagram Album", instagram_bank); ?>
						</h4>
					</div>
					<div class="widget-layout-body">
						<a class="btn btn-success" href="admin.php?page=instagram_bank" style="margin-bottom:4px;"><?php _e("Back to Dashboard", instagram_bank);?></a>
						<input type="submit" class="btn btn-success" style="margin-bottom:4px; float:right" value="<?php _e("Save Changes", instagram_bank);?>" />
						<div class="separator-doubled"></div>
						<div id="update_album_added_message" class="custom-message green" style="display: none;">
							<span>
								<strong><?php _e("Album Saved. Kindly wait for the redirect to happen.", instagram_bank); ?></strong>
							</span>
						</div>
						<div class="fluid-layout">
							<div class="layout-span12">
								<div class="widget-layout">
									<div class="widget-layout-title">
										<h4><?php _e("Album Detail", instagram_bank); ?></h4>
									</div>
									<div class="widget-layout-body">
										<div class="layout-control-group">
											<label class="layout-control-label"><?php _e("Album Title", instagram_bank); ?> : <span class="error">*</span> </label>
											<div class="layout-controls">
												<input type="text" id="ux_txt_album" name="ux_txt_album" class="layout-span12" placeholder="<?php _e("Enter Your Album Title", instagram_bank);?>" value="<?php echo stripcslashes(htmlspecialchars_decode($instagram_album->album_name));?>"/>
												<p class="wpib-desc-italic">	<?php _e("Enter the Tiltle for your Album here", instagram_bank); ?> </p>
											</div>
										</div>
										<div class="layout-control-group">
											<label class="layout-control-label"><?php _e("Import Method", instagram_bank); ?> : <span class="error">*</span> </label>
											<div class="layout-controls-radio">
												<input type="radio" name="ux_rdl_import_type" id="ux_rdl_import_from_username" <?php echo (($instagram_album->import_method == 1) ? "checked=\"checked\"" : "");?> onclick="select_import_type();" value="1"><label style="vertical-align: baseline;"><?php _e("Username", instagram_bank);?></label>
												<input type="radio" name="ux_rdl_import_type" id="ux_rdl_import_from_tags" <?php echo (($instagram_album->import_method == 0) ? "checked=\"checked\"" : "");?> style="margin-left: 10px;" onclick="select_import_type();" value="0"><label style="vertical-align: baseline;"><?php _e("Hashtag", instagram_bank);?></label>
											</div>
										</div>
										<div class="layout-control-group">
											<label class="layout-control-label" id="ux_lbl_import_type"><?php _e("Instagram UserName", instagram_bank); ?> : <span class="error">*</span></label>
											<div class="layout-controls">
												<input type="text" id="ux_txt_user" name="ux_txt_user" class="layout-span12" placeholder="<?php _e("Enter Your Instagram Username", instagram_bank);?>" value="<?php echo esc_attr($instagram_album->user_name);?>">
											<p class="wpib-desc-italic">	<?php _e("Enter Instagram account or Instagram hashtag without using # to import images", instagram_bank); ?> </p>
											</div>
										</div>
										<div class="layout-control-group">
											<label class="layout-control-label"><?php _e("Description", instagram_bank); ?> : </label>
											<div class="layout-controls">
												<textarea name="ux_album_desc" id="ux_album_desc" rows="7" class="layout-span12"  placeholder="<?php _e("Enter Your Album Description", instagram_bank);?>"/ ><?php echo stripslashes(htmlspecialchars_decode($instagram_album->description));?></textarea>
												<p class="wpib-desc-italic">	<?php _e("Enter the description for your Album here", instagram_bank); ?> </p>
											</div>
										</div>
										<div class="layout-control-group">
											<div class="layout-controls">
												<a class="btn btn-success" onclick = "wpib_get_instagram_gallery();"><?php _e("Import Images from Instagram", instagram_bank);?></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="fluid-layout">
							<div class="layout-span12">
								<div class="widget-layout wpib-body-background">
									<div class="widget-layout-title">
										<h4><?php _e("Your Instagram Images", instagram_bank); ?></h4>
									</div>
									<div class="widget-layout-body">
										<div class="fluid-layout">
											<select name="ux_ddl_action" id="ux_ddl_action">
												<option value="" selected="selected" ><?php _e( "Bulk Action", instagram_bank ); ?></option>
												<option value="delete" ><?php _e( "Delete", instagram_bank ); ?></option>
											</select>
											<input type="button" name="ux_delete_selected" id="ux_delete_selected" onclick="delete_selected_images();" class="btn btn-success" value="Apply" />
											<table id="ux_data-instagram-images" class="table table-striped wpib-tbl-backgound">
												<thead>
													<tr class="wpib-tr">
														<th style="width:5%"><input type="checkbox" id="ux_chk_select_all" name="ux_chk_select_all" /></th>
														<th style="width:20%"><?php _e( "Image Details", instagram_bank ); ?></th>
														<th style="width:15%"><?php _e( "Thumbnails", instagram_bank ); ?></th>
														<th style="width:20%"><?php _e( "Title & Description", instagram_bank ); ?></th>
														<th style="width:18%"><?php _e( "Tags(comma separated list)", instagram_bank ); ?></th>
													</tr>
												</thead>
												<tbody id="ux_tbl_show_data">
												<?php
													for ($flag = 0; $flag < count($instagram_pics); $flag++)
													{
														?>
														<tr>
															<td><input type="checkbox" id="ux_chk_select_items" value="<?php echo $instagram_pics[$flag]->pic_id;?>" name="ux_chk_select_items" /></td>
															<td>
																<?php echo $instagram_pics[$flag]->pic_name;?><br>
																<?php $dateFormat = date("F j, Y", strtotime($instagram_pics[$flag]->date));
																 echo $dateFormat;?><br>
																150 x 150<br>
																<?php 
																if($instagram_pics[$flag]->album_cover == 1)
																{
																	?>
																	<input type="radio" checked="checked" name="ux_rdl_album_cover" id="ux_rdl_album_cover" ><?php  _e( "Set as album cover", instagram_bank );?><br>
																	<?php 
																}
																else 
																{
																	?>
																	<input type="radio" name="ux_rdl_album_cover" id="ux_rdl_album_cover" ><?php  _e( "Set as album cover", instagram_bank );?><br>
																	<?php 
																}
																?>
																<a onclick="delete_pic(this);" control_id = "<?php echo $instagram_pics[$flag]->pic_id;?>" style="cursor: pointer;" ><?php _e("Delete", instagram_bank );?></a><br>
																<?php 
																$checked = ($instagram_pics[$flag]->enable_redirect == "1" ? "checked" : "");
																?>
																<input type="checkbox" name="ux_chk_insta_redirect" <?php echo $checked; ?> id="ux_chk_insta_redirect" /> <?php _e( "Enable redirect on Instagram", instagram_bank );?>
															</td>
															<?php $type = $instagram_pics[$flag]->video == 1 ? "video" : "image";?>
															<td>
																<img src="<?php echo $instagram_pics[$flag]->thumbnail_url;?>" image_id = "<?php echo $instagram_pics[$flag]->pic_id;?>" type="<?php echo $type;?>" style="border:2px solid #000000;" />
															</td>
															<td>
																<input type="text" id="ux_txt_insta_title" name="ux_txt_insta_title" placeholder="<?php _e("Enter your Title", instagram_bank);?>" value="<?php echo html_entity_decode(stripcslashes(htmlspecialchars($instagram_pics[$flag]->title))); ?>" style="margin-bottom: 10px;"><br/>
																<textarea name="ux_txt_insta_desc" id="ux_txt_insta_desc" rows="4" cols="20" placeholder="<?php _e("Enter your Description", instagram_bank);?>"><?php echo html_entity_decode(stripcslashes(htmlspecialchars($instagram_pics[$flag]->description)));?></textarea>
															</td>
															<td>
																<textarea name="ux_txt_insta_tags" id="ux_txt_insta_tags" rows="6" cols="20" placeholder="<?php _e("Enter your Tags", instagram_bank);?>"/><?php echo html_entity_decode(stripcslashes(htmlspecialchars($instagram_pics[$flag]->tags))); ?></textarea>
															</td>
														</tr>
														<?php 
													}
													?>
												</tbody>
											</table>
											<div class="layout-control-group">
												<select name="ux_ddl_action" id="ux_ddl_action">
													<option value="" selected="selected"><?php _e( "Bulk Action", instagram_bank ); ?></option>
													<option value="delete" ><?php _e( "Delete", instagram_bank ); ?></option>
												</select>
												<input type="button" name="ux_delete_selected" id="ux_delete_selected" class="btn btn-success" onclick="delete_selected_images();" value="Apply" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="separator-doubled"></div>
						<input type="submit" class="btn btn-success" style="margin-top:10px; float:right" value="<?php _e("Save Changes", instagram_bank);?>" />
						<a class="btn btn-success" href="admin.php?page=instagram_bank" style="margin-top:10px;"><?php _e("Back to Dashboard", instagram_bank);?></a>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">
	var array_album_pics = [];
	var delete_array = [];
	jQuery(document).ready(function() 
	{
		oTable = jQuery("#ux_data-instagram-images").dataTable
		({
			"bJQueryUI": false,
			"bAutoWidth": true,
			"sPaginationType": "full_numbers",
			"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
			"oLanguage": 
			{
				"sLengthMenu": "<span>Show entries:</span> _MENU_"
			},
			"aaSorting": [[ 1, "asc" ]],
			"aoColumnDefs": [{ "bSortable": false, "aTargets": [0] }]
		});
		jQuery(".datatable-header").css("float","right");
		jQuery(".datatable-header").css("margin-bottom","8px");
	});
	
	function wpib_get_instagram_gallery()
	{
		var import_type = jQuery("input[type=radio][name=ux_rdl_import_type]:checked").val();
		var user_name = jQuery("#ux_txt_user").val();
		var album_id = "<?php echo $albumId;?>";
		jQuery.post(ajaxurl, "user_name=" + user_name + "&album_id=" +album_id+"&import_type="+import_type+"&param=get_insta_gallery&action=instagram_library", function (data)
		{
			var oTable = jQuery("#ux_data-instagram-images").dataTable();
			oTable.fnDestroy();
			jQuery("#ux_tbl_show_data").empty();
			jQuery("#ux_tbl_show_data").append(data);
			oTable.fnDraw();
			select_radio();
		});
	}
	
	jQuery("#ux_frm_instagram_album").validate
	({
		rules:
		{
			ux_txt_album: "required",
			ux_txt_user: "required"
		},
		submitHandler: function ()
		{
			var album_id = "<?php echo $albumId;?>";
			var album_title = encodeURIComponent(jQuery("#ux_txt_album").val());
			var album_desc = encodeURIComponent(jQuery("#ux_album_desc").val());
			var user_name = jQuery("#ux_txt_user").val();
			var import_type = jQuery("input[type=radio][name=ux_rdl_import_type]:checked").val();
			jQuery("#update_album_added_message").css("display", "block");
			jQuery("body,html").animate
			({
				scrollTop: jQuery("body,html").position().top
			}, "slow");
			if (delete_array.length > 0)
			{
				jQuery.post(ajaxurl,"delete_array=" +  encodeURIComponent(delete_array) + "&album_id=" + album_id + "&param=delete_album_pic&action=instagram_library", function ()
				{
				});
			}
			
			jQuery.post(ajaxurl, "album_id=" +  album_id + "&album_title=" + album_title + "&album_desc=" + album_desc + 
			 "&user_name=" + user_name + "&import_type="+import_type+"&param=update_insta_album&action=instagram_library", function ()
			{
				
				jQuery.each(oTable.fnGetNodes(), function (index, value)
				{
					var table_data = [];
					controlType = jQuery(value.cells[2]).find("img").attr("type");
					picId = jQuery(value.cells[2]).find("img").attr("image_id");
					isCoverSet = jQuery(value.cells[1]).find("input:radio").attr("checked");
					redirect_instagram = jQuery(value.cells[1]).find("input[type=checkbox][name=ux_chk_insta_redirect]").attr("checked");
					title = jQuery(value.cells[3]).find("input:text").eq(0).val();
					description = jQuery(value.cells[3]).find("textarea").eq(0).val();
					tags = jQuery(value.cells[4]).find("textarea").eq(0).val();
	
						table_data.push(controlType);
						table_data.push(picId);
						table_data.push(isCoverSet);
						table_data.push(redirect_instagram);
						table_data.push(title);
						table_data.push(description);
						table_data.push(tags);
						array_album_pics.push(table_data);
						
				});
				jQuery.post(ajaxurl, "album_pics="+ encodeURIComponent(JSON.stringify(array_album_pics)) + 
	 				"&param=update_insta_pics&action=instagram_library", function ()
	 			{
					setTimeout(function () {
						jQuery("#update_album_added_message").css("display", "none");
						window.location.href = "admin.php?page=instagram_bank";
					}, 10000);
	 			});
			});
		}
	});
	
	
	function delete_pic(control) {
		var r = confirm("<?php _e("Are you sure you want to delete this Image?", instagram_bank)?>");
		if (r == true) {
			var row = jQuery(control).closest("tr");
			var oTable = jQuery("#ux_data-instagram-images").dataTable();
			var controlId = jQuery(control).attr("control_id");
			delete_array.push(controlId);
			oTable.fnDeleteRow(row[0]);
		}
	}
	
	jQuery("#ux_chk_select_all").click(function () {
		var oTable = jQuery("#ux_data-instagram-images").dataTable();
		var checkProp = jQuery("#ux_chk_select_all").prop("checked");
		jQuery("input[type=checkbox][name=ux_chk_select_items]", oTable.fnGetNodes()).each(function () {
			if (checkProp) {
				jQuery(this).attr("checked", "checked");
			}
			else {
				jQuery(this).removeAttr("checked");
			}
		});
	});
	
	function delete_selected_images() {
		var r = confirm("<?php _e("Are you sure you want to delete these Images", instagram_bank)?>");
		if (r == true) {
			var oTable = jQuery("#ux_data-instagram-images").dataTable();
			jQuery("input[type=checkbox][name=ux_chk_select_items]", oTable.fnGetNodes()).each(function () {
				var isChecked = jQuery(this).attr("checked");
				if (isChecked == "checked") {
					var row = jQuery(this).closest("tr");
					var controlId = jQuery(this).attr("value");
					delete_array.push(controlId);
					oTable.fnDeleteRow(row[0]);
					select_radio();
				}
			});
		}
	}
	
	function select_radio() {
		var oTable = jQuery("#ux_data-instagram-images").dataTable();
		if ((jQuery("input[type=radio][name=ux_rdl_album_cover]:checked", oTable.fnGetNodes()).length) < 1){
			jQuery("input[type=radio][name=ux_rdl_album_cover]:first").attr("checked","checked");
		}
	}
	function select_import_type()
	{
		var type = jQuery("input[type=radio][name=ux_rdl_import_type]:checked").val();
		if(type == 1)
		{
			jQuery("#ux_lbl_import_type").html("<?php _e("Instagram UserName", instagram_bank); ?>:<span class=\"error\">*</span>");
			jQuery("#ux_txt_user").attr("placeholder","<?php _e("Enter your Instagram Username", instagram_bank);?>");
		}
		else
		{
			jQuery("#ux_lbl_import_type").html("<?php _e("Hash Tags", instagram_bank); ?>:<span class=\"error\">*</span>");
			jQuery("#ux_txt_user").attr("placeholder","<?php _e("Enter Hashtags to import images", instagram_bank);?>");
		}
	}
	</script>
<?php 
}
?>