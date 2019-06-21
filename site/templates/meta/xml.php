<?php

defined('_JEXEC') or die;


/**
 * abstract parent class for xml templates
 *
 * @package RSGallery2
 * @copyright (C) 2003 - 2019 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 * @author  Jonah Braun <Jonah@WhaleHosting.ca>
 */
class rsgXmlGalleryTemplate_generic
{
	var $gallery;

	/**
	 * constructor
	 * @param rsgGallery $gallery object
	 **/
	function __construct($gallery)
	{
		$this->gallery = $gallery;
	}

	/**
	 * @return string
	 */
	static function getName()
	{
		return 'generic xml template';
	}

	/**
	 * Prepare XML first.  Then if there are errors we print an error before changing Content-Type to xml.
	 **/
	function prepare()
	{
		echo '<gallery name="' . $this->gallery->name . '">';

		foreach ($this->gallery->itemRows() as $img)
		{
			echo '  <image name="' . $img['name'] . '" />' . "\n";
		}

		echo '</gallery>';
	}

	/**
	 * print xml headers
	 **/
	function printHead()
	{
		header('Content-Type: application/xml');
		echo '<?xml version="1.0" encoding="iso-8859-1"?>';
	}
}

?>
