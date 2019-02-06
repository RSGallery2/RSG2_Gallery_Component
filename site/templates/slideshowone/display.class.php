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
		$slideArray = "";

		//$SLIDES = [];
		foreach ($gallery->items() as $item)
		{
			if ($item->type != 'image')
			{
				return;
			}

			$display = $item->display();

			// org: $slideArray .= "SLIDES[" . $k . "] = ['" . $display->url() . "', '{$item->title}'];\n";
			$slideArray .= "SLIDES[" . $k . "] = ['" . $display->url() . "', '{$item->title}'];";
			//$slideArray .= "SLIDES[" . $k . "] = ['url', 'title'];";
			//$slideArray2 = [$display->url() , $item->title ];
			$slideArray2 = ['url', 'title'];
			//$SLIDES []  = [$k => $slideArray2];
			//$SLIDES []  = [(string)$k => $slideArray2];
			//$SLIDES []  = ['{$k}' => $slideArray2];
			//$SLIDES []  = ["{$k}" => $slideArray2];
			$SLIDES ['SLIDES[' . $k . ']'] = ['url', 'title'] ;
			$k++;
		}
		$this->slides = $slideArray;

		/**/
		echo '<br>';
		echo '<br>';
		echo '<br>';
		echo '$slideArray: ' . json_encode ($slideArray);
		echo '<br>';
		echo '<br>';
		/**/
		/**/
		echo '<br>';
		echo '<br>';
		echo '<br>';
		echo '$slideArray: ' . json_encode ($SLIDES);
		echo '<br>';
		echo '<br>';
		/**/

		/**
		$moves = [
			'SLIDES[0]' => ['url', 'title'],
			'SLIDES[1]' => ['url', 'title'],
			'SLIDES[2]' => ['url', 'title'],
			'SLIDES[3]' => ['url', 'title']
		];

		echo '<br>';
		echo '<br>';
		echo '<br>';
		echo '$moves: ' . json_encode ($moves);
		echo '<br>';
		echo '<br>';
		/**/

		$this->display('slideshow.php');
	}
}