<?php
/**
 * @version       $Id $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

JHtml::_('behavior.framework', true);  // ToDo: Remove mootools

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
$this->slideOptions ['imgWidth'] = $this->params->get('imgWidth', 401);
$this->slideOptions ['imgHeigth'] = $this->params->get('imgHeigth', 401);
$this->slideOptions ['zoomWidth'] = $this->params->get('zoomWidth', 41);
$this->slideOptions ['zoomHeigth'] = $this->params->get('zoomHeigth', 31);

// $this->slideOptions [''] = ;

$this->isDisplayButtons = $this->params->get('isDisplayButtons', $this->isDisplayButtons);
$this->isButtonsAbove = $this->params->get('isButtonsAbove', $this->isButtonsAbove);


$doc = JFactory::getDocument();
$doc->addScriptOptions('slideArray', $this->slideOptions);


//--- first image to show --------------------------

$firstItem = $this->gallery->getItem();
$firstImage = $firstItem->display();

//--- buttons below or above slideshow --------------------------

function displayButtons ()
{
	$html[] = '<div class="clearfix"></div>';
    $html[] = '<div class="PlayerIconArrayContainer">';
    $html[] = '    <div class="PlayerIconArray">';
    $html[] = '        <a class="PlayerIcon" href="javascript:;" onclick="startSS()">';
    $html[] = '            <i class="fa fa-play"></i>';
    $html[] = '        </a>';
    $html[] = '        <a class="PlayerIcon" href="javascript:;" onclick="stopSS()">';
    $html[] = '            <i class="fa fa-stop"></i>';
    $html[] = '        </a>';
    $html[] = '        <a class="PlayerIcon" href="javascript:;" onclick="prevSS()">';
    $html[] = '            <i class="fa fa-backward"></i>';
    $html[] = '        </a>';
    $html[] = '        <a class="PlayerIcon" href="javascript:;" onclick="nextSS()">';
    $html[] = '            <i class="fa fa-forward"></i>';
    $html[] = '        </a>';
    $html[] = '    </div>';
    $html[] = '</div>';

	$html = implode("\n", $html);;
	return $html;
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
