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

echo '<div class="rsg2">';

$gallery = $displayData['gallery'];
echo '<hr>';
echo '<h3>' . $gallery->name . '</h3><br>';
echo '<div class="intro_text">';
echo    $rsgConfig->get('intro_text');
echo '</div>';
echo '<hr>';

echo '<div class="rsg2-clr"/>';

/**
$imagesJson = json_encode($displayData['images']);
echo "Images: " . $imagesJson;
/**/

$images = $displayData['images'];
//$rowLimit = $rsgConfig->rowlimit;
$rowLimit = 3;

echo '<table id="rsg2-thumbsList" border="0">';
echo '   <tbody>';

foreach ($images as $idx=>$image)
{
	// Start new row
	if (($idx  % $rowLimit) == 0)
	{
		echo '      <tr>' . '<br>';
	}

	echo '			<td>' . '<br>';
	echo '				<div class="shadow-box">' . '<br>';
	echo '					<div class="img-shadow">' . '<br>';
	echo '						<a href="/Joomla3x/index.php/gallery-test/item/1925/asInline">' . '<br>';
	echo '                         <img src="' . $image->UrlThumbFile . '" alt="" />' . '<br>';
	echo '						</a>' . '<br>';
	echo '					</div>' . '<br>';
	echo '				</div>' . '<br>';
	echo '				<div class="rsg2-clr"/>' . '<br>';
	echo '				<br>';
	echo '				<span class="rsg2_thumb_name">' . '<br>';
	echo '		           ' . $image->name;
	echo '				</span>' . '<br>';
	echo '				<br>';
	echo '			</td>' . '<br>';

	// Start new row
	if (($idx + 1) % $rowLimit == 0)
	{
		echo '      </tr>' . '<br>';
	}
}
echo '   </tbody>';
echo '</table>';
echo '<br>';

echo '</div>'; // <div class="rsg2">



?>

