<?php
/**
 * @version       $Id$
 * @package       RSGallery2
 * @copyright (C) 2003 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

/**
 * Template class for RSGallery2
 *
 * @package RSGallery2
 * @author  Ronald Smit <ronald.smit@rsdev.nl>
 */
class rsgDisplay_slideshow_fith extends rsgDisplay
{

	/**
	 *
	 */
	function showSlideShow()
	{
		$doc = JFactory::getDocument();
		$cssSliders = JURI::base(true).'/components/com_rsgallery2/templates/slideshow_fith/css/slideshow_fith.css';
		$doc->addStyleSheet($cssSliders);

		$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/slideshow_fith/js/slideshow_fith.js';
		$doc->addScript($jsScript);

		// global $rsgConfig;

		$gallery = rsgGalleryManager::get();

		// show nothing if there are no items
		if (!$gallery->itemCount())
		{
			return;
		}

		$imgIdx = 0;
		$text = "";
		/**
		foreach ($gallery->items() as $item)
		{
			if ($item->type != 'image')
			{
				return;
			}

			$display = $item->display();

			// für das Javascript
			$text .= "SLIDES[" . $imgIdx . "] = ['" . $display->url() . "', '{$item->title}'];\n";
			$k++;
		}
		
		$this->slides = $text;
		
		/**
		<div class="carousel__track-container">
			<ul class="carousel__track">
				<li class="carousel__slide is-selected">
                    <a href="#" style="background-image: url('images/carousel_image_1.jpg')"></a>
                </li>
				<li class="carousel__slide">
		            <a href="#" style="background-image: url('carousel_image_2.jpg')"></a>
		        </li>
		        <li class="carousel__slide">
					<a href="#" style="background-image: url('carousel_image_3.jpg')"></a>
		        </li>
		        <li class="carousel__slide">
					<a href="#" style="background-image: url('carousel_image_4.jpg')"></a>
                </li>
			</ul>
        </div>
		/**/

		/**
		<svg xmlns="http://www.w3.org/2000/svg">
			<symbol id="left" width="54" height="69.007" viewBox="0 0 54 69.007"><path d="M47 0L3.44 34.502 47 69.007z"/></symbol>
			<symbol id="right" width="54" height="69.007" viewBox="0 0 54 69.007"><path d="M5-.121l43.56 34.502L5 68.886z"/></symbol>
		</svg>
		/**/

		$html = [];
		
		$html[] = '<div class="carousel_container">';

		$html[] = '    <button class="carousel_button next is-hidden">';
		$html[] = '        <svg viewBox="0 0 54 69.007">';
        //$html[] = '            <use xlink:href="images/sprite.svg#left" />';
        $html[] = '            <symbol id="left" width="54" height="69.007" viewBox="0 0 54 69.007"><path d="M47 0L3.44 34.502 47 69.007z"/></symbol>';
		$html[] = '        </svg>';
		$html[] = '    </button>';

		$html[] = '    <ul class="carousel_images">';
		$isSelected = ' is-selected'; // for first element
		foreach ($gallery->items() as $item)
		{
			$display = $item->display();

			$html[] = '        <li class="carousel_image' . $isSelected .'">';
			$html[] = '            <a href="#" style="background-image: url(\'' . $display->url() . '\')"></a>';
			$html[] = '        </li>';

			$isSelected = '';
		}
		$html[] = '    </ul>';

		$html[] = '    <button class="carousel_button back">'; //  is-hidden
		$html[] = '        <svg viewBox="0 0 54 69.007">   xmlns="http://www.w3.org/2000/svg"';
		//$html[] = '        <use xlink:href="images/sprite.svg#left" />';
		$html[] = '            <symbol id="right" width="54" height="69.007" viewBox="0 0 54 69.007"><path d="M5-.121l43.56 34.502L5 68.886z"/></symbol>';
		$html[] = '        </svg>';
		$html[] = '    </button>';

		$html[] = '</div>';
		
		$text = implode("\n", $html);;
		
		$this->slides = $text;

		$this->galleryname = $gallery->name;
		$this->gid         = $gallery->id;

		$this->display('slideshow.php');
	}
}
