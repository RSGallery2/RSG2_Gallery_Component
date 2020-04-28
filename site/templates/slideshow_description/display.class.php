<?php
/**
 * @version       $Id$
 * @package       RSGallery2
 * @copyright (C) 2003 - 2020 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

/**
 * Template class for RSGallery2
 *
 * @package RSGallery2
 * @author  Ronald Smit <ronald.smit@rsdev.nl>
 */
class rsgDisplay_slideshow_description extends rsgDisplay
{

	/**
	public $isAutoStart = True;
	public $isDisplayButtons = False;
	public $isButtonsAbove = True;
	/**/

	function showSlideShow()
	{
		// global $rsgConfig;

		$gallery = rsgGalleryManager::get();

		// show nothing if there are no items
		if (!$gallery->itemCount())
		{
			return;
		}

		$k    = 1;
		$slideId    = 'id="slide-1';
		$html[] = "";

		foreach ($gallery->items() as $item)
		{
			if ($item->type != 'image')
			{
				return;
			}

			//---  -----------------------

			$display = $item->display();

			$html[] = '<div ' . $slideId . '" class="slide">';
			$html[] = '    <h3>' . $item->title . '</h3>';
			$html[] = '    <p>' . $item->descr . '</p>';
			$html[] = '    <a href="#">';
			$html[] = '    <img src="' . $display->url() . '" alt="' . $display->url() . '"></img>';
			$html[] = '    </a>';
			$html[] = '';
			$html[] = '</div>';

			$k++;
			$slideId = '';
		}

		$html = implode("\n", $html);;
		$this->slides = $html;

		$this->display('slideshow.php');
	}
}