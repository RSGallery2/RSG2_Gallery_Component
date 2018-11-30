/**-------------------------------------------------------------------------------------
 * drag and drop files
 * -------------------------------------------------------------------------------------
 * each dropped filename gets a statusbar.
 * All image file names are collected in a list in order as they are dropped
 * Each filename will be send by ajax to create a database entry to keep the dropped order
 * On answer the files are uploaded parallel all at once
 * -------------------------------------------------------------------------------------
 */

jQuery(document).ready(function ($) {

	var buttonManualFile = $('#select_manual_file');

    buttonManualFile.on('click', function (e) {
        //alert('buttonManualFile.on click: '); // + JSON.stringify($(this)));
        fileInput.click();
    });


	$('.modalActive').click(function() {

        $('#popupModal').css('display', 'block');
        $('#popupImage').attr("src", this.src);
        $('#popupCaption').html(this.alt);
	});

    $('#popupClose').click(function() {
        $('#popupModal').css('display', 'none');
    });



    var form = document.getElementById('adminForm');

            form.task.value = 'upload.uploadFromZip'; // upload.uploadZipFile
            form.batchmethod.value = 'zip';
            form.ftppath.value = "";
            form.xcat.value = gallery_id;
            form.selcat.value = bOneGalleryName4All;
            form.rsgOption.value = "";


            jQuery('#loading').css('display', 'block');

            form.submit();



}