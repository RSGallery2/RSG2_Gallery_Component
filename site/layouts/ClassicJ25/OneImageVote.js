/**-------------------------------------------------------------------------------------
 * drag and drop files
 * -------------------------------------------------------------------------------------
 * each dropped filename gets a statusbar.
 * All image file names are collected in a list in order as they are dropped
 * Each filename will be send by ajax to create a database entry to keep the dropped order
 * On answer the files are uploaded parallel all at once
 * -------------------------------------------------------------------------------------
 */

/**
jQuery(document).ready(function ($) {

    $('.modalActive').click(function() {

        $('#popupModal').css('display', 'block');
        $('#popupImage').attr("src", this.src);
        $('#popupCaption').html(this.alt);
    });

    $('#popupClose').click(function() {
        $('#popupModal').css('display', 'none');
    });
});

/**/



jQuery(document).ready(function ($) {

    /**/
	var buttonStars = $('.btn_star');

    buttonStars.on('click', function (e) {
        //alert('buttonManualFile.on click: '); // + JSON.stringify($(this)));
        //fileInput.click();
        //alert("Test");

        /**
        // Mark as selected
        $(this).css({
            //"border-color": "#C1E0FF",
            //"border-color": "darkgreen",
            //"border-color": "darkblue",
            //"border-color": "Maroon",
            //"border-color": "orange",
            //"border-color": "DarkOrange",
            //"border-color": "blue", // rgb(0, 94, 141)
            "border-color": "lightblue", // rgb(0, 94, 141)
            "border-color": "#005E8D", // rgb(0, 94, 141)

            "border-width":"4px",
            "border-style":"solid"});
        /**/

        var form = document.getElementById('rsgVoteForm');
        alert ("ID:" + $(this).attr('id'));
        form.debug.value = $(this).attr('id');
        alert ("ID 2:" + $(this).attr('id').substr(-1));
        form.rating.value = $(this).attr('id').substr(-1);

        $(this).addClass('checked');

        form.submit();
    });


    /**
    var form = document.getElementById('adminForm');

            form.task.value = 'upload.uploadFromZip'; // upload.uploadZipFile
            form.batchmethod.value = 'zip';
            form.ftppath.value = "";
            form.xcat.value = gallery_id;
            form.selcat.value = bOneGalleryName4All;
            form.rsgOption.value = "";


            jQuery('#loading').css('display', 'block');

            form.submit();
    /**/
});