


//--------------------------------------------------------------------------------------
// modal image with back button
//--------------------------------------------------------------------------------------

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


