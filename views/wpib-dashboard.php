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
	$id = (count($wpib_album_id) == 0 ? "1" : (intval($wpib_album_id) + 1));
	
	$result = $wpdb->get_results
	(
		"SELECT * FROM ".wpib_albums()." order by album_id asc "
	);
	?>
	
	<form id="ux_frm_dashboard" class="layout-form wpib-page-width" style="width:1000px;">
		<div class="fluid-layout">
			<div class="layout-span12">
				<div class="widget-layout wpib-body-background">
					<div class="widget-layout-title">
						<h4>
							<?php _e("Dashboard - Wp Instagram Bank", instagram_bank); ?>
						</h4>
					</div>
					<div class="widget-layout-body">
						<div class="fluid-layout">
							<div class="layout-span12">
								<div class="layout-control-group" style="margin-bottom:10px;">
									<a class="btn btn-success" href="admin.php?page=add_album&albumId=<?php echo $id;?>"><?php _e("Add New Instagram Album", instagram_bank);?></a>
								</div>
								<div class="separator-doubled"></div>
								<table id="ux_data-instagram-album" class="table wpib-tbl-backgound">
									<thead>
										<tr class="wpib-tr">
											<th style="width:20%"><?php _e( "Album Cover", instagram_bank ); ?></th>
											<th style="width:15%"><?php _e( "Album Name", instagram_bank ); ?></th>
											<th style="width:25%"><?php _e( "Instagram Account/Hashtag", instagram_bank ); ?></th>
											<th style="width:25%"><?php _e( "No. of Images", instagram_bank ); ?></th>
											
										</tr>
									</thead>
									<tbody>
										<?php
										for($flag = 0 ;$flag < count($result); $flag++)
										{
											$albumCover = $wpdb->get_row
											(
												$wpdb->prepare
												(
														"SELECT album_cover,thumbnail_url FROM ".wpib_album_pics()." WHERE album_cover=1 and album_id = %d",
														$result[$flag]->album_id
												)
											);
											$count_pic = $wpdb->get_var
											(
												$wpdb->prepare
												(
													"SELECT count(".wpib_albums().".album_id) FROM ".wpib_albums()." join ".wpib_album_pics()." on ".wpib_albums().".album_id =  ".wpib_album_pics().".album_id where ".wpib_albums().".album_id = %d ",
													$result[$flag]->album_id
												)
											);
											?>
											<tr>
												<td>
													<?php 
													if(count($albumCover) != 0)
													{
														?>
														<img src="<?php echo $albumCover->thumbnail_url;?> " style="border:2px solid #000000;"/>
														<?php
													}
													else 
													{
														?>
														<img src="<?php echo stripcslashes(plugins_url( "/assets/images/album-cover.jpg" , dirname(__FILE__))); ?>" style="width:150px;height:150px;border:2px solid #000000;"/>
														<?php
													}
													?>
													<br>
													<a href="admin.php?page=add_album&albumId=<?php echo $result[$flag]->album_id;?>"><?php _e( "Edit", instagram_bank ); ?></a> | 
													<a href="#" onclick="delete_album(<?php echo $result[$flag]->album_id;?>);"><?php _e( "Delete", instagram_bank ); ?></a>
												</td>
												<td>
													<?php echo $result[$flag]->album_name;?>
												</td>
												<td>
													<?php echo $result[$flag]->user_name;?>
												</td>
												<td>
													<?php echo $count_pic;?>
												</td>
											</tr>
											<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		jQuery(document).ready(function() 
		{
			jQuery("#ux_data-instagram-album").dataTable
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
			jQuery(".dataTables_length").css("margin-top","5px");
			jQuery(".datatable-header").css("float","right");
			jQuery(".datatable-header").css("margin-bottom","8px");
		});
		
		function delete_album(id)
		{
			var r = confirm("<?php _e( "Are you sure you want to delete this Album?", instagram_bank ); ?>");
			if(r == true)
			{
				jQuery.post(ajaxurl, "id="+id+"&param=delete_album&action=instagram_library", function(data)
				{
					var check_page = "<?php echo $_REQUEST["page"]; ?>";
					window.location.href = "admin.php?page="+check_page;
				});
			}
		}
	</script>
<?php 
}
?>
