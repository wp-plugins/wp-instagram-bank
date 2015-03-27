<script type="text/javascript">
jQuery(document).ready(function () {
	jQuery("a[rel^=\"<?php echo $unique_id;?>prettyPhoto\"]").prettyPhoto
	({
		animation_speed: 500, /* fast/slow/normal */
		slideshow: 4000, /* false OR interval time in ms */
		autoplay_slideshow: false, /* true/false */
		opacity: 0.80, /* Value between 0 and 1 */
		show_title: false, /* true/false */
		allow_resize: true
	});
});


var $optionSets = jQuery("#instagram_bank_filters_<?php echo $unique_id;?>"),
$optionLinks = $optionSets.find("a");
$optionLinks.click(function () {
	var selector_<?php echo $unique_id;?> = jQuery(this).attr("data-filter");
	if (selector_<?php echo $unique_id;?> != "*") {
		jQuery("#instagram-bank-thumbnails_<?php echo $unique_id;?> > a > div.custom_desc").addClass("jp-hidden");
		jQuery("#instagram-bank-thumbnails_<?php echo $unique_id;?> > a" + selector_<?php echo $unique_id;?> + " > div.custom_desc").removeClass("jp-hidden");
		jQuery("#instagram-bank-thumbnails_<?php echo $unique_id;?> > a" + selector_<?php echo $unique_id;?> + " > div.custom_desc").css("display", "");
	}
	else {
		jQuery("#instagram-bank-thumbnails_<?php echo $unique_id;?> > a > div.custom_desc").removeClass("jp-hidden");
		jQuery("#instagram-bank-thumbnails_<?php echo $unique_id;?> > a > div.custom_desc").css("display", "");
	}

	return false;
});
jQuery("#instagram_bank_filters_<?php echo $unique_id;?> a").on("click", function () {
jQuery("#instagram_bank_filters_<?php echo $unique_id;?>").find(".act").removeClass("act");
jQuery(this).addClass("act");
});
</script>