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

        // show user it is checked
        $(this).addClass('checked');

        // transfer actual pagination 'limitstart'
        // <input type="hidden" name="limitstart" value="2">
        srcLimitStart = $( "input[name=limitstart]:first" );
        limitStart = srcLimitStart.val();
        form.paginationImgIdx.value = limitStart;


        form.submit();
    });


});
