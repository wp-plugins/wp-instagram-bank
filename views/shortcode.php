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
					<select id="add_album_id" class="layout-span8">
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
					<label class="custom-layout-label"><?php _e("Display", instagram_bank); ?> : </label>
					<select name="ux_ddl_display_images" onchange="display_images_type();" id="ux_ddl_display_images" class="layout-span3" style="margin-left: 35px;">
						<option value="all">All Images</option>
						<option value="selected">Selected Images</option>
					</select>
					<div style="display: initial;margin-left: 10px;">
						<label class="custom-layout-label"><?php _e("Sort By", instagram_bank); ?> : </label>
						<select name="ux_ddl_sort_order" id="ux_ddl_sort_order" class="layout-span4">
							<option value="random">Random</option>
							<option value="pic_id">Image Id</option>
							<option value="title">Title Text</option>
							<option value="date">Date</option>
						</select>
					</div>
				</div>
				<div class="layout-control-group" id="div_show_no_of_images" style="display: none;">
					<label class="custom-layout-label"><?php _e("No. of Images", instagram_bank); ?> : </label>
					<input type="text" class="layout-span8" onkeypress="return OnlyDigits(event);" name="show_no_of_images" id="show_no_of_images" value="10" />
				</div>
				<div class="layout-control-group">
					<label class="custom-layout-label" style="vertical-align: baseline;"><?php _e("Show Album Title", instagram_bank); ?> : </label>
					<input type="checkbox" checked="checked" name="ux_album_title" id="ux_album_title"/>
					<div style="display: initial;margin-left: 10px;">
						<label class="custom-layout-label" style="vertical-align: baseline;"><?php _e("Show Album Desc", instagram_bank); ?> : </label>
						<input type="checkbox" checked="checked" name="ux_album_desc" id="ux_album_desc"/>
					</div>
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
		function display_images_type()
		{
			var show_images = jQuery("#ux_ddl_display_images").val();
			if(show_images == "selected")
			{
				jQuery("#div_show_no_of_images").css("display","block");
			}
			else
			{
				jQuery("#div_show_no_of_images").css("display","none");
			}
		}
		function Insert_album_Form()
		{
			var album_id = jQuery("#add_album_id").val();
			var show_title = jQuery("#ux_album_title").prop("checked");
			var show_desc = jQuery("#ux_album_desc").prop("checked");
			var display_type = jQuery("#ux_ddl_display_images").val();
			var show_no_of_images = jQuery("#show_no_of_images").val();
			var order = jQuery("#ux_ddl_sort_order").val();

			if(display_type == "all")
			{
				var ux_show_images = "display_images=\"all\" sort_by=\""+order+"\"";
			}
			else
			{
				var ux_show_images = "display_images=\"selected\" no_of_images=\""+show_no_of_images+"\" sort_by=\""+order+"\"";
			}

			if(album_id == 0)
			{
				var error_message = jQuery("<div id=\"top-error\" class=\"top-right top-error\" style=\"display: block; z-index: 999999;\"><div class=\"top-error-notification\"></div><div class=\"top-error-notification ui-corner-all growl-top-error\" ><div onclick=\"error_message_close();\" id=\"close-top-error\" class=\"top-error-close\">x</div><div class=\"top-error-header\"><?php _e("Error!",  instagram_bank); ?></div><div class=\"top-error-top-error\"><?php _e("Please choose a Album to insert into Shortcode", instagram_bank) ?></div></div></div>");
				jQuery("body").append(error_message);
				return;
			}
			window.send_to_editor("[wp_instagram_bank albumid=" + album_id + " "+ux_show_images+" title=\"" + show_title +"\" desc=\"" + show_desc +"\" ]");
		}
		function OnlyDigits(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode;
			return (charCode > 47 && charCode < 58) || charCode == 127 || charCode == 8;
		}
	</script>
	<?php 
}
?>