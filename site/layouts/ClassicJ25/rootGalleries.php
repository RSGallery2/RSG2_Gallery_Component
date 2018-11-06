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

// echo "layout classic J25: rootGalleries: <br>";

// on develop show open tasks if existing
//if (!empty ($Rsg2DevelopActive))
if (false)
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

//$isDisplaySlideshow   = $config->displaySlideshow && $kid->itemCount() > 1;
$isDisplayOwner       = $config->showGalleryOwner;
$isDisplaySize        = $config->showGallerySize;
$isDisplayDate        = $config->showGalleryDate;
$isDisplayIncludeKids = $config->includeKids;
$rootGalleriesCount   = $config->rootGalleriesCount;

$doc          = JFactory::getDocument();
$doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css");

$template_dir = JURI_SITE . "/components/com_rsgallery2/templates/" . $config->template;
$doc->addStyleSheet($template_dir . "/css/template.css", "text/css");

echo '<div class="rsg2">';

echo '<div class="row inline">';


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



//--- limit box ----------------------------------------

if ($isDisplayLimitBox)
{

	echo '<div class="btn-group pull-right hidden-phone">';
	echo '   <label for "limit" class="element-invisible">';
	echo '      ' . JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');
	echo '   </label>';
	echo '   ' . $pagination->getlimitBox();
	echo '</div>';
}

//--- search box ----------------------------------------

if (true)
{
//echo '<div align="right" class="j25search_box">';
	echo '<div class="j25search_box pull-right">';
	echo '	<form name="rsg2_search" class="form-search form-inline warning" method="post" action="' . JRoute::_('index.php') . '" >';
	echo '   <div class="input-prepend">';
	echo '            <button type="submit" class="btn">Search</button>';
	echo '            <input type="search" name="searchtextX"  maxlength="200"';
	echo '                   class="inputbox search-query input-medium"';
	echo '                   placeholder="' . JText::_('COM_RSGALLERY2_KEYWORDS') . '">';
	echo '        </div>';
	echo '        <input type="hidden" name="option" value="com_rsgallery2" />';
	echo '        <input type="hidden" name="rsgOption" value="search" />';
	echo '        <input type="hidden" name="task" value="showResults" />';
	echo '	</form>';
	echo '</div>';
}

//--- title ----------------------------------------

if (true)
{
	$title = "Root galleries"; // ToDo: give it a source in config/Menu
	echo '<div class="rsg2-title">';
	echo '<h2>' . $title . '</h2>';
	echo '</div>';
}

// end of row
echo '</div>';

//--- root gallery title -------------------------------------

$intro_text = $config->intro_text;
if (strlen($intro_text) > 0)
{
	echo '<div class="intro_text"><?php echo $intro_text; ?></div>';
}


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
/*y*/	echo '       <div class="rsg_gallery_details">';
	echo '          <div class="rsg2_details">';
    echo '            ' . JText::_('COM_RSGALLERY2_OWNER_DBLPT') . $gallery->OwnerName . '<br />';
    echo '						Size: ' . $gallery->imgCount . ' ' . JText::_('COM_RSGALLERY2_IMAGES') . '<br />';
    echo '              ' . JText::_('COM_RSGALLERY2_CREATED') . JHTML::_("date", $gallery->date, JText::_('COM_RSGALLERY2_DATE_FORMAT_LC3')) . '<br />';
    echo '			</div>';
    echo '		</div>';
    echo '		<div class="rsg2-galleryList-description">	' /* yyyy */ .  $gallery->description . '		</div>';
    echo '		</div>';
    echo '		<div class="rsg_sub_url_single">';
    echo '		</div>';

//    echo '	       </div>';
//    echo '	   </div>';
    echo '</div>';

}

echo 'ListFooter start -------------' . '<br>';
echo '<div colspan="10">';
echo $pagination->getListFooter();
echo '</div>';
echo 'ListFooter end -------------' . '<br>';

echo '</div>'; // <div class="rsg2">

?>
