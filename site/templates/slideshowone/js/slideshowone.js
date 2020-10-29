/**
 * slideshowone
 *
 * @version       2.0
 * @package       RSGallery2
 * @copyright (C) 2008 - 2020 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 --------------------------------------------
 2008 adapted from
 SlideShow. Written by PerlScriptsJavaScripts.com
 Copyright http://www.perlscriptsjavascripts.com
 Code page http://www.perlscriptsjavascripts.com/js/slideshow.html
 PerlScriptsJavaScripts.com: Free and commercial Perl and JavaScripts
 --------------------------------------------
 // ToDo: lightbox on click of image  (may be with own slideshow in big)
 */

/*---------------------------------------------------
 initialize
---------------------------------------------------*/

//--- joomla options ------------------------------------

// options defined in templateDetails.xml file
var slideOptions = Joomla.getOptions('slideArray');
//console.log(slideOptions);

//--- create local variables from options ------------------------------------

var displayTime = slideOptions.displayTime;// seconds to display each image?
var debugLevel = slideOptions.debugLevel; // " > 0 " ->
var isAutoStart = slideOptions.isAutoStart;

// start slideshow once dom is ready (uses jquery)
if (typeof displayTime === 'undefined')
{
    displayTime = 5;
}
// start slideshow once dom is ready (uses jquery)
if (typeof debugLevel === 'undefined')
{
    debugLevel = 0;
}
// start slideshow once dom is ready (uses jquery)
if (typeof isAutoStart === 'undefined')
{
    isAutoStart = true;
}
// path to image/name of image in slide show. this will also preload all images
// each element in the array must be in sequential order starting with zero (0)
var SLIDES = slideOptions.SLIDES;
var slidesLength = SLIDES.length;

if (debugLevel > 1) {
    console.log(JSON.stringify(SLIDES));
}

/* --- Not used options -------------

// prepared for future implementation

//var effect = 23;// transition effect. number between 0 and 23, 23 is random effect
//var effectType = slideOptions.effectType;// transition effect. number between 0 and 23, 23 is random effect
//var duration = 1.5;// transition duration. number of seconds effect lasts
//var transitionTime = slideOptions.transitionTime;// transition duration. number of seconds effect lasts
//var display = 4;// seconds to display each image?
//var oW = 400;// width of stage (first image)
var imgWidth = slideOptions.imgWidth;// width of stage (first image)
//var oH = 400;// height of stage
var imgHeight = slideOptions.imgHeight;// height of stage
//var zW = 40;// zoom width by (add or subtracts this many pixels from image width)
var zoomWidth = slideOptions.zoomWidth;// zoom width by (add or subtracts this many pixels from image width)
//var zH = 30;// zoom height by
var zoomHeight = slideOptions.zoomHeight;// zoom height by

/**/

//--- create slides ------------------------------------

//var Slides = new Array();
var Slides = [];
for (var a = 0; a < SLIDES.length; a++) {
    Slides[a] = new Image();
    Slides[a].src = SLIDES[a][0];
}

//--- global base variables ------------------------------------

// this form
var dom_form;
// transition
var t_slides = 0;

var touchStartX;
var touchStartY;
var mouseStartX;
var mouseStartY;

/*---------------------------------------------------
   start slides
---------------------------------------------------*/

jQuery(document).ready(function () {
    
    initialize();
    
    if (isAutoStart) {
        if (debugLevel > 1) {
            console.log('autostart: startSS');
        }
        startSS();
    }
    else {
        if (debugLevel > 1) {
            console.log('autostart: deselected');
        }
    }

    /**
    alert ("debugLevel: " + debugLevel);
    /**/
});

/*---------------------------------------------------
   slides action functions
---------------------------------------------------*/

/**
 *
 */
function initialize() {
    dom_form = document._slideShow;
    dom_form.delay.value = displayTime;

    // touch events
    dom_form.addEventListener("touchstart", function(e){ touchStart (e); });
    dom_form.addEventListener("touchend", function(e){ touchEnd (e); });
    dom_form.addEventListener("mousedown", function(e){ mouseDown (e); });
    dom_form.addEventListener("mouseup", function(e){ mouseUp (e); });

}

function touchStart (e) {
//    console.log ("swipe start");

    var touchobj = e.changedTouches[0]; // first finger

    touchStartX = parseInt(touchobj.clientX); // X/Y-Koordinaten relativ zum Viewport
    touchStartY = parseInt(touchobj.clientY);

    e.preventDefault();
}

function touchEnd (e) {


    console.log ("swipe end");

    var touchobj = e.changedTouches[0]; // erster Finger

    var touchEndX = parseInt(touchobj.clientX); // X/Y-Koordinaten relativ zum Viewport
    var touchEndY = parseInt(touchobj.clientY);

    // end minus start is direction
    var deltaX = touchEndX - touchStartX;
    var deltaY = touchEndY - touchStartY;

    var length = Math.sqrt (deltaX*deltaX + deltaY*deltaY);
    console.log ("Math.sqrt");


    // swipe long enough
    if (length > document.images["stage"].height / 3) {
        if (deltaX >= 0) {
            // runNextSlide(dom_form.currSlide.value);
            nextSS (); // does stop afterwards
        }
        else {
            // runPreviousSlide(dom_form.currSlide.value);
            prevSS(); // does stop afterwards
        }
    }

    e.preventDefault();
}

function mouseDown (e) {
//    console.log ("mouse down start");

    //var cX = event.clientX;
    // var sX = event.screenX;
    //var cY = event.clientY;
    // var sY = event.screenY;

    mouseStartX = parseInt(e.clientX); // X/Y-Koordinaten relativ zum Viewport
    mouseStartY = parseInt(e.clientY);

    e.preventDefault();
}

