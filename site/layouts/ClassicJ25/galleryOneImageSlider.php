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

// echo 'layout classic J25: gallery: <br>';
// on develop show open tasks if existing
//if (!empty ($Rsg2DevelopActive))
if (false)
{
    echo '<span style="color:red">'
        . 'Tasks: <br>'
        . '* Fix pagination<br>'
        . '* Add call to slideshow <br>'
        . '* !!! Sub galleries at the end !!!<br>'
        . '* slideshow button<br>'
        . '* Check original code again<br>'
        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
        . '</span><br><br>';
}

$gallery = $displayData['gallery'];
$pagination = $displayData['pagination'];
$images = $displayData['images'];
// ToDO: exit on no images with message on screen -> better message ....
if (count ($images) < 1)
{
    // Jtext:: ....
    echo "<br><br><h2>No images in gallery found</h2>h2><br><br><br>";
    return;
}

$image = $images [0];
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
$isDisplaySlideshow = $config->displaySlideshowImageDisplay;
$isThumbsShowName = $config->displayThumbsShowName;
$displayPaginationBarMode =$config->displayPaginationBarMode;

// Display none:0, Display both:1, Display top:2, Display bottom:3
const PAGINATION_MODE_NONE   = '0';
const PAGINATION_MODE_BOTH   = '1';
const PAGINATION_MODE_TOP    = '2';
const PAGINATION_MODE_BOTTOM = '3';

$isDisplayPaginationTop = false; 
$isDisplayPaginationBottom = false; 

switch ($displayPaginationBarMode)
{
    case PAGINATION_MODE_TOP:
        $isDisplayPaginationTop = true; 
    break;
    case PAGINATION_MODE_BOTH:
        $isDisplayPaginationTop = true; 
        $isDisplayPaginationBottom = true; 
    break;
    case PAGINATION_MODE_BOTTOM:
        $isDisplayPaginationBottom = true; 
    break;
}

/*---------------------------------------------------------------
    Header: search/pagination selector (images per page)
---------------------------------------------------------------*/

echo '<div class="rsg2">';

//---------------
echo '<div class="row inline">';


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
        <?php echo JText::_('COM_RSGALLERY2_SLIDESHOW'); ?>
    </a>
	<br />
    <?php
}

echo '<div class="rsg2-clr"></div>';

/*---------------------------------------------------------------
   image box ...
/*-------------------------------------------------------------*/

echo '<br>';

/*---------------------------------------------------------------
   top pagination
/*-------------------------------------------------------------*/

if ($isDisplayPaginationTop)
{
//	echo 'PaginationTop start -------------' . '<br>';
	//echo '<div colspan="10">';
    echo '    <div align="center">';
	echo          $pagination->getListFooter();
    echo '    </div>'; // center
	//echo '</div>';
//	echo 'PaginationTop end -------------' . '<br>';
}

/*---------------------------------------------------------------
   image
/*-------------------------------------------------------------*/

// ToDo: count image hits
// ToDO count gallery hits other view

$imageDisplayUrl = $image->UrlDisplayFile;
$imageOriginalUrl = $image->UrlOriginalFile;


/**/
echo '    <div class="rsg_sem_inl">';
echo '        <div class="rsg_sem_inl_dispImg">';
echo '            <table width="100%" cellspacing="0" cellpadding="0" border="0">';
echo '                <tbody>';
echo '                    <tr>';
echo '                        <td>';
echo '                            <h2 class="rsg2_display_name" align="center">' . htmlspecialchars(stripslashes($image->title), ENT_QUOTES) . '</h2>';
echo '                        </td>';
echo '                    </tr>';
echo '                    <tr>';
echo '                        <td>';
echo '                            <div align="center">';

switch ($config->imagePopupMode)
{
    // No popup
    case 0:
    {
        echo '                    <img class="rsg2-displayImage" src="' . $imageDisplayUrl . '" alt="' . $item->name. '" title="' . $item->name . '" / >';
		break;
	}
	// Normal popup
	case 1:
	{
        echo '                    <a href="' . $imageOriginalUrl . '" target="_blank" rel="noopener" >';
        echo '                        <img class="rsg2-displayImage" src="' . $imageDisplayUrl . '" alt="' . $image->name . '" title="' . $image->name . '" />';
        echo '                    </a>';
        break;
    }
    // Modal popup
    case 2:
    {
        // echo modal ....
        echo JHTML::_('behavior.modal');
        echo '                    <a class="modal" href="' . $imageOriginalUrl . '">';
        echo '                        <img class="rsg2-displayImage" src="' . $imageDisplayUrl . '" alt="' . $image->name . '" title="' . $image->name . '">';
        echo '                    </a>';

        /**
         * $doc = JFactory::getDocument();
         * $doc->addScriptDeclaration($jsModal);
         * /**/
        break;
    }
}

//echo '                                <a href="http://127.0.0.1/joomla3xRelease//images/rsgallery/original/Dia_1992_10_Nr001.jpg" target="_blank">';
//echo '                                    <img class="rsg2-displayImage" src="http://127.0.0.1/joomla3xRelease/images/rsgallery/display/Dia_1992_10_Nr001.jpg.jpg" alt="Dia_1992_10_Nr001.jpg" title="Dia_1992_10_Nr001.jpg">';
//echo '                                </a>';

echo '                            </div>'; // center
echo '                        </td>';
echo '                    </tr>';

