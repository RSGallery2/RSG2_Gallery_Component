<?php
/**
 * @version       $Id $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2024 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

JHtml::_('jquery.framework'); // load jquery
//JHtml::_('jquery.ui'); // load jquery ui from Joomla
//$this->document->addScript(JURI::root(true).'/components/com_mycomponent/assets/jquery.ui.slider.min.js'); // load *same version* widget code from jQuery UI archive
// https://code.google.com/p/jquery-ui/downloads/detail?name=jquery-ui-1.8.23.zip&can=2&q=

global $mainframe;

$doc = JFactory::getDocument();
$doc->addStyleSheet("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
$css1 = JURI::base() . 'components/com_rsgallery2/templates/slideshowone/css/slideshowone.css';
$doc->addStyleSheet($css1);
$css1 = JURI::base() . 'components/com_rsgallery2/templates/slideshowone/css/user.css';
if(file_exists($css1))
{
	$doc->addStyleSheet($css1);
}

$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/slideshowone/js/slideshowone.js';
$doc->addScript($jsScript);


//--- slideshow parameter --------------------------

// change if defined in params.ini file

$this->slideOptions ['isAutoStart'] = $this->params->get('isAutoStart', True);
$this->slideOptions ['effectType'] = $this->params->get('effectType', 23);
$this->slideOptions ['transitionTime'] = $this->params->get('transitionTime', '1.5');
$this->slideOptions ['displayTime'] = $this->params->get('displayTime', '4.0');
/* Not used
$this->slideOptions ['imgWidth'] = $this->params->get('imgWidth', 401);
$this->slideOptions ['imgHeight'] = $this->params->get('imgHeight', 401);
$this->slideOptions ['zoomWidth'] = $this->params->get('zoomWidth', 41);
$this->slideOptions ['zoomHeight'] = $this->params->get('zoomHeight', 31);
/**/

// $this->slideOptions [''] = ;

$this->isDisplayButtons = $this->params->get('isDisplayButtons', $this->isDisplayButtons);
$this->isButtonsAbove = $this->params->get('isButtonsAbove', $this->isButtonsAbove);


$doc = JFactory::getDocument();
$doc->addScriptOptions('slideArray', $this->slideOptions);


//--- first image to show --------------------------

$firstItem = $this->gallery->getItem();
if ( ! empty($firstItem))
{
	$firstImage = $firstItem->display();
}

//--- buttons below or above slideshow --------------------------

function displayButtons ()
{
//	$html[] = '<div class="clearfix"></div>';
    $html[] = '<div class="PlayerIconArrayContainer">';
    $html[] = '    <div class="PlayerIconArray">';
    $html[] = '        <a name="btnStartSlide" class="PlayerIcon" href="javascript:;" onclick="startSS()" ontouchstart="startSS()">';
    $html[] = '            <i class="fa fa-play"></i>';
    $html[] = '        </a>';
    $html[] = '        <a name="btnStopSlide" class="PlayerIcon" href="javascript:;" onclick="stopSS()" ontouchstart="stopSS()">';
    $html[] = '            <i class="fa fa-stop"></i>';
    $html[] = '        </a>';
    $html[] = '        <a name="btnPrevSlide" class="PlayerIcon" href="javascript:;" onclick="prevSS()" ontouchstart="prevSS()">';
    $html[] = '            <i class="fa fa-backward"></i>';
    $html[] = '        </a>';
    $html[] = '        <a name="btnNextSlide" class="PlayerIcon" href="javascript:;" onclick="nextSS()" ontouchstart="nextSS()">';
    $html[] = '            <i class="fa fa-forward"></i>';
    $html[] = '        </a>';
    $html[] = '    </div>';
    $html[] = '</div>';

	$html = implode("\n", $html);;
	return $html;
}


//--- back link to gallery view --------------------------------------

//Show link only when menu-item is not a direct link to the slideshow
$input = JFactory::getApplication()->input;
$view  = $input->get('view', '', 'CMD');
if ($view !== 'slideshow')
{
	$menuId = $input->get('Itemid', null, 'INT');
	$gid = $this->gid;

	$html = [];

	$html[] = '<div style="float: right;">' ."\n"
		. '<a href="' .  JRoute::_('index.php?option=com_rsgallery2&Itemid=' . $menuId . '&gid=' . $gid) . '">'
		//. '<a href="#">'
		. JText::_('COM_RSGALLERY2_BACK_TO_GALLERY')
		. '</a>';
	$html[] = '</div>';

	echo implode("\n", $html);
}


echo '<div class="rsg2-slideshowone">';


//--- Gallery title --------------------------------------

if (True)
{
	echo '<h3>';
	echo '    <div style="text-align:center;font-size:24px;">';
	echo '        ' . $this->galleryName;
	echo '    </div>';
	echo '</h3>';
}

echo '<div class="rsg2-clr"></div>';

echo '	<form name="_slideShow">';

echo '		<input type="Hidden" name="currSlide" value="0">';
echo '		<input type="Hidden" name="delay">';

echo '		<div id="myGallery' . $this->gid . '" class="PlayerContainer">';

			if ($this->isDisplayButtons && $this->isButtonsAbove)
			{
				// echo '		test 01';
				echo displayButtons();
			}
//echo '		test 02';
            echo '<img name="stage" class="PlayerImage" src="' . $firstImage->url() . '" style="filter: revealtrans(); font-size:12px;">';
//echo '		test 03';

            if ($this->isDisplayButtons && ! $this->isButtonsAbove)
			{
				// echo '		test 04';
				echo displayButtons ();
			}

echo '	</div>';

echo '	<div style="visibility:hidden;">';
echo '		<select name="wichIm" onchange="selected(this.options[this.selectedIndex].value)"></select>';
echo '	</div>';

echo '</form>';

echo '</div>'; // rsg2-slideshowone
