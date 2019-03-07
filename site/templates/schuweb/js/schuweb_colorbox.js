jQuery(document).ready(function () {
    jQuery('a.group_images').colorbox({rel:'group_images', maxWidth: '100%', maxHeight: '100%'});
    jQuery("a.youtube").colorbox({iframe:true, rel:'groupVideos', innerWidth:640, innerHeight:390, maxWidth: '100%', maxHeight: '100%'});
    jQuery("a.vimeo").colorbox({iframe:true, rel:'groupVideos', innerWidth:500, innerHeight:409, maxWidth: '100%', maxHeight: '100%'});
});