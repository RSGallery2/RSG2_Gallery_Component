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
        . '* !!! slideshow button only if image count ... also in other<br>'
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

//--- definition data --------------------------------------

$galleryId = $gallery->id;
$isDisplayGalleryName = true; // $config->; // ToDo: reserve in config,  name
$isDisplayGalleryDescription = true; // $config->; // ToDo: reserve in config, name
$isDisplaySlideshow = $config->displaySlideshowImageDisplay;
$isThumbsShowName = $config->displayThumbsShowName;
$displayPaginationBarMode =$config->displayPaginationBarMode;

$isDisplayDesc = $config->displayDesc;
$isDisplayVoting = $config->displayVoting;
$isDisplayComments = $config->displayComments;
$isDisplayEXIF = $config->displayEXIF;

$isDisplayImgHits = $config->isDisplayImgHits;


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

//--- include css --------------------------------------

$doc          = JFactory::getDocument();
$doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css");
$template_dir = JURI_SITE . "/components/com_rsgallery2/templates/" . $config->template;
$doc->addStyleSheet($template_dir . "/css/template.css", "text/css");

//Adding stylesheet for voting
if ($isDisplayVoting)
{
    $doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgvoting/rsgvoting.css");
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

/*---------------------------------------------------------------
   RSG part: image box ...
---------------------------------------------------------------*/

echo '<div class="rsg2-clr"></div>';

echo '<br>';

/*---------------------------------------------------------------
   top pagination
---------------------------------------------------------------*/

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
---------------------------------------------------------------*/

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
echo '                            </div>';
//echo '                            <div class="rsg2-clr">&nbsp;</div>';
echo '                        </td>';
echo '			          </tr>';

echo '                </tbody>';
echo '            </table>';
echo '        </div>'; // rsg_sem_inl_dispImg
echo '    </div>'; // rsg_sem_inl


echo '<br>';


/*---------------------------------------------------------------
   bottom pagination
---------------------------------------------------------------*/

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

/*---------------------------------------------------------------
   description / voting / comments / EXIF
---------------------------------------------------------------*/

$isDisplayImgDetails = false;

if ($isDisplayDesc || $isDisplayVoting = $config->displayVoting || $isDisplayComments || $isDisplayEXIF)
{
	$isDisplayImgDetails = true;
}

// Display image details
if ($isDisplayImgDetails)
{
    //--- active tab --------------------------------

	$isDisplayDescActive = '';
	$isDisplayVotingActive = '';
	$isDisplayCommentsActive = '';
	$isDisplayEXIFActive = '';

	if ($isDisplayDesc)
    {
	    $isDisplayDescActive = 'active';
    }
    else
    {
	    if ($isDisplayVoting)
	    {
		    $isDisplayVotingActive = 'active';
	    }
	    else
	    {
		    if ($isDisplayComments)
		    {
			    $isDisplayCommentsActive = 'active';
		    }
		    else
		    {
			    if ($isDisplayEXIF)
			    {
				    $isDisplayEXIFActive = 'active';
			    }
		    }
	    }
    }

    echo '<div class="well">';

    echo '    <div class="tabbable">'; // <!-- Only required for left/right tabs -->
    echo '        <ul class="nav nav-tabs">';

    /*---------------------------------------------------------------
       tab headers
    ---------------------------------------------------------------*/

    //--- tab headers --------------------------------

    if ($isDisplayDesc)
    {
	    echo '        <li class="' . $isDisplayDescActive . '"><a href="#tabDesc" data-toggle="tab">' . JText::_('COM_RSGALLERY2_DESCRIPTION') . '</a></li>';
    }
    if ($isDisplayVoting)
    {
        echo '        <li class="' . $isDisplayVoting . '"><a href="#tabVote" data-toggle="tab">' . JText::_('COM_RSGALLERY2_VOTING') . '</a></li>';
    }
    if ($isDisplayComments)
    {
        echo '        <li class="' . $isDisplayCommentsActive . '"><a href="#tabComment" data-toggle="tab">' . JText::_('COM_RSGALLERY2_COMMENTS') . '</a></li>';
    }
    if ($isDisplayEXIF)
    {
        echo '        <li class="' . $isDisplayEXIFActive . '"><a href="#tabExif" data-toggle="tab">' . JText::_('COM_RSGALLERY2_EXIF') . '</a></li>';
    }
    echo '        </ul>';

    /*---------------------------------------------------------------
       tab content
    ---------------------------------------------------------------*/

    echo '        <div class="tab-content">';

    //--- image description --------------------------------

    if ($isDisplayDesc)
    {
	    echo '        <div class="tab-pane ' . $isDisplayDescActive . '" id="tabDesc">';
	    echo '            <div  class="page_inline_tabs_description" >';
	    // Hits
        if ($isDisplayImgHits)
        {
	        //echo '            <p class="rsg2_hits">' . JText::_('COM_RSGALLERY2_HITS') . '&nbsp;<span>' . $image->hits . '</span>';
            echo '            <dl class="dl-horizontal">';
            echo '                <dt>' . JText::_('COM_RSGALLERY2_HITS') . '</dt><dd>' . $image->hits . '</dd>';
            echo '            </dl>';
        }
        // Description
        echo '                <p class="rsg2_description">' . nl2br($image->descr) . '</p>';
	    echo '            </div>';
	    echo '        </div>';
    }

    //--- voting --------------------------------

    if ($isDisplayVoting)
    {
	    echo '        <div class="tab-pane ' . $isDisplayVoting . '" id="tabVote">';
	    echo '            <div  class="page_inline_tabs_voting" >';
        if ( ! empty ($image->votingData))
        {
            echo $this->htmlVotingData ($image->votingData);
        }
        else
        {
            echo '                <p>' . JText::_('COM_RSGALLERY2_VOTING_IS_DISABLED') . '</p>';
        }
        echo '            </div>';
	    echo '        </div>';
    }

    //--- comments --------------------------------

    if ($isDisplayComments)
    {
	    echo '        <div class="tab-pane' . $isDisplayCommentsActive . '" id="tabComment">';
	    echo '            <div  class="page_inline_tabs_comments" >';
        if ( ! empty ($image->Comments))
        {
            echo $this->htmlComments ($image->Comments);
        }
        else
        {
            echo '                <p>' . JText::_('COM_RSGALLERY2_COMMENTING_IS_DISABLED') . '</p>';
        }
	    echo '            </div>';
	    echo '        </div>';
    }

    //--- EXIF data --------------------------------

    if ($isDisplayEXIF)
    {
	    echo '        <div class="tab-pane' . $isDisplayEXIFActive . '" id="tabExif">';
	    echo '            <div  class="page_inline_tabs_exif" >';
	    //echo '                <p>Howdy, I\'m in Section 4.</p>';
        if ( ! empty ($image->exifData))
        {
            echo htmlExifData ($image->exifData);
        }
        else
        {
            // echo '                <p>' . JText::_('COM_RSGALLERY2_NO_EXIF_ITEM_SELECTED_') . '</p>';
        }
	    echo '            </div>';
	    echo '        </div>';
    }

    echo '        </div>'; // tab-content
    echo '    </div>'; // tabbable

    echo '</div>'; // well

    /**
     *
    echo '    <div class="rsg_sem_inl_ImgDetails">';
    echo '    	<dl class="tabs" id="page_inline_tabs">';
    echo '    		<dt style="display:none;"/>';
    echo '    		<dd style="display:none;"/>';
    echo '    		<dt class="tabs page_inline_tabs_description open" style="cursor: pointer;">';
    echo '    			<span>';
    echo '    				<h3>';
    echo '    					<a href="javascript:void(0);">Description</a>';
    echo '    				</h3>';
    echo '    			</span>';
    echo '    		</dt>';
    echo '    		<dt class="tabs page_inline_tabs_voting closed" style="cursor: pointer;">';
    echo '    			<span>';
    echo '    				<h3>';
    echo '    					<a href="javascript:void(0);">Voting</a>';
    echo '    				</h3>';
    echo '    			</span>';
    echo '    		</dt>';
    echo '    		<dt class="tabs page_inline_tabs_comments closed" style="cursor: pointer;">';
    echo '    			<span>';
    echo '    				<h3>';
    echo '    					<a href="javascript:void(0);">Comments</a>';
    echo '    				</h3>';
    echo '    			</span>';
    echo '    		</dt>';
    echo '    		<dt class="tabs page_inline_tabs_exif closed" style="cursor: pointer;">';
    echo '    			<span>';
    echo '    				<h3>';
    echo '    					<a href="javascript:void(0);">EXIF</a>';
    echo '    				</h3>';
    echo '    			</span>';
    echo '    		</dt>';
    echo '    	</dl>';
    echo '    	<div class="current">';
    echo '    		<dd class="tabs" style="display: block;">';
    echo '    			<p class="rsg2_hits">Hits <span>1</span>';';
    echo '    			</p>';';
    echo '    		</dd>';
    echo '    		<dd class="tabs" style="display: none;">Voting is disabled!</dd>';
    echo '    		<dd class="tabs" style="display: none;">Commenting is disabled</dd>';
    echo '    		<dd class="tabs" style="display: none;">';
    echo '    			<div class="rsg2_exif_container">';';
    echo '    				<table class="adminlist" border="1">';';
    echo '    					<tbody>';
    echo '    						<tr>';';
    echo '    							<th>Setting</th>';';
    echo '    							<th>Value</th>';';
    echo '    						</tr>';';
    echo '    						<tr>';';
    echo '    							<td>';
    echo '    								<span class="rsg2_label">FileName</span>';
    echo '    							</td>';';
    echo '    							<td>C:\xampp\htdocs\Joomla3xRelease/images/rsgallery/original/Dia_1992_10_Nr001.jpg</td>';';
    echo '    						</tr>';';
    echo '    						<tr>';';
    echo '    							<td>';
    echo '    								<span class="rsg2_label">FileDateTime</span>';
    echo '    							</td>';';
    echo '    							<td>28-Sep-2018 12:23:59</td>';';
    echo '    						</tr>';';
    echo '    						<tr>';';
    echo '    							<td>';
    echo '    								<span class="rsg2_label">resolution</span>';
    echo '    							</td>';';
    echo '    							<td>2442x1588</td>';';
    echo '    						</tr>';';
    echo '    					</tbody>';';
    echo '    				</table>';';
    echo '    			</div>';
    echo '    		</dd>';
    echo '    	</div>	';
    echo '    </div>';
     * /**/
}

echo '</div>'; // <div class="rsg2">

function htmlVotingData($votingData)
{
    $Html = [];

    $Html[] = "";

    return $Html;
}

function htmlComments ($comments)
{
    $Html = [];

    $Html[] = "";

    return $Html;
}

function htmlExifData ($exifData)
{
    $Html = [];

    $Html[] = "";

    $Html[] =  '        <div class="rsg2_exif_container">';
    $Html[] =  '            <dl class="dl-horizontal">';

    // user requested EXIF tags
    foreach ($exifData as $exifKey => $exifValue)
    {
        $Html[] =  '            <dt>' . $exifKey . '</dt><dd>' . $exifValue . '</dd>';
    }

    $Html[] =  '            </dl>';
    $Html[] =  '		</div>'; // rsg2_exif_container


    return implode("\n", $Html);
}


?>

