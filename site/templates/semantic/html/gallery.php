<?php
/**
 * RSGallery2 root or single gallery view
 *
 * @package       RSGallery2
 * @copyright (C) 2003 - 2023 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

global $rsgConfig, $Rsg2DevelopActive, $isDebugSiteActive;

// Access parameter from params.ini (example). defined in template.xml
//echo('<!-- using template parameter: testParameter = ' . $this->params->get('testParameter') . ' -->');

if ($isDebugSiteActive)
{
	JLog::add('semantic:::gallery.php -> template: ' . $rsgConfig->get('template') . ' gallery view ');
}


if($Rsg2DevelopActive)
{
	// ToDo: auto class name ...
//	echo '<div style="float:left;"><strong>[' . 'schuweb' . ' gallery view]</strong></div>';
//	echo '<div style="float:left;"><strong>[' . <template/class name> . ' gallery view]</strong></div>';
//	echo '<div style="float:left;"><strong>[' . $rsgConfig->get('template') . ' gallery view]</strong></div><br>';
}

// Show My Galleries link (if user is logged in (user id not 0))
if ($rsgConfig->get('show_mygalleries') AND (JFactory::getUser()->id))
{
	// in meta
	echo $this->showRsgMyGalleryHeader();
}

// Max item count selector
$this->showNavLimitBox ($this->pageNav);

// Show search box
$this->showSearchBox();

// Show gallery title
// Single gallery ? (not Root gallery ?)
if ($this->gallery->id != 0)
{
// ToDo: params.ini ...
	if ($rsgConfig->get('displayGalleryName'))
	{
		$this->showGalleryName($this->gallery);
	}
}

// Show gallery or root description
if ($rsgConfig->get ('displayGalleryDescription') || $this->gallery->id == 0)
{
	$this->showGalleryDescription($this->gallery);
}

//--- root gallery boxes with thumbs ----------------------------------

foreach ($this->kids as $kid)
{
	$published = "";
	if ($kid->published)
	{
		$published = " system-unpublished";
	}

	echo '<div class="rsg_galleryblock' . $published . '">';
	echo '    <div class="rsg2-galleryList-status">' . $kid->status . '</div>';
	echo '    <div class="rsg2-galleryList-thumb">';
	echo          $kid->thumbHTML;
	echo '    </div>';
	echo '    <div class="rsg2-galleryList-text">';
	echo          $kid->galleryName;
	echo '	      <span class="rsg2-galleryList-newImages">';
	if ($this->gallery->hasNewImages())
	{
		echo '        <sup>';
		echo              JText::_('COM_RSGALLERY2_NEW');
		echo '        </sup>';
	}
	echo '		  </span>';
	echo          $this->_showGalleryDetails($kid);
	echo '		  <div class="rsg2-galleryList-description">' . stripslashes($kid->description) . '</div>';
	echo '    </div>';
	echo '    <div class="rsg_sub_url_single">';
	              $this->_subGalleryList($kid);
	echo '    </div>';
	echo '</div>';
}


echo '<div class="rsg2-clr"></div>';

//--- Root gallery navigation ----------------------------------

if ($this->pageNav->total)
{
	echo '<div class="pagination">';
	echo       $this->pageNav->getPagesLinks();
	//echo '	   <br />';
	//echo       $this->pageNav->getResultsCounter();

	echo '</div>';
	echo '<div class="rsg2-clr"></div>';
}

//--- Random and latest images ----------------------------------

// Show random and latest only in the top gallery
// Root gallery ?
if ($this->gallery->id == 0)
{
	// Show block with random images
	$this->showImages("random", 3);
	// Show block with latest images
	$this->showImages("latest", 3);
}


