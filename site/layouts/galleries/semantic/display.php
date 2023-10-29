<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 * @copyright   (C) 2017-2023 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

global $rsgConfig, $isDevelopSiteActive;

//JHtml::_('behavior.core');

// echo "layout classic J25: rootGalleries: <br>";

// on develop show open tasks if existing
//if (!empty ($Rsg2DevelopActive))
if ($isDevelopSiteActive)
{
    echo '<span style="color:red">'
        . 'Tasks: <br>'
        . '* <br>'
        . '* <br>'
        . '* <br>'
        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
        . '</span><br><br>';
}

// Part of general rsgConfig
$galleries = $displayData['galleries'];
$pagination = $displayData['pagination'];
$config = $displayData['config'];

/**
//$isDisplaySlideshow   = $rsgConfig->get('displaySlideshow') && $kid->itemCount() > 1;
$isDisplayOwner       = $rsgConfig->get('showGalleryOwner');
$isDisplaySize        = $rsgConfig->get('showGallerySize');
$isDisplayDate        = $rsgConfig->get('showGalleryDate');
$isDisplayIncludeKids = $rsgConfig->get('includeKids', true);
/**/

$isDisplaySlideshow   = $config->displaySlideshow; // && $kid->itemCount() > 1;
$isDisplayOwner       = $config->showGalleryOwner;
$isDisplaySize        = $config->showGallerySize;
$isDisplayDate        = $config->showGalleryDate;

$isDisplayDate        = $config->showGalleryDate;


$isDisplayIncludeKids = $config->includeKids;
$rootGalleriesCount   = $config->rootGalleriesCount;

$doc          = JFactory::getDocument();
/**
$doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css");
/**/

$template_dir = JURI_SITE . "/components/com_rsgallery2/templates/" . $config->template;
$doc->addStyleSheet($template_dir . "/css/template.css", "text/css");

foreach ($galleries as $idx=>$gallery)
{
    //echo "<h4>" . $idx . ': ' . ($idx  % $rootGalleriesCount) . "</h4>";
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
    $imageHref = JRoute::_("index.php?option=com_rsgallery2&view=gallery&gid=" . $gallery->id);

    $HasNewImagesText = "";
    if ($gallery->IsHasNewImages)
    {
        $HasNewImagesText = '<sup>' . JText::_('COM_RSGALLERY2_NEW') . '</sup>';
    }


    echo '<div class="rsg_galleryblock' . $publishedAddition . '">';
//    echo '<div class="container-fluid">';
//    echo '   <div class="row-fluid">';
//    echo '      <div class="span2">';

    echo '   <div class="rsg2-galleryList-status"/>' . $galleryStatus . '</div>';
	echo '   <div class="rsg2-galleryList-thumb">';
	echo '      <div class="img-shadow">';
	echo '         <a href="' . $imageHref . '">';
	echo '            <img  class="rsg2-galleryList-thumb"  src="' . $gallery->UrlThumbFile . '" alt="' . $thumbAlt . '" />';
	echo '         </a>';
	echo '      </div>';
	echo '    </div>';
	echo '    <div class="rsg2-galleryList-text">';
    echo '           ' . $gallery->name;
    echo '       <span class="rsg2-galleryList-newImages">';
	echo '            ' . $HasNewImagesText;
	echo '       </span>';
	echo '       <div class="rsg_gallery_details">';
	echo '          <div class="rsg2_details">';
    if($isDisplaySlideshow && $gallery->imgCount > 0) {
        echo '            <a href="' . JRoute::_("index.php?option=com_rsgallery2&page=slideshow&gid=" . $gallery->id) . '">' . JText::_('COM_RSGALLERY2_SLIDESHOW') . '</a>';
	}
    if($isDisplayOwner) {
        echo '            ' . JText::_('COM_RSGALLERY2_OWNER_DBLPT') . $gallery->OwnerName . '<br />';
    }
	if($isDisplaySize) {
        echo '						' .   JText::_('COM_RSGALLERY2_SIZE_DBLPT') . ' ' . $gallery->imgCount . ' ' . JText::_('COM_RSGALLERY2_IMAGES') . '<br />';
    }
	if($isDisplayDate) {
        echo '              ' . JText::_('COM_RSGALLERY2_CREATED') . JHTML::_("date", $gallery->date, JText::_('COM_RSGALLERY2_DATE_FORMAT_LC3')) . '<br />';
    }
    echo '			</div>'; // rsg_gallery_details
    echo '		</div>';
    echo '		<div class="rsg2-galleryList-description">	' /* yyyy */ .  $gallery->description . '		</div>';
    echo '		</div>';
    echo '		<div class="rsg_sub_url_single">';
    echo '		</div>';

//    echo '	       </div>';
//    echo '	   </div>';
    echo '</div>';

}

?>
