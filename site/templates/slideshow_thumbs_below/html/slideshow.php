<?php
/**
 * @version       $Id $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2021 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

global $mainframe;

$doc = JFactory::getDocument();

//$css1 = JURI::base() . 'components/com_rsgallery2/templates/slideshow_thumbs_below/css/slideshow_thumbs_below.css';
//$doc->addStyleSheet($css1);
$css1 = JURI::base() . 'components/com_rsgallery2/templates/slideshow_thumbs_below/css/thumbs_below.css';
$doc->addStyleSheet($css1);

$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/slideshow_thumbs_below/js/coda-slider.1.1.1.pack.js';
$doc->addScript($jsScript);


//--- slideshow parameter --------------------------


/**
$myOptions = $document->getScriptOptions('mod_example');
// Change the value
$myOptions['colors'] = array('selector' => 'body', 'color' => 'green');
// Set new options
$document->addScriptOptions('mod_example', $myOptions);
/**/


// change if defined in params.ini file

/**
$this->isAutoStart = $this->params->get('isAutoStart', $this->isAutoStart);
$this->isDisplayButtons = $this->params->get('isDisplayButtons', $this->isDisplayButtons);
$this->isButtonsAbove = $this->params->get('isButtonsAbove', $this->isButtonsAbove);

//--- first image to show --------------------------

$firstItem = $this->gallery->getItem();
$firstImage = $firstItem->display();

//--- buttons below or above slideshow --------------------------

/**/






?>

<script type="text/javascript">
    /**/
    if (typeof jQuery != 'undefined') {
        // jQuery is loaded => print the version
//        alert(jQuery.fn.jquery);
    }
    else
    {
        alert("jQuery undefined");
    }
    /**/
</script>

<div class="rsg2-slideshow_thumbs_below">

	<form name="_slideShow">

        <div id="page-wrap">


        <div class="slider-wrap">
            <div id="main-photo-slider" class="csw">
                <div class="panelContainer">

                    <?php
                    echo $this->slides;
                    ?>

                </div>
            </div>


            <?php
            echo $this->thumbs;
            ?>

        </div>


	</form>



</div>
