
/* 
	slideshow_fith
	
	--------------------------------------------

*/

/**
 * 
 */
jQuery(document).ready(function ($) {

    const track = document.querySelector('.carousel_images');
    const slides = Array.from(track.children);
    console.log('slides', slides);

    const rect = slides[0].getBoundingClientRect();
    const slideWidth = rect.width;
    console.log('slideWidth', slideWidth);

    const setSlidePostion = (slide, index) => {
        slide.style.left = slideWidth * index + 'px';
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
    /**/

    backButton.addEventListener('click', e => {

        nextButton.classList.remove('is-hidden')

    })

    nextButton.addEventListener('click', e => {

        backButton.classList.remove('is-hidden')

    })

})