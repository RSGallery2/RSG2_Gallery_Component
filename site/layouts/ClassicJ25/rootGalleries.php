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

echo "layout classic J25: rootGalleries: <br>";
/**
<!--button onclick="<?php echo $doTask; ?>" class="btn btn-small" data-toggle="collapse" data-target="#collapse-<?php echo $name; ?>"<?php echo $onClose; ?>>
<span class="icon-cog" aria-hidden="true"></span>
<?php echo $text; ?>
</button-->
/**/

// This displays the root gallery text 2

//$gallery = json_encode($displayData['item']);
//echo "Gallery: " . $gallery;
//$intro_text =  $rsgConfig->get("intro_text")

//<div class="intro_text"><h3><strong><?php echo $intro_text; ? ></strong></h3></div>

$galleries = $displayData['galleries'];

$rowLimit = 3;

foreach ($galleries as $idx=>$gallery)
{
    //echo "<h4>" . $idx . ': ' . ($idx  % $rowLimit) . "</h4>";
    //echo "<h4>" . $idx . ': ' . $gallery->name . "</h4>";
	$date = $gallery->date; // ToDO: format

    echo '<div class="rsg_galleryblock">';
    //echo '   <div class="rsg2-galleryList-status"/>' . $gallery->status . '</div>';
    echo '   <div class="rsg2-galleryList-status"/>' . '</div>';
	echo '   <div class="rsg2-galleryList-thumb">';
	echo '      <div class="img-shadow">';
	echo '         <a href="/Joomla3xRelease/index.php/galleries-overview/gallery/' . $gallery->id . '">';
	echo '            <img  class="rsg2-galleryList-thumb"  src="' . $gallery->UrlThumbFile . '" alt="No pictures in gallery" />';
	echo '         </a>';
	echo '       </div>';
	echo '    </div>';
	echo '    <div class="rsg2-galleryList-text">';
    echo "            ' . $gallery->name . 	<span class='rsg2-galleryList-newImages'>";
	echo '<sup/>';
	echo '</span>';
	echo '<div class="rsg_gallery_details">';
	echo '<div class="rsg2_details">';
    echo 'Owner: finnern						<br />';
    echo '						Size: ' . $gallery->imgCount . ' images						<br />';
    echo '						' . $date . '						<br />';
    echo '				</div>';
    echo '			</div>';
    echo '			<div class="rsg2-galleryList-description">	' .  $gallery->description . '		</div>';
    echo '		</div>';
    echo '		<div class="rsg_sub_url_single">';
    echo '		</div>';
    echo '	</div>';


}

?>