echo '                    <tr>';
echo '                        <td>';
echo '                            <div class="rsg2-toolbar">';
echo '                                <a class="btn" id="download-image" '; //
echo '                                    href="' . $imageOriginalUrl . '" download';
echo '                                    title="' . JText::_("COM_RSGALLERY2_DOWNLOAD") . '"';
echo '                                    ';
echo '                                    role="button" ';
echo '                                    >';
echo '                                    <i class="icon-download"></i>';
echo '                                </a>';
/**
echo '                                <a id="back-to-top" href="#" class="btn btn-primary btn-lg" role="button"';
echo '                                    title="Click to return on the top page" ';
echo '                                    data-toggle="tooltip" data-placement="left">';
echo '                                    <span class="glyphicon glyphicon-chevron-up"></span>';
echo '                                </a>';
/**/

/**
echo '                                <a href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;task=downloadfile&amp;id=5&amp;Itemid=110">';
echo '                                    <img src="http://127.0.0.1/joomla3xRelease//components/com_rsgallery2/images/download_f2.png" alt="Download" width="20" height="20">';
echo '                                    <br><span style="font-size:smaller;">Download</span>';
echo '                                </a>';
/**/
echo '                            </div>';
//echo '                            <div class="rsg2-clr">&nbsp;</div>';
echo '                        </td>';
echo '			          </tr>';

echo '                </tbody>';
echo '            </table>';
echo '        </div>'; // rsg_sem_inl_dispImg
echo '    </div>'; // rsg_sem_inl

/**
a[download] {
    color: hsla(216, 70%, 53%, 1);
    text-decoration: underline;
}

a[download]::before {
    content: url('../icons/icon-download.svg');
    height: 1em;
  position: relative;
  top: 0.75em;
  right: 0.5em;
  width: 1em;
}

a[download]:hover,
a[download]:focus {
    text-decoration: none;
}
/**/

/*
<ul>
    <li>
        <a href="download/320" media="min-width: 320px">
            <img src="files/320.jpg" alt="">
        </a>
    </li>
    <li>
        <a href="download/1382" media="min-width: 1382px">
            <img src="files/1382.jpg" alt="">
        </a>
    </li>
</ul>
/**/





/**

echo '                    <tr>';
echo '                        <td>';
echo '                            <div class="rsg2-toolbar">';
echo '                                <a href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;task=downloadfile&amp;id=5&amp;Itemid=110">';
echo '                                    <img src="http://127.0.0.1/joomla3xRelease//components/com_rsgallery2/images/download_f2.png" alt="Download" width="20" height="20">';
echo '                                    <br><span style="font-size:smaller;">Download</span>';
echo '                                </a>';
echo '                            </div>';
				<div class="rsg2-clr">&nbsp;</div>
				</td>';
echo '			</tr>';
/**/

/**
 *
echo '		<div class="rsg_sem_inl_ImgDetails">';
echo '		<dl class="tabs" id="page_inline_tabs"><dt style="display:none;"></dt><dd style="display:none;"></dd><dt class="tabs page_inline_tabs_description open" style="cursor: pointer;"><span><h3><a href="javascript:void(0);">Description</a></h3></span></dt><dt class="tabs page_inline_tabs_voting closed" style="cursor: pointer;"><span><h3><a href="javascript:void(0);">Voting</a></h3></span></dt><dt class="tabs page_inline_tabs_comments closed" style="cursor: pointer;"><span><h3><a href="javascript:void(0);">Comments</a></h3></span></dt><dt class="tabs page_inline_tabs_exif closed" style="cursor: pointer;"><span><h3><a href="javascript:void(0);">EXIF</a></h3></span></dt></dl><div class="current"><dd class="tabs" style="display: block;">			<p class="rsg2_hits">Hits <span>1</span>';
echo '			</p>';
echo '			</dd><dd class="tabs" style="display: none;">Voting is disabled!</dd><dd class="tabs" style="display: none;">Commenting is disabled</dd><dd class="tabs" style="display: none;">		<div class="rsg2_exif_container">';
echo '			<table class="adminlist" border="1">';
echo '				<tbody><tr>';
echo '					<th>Setting</th>';
echo '					<th>Value</th>';
/**/

/**
echo '				</tr>';
echo '				<tr>';
echo '				    <td><span class="rsg2_label">FileName</span></td>';
echo '					<td>C:\xampp\htdocs\Joomla3xRelease/images/rsgallery/original/Dia_1992_10_Nr001.jpg</td>';
echo '				</tr>';
echo '				<tr>';
echo '				    <td><span class="rsg2_label">FileDateTime</span></td>';
echo '					<td>28-Sep-2018 12:23:59</td>';
echo '				</tr>';
echo '				<tr>';
echo '				<td><span class="rsg2_label">resolution</span></td>';
echo '				<td>2442x1588</td>';
echo '				</tr>';
echo '		    </tbody>';
echo '		</table>';
echo '	</div>';
/**/

echo '<br>';

/*---------------------------------------------------------------
   bottom pagination
/*-------------------------------------------------------------*/

if ($isDisplayPaginationBottom)
{
//    echo 'PaginationFooter start -------------' . '<br>';
//    echo '<div colspan="10">';
    echo '    <div align="center">';
    echo          $pagination->getListFooter();
    echo '    </div>'; // center
//    echo '</div>';
//    echo 'PaginationFooter end -------------' . '<br>';
}

echo '</div>'; // <div class="rsg2">

?>

