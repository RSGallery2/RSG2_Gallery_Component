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

        console.log ("vote.01A");

        var form = document.getElementById('rsgVoteForm');
        console.log ("vote.01B");

        //form.debug.value = $(this).attr('id');
        console.log ("vote.01C");

        form.rating.value = $(this).attr('id').substr(-1);

        console.log ("vote.01D");

        // show user it is checked
        $(this).addClass('checked');

        console.log ("vote.02");

        // transfer actual pagination 'limitstart'
        // <input type="hidden" name="limitstart" value="2">
        srcLimitStart = $( "input[name=limitstart]:first" );
        console.log ("vote.03");

        limitStart = srcLimitStart.val();

        console.log ("vote.04");

        form.paginationImgIdx.value = limitStart;

        console.log ("vote.05");


        form.submit();
        console.log ("vote.06");

    });


});
