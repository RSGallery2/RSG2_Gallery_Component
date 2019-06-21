<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017-2019 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();
JHtml::_('behavior.framework', true);  // load mootools ToDo: Remove mootools
JHtml::_('jquery.framework'); // load jquery

global $rsgConfig;

$document = JFactory::getDocument();

//Add stylesheets and scripts to header
//$css1 = "<link rel=\"stylesheet\" href=\"components/com_rsgallery2/templates/slideshow_phatfusion/css/slideshow.css\" type=\"text/css\" media=\"screen\" charset=\"utf-8\" />";
$css1 = JURI::base() . 'components/com_rsgallery2/templates/slideshow_phatfusion/css/slideshow.css';
$document->addStyleSheet($css1);

$js1 = JURI::base() . 'components/com_rsgallery2/templates/slideshow_phatfusion/js/backgroundSlider.js';
$document->addScript($js1);
$js2 = JURI::base() . 'components/com_rsgallery2/templates/slideshow_phatfusion/js/slideshow.js';
$document->addScript($js2);
$css1 = JURI::base() . 'components/com_rsgallery2/templates/slideshow_phatfusion/css/user.css';
if(file_exists($css1))
{
	$doc->addStyleSheet($css1);
}

//--- Override default CSS styles ---
// Add styles

$style = ''
	. '.slideshowContainer {'
	. 'border: 1px solid #ccc;'
	. 'width: 400px;'
	. 'height: 300px;'
	. 'margin-bottom: 5px;'
	. '}';
$document->addStyleDeclaration($style);

?>

<!-- show main slideshow screen -->
<div id="container">
	<h3><?php echo $this->galleryname; ?></h3>
	<div id="slideshowContainer" class="slideshowContainer"></div>
	<div id="thumbnails">
		<?php echo $this->slides; ?>
		<p>
			<a href="#" onclick="showSS.previous(); return false;">&lt;&lt; Previous</a> |
			<a href="#" onclick="showSS.play(); return false;">Play</a> |
			<a href="#" onclick="showSS.stop(); return false;">Stop</a> |
			<a href="#" onclick="showSS.next();return false;">Next &gt;&gt;</a>
		</p>
	</div>
	<!-- Set parameters for slideshow -->
	<script type="text/javascript">
        var showSS;
        jQuery(document).ready(function () {

            // window.addEvent('domready', function () {
            console.log("PHP: phatfusion: domready");

			var obj = {
				wait            : 3000,
				effect          : 'fade',
				duration        : 1000,
				loop            : true,
				thumbnails      : true,
				backgroundSlider: true,
				onClick         : function (i) {
                    console.log("PHP: onClick");
					alert(i)
				}
			};

            console.log("PHP: new slideshow");
            showSS = new SlideShow('slideshowContainer', 'slideshowThumbnail', obj);

            console.log("PHP: showSS.play 01.before");
            showSS.play();
            console.log("PHP: showSS.play 02.after");
		})
	</script>
</div><!-- end container -->
