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
//$galleries = $displayData['galleries'];
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
$cfgDisplayLimitBox = $config->dispLimitBox;
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
		if ($pagination->total > (int) $config->rootGalleriesCount)
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
$isDisplaySearch = $config->displaySearch;
if ($isDisplaySearch)
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


?>
