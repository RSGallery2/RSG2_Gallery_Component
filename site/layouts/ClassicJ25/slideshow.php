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
//if (!empty ($Rsg2DevelopActive))
if ($isDevelopSiteActive)
{
    echo '<span style="color:red">'
        . 'Tasks: <br>'
        . '* Fix pagination<br>'
        . '* <br>'
        . '* <br>'
        . '* <br>'
        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
        . '</span><br><br>';
}

$gallery = $displayData['gallery'];
$pagination = $displayData['pagination'];
$images = $displayData['images'];
$config = $displayData['config'];

$doc          = JFactory::getDocument();
if ($doc->getType() == 'html')
{
    $doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css");
}

$template_dir = JURI_SITE . "/components/com_rsgallery2/templates/" . $config->template;
$doc->addStyleSheet($template_dir . "/css/template.css", "text/css");



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

echo '<div class="rsg2-title">';
echo '<h2>' . $gallery->name . '</h2>';
echo '</div>';
//echo '</div>';
//

// end of row
echo '</div>';

//--- gallery text ----------------------------------------

echo '<div class="intro_text">';
//echo    $rsgConfig->get('intro_text');
echo    $gallery->description;
echo '</div>';

// echo '<hr>';

echo '<div class="rsg2-clr"></div>';

//$rowLimit = $rsgConfig->rowlimit;
$rowLimit = 3;

echo '<table id="rsg2-thumbsList" border="0">';
echo '   <tbody>';

foreach ($images as $idx=>$image)
{
	// Start new row
	if (($idx  % $rowLimit) == 0)
	{
		echo '      <tr>';
	}


    echo '<div class="parth_content">';

/**
	//Show link only when menu-item is a direct link to the slideshow
	$input = JFactory::getApplication()->input;
	//$view  = $input->get('view', '', 'CMD');
	if ($view !== 'slideshow')
	{
		 >
		<div style="float: right;">
			<a href="index.php?option=com_rsgallery2&Itemid=<?php
			// echo JRequest::getInt('Itemid');
			echo $input->get('Itemid', null, 'INT'); ?>&gid=<?php echo $this->gid; ?>">
				<?php echo JText::_('COM_RSGALLERY2_BACK_TO_GALLERY'); ?>
			</a>
		</div>
		< ? php
	}
	? >
	<div class="rsg2-clr"></div>
	<div style="text-align:center;font-size:24px;">
		< ?php echo $this->galleryname; ? >
	</div>
	<div class="rsg2-clr"></div>
	<div id="myGallery< ?php echo $this->gid; ? >" class="myGallery">
		< ? php echo $this->slides; ? >
	</div><!-- end myGallery -->
</div><!-- End parth_content -->
/**/	
//** --------------------------------------------
/**	
			$display = $item->display();
			$thumb   = $item->thumb();

			//Get maximum height/width of display images
			$imgSizes = getimagesize($display->filePath());
			if ($this->maxSlideshowWidth < $imgSizes[0])
			{
				$this->maxSlideshowWidth = $imgSizes[0];
			}
			if ($this->maxSlideshowHeight < $imgSizes[1])
			{
				$this->maxSlideshowHeight = $imgSizes[1];
			}

			//The subtitleSelector for jd.gallery.js is p. This interferes with any
			//p-tags in the item description. Changing p tag to div.descr tag works for Firefox
			//but not for IE (tested IE7). So removing p tags from item description:
			$search[]    = \'<p>\';
			$search[]    = \'</p>\';
			$replace     = \' \';
			$item->descr = str_replace($search, $replace, $item->descr);
			//$openImageLink = \'index.php?option=com_rsgallery2&page=inline&Itemid=\'.JRequest::getInt(\'Itemid\').\'&id=\'.$item->id;
			$input         = JFactory::getApplication()->input;
			$itemId        = $input->get(\'Itemid\', null, \'INT\');
			$openImageLink = \'index.php?option=com_rsgallery2&page=inline&Itemid=\' . $itemId . \'&id=\' . $item->id;

			$text .= "<div class=\"imageElement\">" .
				"<h3>$item->title</h3>" .
				"<p>$item->descr</p>" .
				"<a href=\"$openImageLink\" title=\"open image\" class=\"open\"></a>" .
				"<img src=\"" . $display->url() . "\" class=\"full\" />" .
				"<img src=\"" . $thumb->url() . "\" class=\"thumbnail\" />" .
				"</div>";
			$k++;
		}

	
	
	
	
	
	echo '			<td>';
	echo '				<div class="shadow-box">';
	echo '					<div class="img-shadow">';
	echo '						<a href="/Joomla3x/index.php/gallery-test/item/1925/asInline">';
	echo '                         <img src="' . $image->UrlThumbFile . '" alt="" />';
	echo '						</a>';
	echo '					</div>';
	echo '				</div>';
	echo '				<div class="rsg2-clr"></div>';
	echo '				<br>';
	echo '				<span class="rsg2_thumb_name">';
	echo '		           ' . $image->name;
	echo '				</span>';
	echo '				<br>';
	echo '				<i></i>';
	echo '			</td>';

	// Start new row
	if (($idx + 1) % $rowLimit == 0)
	{
		echo '      </tr>';
	}
/**/	
}
echo '   </tbody>';
echo '</table>';
echo '<br>';
echo '<br>';

//echo 'ListFooter start -------------' . '<br>';
echo '<div colspan="10">';
echo $pagination->getListFooter();
echo '</div>';
//echo 'ListFooter end -------------' . '<br>';

echo '</div>'; // <div class="rsg2">


?>

