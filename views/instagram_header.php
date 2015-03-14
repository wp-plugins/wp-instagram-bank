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
	?>
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab" id="instagram_bank" href="admin.php?page=instagram_bank"><?php _e("Dashboard", instagram_bank);?></a>
		<a class="nav-tab" id="add_album" href="admin.php?page=add_album"><?php _e("Add New Album", instagram_bank);?></a>
		<a class="nav-tab" id="short_code" href="admin.php?page=short_code"><?php _e("Short-Codes", instagram_bank);?></a>
		<a class="nav-tab" id="recommended_plugins_instagram" href="admin.php?page=recommended_plugins_instagram"><?php _e("Recommendations", instagram_bank);?></a>
		<a class="nav-tab" id="other_services_instagram" href="admin.php?page=other_services_instagram"><?php _e("Our Other Services", instagram_bank);?></a>
	</h2>
	<script>
	jQuery(document).ready(function()
	{
		jQuery(".nav-tab-wrapper > a#<?php echo $_REQUEST["page"];?>").addClass("nav-tab-active");
	});
	</script>
<?php 
}
?>