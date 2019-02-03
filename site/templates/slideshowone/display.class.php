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
class rsgDisplay_slideshowone extends rsgDisplay
{

	public $isAutoStart = True;
	public $isDisplayButtons = False;
	public $isButtonsAbove = True;
	
	function showSlideShow()
	{
		// global $rsgConfig;

		$gallery = rsgGalleryManager::get();

		// show nothing if there are no items
		if (!$gallery->itemCount())
		{
			return;
		}

		$k    = 0;
		$html = "";
		
		foreach ($gallery->items() as $item)
		{
			if ($item->type != 'image')
			{
				return;
			}

			$display = $item->display();

			$html .= "SLIDES[" . $k . "] = ['" . $display->url() . "', '{$item->title}'];\n";
			$k++;
		}
		$this->slides = $html;
		$this->display('slideshow.php');
	}
}