<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;

defined('_JEXEC') or die;

// global $rsgConfig;

// https://magazine.joomla.org/issues/issue-nov-2013/item/1590-jlayout-layouts-improvements-joomla-3-2

// $layout = new JLayoutFile('joomla.content.tags', null, array('component' => 'com_tags'));

//HTMLHelper::_('script', 'com_foo/script.js', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_foo/style.css', array('version' => 'auto', 'relative' => true));

$input = JFactory::getApplication()->input;

/**
$ItemId = $input->get('Itemid', null, 'INT');
$gid = $input->get('gid', null, 'INT');
$view = $input->get('view', null, 'CMD');
$option = $input->get('option', null, 'CMD');
/**/

if ($this->config->displaySearch) {
    $layout = new JLayoutFile('ClassicJ25.search');
    echo $layout->render();
}

$rootGalleryData = array(
    'galleries' => $this->galleries,
    'config' => $this->config, // front part of rsgallery config
    'pagination' => $this->pagination,
//		''//,
//		''//,
);

$rootRandomData = array(
    'images' => $this->imagesRandom,
    'config' => $this->config, // front part of rsgallery config
    'title' => JText::_('COM_RSGALLERY2_RANDOM_IMAGES')
//		''//,
//		''//,
);

$rootLatestData = array(
    'images' => $this->imagesLatest,
    'config' => $this->config, // front part of rsgallery config
    'title' => JText::_('COM_RSGALLERY2_LATEST_IMAGES')
//		''//,
//		''//,
);


$singleGalleryData = array(
	'gallery' => $this->gallery,
    'images' => $this->images,
    'galleries' => $this->galleries,
    'pagination' => $this->pagination,
    'config' => $this->config, // front part of rsgallery config
//		''//,
//		''//,
);

//$layoutGallery = JLayoutHelper::render('.', $myLayoutData);
//echo "--- Before render ClassicJ25.rootGallery / One Gallery thumbs ---------------<br>";
//$layout = new JLayoutFile('ClassicJ25.gallery', null, array('debug' => true, 'client' => 1)); // , null, array('debug' => true, 'client' => 1, 'component' => 'com_tags')

//---  -------------------------------------

// root galleries display ?
if($this->galleryId > 0)
{
	// Standard tiles ?
	if(empty($this->isGallerySingleImageView))
	{
		/*--------------------------------------------------------
		   single gallery display
		--------------------------------------------------------*/

		//--- gallery display -------------------------------------

		// display as table
		if ($this->config->displayThumbsStyle == 'table')
		{
			// display as table
			$layout = new JLayoutFile('ClassicJ25.galleryAsTable');
			echo $layout->render($singleGalleryData);
		}
		else
		{
			// display as float
			if ($this->config->displayThumbsStyle == 'float')
			{
				// display as float
				$layout = new JLayoutFile('ClassicJ25.galleryAsFloat');
				echo $layout->render($singleGalleryData);
			}
			else
			{
				// display as "magic"
				echo '<br><br><br><strong>' . JText::_('COM_RSGALLERY2_MAGIC_NOT_SUPPORTED_YET') . '</strong><br><br><br>';
			}
		}

		//--- child galleries --------------------------

		if (count($this->galleries))
		{
			$layout = new JLayoutFile('ClassicJ25.rootGalleries');
			echo $layout->render($rootGalleryData);
		}
	}
	else
	{
		/*--------------------------------------------------
		  single images slider display
		--------------------------------------------------*/

		// display as table
		$layout = new JLayoutFile('ClassicJ25.galleryOneImageSlider');
		echo $layout->render($singleGalleryData);

	}
}
else
{
    /*--------------------------------------------------------
	   root gallery display
    --------------------------------------------------------*/

    // Header
	$layout = new JLayoutFile('ClassicJ25.rootGalleriesHeader');
	echo $layout->render($rootGalleryData);

	// galleries
	$layout = new JLayoutFile('ClassicJ25.rootGalleries');
	echo $layout->render($rootGalleryData);

	// footer
	$layout = new JLayoutFile('ClassicJ25.rootGalleriesFooter');
	echo $layout->render($rootGalleryData);

	//--- root random images -------------------------------------

    if ($this->config->displayRandom)
    {
        $layout = new JLayoutFile('ClassicJ25.rootImagesBoxHorizontal');
        echo $layout->render($rootRandomData);
    }

    //--- root latest images -------------------------------------

    if ($this->config->displayLatest)
    {
        $layout = new JLayoutFile('ClassicJ25.rootImagesBoxHorizontal');
        echo $layout->render($rootLatestData);
    }

}
//echo '<br>';
//echo "--- After Render ---------------<br>";

