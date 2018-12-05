/**-------------------------------------------------------------------------------------
 *
 * -------------------------------------------------------------------------------------
 *
 *
 *
 *
 * -------------------------------------------------------------------------------------
 */


jQuery(document).ready(function ($) {

    /**/
	var buttonStars = $('.btn_star');

    buttonStars.on('click', function (e) {

        var form = document.getElementById('rsgVoteForm');
        form.debug.value = $(this).attr('id');
        form.rating.value = $(this).attr('id').substr(-1);

        $(this).addClass('checked');

        form.submit();
    });


});