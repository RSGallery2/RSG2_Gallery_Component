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
		
		$html[] = '<div class="carousel_container_fith">';

		//$html[] = '    <div class="row-fluid center">';

		//--- button previous -------------------------

		$html[] = '    <button class="carousel_button_fith back btn">'; // is-hidden
		//$html[] = '        <svg width="20" height="32" viewBox="0 0 54 67.007">'; // xmlns="http://www.w3.org/2000/svg"';
		$html[] = '        <svg width="16" height="24" viewBox="0 -2 44 67.007">'; // xmlns="http://www.w3.org/2000/svg"';
		//$html[] = '                <path d="M47 2 L3.44 34.502 47 68.007z" />';
		$html[] = '                <path d="M47 0 L3.44 34.502 47 69.007z"/>';
		$html[] = '            Sorry, your browser does not support inline SVG.';
		$html[] = '        </svg>';
		$html[] = '    </button>';


		$imgLink = 'http://127.0.0.1/Joomla3xRelease/images/rsgallery/display/2017-11-02_00040.jpg.jpg';
		$imgLinkHyphened = "'" . $imgLink . "'";

		/**
		$html[] = '    <img id="image" src="' . $imgLink . '" />';
		$html[] = '    <br>';

		$html[] = '    <a width="599" height="400" href="link" style="background-image:url(' . $imgLinkHyphened . ');" >link</a>';
		$html[] = '    <br>';

		$html[] = '    <a href="http://rsgallery2.org/" title="test background image" id="range-logo">Range Web Development</a>';
		$html[] = '    <br>';
		/**/

		//--- images  -------------------------


		//$html[] = '    <ul class="carousel_images_fith">';
		$isSelected = ' is-selected'; // for first element

		$images = $gallery->items();
		$imgCount = count($images);
		foreach ($images as $imgIdx => $item)
		{
			$display = $item->display();
			$thumb = $item->thumb();

			$imgLink         = $display->url(); // 'http://127.0.0.1/Joomla3xRelease/images/rsgallery/display/2017-11-02_00040.jpg.jpg';
			$imgLinkHyphened = "'" . $imgLink . "'";

			/* Mittl ....
			$html[] = '        <li class="carousel_image_fith ' . $isSelected . ' ">';
			$html[] = '            <a href="#" style="background-image: url(' . $imgLinkHyphened . ')" >';
			// $html[] = '            <a href="#" style="width:300px height:200px background-image: url(' . $imgLinkHyphened . ')" >';
			//$html[] = '            <a href="#" style="width:300px background-image: url(' . $imgLinkHyphened . ')" >';
			//$html[] = '                 X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X';
			$html[] = '            </a>';
			//$html[] = '            <a href="#" style="width:300px background-color: blue" >';
			/**
			$html[] = '            <a href="#" style="background-color: blue" >';
			$html[] = '                 X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X';
			$html[] = '            </a>';
			/**
			$html[] = '        </li>';
			/**/

			$link = '#';
			$html[] = '            <a class="carousel_image_link_fith ' . $isSelected . '" href="' . $link  . '" >'; // style="background-color: blue" >';
			$html[] = '                 <img class="carousel_image_fith" src="' . $display->url() . '" class="full" />';
			//$html[] = '                 <img src="' . $thumb->url() . '" class="full" />';
			$html[] = '            </a>';

			$isSelected = 'is-hidden';

			if ($imgIdx > 4)
			{
				break;
			}
		}
		// $html[] = '    </ul>';

		//--- button next -------------------------

		/**/
		$html[] = '    <button class="carousel_button_fith next btn">'; //  is-hidden
		//$html[] = '        <svg width="20" height="32" viewBox="0 0 54 67.007">'; // xmlns="http://www.w3.org/2000/svg"';
		$html[] = '        <svg width="16" height="24" viewBox="0 -2 44 67.007">'; // xmlns="http://www.w3.org/2000/svg"';
		//$html[] = '                <path d="M5 -.121l43.56 34.502 L5 68.886z" />';
		$html[] = '                <path d="M5 -.121 L43.56 34.502 L5 68.886z" />';
		$html[] = '            Sorry, your browser does not support inline SVG.';
		$html[] = '        </svg>';
		$html[] = '    </button>';
		/**/
		//$html[] = '    </div>'; // class=row

		/**
		$html[] = '    <br>';
		$html[] = '    <br>';
		$html[] = '    <br>';
		$html[] = '    <br>';
		$html[] = '    <br>';
		$html[] = '    <br>';
		$html[] = '    <br>';
		$html[] = '    <hr>';
		/**/
		/**
		$html[] = '    <img id="image" src="' . $imgLink . '" />';
		$html[] = '    <br>';
		/**/

		$html[] = '</div>';
		
		$text = implode("\n", $html);;
		
		$this->slides = $text;

		$this->galleryname = $gallery->name;
		$this->gid         = $gallery->id;

		$this->display('slideshow.php');
	}
}
