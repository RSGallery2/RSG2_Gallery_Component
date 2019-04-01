var $j = jQuery.noConflict();

var theInt = null;
var curclicked = 0;
var stop = 0;

theInterval = function(cur){
       clearInterval(theInt);

       theInt = setInterval(function(){
               $j(".coda-nav-right a").eq(curclicked).trigger('click');
               curclicked++;
               if( 10 == curclicked )
                       curclicked = 0;
               $j("#stop").click(
                       function(){
                               if (stop==0){
                               clearInterval(theInt);
                               stop=1;}
                       });
       }, 750);
       $j("#stop").click(
               function(){
                       stop=0;
                       theInterval();
               }
       );
};
$j(function(){
       $j("#main-photo-slider").prepend('<div id="stop">Start/Stop</div>');
       $j("#main-photo-slider").codaSlider();
       theInterval();
});