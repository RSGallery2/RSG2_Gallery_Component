<?php
/**
 * @version       $Id $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

//JHtml::_('behavior.framework', true);  // load mootools ToDo: Remove mootools
JHtml::_('jquery.framework'); // load jquery
//JHtml::_('jquery.ui'); // load jquery ui from Joomla
//$this->document->addScript(JURI::root(true).'/components/com_mycomponent/assets/jquery.ui.slider.min.js'); // load *same version* widget code from jQuery UI archive
// https://code.google.com/p/jquery-ui/downloads/detail?name=jquery-ui-1.8.23.zip&can=2&q=

global $mainframe;

$doc = JFactory::getDocument();
$doc->addStyleSheet("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
$css1 = JURI::base() . 'components/com_rsgallery2/templates/slideshowone/css/slideshowone.css';
$doc->addStyleSheet($css1);

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

//--- Gallery title --------------------------------------

if (True)
{
	echo '<h3>';
	echo '    <div style="text-align:center;font-size:24px;">';
	echo '        ' . $this->galleryname;
	echo '    </div>';
	echo '</h3>';
}



?>

<div class="rsg2-slideshowone">

	<form name="_slideShow">

		<input type="Hidden" name="currSlide" value="0">
		<input type="Hidden" name="delay">

		<div class="PlayerContainer">
			<?php
			if ($this->isDisplayButtons && $this->isButtonsAbove)
			{
				echo displayButtons();
			}
            echo '<img name="stage" class="PlayerImage" src="' . $firstImage->url() . '" style="filter: revealtrans(); font-size:12px;">';

            if ($this->isDisplayButtons && ! $this->isButtonsAbove)
			{
				echo displayButtons ();
			}
			?>
		</div>

		<div style="visibility:hidden;">
			<select name="wichIm" onchange="selected(this.options[this.selectedIndex].value)"></select>
		</div>

	</form>


</div>
