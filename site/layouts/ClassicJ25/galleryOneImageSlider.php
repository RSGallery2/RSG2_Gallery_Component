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

$imageColumns = $config->thumbsColPerPage;

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
        <?php echo JText::_('COM_RSGALLERY2_SLIDESHOW'); ?></a>
	<br />
    <?php
}

echo '<div class="rsg2-clr"></div>';

/*---------------------------------------------------------------
   image box ...
/*-------------------------------------------------------------*/

echo '<br>';


<div class="rsg2">
		<a href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;page=slideshow&amp;gid=2&amp;Itemid=110">
		Slideshow</a>
	<br>

<div class="rsg_sem_inl">
		<div class="rsg_sem_inl_Nav">
			</div>
		<div class="rsg_sem_inl_dispImg">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tbody><tr>
				<td>
					<h2 class="rsg2_display_name" align="center">Dia_1992_10_Nr001</h2>
				</td>
			</tr>
			<tr>
				<td>
					<div align="center">
								<a href="http://127.0.0.1/joomla3xRelease//images/rsgallery/original/Dia_1992_10_Nr001.jpg" target="_blank">
			<img class="rsg2-displayImage" src="http://127.0.0.1/joomla3xRelease/images/rsgallery/display/Dia_1992_10_Nr001.jpg.jpg" alt="Dia_1992_10_Nr001.jpg" title="Dia_1992_10_Nr001.jpg">
		</a>
							</div>
				</td>
			</tr>
			<tr>
				<td><div class="rsg2-toolbar">				<a href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;task=downloadfile&amp;id=5&amp;Itemid=110">
					<img src="http://127.0.0.1/joomla3xRelease//components/com_rsgallery2/images/download_f2.png" alt="Download" width="20" height="20">
											<br><span style="font-size:smaller;">Download</span>
										</a>
				</div><div class="rsg2-clr">&nbsp;</div></td>
			</tr>
		</tbody></table>
			</div>
		<div class="rsg_sem_inl_Nav">
				<div align="center">
			<div class="pagination">
				<nav role="navigation" aria-label="Pagination"><ul class="pagination-list"><li><a title="Start" href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=110&amp;gid=2&amp;limitstart=0" class="pagenav" aria-label="Go to start page"><span class="icon-first" aria-hidden="true"></span></a></li><li><a title="Prev" href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=110&amp;gid=2&amp;limitstart=0" class="pagenav" aria-label="Go to prev page"><span class="icon-previous" aria-hidden="true"></span></a></li><li class="hidden-phone"><a title="1" href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=110&amp;gid=2&amp;limitstart=0" class="pagenav" aria-label="Go to page 1">1</a></li><li class="active hidden-phone"><a aria-current="true" aria-label="Page 2">2</a></li><li class="hidden-phone"><a title="3" href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=110&amp;gid=2&amp;limitstart=2" class="pagenav" aria-label="Go to page 3">3</a></li><li class="hidden-phone"><a title="4" href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=110&amp;gid=2&amp;limitstart=3" class="pagenav" aria-label="Go to page 4">4</a></li><li class="hidden-phone"><a title="5" href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=110&amp;gid=2&amp;limitstart=4" class="pagenav" aria-label="Go to page 5">...</a></li><li><a title="Next" href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=110&amp;gid=2&amp;limitstart=2" class="pagenav" aria-label="Go to next page"><span class="icon-next" aria-hidden="true"></span></a></li><li><a title="End" href="/joomla3xRelease/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=110&amp;gid=2&amp;limitstart=4" class="pagenav" aria-label="Go to end page"><span class="icon-last" aria-hidden="true"></span></a></li></ul></nav>			</div>
		</div>
			</div>
		<div class="rsg_sem_inl_ImgDetails">
		<dl class="tabs" id="page_inline_tabs"><dt style="display:none;"></dt><dd style="display:none;"></dd><dt class="tabs page_inline_tabs_description open" style="cursor: pointer;"><span><h3><a href="javascript:void(0);">Description</a></h3></span></dt><dt class="tabs page_inline_tabs_voting closed" style="cursor: pointer;"><span><h3><a href="javascript:void(0);">Voting</a></h3></span></dt><dt class="tabs page_inline_tabs_comments closed" style="cursor: pointer;"><span><h3><a href="javascript:void(0);">Comments</a></h3></span></dt><dt class="tabs page_inline_tabs_exif closed" style="cursor: pointer;"><span><h3><a href="javascript:void(0);">EXIF</a></h3></span></dt></dl><div class="current"><dd class="tabs" style="display: block;">			<p class="rsg2_hits">Hits <span>1</span>
			</p>
			</dd><dd class="tabs" style="display: none;">Voting is disabled!</dd><dd class="tabs" style="display: none;">Commenting is disabled</dd><dd class="tabs" style="display: none;">		<div class="rsg2_exif_container">
			<table class="adminlist" border="1">
				<tbody><tr>
					<th>Setting</th>
					<th>Value</th>
				</tr>
										<tr>
							<td><span class="rsg2_label">FileName</span></td>
							<td>C:\xampp\htdocs\Joomla3xRelease/images/rsgallery/original/Dia_1992_10_Nr001.jpg</td>
						</tr>
												<tr>
							<td><span class="rsg2_label">FileDateTime</span></td>
							<td>28-Sep-2018 12:23:59</td>
						</tr>
												<tr>
							<td><span class="rsg2_label">resolution</span></td>
							<td>2442x1588</td>
						</tr>
									</tbody></table>
		</div>
		</dd></div>	</div>
	<div class="rsg_sem_inl_footer">
				<div id="rsg2-footer">
			<br><br>com_rsgallery2 4.4.1<br>(c) 2005-2018 RSGallery2 Team		</div>
		<div class="rsg2-clr">&nbsp;</div>
			</div>
</div></div>



echo '<br>';

/*---------------------------------------------------------------
   footer pagination
/*-------------------------------------------------------------*/

echo 'ListFooter start -------------' . '<br>';
echo '<div colspan="10">';
echo $pagination->getListFooter();
echo '</div>';
echo 'ListFooter end -------------' . '<br>';

echo '</div>'; // <div class="rsg2">


?>

