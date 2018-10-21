<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//JHtml::_('behavior.core');

//$doTask  = $displayData['doTask'];
 echo "layout classic J25: gallery: <br>";
/**
<!--button onclick="<?php echo $doTask; ?>" class="btn btn-small" data-toggle="collapse" data-target="#collapse-<?php echo $name; ?>"<?php echo $onClose; ?>>
	<span class="icon-cog" aria-hidden="true"></span>
	<?php echo $text; ?>
</button--> 
/**/

// This displays the gallery thumbs
//$images = json_encode($displayData['images']);
$images = $displayData['images'];
$pagination = $displayData['pagination'];

$i=1;

echo "Images count: " . count($images);


// $gallery = json_encode($displayData['item']);
$url = $gallery->url;
$thumb = $gallery->thumb;

// echo "Item: " . $Found;

?>
<div class=\"img-shadow\"><a href=\"" . $this->url . "\">" . galleryUtils::getThumb($this->get('id'), 0, 0, "") . "</a></div>
