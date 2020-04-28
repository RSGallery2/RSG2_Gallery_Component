<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 * @copyright   (C) 2017-2020 RSGallery2 Team
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
//$isDisplaySlideshow   = $config->displaySlideshow && $kid->itemCount() > 1;
$isDisplayOwner       = $config->showGalleryOwner;
$isDisplaySize        = $config->showGallerySize;
$isDisplayDate        = $config->showGalleryDate;
$isDisplayIncludeKids = $config->includeKids;
$rootGalleriesCount   = $config->rootGalleriesCount;
/**/

/**
$doc          = JFactory::getDocument();
$doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css");

$template_dir = JURI_SITE . "/components/com_rsgallery2/templates/" . $config->template;
$doc->addStyleSheet($template_dir . "/css/template.css", "text/css");
/**/

//echo 'ListFooter start -------------' . '<br>';
echo '<div colspan="10">';
echo $pagination->getListFooter();
echo '</div>';
//echo 'ListFooter end -------------' . '<br>';

echo '</div>'; // <div class="rsg2">

?>
