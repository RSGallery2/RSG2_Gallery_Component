<?php
/**
 * @version       $Id$
 * @package       RSGallery2
 * @copyright (C) 2003 - 2021 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

/**
 * Slideshow class for RSGallery2
 * Based on Phatfusion from phatfusion.net
 *
 * @package RSGallery2
 * @author  Ronald Smit <ronald.smit@rsdev.nl>
 */
class rsgDisplay_slideshow_phatfusion extends rsgDisplay
{

	/**
	 *
	 */
	function showSlideShow()
	{
		global $rsgConfig;

		$gallery = rsgGalleryManager::get();

		// show nothing if there are no items
		if (!$gallery->itemCount())
		{
			return;
		}

		$k    = 0;
		$text = "";
		foreach ($gallery->items() as $item)
		{
			if ($item->type != 'image')
			{
				return;
			}

			$display = $item->display();
			$thumb   = $item->thumb();

			$text .= "<a href=\"" . $display->url() . "\" class=\"slideshowThumbnail\"><img src=\"" . $thumb->url() . "\" border=\"0\" /></a>";
			$k++;
		}
		$this->slides      = $text;
		$this->galleryname = $gallery->name;
		$this->gid         = $gallery->id;

		$this->display('slideshow.php');
	}
}