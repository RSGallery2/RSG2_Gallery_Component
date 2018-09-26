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

// https://magazine.joomla.org/issues/issue-nov-2013/item/1590-jlayout-layouts-improvements-joomla-3-2

// $layout = new JLayoutFile('joomla.content.tags', null, array('component' => 'com_tags'));

//HTMLHelper::_('script', 'com_foo/script.js', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_foo/style.css', array('version' => 'auto', 'relative' => true));

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

$galleryData = array(
		'item' => $this->item
//       ,
//		'model' => $model
	);
//$layoutGallery = JLayoutHelper::render('.', $myLayoutData);
//echo "--- Before render ---------------<br>";
//$layout = new JLayoutFile('ClassicJ25.gallery', null, array('debug' => true, 'client' => 1)); // , null, array('debug' => true, 'client' => 1, 'component' => 'com_tags')
$layout = new JLayoutFile('ClassicJ25.gallery');
echo $layout->render($galleryData);
//echo "---After Render ---------------<br>";

