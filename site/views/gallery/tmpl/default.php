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

$template_dir = JURI_SITE . "/components/com_rsgallery2/templates/" . $this->config->template;
$doc          = JFactory::getDocument();
$doc->addStyleSheet($template_dir . "/css/template.css", "text/css");

/* Does work so far
$layout = new FileLayout('rsgallery2.page');
$data = array();
$data['text'] = 'RSGallery2 Hello Joomla!  (2)';
echo $layout->render($data);
/**/
$input = JFactory::getApplication()->input;

$ItemId = $input->get('Itemid', null, 'INT');
$gid = $input->get('gid', null, 'INT');
$view = $input->get('view', null, 'CMD');
$option = $input->get('option', null, 'CMD');

if ($this->config->displaySearch) {
    $layout = new JLayoutFile('ClassicJ25.search');
    echo $layout->render();
}

$rootGalleryData = array(
    'galleries' => $this->galleries,
    'config' => $this->config, // front part of rsgallery config
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
    'config' => $this->config, // front part of rsgallery config
//		''//,
//		''//,
);

//$layoutGallery = JLayoutHelper::render('.', $myLayoutData);
echo "--- Before render ClassicJ25.rootGallery / One Gallery thumbs ---------------<br>";
//$layout = new JLayoutFile('ClassicJ25.gallery', null, array('debug' => true, 'client' => 1)); // , null, array('debug' => true, 'client' => 1, 'component' => 'com_tags')

//---  -------------------------------------

// root galleries display ?
if($this->galleryId == 0)
{
    //--- root gallery title -------------------------------------

    $layout = new JLayoutFile('ClassicJ25.rootGalleryTitle');
    echo $layout->render($rootGalleryData);

    //--- root limit box -------------------------------------

    /**
    <option value="2">COM_RSGALLERY2_ALWAYS</option>
	<option value="1">COM_RSGALLERY2_IF_MORE_GALLERIES_THAN_LIMIT</option>
	<option value="0">COM_RSGALLERY2_NEVER</option>    $isDisplayLimitbox = true;
    /**/
    $isDisplayLimitBox = false;
    $cfgDisplayLimitBox = $this->config->dispLimitBox;
    // Display always
    if ($cfgDisplayLimitBox == 2)
    {
        $isDisplayLimitBox = true;
    }
    else
    {
        // More galleries existing than displayed ?
        if ($cfgDisplayLimitBox == 1)
        {
            if ($this->galleryCount > $this->config->rootGalleriesCount)
            {
                $isDisplayLimitBox = true;
            }
        }
    }
    
    if ($isDisplayLimitBox) {
        $layout = new JLayoutFile('ClassicJ25.rootLimitBox');
        echo $layout->render($rootGalleryData);
    }

    //--- root gallery display -------------------------------------

    $layout = new JLayoutFile('ClassicJ25.rootGalleries');
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
else
{
    //--- single gallery display -------------------------------------

    //--- gallery display -------------------------------------

    $layout = new JLayoutFile('ClassicJ25.gallery');
    echo $layout->render($singleGalleryData);
}

echo '<br>';
echo "--- After Render ---------------<br>";

