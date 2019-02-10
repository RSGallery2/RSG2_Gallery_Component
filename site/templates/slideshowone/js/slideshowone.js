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

jQuery(document).ready(function () {


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
    var oW = slideOptions.imgWidth;// width of stage (first image)
    //var oH = 400;// height of stage
    var oH = slideOptions.imgHeigth;// height of stage
    //var zW = 40;// zoom width by (add or subtracts this many pixels from image width)
    var zW = slideOptions.zoomWidth;// zoom width by (add or subtracts this many pixels from image width)
    //var zH = 30;// zoom height by
    var zH = slideOptions.zoomHeigth;// zoom height by

    var isAutoStart = slideOptions.isAutoStart;

    // path to image/name of image in slide show. this will also preload all images
    // each element in the array must be in sequential order starting with zero (0)
    var SLIDES = slideOptions.SLIDES;
    //console.log(JSON.stringify(SLIDES));
    var debugLevel = 2; // 3 -> show all run ...

    // end options

    var S = new Array();
    for (a = 0; a < SLIDES.length; a++) {
        S[a] = new Image();
        S[a].src = SLIDES[a][0];
    }

    // form
    var f = document._slideShow;
    // index
    var n = 0;
    // time
    var t = 0;

    //document.images["stage"].width  = oW;
    //document.images["stage"].height = oH;
    f.delay.value = displayTime;

    function startSS() {
        if (debugLevel > 1) {
            console.log('>startSS');
        }
        //t = setTimeout({runSS(f.currSlide.value)();}, 1 * 1);
        var action = function () {
            runSS(f.currSlide.value);
        };
        t = setTimeout(action, f.delay.value * 1000);
    }

    function runSS(n) {
        if (debugLevel > 2) {
            console.log('>runSS');
        }
        n++;
        if (n >= SLIDES.length) {
            n = 0;
        }

        document.images["stage"].src = S[n].src;
        if (document.all && navigator.userAgent.indexOf("Opera") < 0 && navigator.userAgent.indexOf("Windows") >= 0) {
            document.images["stage"].style.visibility = "hidden";
            document.images["stage"].filters.item(0).apply();
            document.images["stage"].filters.item(0).transition = effectType;
            document.images["stage"].style.visibility = "visible";
            document.images["stage"].filters(0).play(transitionTime);
        }
        f.currSlide.value = n;
        //t = setTimeout("runSS(" + f.currSlide.value + ")", f.delay.value * 1000);
        var action = function () {
            runSS(f.currSlide.value);
        };
        t = setTimeout(action, f.delay.value * 1000);
    }

    function stopSS() {
        if (debugLevel > 1) {
            console.log('>stopSS');
        }
        if (t) {
            t = clearTimeout(t);
        }
    }

    function nextSS() {
        if (debugLevel > 1) {
            console.log('>nextSS');
        }
        stopSS();
        n = f.currSlide.value;
        n++;
        if (n >= SLIDES.length) {
            n = 0;
        }
        if (n < 0) {
            n = SLIDES.length - 1;
        }
        document.images["stage"].src = S[n].src;
        f.currSlide.value = n;
        if (document.all && navigator.userAgent.indexOf("Opera") < 0 && navigator.userAgent.indexOf("Windows") >= 0) {
            document.images["stage"].style.visibility = "hidden";
            document.images["stage"].filters.item(0).apply();
            document.images["stage"].filters.item(0).transition = effectType;
            document.images["stage"].style.visibility = "visible";
            document.images["stage"].filters(0).play(transitionTime);
        }
    }

    function prevSS() {
        if (debugLevel > 1) {
            console.log('>prevSS');
        }
        stopSS();
        n = f.currSlide.value;
        n--;
        if (n >= SLIDES.length) {
            n = 0;
        }
        if (n < 0) {
            n = SLIDES.length - 1;
        }
        document.images["stage"].src = S[n].src;
        f.currSlide.value = n;

        if (document.all && navigator.userAgent.indexOf("Opera") < 0 && navigator.userAgent.indexOf("Windows") >= 0) {
            document.images["stage"].style.visibility = "hidden";
            document.images["stage"].filters.item(0).apply();
            document.images["stage"].filters.item(0).transition = effectType;
            document.images["stage"].style.visibility = "visible";
            document.images["stage"].filters(0).play(transitionTime);
        }
    }

    function selected(n) {
        if (debugLevel > 1) {
            console.log('>selected');
        }
        stopSS();
        document.images["stage"].src = S[n].src;
        f.currSlide.value = n;

        if (document.all && navigator.userAgent.indexOf("Opera") < 0 && navigator.userAgent.indexOf("Windows") >= 0) {
            document.images["stage"].style.visibility = "hidden";
            document.images["stage"].filters.item(0).apply();
            document.images["stage"].filters.item(0).transition = effectType;
            document.images["stage"].style.visibility = "visible";
            document.images["stage"].filters(0).play(transitionTime);
        }
    }

    function zoom(dim1, dim2) {
        if (debugLevel > 1) {
            console.log('>zoom');
        }
        if (dim1) {
            if (document.images["stage"].width < oW) {
                document.images["stage"].width = oW;
                document.images["stage"].height = oH;
            } else {
                document.images["stage"].width += dim1;
                document.images["stage"].height += dim2;
            }
            if (dim1 < 0) {
                if (document.images["stage"].width < oW) {
                    document.images["stage"].width = oW;
                    document.images["stage"].height = oH;
                }
            }
        } else {
            document.images["stage"].width = oW;
            document.images["stage"].height = oH;
        }
    }

    // start slideshow right once dom is ready (uses mootools)
    if (typeof isAutoStart === 'undefined')
    {
        isAutoStart = true;
    }

// jQuery(document).ready(function () {


    if (isAutoStart) {
        //console.log('do runSS');
        //runSS(f.currSlide.value);
        console.log('do startSS');
        startSS();
    }

    /**/
});
