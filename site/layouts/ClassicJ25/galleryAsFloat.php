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

global $rsgConfig, $isDevelopSiteActive;

//JHtml::_('behavior.core');

// echo 'layout classic J25: gallery: <br>';
// on develop show open tasks if existing
//if (!empty ($isDevelopSiteActive))
if ($isDevelopSiteActive)
{
    echo '<span style="color:red">'
        . 'Tasks: <br>'
        . '* Fix pagination (button for per page)<br>'
        . '* test links to single image in batch<br>'
        . '* image alt -> do escape of bad characters<br>'
        . '* pagination above/below<br>'
//        . '* <br>'
        . '</span><br><br>';
}

$gallery = $displayData['gallery'];
$pagination = $displayData['pagination'];
$images = $displayData['images'];
$config = $displayData['config'];

//--- include css --------------------------------------

$doc          = JFactory::getDocument();
$doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css");
$template_dir = JURI_SITE . "/components/com_rsgallery2/templates/" . $config->template;
$doc->addStyleSheet($template_dir . "/css/template.css", "text/css");

//--- definition data --------------------------------------

$galleryId = $gallery->id;

$isDisplayGalleryName = true; // $config->; // ToDo: reserve in config,  name
$isDisplayGalleryDescription = true; // $config->; // ToDo: reserve in config, name
$isDisplaySlideshow = $config->displaySlideshowGalleryView;
$isThumbsShowName = $config->displayThumbsShowName;
$floatDirection = $config->floatDirection;

/*---------------------------------------------------------------
    Header: search/pagination selector (images per page)
---------------------------------------------------------------*/

echo '<div class="rsg2">';

//---------------
echo '<div class="row inline">';

    //--- limit box ----------------------------------------

    echo '<div class="btn-group pull-right hidden-phone">';
    echo '   <label for "limit" class="element-invisible">';
    echo '      ' . JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');
    echo '   </label>';
    echo '   ' . $pagination->getlimitBox();
    echo '</div>';

    //--- search box ----------------------------------------

    //echo '<div align="right" class="j25search_box">';
    echo '<div class="j25search_box pull-right">';
    echo '	<form name="rsg2_search" class="form-search form-inline warning" method="post" action="' . JRoute::_('index.php') . '" >';
    echo '   <div class="input-prepend">';
    echo '            <button type="submit" class="btn">Search</button>';
    echo '            <input type="search" name="searchtextX"  maxlength="200"';
    echo '                   class="inputbox search-query input-medium"';
    echo '                   placeholder="'. JText::_('COM_RSGALLERY2_KEYWORDS') . '">';
    echo '        </div>';
    echo '        <input type="hidden" name="option" value="com_rsgallery2" />';
    echo '        <input type="hidden" name="rsgOption" value="search" />';
    echo '        <input type="hidden" name="task" value="showResults" />';
    echo '	</form>';
    echo '</div>';

//--- gallery name ----------------------------------------
if ($isDisplayGalleryName) {
    echo '<div class="rsg2-title">';
    echo '<h2>' . $gallery->name . '</h2>';
    echo '</div>';
    //echo '</div>';
    //
}

// end of row
echo '</div>';

//--- gallery description ----------------------------------------

if ($isDisplayGalleryDescription)
{
    echo '<div class="intro_text">';
    //echo    $rsgConfig->get('intro_text');
    echo    $gallery->description;
    echo '</div>';
}

if ($isDisplaySlideshow)
{
    ?>
	<a href='<?php echo JRoute::_("index.php?option=com_rsgallery2&page=slideshow&gid=" . $gallery->id); ?>'>
        <?php echo JText::_('COM_RSGALLERY2_SLIDESHOW'); ?></a>
	<br />
    <?php
}

echo '<div class="rsg2-clr"></div>';

/*---------------------------------------------------------------
   image boxes
/*-------------------------------------------------------------*/

echo '<ul id="rsg2-thumbsList">';

foreach ($images as $idx=>$image)
{
    echo '    <li style="float:' . $floatDirection . '"" >';
    echo '        <div class="img-shadow">';
    echo '            <a href="' . JRoute::_('index.php?option=com_rsgallery2&view=gallery'
                                            . '&gid=' . $galleryId
                                            . '&imgid=' . $image->id
                                            . '&layout=OneImageOfMany' ) . '">';
    echo '                <img src="' . $image->UrlThumbFile . '" '
                                . 'alt="' . $image->descr . '" />';
    echo '            </a>';
    echo '        </div>';
    echo '        <div class="rsg2-clr"></div>';
    echo '        <br>';
    if ($isThumbsShowName)
    {
        echo '        <span class="rsg2_thumb_name">';
        echo '		      ' . $image->name;
        echo '        </span>';
    }
    echo '    </li>';

}
echo '</ul>';

echo '<div class="rsg2-clr">&nbsp;</div>';

/*---------------------------------------------------------------
   footer pagination
/*-------------------------------------------------------------*/

//echo 'ListFooter start -------------' . '<br>';
echo '<div colspan="10">';
echo $pagination->getListFooter();
echo '</div>';
//echo 'ListFooter end -------------' . '<br>';

echo '</div>'; // <div class="rsg2">


?>

