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
    
        var form = document.getElementById('adminForm');
    
        form.task.value = 'maintslideshows.changeSlideshow';
        form.submit();
						
	});
    
    /**
     $('btnConfigFile').click(function() {
        alert ("bbb")
        $('#popupModal').css('display', 'none');
        $('#popupModal').css('display', 'block');
        $('#popupImage').attr("src", this.src);
        $('#popupCaption').html(this.alt);
    });
     /**/
    
    /**/
    // $( "input[value='Hot Fuzz']" ).next().text( "Hot Fuzz" );
    $("button[name='btnConfigPara']").click(function(event) {
        var splitNameArray;
        var slideshowId;

        //--- slideshow id ----------------------

        var elementId = event.target.id;
        //alert ('elementId: ' + elementId);

        splitNameArray = elementId.split('_');
        // Remove first part and join following
        splitNameArray.shift();
        slideshowId = splitNameArray.join ('_');
        // alert ('slideshowId: ' + slideshowId);

        //-- assign to control --------------

        var form = document.getElementById('adminForm');
    
        form.task.value = 'maintslideshows.saveConfigParameter';
        form.usedSlideshow.value = slideshowId;

        form.submit();
    });
    /**/

    /**/
    $("button[name='btnConfigFile']").click(function(event) {
        var splitNameArray;
        var slideshowId;
        var elementId;
        var paramsIniText;

        //--- slideshow id ----------------------

        elementId = event.target.id;
        //alert ('elementId: ' + elementId);

        splitNameArray = elementId.split('_');
        // Remove first part and join following
        splitNameArray.shift();
        slideshowId = splitNameArray.join ('_');
        // alert ('slideshowId: ' + slideshowId);

        //-- params.ini content --------------

        paramsIniText = $("#params_ini_" + slideshowId).val();
        //alert ('paramsIniText: ' + paramsIniText);

        //-- assign to control --------------

        var form = document.getElementById('adminForm');

        form.task.value = 'maintslideshows.saveConfigFile';
        form.usedSlideshow.value = slideshowId;
        form.paramsIniText.value = paramsIniText;

        form.submit();
    });
    /**/
    
    /**
     $('btnConfigFile').click(function() {
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


