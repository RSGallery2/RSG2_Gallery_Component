<?php
/**
 * Item class
 *
 * @version       $Id: audio.php 1011 2011-01-26 15:36:02Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2005-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery2 is Free Software
 */

defined('_JEXEC') or die();

/**
 * The generic item class
 *
 * @package RSGallery2
 * @author  Jonah Braun <Jonah@WhaleHosting.ca>
 */
class rsgItem_audio extends rsgItem
{
	/**
	 * rsgResource: the original image
	 */
	var $original = null;

	/**
	 * @param mixed|null $type
	 * @param            $mimetype
	 * @param            $gallery
	 * @param            $row
	 * @since 4.3.0
     */
	function __construct($type, $mimetype, &$gallery, $row)
	{
		parent::__construct($type, $mimetype, $gallery, $row);

		$this->_determineResources();
	}

	/**
	 * @return the thumbnail
	 * @todo: we need to return a nice generic audio thumbnail
	 * @since 4.3.0
     */
	function thumb()
	{
		return $this->thumb;
	}

	/**
	 * @return the original image
	 * @since 4.3.0
     */
	function original()
	{
		return $this->original;
	}
	/**
	
	 * @since 4.3.0
    */
	function _determineResources()
	{
		global $rsgConfig;

		$original = $rsgConfig->get('imgPath_original'). '/' .$this->name;

		if (file_exists(JPATH_ROOT . $original))
		{
			// original image exists
			$this->original = new rsgResource($original);
		}
		else
		{
			return;
		}
	}
}