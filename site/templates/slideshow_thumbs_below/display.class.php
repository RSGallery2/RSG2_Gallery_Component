<?php
/**
 * @version       $Id$
 * @package       RSGallery2
 * @copyright (C) 2003 - 2019 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

/**
 * Template class for RSGallery2
 *
 * @package RSGallery2
 * @author  Ronald Smit <ronald.smit@rsdev.nl>
 */
class rsgDisplay_slideshow_thumbs_below extends rsgDisplay
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
		$htmlPanel[] = "";
		$htmlThumb[] = "";

		$isFirst = True;

		foreach ($gallery->items() as $item)
		{
			if ($item->type != 'image')
			{
				return;
			}

			$display = $item->display();


			//---  -----------------------

			/**
			<div class="panel" title="Panel 4">
					<div class="wrapper">
						<img src="images/tempphoto-4.jpg" alt="temp" />
						<div class="photo-meta-data">
		                    A Poem by Shel Silverstein<br />
							<span>Falling Up</span>
						</div>
					</div>
				</div>
			/**/

			$htmlPanel[] = '<div class="panel" title="Panel ' . $k . '">';
			$htmlPanel[] = '    <div class="wrapper">';
			$htmlPanel[] = '        <img src="' . $display->url() . '" alt="' . $display->url() . '">';
			$htmlPanel[] = '        <div  class="photo-meta-data">';
			// $htmlPanel[] = '            <p>' . $item->descr . '</p>';
			$htmlPanel[] = '            ' . $item->descr . '';
			$htmlPanel[] = '        </div>';
			$htmlPanel[] = '    </div>';
			$htmlPanel[] = '</div>';


			//--- Thumbs -----------------------
			/**
			<a href="#1" class="cross-link active-thumb">
				<img src="images/tempphoto-1thumb.jpg" class="nav-thumb" alt="temp-thumb" />
			</a>
			<div id="movers-row">
				<div>
					<a href="#2" class="cross-link">
						<img src="images/tempphoto-2thumb.jpg" class="nav-thumb" alt="temp-thumb" />
					</a>
				</div>
				<div>
					<a href="#3" class="cross-link">
						<img src="images/tempphoto-3thumb.jpg" class="nav-thumb" alt="temp-thumb" />
					</a>
				</div>
				<div>
					<a href="#4" class="cross-link">
						<img src="images/tempphoto-4thumb.jpg" class="nav-thumb" alt="temp-thumb" />
					</a>
				</div>
				<div>
					<a href="#5" class="cross-link">
						<img src="images/tempphoto-5thumb.jpg" class="nav-thumb" alt="temp-thumb" />
					</a>
				</div>
				<div>
					<a href="#6" class="cross-link">
						<img src="images/tempphoto-6thumb.jpg" class="nav-thumb" alt="temp-thumb" />
					</a>
				</div>
			</div>
			/**/

			$thumb = $item->thumb();

			if ($isFirst)
			{
				$htmlThumb[] = '<a href="#1" class="cross-link active-thumb">';
				//$htmlThumb[] = '    <img src="' . $thumb->name . '" class="nav-thumb" alt="temp-thumb" />';
				$htmlThumb[] = '    <img src="' . $thumb->url() . '" class="nav-thumb" alt="temp-thumb" />';
				$htmlThumb[] = '</a>';

				// Start of movers
				$htmlThumb[] = '<div id="movers-row">';
			}
			else
			{
				$htmlThumb[] = '<div>';
				$htmlThumb[] = '	<a href="#2" class="cross-link">';
				//$htmlThumb[] = '		<img src="' . $thumb->name . '" class="nav-thumb" alt="temp-thumb" />';
				$htmlThumb[] = '		<img src="' . $thumb->url() . '" class="nav-thumb" alt="temp-thumb" />';
				$htmlThumb[] = '	</a>';
				$htmlThumb[] = '</div>';
			}

			$k++;
			$slideId = '';
		}

		$html = implode("\n", $htmlPanel);;
		$this->slides = $html;

		$html = implode("\n", $htmlThumb);;
		$this->thumbs = $html;


		// End of movers
		$html = '<div>';


		$this->display('slideshow.php');
	}
}