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

// ToDO: get somewhere
$rowLimit = 3;

//$isDisplaySlideshow   = $rsgConfig->get('displaySlideshow') && $kid->itemCount() > 1;
$isDisplayOwner       = $rsgConfig->get('showGalleryOwner');
$isDisplaySize        = $rsgConfig->get('showGallerySize');
$isDisplayDate        = $rsgConfig->get('showGalleryDate');
$isDisplayIncludeKids = $rsgConfig->get('includeKids', true);



foreach ($galleries as $idx=>$gallery)
{
    //echo "<h4>" . $idx . ': ' . ($idx  % $rowLimit) . "</h4>";
    //echo "<h4>" . $idx . ': ' . $gallery->name . "</h4>";

    $altImage = 'No pictures in gallery';
    if($gallery->imgCount > 0)
    {
        $altImage = $gallery->UrlThumbFile;
    }

    $publishedAddition = $gallery->published ? "" : " system-unpublished";

    // ToDo: Gallery status
    //      $galleryStatus = $gallery->status;
    $galleryStatus = "";

    $thumbAlt = "";

    $HasNewImagesText = "";
    if ($gallery->IsHasNewImages)
    {
        $HasNewImagesText = '<sup>' . JText::_('COM_RSGALLERY2_NEW') . '</sup>';
    }


    echo '<div class="rsg_galleryblock' . $publishedAddition . '">';
    echo '   <div class="rsg2-galleryList-status"/>' . $galleryStatus . '</div>';
	echo '   <div class="rsg2-galleryList-thumb">';
	echo '      <div class="img-shadow">';
	echo '         <a href="/Joomla3xRelease/index.php/galleries-overview/gallery/' . $gallery->id . '">';
	echo '            <img  class="rsg2-galleryList-thumb"  src="' . $gallery->UrlThumbFile . '" alt="' . $thumbAlt . '" />';
	echo '         </a>';
	echo '      </div>';
	echo '    </div>';
	echo '    <div class="rsg2-galleryList-text">';
    echo '           ' . $gallery->name;
    echo '        <span class="rsg2-galleryList-newImages">';
	echo '            ' . $HasNewImagesText;
	echo '        </span>';
	echo '        <div class="rsg_gallery_details">';
	echo '            <div class="rsg2_details">';
    echo '            ' . JText::_('COM_RSGALLERY2_OWNER_DBLPT') . $gallery->OwnerName . '<br />';
    echo '						Size: ' . $gallery->imgCount . ' ' . JText::_('COM_RSGALLERY2_IMAGES') . '<br />';
    echo '              ' . JText::_('COM_RSGALLERY2_CREATED') . JHTML::_("date", $gallery->date, JText::_('COM_RSGALLERY2_DATE_FORMAT_LC3')) . '<br />';
    echo '				</div>';
    echo '			</div>';
    echo '			<div class="rsg2-galleryList-description">	' .  $gallery->description . '		</div>';
    echo '		</div>';
    echo '		<div class="rsg_sub_url_single">';
    echo '		</div>';
    echo '	</div>';


}

?>
