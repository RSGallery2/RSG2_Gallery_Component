<?php
/**
 * @version       $Id $
 * @package       RSGallery2
 * @copyright (C) 2019 - 2019 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

global $rsgConfig;

//Show My Galleries link (if user is logged in (user id not 0))
if ($rsgConfig->get('show_mygalleries') AND (JFactory::getUser()->id))
{
	echo $this->showRsgHeader();
}

//Show search box
$this->showSearchBox();


/**

// JHtml::_('behavior.framework', true);  // ToDo: Remove mootools

global $mainframe;

JHtml::_('jquery.ui');
//JHtml::_('jquery.ui', array('core', 'sortable'));
JHtml::_('jquery.framework'); // load jquery
JHtml::_('jquery.ui'); // load jquery ui from Joomla
//$this->document->addScript(JURI::root(true).'/components/com_mycomponent/assets/jquery.ui.slider.min.js'); // load *same version* widget code from jQuery UI archive
// https://code.google.com/p/jquery-ui/downloads/detail?name=jquery-ui-1.8.23.zip&can=2&q=



$doc = JFactory::getDocument();
$cssFile = JURI::base() . 'components/com_rsgallery2/templates/schuweb/css/bootstrap.min.css';
$doc->addStyleSheet($cssFile);
$cssFile = JURI::base() . 'components/com_rsgallery2/templates/schuweb/css/bootstrap-theme.min.css';
$doc->addStyleSheet($cssFile);
$cssFile = JURI::base() . 'components/com_rsgallery2/templates/schuweb/css/colorbox.css';
$doc->addStyleSheet($cssFile);
$cssFile = JURI::base() . 'components/com_rsgallery2/templates/schuweb/css/schuweb.css';
$doc->addStyleSheet($cssFile);

$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/schuweb/js/colorbox/jquery.colorbox.js';
$doc->addScript($jsScript);
//$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/schuweb/js/colorbox/jquery.colorbox-min.js';
//$doc->addScript($jsScript);

$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/schuweb/js/bootstrap.min.js';
$doc->addScript($jsScript);
$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/schuweb/js/modal.js';
$doc->addScript($jsScript);
$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/schuweb/js/schuweb_colorbox.js';
$doc->addScript($jsScript);


//--- slideshow parameter --------------------------

// change if defined in params.ini file

$this->slideOptions ['isAutoStart'] = $this->params->get('isAutoStart', True);
$this->slideOptions ['effectType'] = $this->params->get('effectType', 23);
$this->slideOptions ['transitionTime'] = $this->params->get('transitionTime', '1.5');
$this->slideOptions ['displayTime'] = $this->params->get('displayTime', '4.0');
/* Not used
$this->slideOptions ['imgWidth'] = $this->params->get('imgWidth', 401);
$this->slideOptions ['imgHeigth'] = $this->params->get('imgHeigth', 401);
$this->slideOptions ['zoomWidth'] = $this->params->get('zoomWidth', 41);
$this->slideOptions ['zoomHeigth'] = $this->params->get('zoomHeigth', 31);
/** /

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

<div class="rsg2-schuweb">

	<form name="_slideShow">

		<input type="Hidden" name="currSlide" value="0">
		<input type="Hidden" name="delay">

		<div class="PlayerContainer">
			<?php
			if ($this->isDisplayButtons && $this->isButtonsAbove)
			{
				echo displayButtons();
			}

//            echo '<img name="stage" class="PlayerImage" src="' . $firstImage->url() . '" style="filter: revealtrans(); font-size:12px;">';

            echo '<ul class="thumbnails">';
            foreach ($this->images as $image)
            {
	            echo '<li class="span<' . $this->image_grid_size . '">';
	            echo '<a href="' . $image['display'] . '" class="thumbnail group_images">';
                echo '                    <img src="' . $image['thumb'] . '" alt="">';
                echo '                </a>';
                echo '            </li>';
			}
            echo '</ul>';

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

/**/