
/* 
	slideshow_fith
	
	--------------------------------------------

*/

/**
 * 
 */
jQuery(document).ready(function ($) {

    var imgLinkList =  document.querySelectorAll('.carousel_image_link_fith');
    console.log('imgLinkList: ', imgLinkList);
    //var imgList =  document.querySelectorAll('.carousel_images_fith');
    //console.log('imgList: ', imgList);
    var imgListCount = imgLinkList.length;
    console.log('imgListCount: ', imgListCount);

    var nextButton =  document.querySelector('.carousel_button_fith.next');
    console.log('nextButton: ', nextButton);
    var prevButton =  document.querySelector('.carousel_button_fith.back');
    console.log('prevButton : ', prevButton );

    var playButton =  document.querySelector('.carousel_button_fith.play');
    console.log('playButton: ', playButton);
    var stopButton =  document.querySelector('.carousel_button_fith.stop');
    console.log('stopButton: ', stopButton);

    var backwardButton =  document.querySelector('.carousel_button_fith.backward');
    console.log('backwardButton: ', backwardButton);
    var forwardButton =  document.querySelector('.carousel_button_fith.forward');
    console.log('forwardButton: ', forwardButton);

    var actImgIdx = 0;

    function activateImage (offset) {
        var nextImgIdx = 0;
        var nextLinkElem;
        var prevLinkElem;
        var oldImgIdx = actImgIdx;
        console.log('oldImgIdx: ', oldImgIdx);

        var prevLinkElem = imgLinkList [actImgIdx];

        nextImgIdx = actImgIdx + offset;
        console.log('nextImgIdx: ', nextImgIdx);
        if (nextImgIdx >= imgListCount)
        {
            nextImgIdx = 0;
        }
        else
        {
            if (nextImgIdx <= 0)
            {
                nextImgIdx = imgListCount-1;
            }
        }
        console.log('nextImgIdx (2): ', nextImgIdx);

        var nextLinkElem = imgLinkList [nextImgIdx];
        console.log('nextLinkElem: ', nextLinkElem);
        nextLinkElem.classList.remove("is-hidden");
        nextLinkElem.classList.add("is-selected");
        console.log('nextLinkElem(2): ', nextLinkElem);
        console.log('nextLinkElem.classList', nextLinkElem.classList);

        actImgIdx = nextImgIdx;

        console.log('prevLinkElem: ', prevLinkElem);
        prevLinkElem.classList.remove("is-selected");
        prevLinkElem.classList.add("is-hidden");
        console.log('prevLinkElem(2): ', prevLinkElem);
        console.log('prevLinkElem.classList', prevLinkElem.classList);
    }

    nextButton.onclick = function(event) {
    // nextButton.addEventListener('click', e => {
        console.log('nextButton');
        //console.log('e:', e);

        activateImage (+1);

//        alert ("nextButton");

        event.stopPropagation();

        return false;
        //return true;
    };
    //});

    prevButton.onclick = function(event) {
        console.log('prevButton');

        //prevButton.addEventListener('click', e => {
        //console.log('event:', event);
        //console.log('prevButton');

        activateImage(-1);

//        alert("prevButton");

        event.stopPropagation();

        return false;
        //return true;
    };
    //});

    playButton.onclick = function(event) {
        console.log('playButton');

        //prevButton.addEventListener('click', e => {
        //console.log('event:', event);
        //console.log('prevButton');

//        activateImage(-1);

//        alert("prevButton");

        event.stopPropagation();

        return false;
        //return true;
    };
    //});

    stopButton.onclick = function(event) {
        console.log('stopButton');

        //prevButton.addEventListener('click', e => {
        //console.log('event:', event);
        //console.log('prevButton');

//        activateImage(-1);

//        alert("prevButton");

        event.stopPropagation();

        return false;
        //return true;
    };
    //});

    backwardButton.onclick = function(event) {
        console.log('backwardButton');

        //prevButton.addEventListener('click', e => {
        //console.log('event:', event);
        //console.log('prevButton');

//        activateImage(-1);

//        alert("prevButton");

        event.stopPropagation();

        return false;
        //return true;
    };
    //});

    forwardButton.onclick = function(event) {
        console.log('forwardButton');

        //prevButton.addEventListener('click', e => {
        //console.log('event:', event);
        //console.log('prevButton');

//        activateImage(-1);

//        alert("prevButton");

        event.stopPropagation();

        return false;
        //return true;
    };
    //});



    /**



    const track = document.querySelector('.carousel_images_fith');
    console.log('track', track);
    const slides = Array.from(track.children);
    console.log('slides', slides);

    const rect = slides[0].getBoundingClientRect();
    const slideWidth = rect.width;
    console.log('slideWidth', slideWidth);

    const setSlidePostion = (slide, index) => {
        console.log('index: ', index);
        console.log('slideWidth', slideWidth);
        let left = slideWidth * index;
        console.log('left: ', left);
        slide.style.left = left + 'px';
        //slide.style.top = '0px';

        console.log('slide.style', slide.style);
        console.log('slide', slide);
    }
    slides.forEach(setSlidePostion);



    const nextButton = document.querySelector('.next');

    const moveSlide = (track, targetSlide, currentSlide) => {
        track.style.transform = `translateX(-${targetSlide.style.left})`
        currentSlide.classList.remove('is-selected')
        targetSlide.classList.add('is-selected')
    }

    nextButton.addEventListener('click', e => {

        console.log('nextButton');

        const currentSlide = track.querySelector('.is-selected');
        const nextSlide = currentSlide.nextElementSibling;

        moveSlide(track, nextSlide, currentSlide);

        const isLastSlide = !nextSlide.nextElementSibling;
        if (isLastSlide) {
            nextButton.classList.add('is-hidden');
        }
    })

    const backButton = document.querySelector('.back')


    backButton.addEventListener('click', e => {

        console.log('backButton');

        const currentSlide = track.querySelector('.is-selected');
        const backSlide = currentSlide.previousElementSibling;
        const movingWidth = backSlide.style.left;

        moveSlide(track, nextSlide, currentSlide);

        const isLastSlide = !backSlide.previousElementSibling;
        if (isLastSlide) {
            backButton.classList.add('is-hidden')
        }

        const currentLogoPart = joomlaLogo.querySelector('.is-selected');
        const backLogoPart = currentLogoPart.previousElementSibling;

        currentLogoPart.classList.remove('is-selected');
        backLogoPart.classList.add('is-selected');
    })

    const showHideNextBackButtons = (targetIndex, backButton, nextButton) => {
        if (targetIndex === 0) {
            backButton.classList.add('is-hidden');
            nextButton.classList.remove('is-hidden')
        } else if (targetIndex === slides.length - 1) {
            backButton.classList.remove('is-hidden');
            nextButton.classList.add('is-hidden');
        } else {
            backButton.classList.remove('is-hidden');
            nextButton.classList.remove('is-hidden');
        }
    }

    /**
    if (targetIndex === 0) {
        backButton.classList.add('is-hidden')
        nextButton.classList.remove('is-hidden')
    } else if (targetIndex === slides.length - 1) {
        backButton.classList.remove('is-hidden')
        nextButton.classList.add('is-hidden')
    } else {
        backButton.classList.remove('is-hidden')
        nextButton.classList.remove('is-hidden')
    }
    /**/

    /**
    const targetSlide = slides[targetIndex];
    /**

    backButton.addEventListener('click', e => {

        nextButton.classList.remove('is-hidden')

    })

    nextButton.addEventListener('click', e => {

        backButton.classList.remove('is-hidden')

    })
    /**/
})
