/*
slideshowone

...

adapted from
SlideShow. Written by PerlScriptsJavaScripts.com
Copyright http://www.perlscriptsjavascripts.com
Code page http://www.perlscriptsjavascripts.com/js/slideshow.html
Free and commercial Perl and JavaScripts

--------------------------------------------
*/

// jQuery(document).ready(function () {


    /**
     * initialize
     */

    // options defined in templateDetails.xml file
    var slideOptions = Joomla.getOptions('slideArray');
    //console.log(slideOptions);

    /**
     //for (var item in slideOptions) // names
     //for (let i of arr) { // content
    slideOptions.forEach(function (element, index) {

        alert ('slideOptions Each');
        alert ('JSON.stringify(slideOptions)
        //alert('jsvars: ' + JSON.stringify(element) + '=' + JSON.stringify(index);
    });
    /**/

    // start options

    //var effect = 23;// transition effect. number between 0 and 23, 23 is random effect
    var effectType = slideOptions.effectType;// transition effect. number between 0 and 23, 23 is random effect
    //var duration = 1.5;// transition duration. number of seconds effect lasts
    var transitionTime = slideOptions.transitionTime;// transition duration. number of seconds effect lasts
    //var display = 4;// seconds to display each image?
    var displayTime = slideOptions.displayTime;// seconds to display each image?
    //var oW = 400;// width of stage (first image)
    var imgWidth = slideOptions.imgWidth;// width of stage (first image)
    //var oH = 400;// height of stage
    var imgHeigth = slideOptions.imgHeigth;// height of stage
    //var zW = 40;// zoom width by (add or subtracts this many pixels from image width)
    var zoomWidth = slideOptions.zoomWidth;// zoom width by (add or subtracts this many pixels from image width)
    //var zH = 30;// zoom height by
    var zoomHeigth = slideOptions.zoomHeigth;// zoom height by

    var isAutoStart = slideOptions.isAutoStart;

    // path to image/name of image in slide show. this will also preload all images
    // each element in the array must be in sequential order starting with zero (0)
    var SLIDES = slideOptions.SLIDES;
    var slidesLength = SLIDES.length;
    //console.log(JSON.stringify(SLIDES));
    var debugLevel = 2; // 3 -> show all run ...

    // end options

    var Slides = new Array();
    for (var a = 0; a < SLIDES.length; a++) {
        Slides[a] = new Image();
        Slides[a].src = SLIDES[a][0];
    }

    // this form
    var f_slides;
    // index
    //var idxSlide = 0;
    // time
    var t_slides = 0;

    //document.images["stage"].width  = imgWidth;
    //document.images["stage"].height = imgHeigth;

    function initialize() {
        f_slides = document._slideShow;
        f_slides.delay.value = displayTime;
    }

    function nextIdx (actIdx, arrayLength)
    {
        var _nextIdx = (parseInt(actIdx) + 1) % parseInt(arrayLength);
        //console.log('nextIdx in: ' + actIdx + ' next: ' +  _nextIdx + ' length:' +  arrayLength + ' current:' +  f_slides.currSlide.value);
        
        return _nextIdx;
    }
    
    function previousIdx (actIdx, arrayLength)
    {
        var _prevIdx = (parseInt(actIdx) - 1 + parseInt(arrayLength)) % parseInt(arrayLength);
        //console.log('prevIdx in: ' + actIdx + ' prev: ' +  _prevIdx + ' length:' +  arrayLength + ' current:' +  f_slides.currSlide.value);
    
        return _prevIdx;
    }

    function initSlide (idxSlide) {
        
        document.images['stage'].src = Slides[idxSlide].src
        f_slides.currSlide.value = idxSlide
        
        if (document.all && navigator.userAgent.indexOf('Opera') < 0 && navigator.userAgent.indexOf('Windows') >= 0) {
            document.images['stage'].style.visibility = 'hidden'
            document.images['stage'].filters.item(0).apply()
            document.images['stage'].filters.item(0).transition = effectType
            document.images['stage'].style.visibility = 'visible'
            document.images['stage'].filters(0).play(transitionTime)
        }
    }

    function startSS() {
        if (debugLevel > 1) {
            console.log('>startSS');
        }
        //t_slides = setTimeout({runNextSlide(f_slides.currSlide.value)();}, 1 * 1);
        var action = function () {
            runNextSlide(f_slides.currSlide.value);
        };
        t_slides = setTimeout(action, f_slides.delay.value * 1000);
    }

    function runNextSlide(idxPrevious) {
        if (debugLevel > 2) {
            console.log('>runNextSlide');
        }
    
        var idxSlide = nextIdx (idxPrevious, slidesLength)
        //console.log('idxSlide (runNextSlide): ' + idxSlide);
    
        initSlide(idxSlide);
    
        //t_slides = setTimeout("runNextSlide(" + f_slides.currSlide.value + ")", f_slides.delay.value * 1000);
        var action = function () {
            runNextSlide(f_slides.currSlide.value);
        };
        t_slides = setTimeout(action, f_slides.delay.value * 1000);
    }

    function stopSS() {
        if (debugLevel > 1) {
            console.log('>stopSS');
        }

        //console.log('idxSlide (stopSS): ' + f_slides.currSlide.value);

        if (t_slides) {
            t_slides = clearTimeout(t_slides);
        }
    }

    function nextSS() {
        if (debugLevel > 1) {
            console.log('>nextSS');
        }

        stopSS();
    
        var idxSlide = nextIdx (f_slides.currSlide.value, slidesLength)
        //console.log('idxSlide (next): ' + idxSlide);
    
        initSlide(idxSlide);
    }

    function prevSS() {
        if (debugLevel > 1) {
            console.log('>prevSS');
        }

        stopSS();
    
        var idxSlide = previousIdx (f_slides.currSlide.value, slidesLength)
        //console.log('idxSlide (prev): ' + idxSlide);
    
        initSlide(idxSlide);
    }


    function selected(idxSlide) {
        if (debugLevel > 1) {
            console.log('>selected');
        }

        stopSS();
    
        initSlide(idxSlide);
    }

    function zoom(dim1, dim2) {
        if (debugLevel > 1) {
            console.log('>zoom');
        }
        if (dim1) {
            if (document.images["stage"].width < imgWidth) {
                document.images["stage"].width = imgWidth;
                document.images["stage"].height = imgHeigth;
            } else {
                document.images["stage"].width += dim1;
                document.images["stage"].height += dim2;
            }
            if (dim1 < 0) {
                if (document.images["stage"].width < imgWidth) {
                    document.images["stage"].width = imgWidth;
                    document.images["stage"].height = imgHeigth;
                }
            }
        } else {
            document.images["stage"].width = imgWidth;
            document.images["stage"].height = imgHeigth;
        }
    }

    // start slideshow right once dom is ready (uses mootools)
    if (typeof isAutoStart === 'undefined')
    {
        isAutoStart = true;
    }

jQuery(document).ready(function () {

    initialize();

    if (isAutoStart) {
        console.log('do startSS');
        startSS();
    }

    /**/
});
