/**
 * @package     RSGallery2
 *
 * supports modal image with back button
 *
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * @since       4.3.0
 */

//--------------------------------------------------------------
// modal image with back button
//--------------------------------------------------------------

/**/
jQuery(document).ready(function ($) {

    $('#maintain_slideshow').change(function() {
    
        alert ("aaaa");
    
        var form = document.getElementById('adminForm');
    
        alert ("aaa1");
        form.task.value = 'maintslideshows.changeSlideshow';
        alert ("aaa2");
        form.submit();
						
		});

		/**
		$('#').click(function() {
			alert ("bbb")
			$('#popupModal').css('display', 'none');
			$('#popupModal').css('display', 'block');
			$('#popupImage').attr("src", this.src);
			$('#popupCaption').html(this.alt);
		});
		/**/

});
/**/

/**
    $(document).ready(function() {
        alert ("aaaa")

        function test ()
        {
            alert("aaaa")
        }

    }

/**/


