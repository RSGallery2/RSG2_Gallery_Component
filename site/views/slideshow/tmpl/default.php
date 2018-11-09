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


$SlideshowData = array(
	'gallery' => $this->gallery,
    'images' => $this->images,
    'pagination' => $this->pagination,
    'config' => $this->config, // front part of rsgallery config
//		''//,
//		''//,
);

//$layoutGallery = JLayoutHelper::render('.', $myLayoutData);
//echo "--- Before render ClassicJ25.rootGallery / One Gallery thumbs ---------------<br>";
//$layout = new JLayoutFile('ClassicJ25.gallery', null, array('debug' => true, 'client' => 1)); // , null, array('debug' => true, 'client' => 1, 'component' => 'com_tags')

//---  -------------------------------------

echo "--- Before Render ---------------<br>";

/*--------------------------------------------------------
   single gallery display
--------------------------------------------------------*/

//--- gallery display -------------------------------------

$layout = new JLayoutFile('ClassicJ25.slideshow');
echo $layout->render($SlideshowData);


echo '<br>';
echo "--- After Render ---------------<br>";

