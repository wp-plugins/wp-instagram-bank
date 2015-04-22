<?php
global $wpdb;
$tag_array=array();
$unique_id = rand(100, 10000);
$album_name = $wpdb->get_var
(
	$wpdb->prepare
	(
		"SELECT album_name FROM " . wpib_albums() . " where album_id = %d",
		$album_id
	)
);
if($display_images == "all" || $display_images == "")
{
	switch($sort_by)
	{
		case "random":
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY RAND()",
					$album_id
				)
			);
		break;
		case "pic_id":
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY pic_id ASC",
					$album_id
				)
			);
		break;
		case "title":
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY title ASC",
					$album_id
				)
			);
		break;
		case "date":
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY date ASC",
					$album_id
				)
			);
		break;
		default:
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY pic_id ASC",
					$album_id
				)
			);
		break;
	}
}
else
{
	switch($sort_by)
	{
		case "random":
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY RAND() LIMIT $no_of_images",
					$album_id
				)
			);
		break;
		case "pic_id":
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY pic_id ASC LIMIT $no_of_images",
					$album_id
				)
			);
		break;
		case "title":
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY title ASC LIMIT $no_of_images",
					$album_id
				)
			);
		break;
		case "date":
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY date ASC LIMIT $no_of_images",
					$album_id
				)
			);
		break;
		default:
			$album_pics = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d ORDER BY pic_id ASC LIMIT $no_of_images",
					$album_id
				)
			);
		break;
	}
}
?>
<script type="text/javascript">
	<?php
	for($flag1 = 0; $flag1< count($album_pics); $flag1++)
	{
		if($album_pics[$flag1]->tags != "")
		{
			$tag_array = array_merge($tag_array, explode(",", $album_pics[$flag1]->tags));
		}
	}
	?>
</script>
<?php 
if (!empty($tag_array)) 
{
	$tags = array_iunique_instagram($tag_array);
	?>
	<div class="thumbs-fluid-layout">
		<div class="intagram-bank-filter" >
			<div class="intagram-bank-filter-categories" id="instagram_bank_filters_<?php echo $unique_id; ?>">
				<a href="#" id="instagram_gallery_filter_<?php echo $unique_id; ?>" class="act"
					data-filter="*"><?php _e("VIEW ALL", instagram_bank); ?>
				</a>
				<?php
				foreach ($tags as $key => $value) {
					$Filterclass = strtoupper(str_replace(" ", "-", $value));
					?>
					<a href="#" id="gallery_filter"
						data-filter=".<?php echo $Filterclass; ?>"><?php echo strtoupper($value); ?></a>
					<?php
					}
				?>
			</div>
		</div>
	</div>
	<?php
}
?>