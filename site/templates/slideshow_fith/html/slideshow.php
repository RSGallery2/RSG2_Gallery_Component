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
$firstImage = $this->gallery->getItem();
$firstImage = $firstImage->display();
/**/
?>

<div class="rsg2-slideshowone">

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

        <input type="Hidden" name="currSlide" value="0">
		<input type="Hidden" name="delay">

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

	</form>


</div>
