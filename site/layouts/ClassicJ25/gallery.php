<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 * @copyright   (C) 2017-2018 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

global $rsgConfig;

//JHtml::_('behavior.core');

 echo "layout classic J25: gallery: <br>";
/**
<!--button onclick="<?php echo $doTask; ?>" class="btn btn-small" data-toggle="collapse" data-target="#collapse-<?php echo $name; ?>"<?php echo $onClose; ?>>
	<span class="icon-cog" aria-hidden="true"></span>
	<?php echo $text; ?>
</button--> 
/**/

/**
$galleryJson = json_encode($displayData['gallery']);
echo "Gallery: " . $galleryJson;
echo "<br>layout classic: gallery end <br>";
//echo "<br><br>";
//echo "<br>";
/**/

$gallery = $displayData['gallery'];
echo '<h3>' . $gallery->name . '</h3>';

/**/
$imagesJson = json_encode($displayData['images']);
echo "Images: " . $imagesJson;
/**/

$images = $displayData['images'];

foreach ($images as $image)
{
	echo "<h4>" . $image->name . "</h4>";
	
	echo '<img src="' . $image->UrlThumbFile . '" alt="" />';
	echo "<br>";
}

?>

