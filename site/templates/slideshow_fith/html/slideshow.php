<?php
/**
 * @version       1.0
 * @package       RSGallery2 slideshoe_FiTh
 * @copyright (C) 2019 - 2019 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();


global $mainframe;

$doc = JFactory::getDocument();

/**
$doc->addStyleSheet("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
$css1 = JURI::base() . 'components/com_rsgallery2/templates/slideshowone/css/slideshowone.css';
$doc->addStyleSheet($css1);

$Script = "
    jQuery(document).ready(function($){
		// alert('test');
		prevSS();
		startSS();
    });
";
$doc->addScriptDeclaration($Script);
/**/

$cssSliders = JURI::base(true).'/components/com_rsgallery2/templates/slideshow_fith/css/slideshow_fith.css';
$doc->addStyleSheet($cssSliders);

$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/slideshow_fith/js/slideshow_fith.js';
$doc->addScript($jsScript);

/*--- ico moon sprite ---*/
$cssSliders = JURI::base(true).'/components/com_rsgallery2/templates/slideshow_fith/images/icoMoonSprite.css';
$doc->addStyleSheet($cssSliders);

$jsScript = JURI::base(true).'/components/com_rsgallery2/templates/slideshow_fith/images/svgxuse.js';
$doc->addScript($jsScript);


function slideButton ($iconClassId, $subClass) // icon-pause
{
	$html[] = '<svg class="icon ' . $iconClassId . ' carousel_button_fith ' . $subClass . '"';
	$html[] = 'xmlns:xlink="http://www.w3.org/1999/xlink"';
	$html[] = 'xmlns="http://www.w3.org/2000/svg"';
	$html[] = 'version="1.1"';
	$html[] = '>';
	$html[] = '    <use xlink:href="http://127.0.0.1/Joomla3xRelease/components/com_rsgallery2/templates/slideshow_fith/images/icoMoonSprite.svg#' . $iconClassId . '">';
	$html[] = '    </use>';
	$html[] = '</svg>';

	$html = implode("\n", $html);;
	return $html;
}





$firstImage = $this->gallery->getItem();
$firstImage = $firstImage->display();
/**/
?>

<div class="rsg2-slideshow_fith">

	<form name="_slideShow">
        <?php
        //Show link only when menu-item is not a direct link to the slideshow
        $input = JFactory::getApplication()->input;
        $view  = $input->get('view', '', 'CMD');
        if ($view !== 'slideshow')
        {
            $menuId = $input->get('Itemid', null, 'INT');
            $gid = $this->gid;

            $html = [];

            $html[] = '<div style="float: right;">' ."\n"
                //. '<a href="' .  JRoute::_('index.php?option=com_rsgallery2&Itemid=' . $menuId . '&gid=' . $gid) . '">'
                    . '<a href="#XXX">'
                        . JText::_('COM_RSGALLERY2_BACK_TO_GALLERY')
                        . '</a>';
                    $html[] = '</div>';

            echo implode("\n", $html);
        }
        ?>

        <input type="Hidden" name="currSlide" value="0" / >
		<input type="Hidden" name="delay" />

		<div class="slideshow_fith_content">
			<h3>
				<div style="text-align:center;font-size:24px;">
					<?php echo $this->galleryname; ?>
				</div>
			</h3>
			<div class="rsg2-clr"></div>
			<div id="myGallery<?php echo $this->gid; ?>" class="myGallery">
				<?php echo $this->slides; ?>
			</div><!-- end myGallery -->
		</div><!-- End slideshow_fith_content -->

        <div class="rsg2-clr"></div>
        <div class="clearfix"></div>
        <div class="row">
		<?php
		echo slideButton('icon-play2', 'play');
		echo slideButton('icon-stop', 'stop');
		echo slideButton('icon-previous', 'back');
		echo slideButton('icon-next', 'next');
		echo slideButton('icon-backward', 'backward');
		echo slideButton('icon-forward2', 'forward');
        ?>
        </div>

    </form>


</div>
