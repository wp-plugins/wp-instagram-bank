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
	$instagram = $wpdb->get_results
	(
		"SELECT * FROM " .wpib_albums()
	);
	?>
	<div id="instagram_bank" style="display:none;">
		<div class="fluid-layout responsive">
			<div style="padding:0px 0 3px 10px;">
				<h3 class="label-shortcode"><?php _e("Insert Instagram Bank Shortcode", instagram_bank); ?></h3>
				<span>
					<i><?php _e("Select a map below to add it to your post or page.", instagram_bank); ?></i>
				</span>
			</div>
			<div class="layout-span12 responsive" style="padding:15px 15px 0 0;">
				<div class="layout-control-group">
					<label class="custom-layout-label" for="ux_instagram_name"><?php _e("Album Name", instagram_bank); ?> : </label>
					<select id="add_album_id" class="layout-span9">
						<option value="0"><?php _e("Select a album", instagram_bank); ?>  </option>
						<?php
						
						for($flag = 0;$flag<count($instagram);$flag++)
						{
							?>
							<option value="<?php echo intval($instagram[$flag]->album_id); ?>"><?php echo esc_html($instagram[$flag]->album_name) ?></option>
						<?php
						}
						?>
					</select>
				</div>
				<div class="layout-control-group">
					<label class="custom-layout-label"><?php _e("Show Album Title", instagram_bank); ?> : </label>
					<input type="checkbox" checked="checked" name="ux_album_title" id="ux_album_title"/>
				</div>
				<div class="layout-control-group">
					<label class="custom-layout-label"><?php _e("Show Album Desc", instagram_bank); ?> : </label>
					<input type="checkbox" checked="checked" name="ux_album_desc" id="ux_album_desc"/>
				</div>
				<div class="layout-control-group">
					<label class="custom-layout-label"></label>
					<input type="button" class="button-primary" value="<?php _e("Insert album", instagram_bank); ?>"
						onclick="Insert_album_Form();"/>&nbsp;&nbsp;&nbsp;
					<a class="button" style="color:#bbb;" href="#"
						onclick="tb_remove(); return false;"><?php _e("Cancel", instagram_bank); ?></a>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function Insert_album_Form()
		{
			var album_id = jQuery("#add_album_id").val();
			var show_title = jQuery("#ux_album_title").prop("checked");
			var show_desc = jQuery("#ux_album_desc").prop("checked");
			if(album_id == 0)
			{
				var error_message = jQuery("<div id=\"top-error\" class=\"top-right top-error\" style=\"display: block;\"><div class=\"top-error-notification\"></div><div class=\"top-error-notification ui-corner-all growl-top-error\" ><div onclick=\"error_message_close();\" id=\"close-top-error\" class=\"top-error-close\">x</div><div class=\"top-error-header\"><?php _e("Error!",  instagram_bank); ?></div><div class=\"top-error-top-error\"><?php _e("Please choose a Map to insert into Shortcode", instagram_bank) ?></div></div></div>");
				jQuery("body").append(error_message);
				return;
			}
			window.send_to_editor("[wp_instagram_bank album_id=" + album_id + " title=\"" + show_title +"\" desc=\"" + show_desc +"\" ]");
		}
		
	</script>
	<?php 
}
?>