function mouseUp (e) {

    console.log ("mouse end");

    // var touchobj = e.changedTouches[0]; // erster Finger

    //console.log ("mouse end 01");

    var mouseEndX = parseInt(e.clientX); // X/Y-Koordinaten relativ zum Viewport
    var mouseEndY = parseInt(e.clientY);

    // end minus start is direction
    var deltaX = mouseEndX - mouseStartX;
    var deltaY = mouseEndY - mouseStartY;

    console.log ("mouse end 01");

    var length = Math.sqrt (deltaX*deltaX + deltaY*deltaY);
    console.log ("mouse move");

    console.log ("length: " + length);
    console.log ("document.images[\"stage\"].width: " + document.images["stage"].width);


    // swipe long enough
    if (length > document.images["stage"].width / 3) {
        if (deltaX >= 0) {
            // runNextSlide(dom_form.currSlide.value);
            nextSS (); // does stop afterwards
            console.log ("mouse nextSS");
        }
        else {
            // runPreviousSlide(dom_form.currSlide.value);
            prevSS(); // does stop afterwards
            console.log ("mouse prevSS");
        }
    }

    e.preventDefault();
}

/**
 *
 */
var isStarted = false;
function startSS() {
    if (debugLevel > 0) {
        console.log('>startSS');
    }

    // Not second call
    if ( ! isStarted) {
        var action = function () {
            runNextSlide(dom_form.currSlide.value);
        };
        t_slides = setTimeout(action, dom_form.delay.value * 1000);

        isStarted = true;
    }
}

/**
 *
 */
function displaySlide (idxSlide) {

    if (debugLevel > 0) {
        console.log('>displaySlide (' + idxSlide + ')');
    }

    document.images['stage'].src = Slides[idxSlide].src;
    dom_form.currSlide.value = idxSlide;

    // document.all => internet explorer && no Opera browser && running on windows
    /* old browser. Remove 2019.03.10
    if (document.all && navigator.userAgent.indexOf('Opera') < 0 && navigator.userAgent.indexOf('Windows') >= 0) {
        //document.images["stage"].width  = imgWidth;
        //document.images["stage"].height = imgHeight;

        document.images['stage'].style.visibility = 'hidden'
        document.images['stage'].filters.item(0).apply()
        document.images['stage'].filters.item(0).transition = effectType
        document.images['stage'].style.visibility = 'visible'
        document.images['stage'].filters(0).play(transitionTime)
    }
    /**/
}

/**
 *
 */
function runNextSlide(idxPrevious) {
    if (debugLevel > 0) {
        console.log('>runNextSlide');
    }

    var idxSlide = nextIdx (idxPrevious, slidesLength);

    displaySlide(idxSlide);

    var action = function () {
        runNextSlide(dom_form.currSlide.value);
    };
    t_slides = setTimeout(action, dom_form.delay.value * 1000);
}

/**
 *
 */
function runPreviousSlide(idxPrevious) {
    if (debugLevel > 0) {
        console.log('>runNextSlide');
    }

    var idxSlide = previousIdx (idxPrevious, slidesLength);

    displaySlide(idxSlide);

    var action = function () {
        runPreviousSlide(dom_form.currSlide.value);
    };
    t_slides = setTimeout(action, dom_form.delay.value * 1000);
}

/**
 *
 */
function stopSS() {
    if (debugLevel > 0) {
        console.log('>stopSS on ' + dom_form.currSlide.value);
    }

    if (t_slides) {
        t_slides = clearTimeout(t_slides);
    }

    isStarted = false;
}

/**
 *
 */
function nextSS() {
    if (debugLevel > 0) {
        console.log('>nextSS');
    }

    stopSS();

    var idxSlide = nextIdx (dom_form.currSlide.value, slidesLength)
    displaySlide(idxSlide);
}

/**
 *
 */
function prevSS() {
    if (debugLevel > 0) {
        console.log('>prevSS');
    }

    stopSS();

    var idxSlide = previousIdx (dom_form.currSlide.value, slidesLength)
    displaySlide(idxSlide);
}


/**
 *
 */
function selected(idxSlide) {
    if (debugLevel > 0) {
        console.log('>selected');
    }

    stopSS();

    displaySlide(idxSlide);
}





/* Not used
function zoom(dim1, dim2) {
    if (debugLevel > 0) {
        console.log('>zoom');
    }
    if (dim1) {
        if (document.images["stage"].width < imgWidth) {
            document.images["stage"].width = imgWidth;
            document.images["stage"].height = imgHeight;
        } else {
            document.images["stage"].width += dim1;
            document.images["stage"].height += dim2;
        }
        if (dim1 < 0) {
            if (document.images["stage"].width < imgWidth) {
                document.images["stage"].width = imgWidth;
                document.images["stage"].height = imgHeight;
            }
        }
    } else {
        document.images["stage"].width = imgWidth;
        document.images["stage"].height = imgHeight;
    }
}
/**/

/*---------------------------------------------------
   next / previous library
---------------------------------------------------*/

/**
 *
 */
function nextIdx (actIdx, arrayLength)
{
    var _nextIdx = (parseInt(actIdx) + 1) % parseInt(arrayLength);
    //console.log('nextIdx in: ' + actIdx + ' next: ' +  _nextIdx + ' length:' +  arrayLength + ' current:' +  dom_form.currSlide.value);
    
    return _nextIdx;
}

/**
 *
 */
function previousIdx (actIdx, arrayLength)
{
    var _prevIdx = (parseInt(actIdx) - 1 + parseInt(arrayLength)) % parseInt(arrayLength);
    //console.log('prevIdx in: ' + actIdx + ' prev: ' +  _prevIdx + ' length:' +  arrayLength + ' current:' +  dom_form.currSlide.value);
    
    return _prevIdx;
}

