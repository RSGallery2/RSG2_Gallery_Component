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
 * @author
 */
class rsgDisplay_slideshow_schuweb extends rsgDisplay
{
	protected $isDisplayButtons = False;
	protected $isButtonsAbove = False;

	protected $gallery;

		/**
	 *
	 *
	 * @since version
	 * @throws Exception
	 */
	function showSlideShow()
	{
		// global $rsgConfig;

		$gallery = rsgGalleryManager::get();

		// why
		$this->gallery = $gallery;


		// show nothing if there are no items
		if (!$gallery->itemCount())
		{
			return;
		}

		//--- collect image information ---------------------------------------

		$k    = 0;

		$SLIDES = [];
		foreach ($gallery->items() as $item)
		{
			if ($item->type != 'image')
			{
				return;
			}

			// image display data. urls ...
			$display = $item->display();

			$SLIDES [$k] = [$display->url(), $item->title] ;

			$k++;
		}

		$this->slideOptions ['SLIDES'] =  $SLIDES;
		$this->galleryname = $gallery->name;
		$this->gid         = $gallery->id;

		$this->image_grid_size = 3;


		$this->display('slideshow.php');
	}
}

