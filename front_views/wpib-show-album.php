<h2><?php echo ($album_name == "Untitled Album" ? "" : stripcslashes(htmlspecialchars_decode($album_name))); ?></h2>
<div class="wp_bank_album" id="instagram-bank-thumbnails_<?php echo $unique_id;?>">
	<?php 
	for($flag = 0; $flag< count($album_pics); $flag++)
	{
		$tag_class = strtoupper(str_replace(" ", "-", $album_pics[$flag]->tags));
		$image_title = $album_pics[$flag]->title != "" ? "<h5>" . esc_attr(html_entity_decode(stripcslashes(htmlspecialchars($album_pics[$flag]->title)))). "</h5>" : "";
		$image_description = $album_pics[$flag]->description != ""  ? "<p>" . esc_attr(html_entity_decode(stripcslashes(htmlspecialchars($album_pics[$flag]->description)))) ."</p>" : "";
		
		$image_url = $album_pics[$flag]->enable_redirect == "1" ? stripcslashes($album_pics[$flag]->url) : stripcslashes($album_pics[$flag]->image_url);
		$lightbox_class = $album_pics[$flag]->enable_redirect != "1" ? $unique_id."prettyPhoto[gallery]" : "";
		$target = $album_pics[$flag]->enable_redirect == "1" ? "_blank" : "";
		if($album_pics[$flag]->video == "1")
		{
			?>
			<a class="<?php echo str_replace(","," ", $tag_class); ?>" href="<?php echo stripcslashes($album_pics[$flag]->url); ?>" target="_blank" data-title="<?php echo $image_title.$image_description;?>" id="ux_insta_img_<?php echo $unique_id;?>">
			<?php 
		}
		else
		{
			?>
			<a rel="<?php echo $lightbox_class;?>" class="<?php echo str_replace(","," ", $tag_class); ?>" href="<?php echo $image_url; ?>" target="<?php echo $target;?>" data-title="<?php echo $image_title.$image_description;?>" id="ux_insta_img_<?php echo $unique_id;?>">
			<?php 
		}
		?>
			<div class="custom_desc" >
				<div class="gb_overlay">
					<div class="overlay_text">
					</div>
					<img id="ux_wpib_img_<?php echo $album_pics[$flag]->pic_id;?>"
						src="<?php echo stripcslashes($album_pics[$flag]->thumbnail_url);?>"/>
				</div>
				<?php if($title =="true" || $desc =="true")
				{
					?>
						<div class="gallery_desc">
							<h5><?php echo stripcslashes(htmlspecialchars_decode($album_pics[$flag]->title));?></h5>
							<?php
							if( ($desc =="true") && ($album_pics[$flag]->description != "") )
							{
								?>
								<p>
									<?php
									echo stripcslashes(htmlspecialchars_decode($album_pics[$flag]->description));
									?>
								</p>
								<?php
							}
							?>
						</div>
					<?php 
					
				}
				?>
			</div>
		</a>
		<?php
	}
	?>
</div>
