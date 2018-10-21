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
echo "--- Before render image overview ---------------<br>";
//$layout = new JLayoutFile('ClassicJ25.gallery', null, array('debug' => true, 'client' => 1)); // , null, array('debug' => true, 'client' => 1, 'component' => 'com_tags')
//$layout = new JLayoutFile('ClassicJ25.gallery');
//echo $layout->render($galleryData);

$displayData['images'] = $this->images;
$displayData['pagination'] = $this->pagination;

// Use simulation of old J25
if ($this->useJ25Views) {
    $layout = new JLayoutFile('ClassicJ25.galleryThumbs');
    echo $layout->render($displayData);
}
else {
    $layout = new JLayoutFile('galleryThumbs');
    echo $layout->render($displayData);
}

echo "--- After Render ---------------<br>";

