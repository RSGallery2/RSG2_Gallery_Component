<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

//use Joomla\CMS\HTML\HTMLHelper;
//use Joomla\CMS\Layout\FileLayout;

defined('_JEXEC') or die;

//$layoutGallery = JLayoutHelper::render('.', $myLayoutData);
echo "--- Before render gallery overview ---------------<br>";
//$layout = new JLayoutFile('ClassicJ25.gallery', null, array('debug' => true, 'client' => 1)); // , null, array('debug' => true, 'client' => 1, 'component' => 'com_tags')
//$layout = new JLayoutFile('ClassicJ25.gallery');

$layout = new JLayoutFile('ClassicJ25.search');
echo $layout->render();

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

//---  -------------------------------------

/*--------------------------------------------------------
   root gallery display
--------------------------------------------------------*/

//// Header
//$layout = new JLayoutFile('ClassicJ25.rootGalleriesHeader');
//echo $layout->render($rootGalleryData);

// galleries
$layout = new JLayoutFile('galleries.semantic.display');
echo $layout->render($rootGalleryData);

//// footer
//$layout = new JLayoutFile('ClassicJ25.rootGalleriesFooter');
//echo $layout->render($rootGalleryData);

/**
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
/**/

//echo $layout->render($galleryData);
echo "--- After Render ---------------<br>";

