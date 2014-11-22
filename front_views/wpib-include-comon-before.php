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

$album_pics = $wpdb->get_results
(
	$wpdb->prepare
	(
		"SELECT * FROM " . wpib_album_pics() . " WHERE album_id = %d order by pic_id asc",
		$album_id
	)
);
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