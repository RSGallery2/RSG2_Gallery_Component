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

$document = JFactory::getDocument();
$document->addStyleSheet("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
$css1 = JURI::base() . 'components/com_rsgallery2/templates/slideshowone/css/slideshowone.css';
$document->addStyleSheet($css1);

$Script = "
    jQuery(document).ready(function($){
		// alert('test');
		prevSS();
		startSS();
    });
";
$document->addScriptDeclaration($Script);

//--- slideshow parameter --------------------------

// change if defined in params.ini file

$this->isAutoStart = $this->params->get('isAutoStart', $this->isAutoStart);
$this->isDisplayButtons = $this->params->get('isDisplayButtons', $this->isDisplayButtons);
$this->isButtonsAbove = $this->params->get('isButtonsAbove', $this->isButtonsAbove);

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
				echo '<br>';
				echo '<br>';

				echo displayButtons ();
			}
			?>
		</div>

		<div style="visibility:hidden;">
			<select name="wichIm" onchange="selected(this.options[this.selectedIndex].value)"></select>
		</div>

	</form>


	<script type="text/javascript">
		//<!--
		/*
		 SlideShow. Written by PerlScriptsJavaScripts.com
		 Copyright http://www.perlscriptsjavascripts.com
		 Code page http://www.perlscriptsjavascripts.com/js/slideshow.html
		 Free and commercial Perl and JavaScripts
		 */

		effect = 23;// transition effect. number between 0 and 23, 23 is random effect
		duration = 1.5;// transition duration. number of seconds effect lasts
		display = 4;// seconds to display each image?
		oW = 400;// width of stage (first image)
		oH = 400;// height of stage
		zW = 40;// zoom width by (add or subtracts this many pixels from image width)
		zH = 30;// zoom height by

		// path to image/name of image in slide show. this will also preload all images
		// each element in the array must be in sequential order starting with zero (0)
		SLIDES = new Array();
		//Echo JS-array from DB-query here

		<?php echo $this->slides;?>

        //alert (JSON.stringify(SLIDES) );
        console.log(JSON.stringify(SLIDES))
		// end required modifications

		S = new Array();
		for (a = 0; a < SLIDES.length; a++) {
			S[a] = new Image();
			S[a].src = SLIDES[a][0];
		}

		// form
		f = document._slideShow;
		// index
		n = 0;
		// time
		t = 0;

		//document.images["stage"].width  = oW;
		//document.images["stage"].height = oH;
		f.delay.value = display;

		function startSS() {
			t = setTimeout("runSS(" + f.currSlide.value + ")", 1 * 1);
		}

		function runSS(n) {
			n++;
			if (n >= SLIDES.length) {
				n = 0;
			}

			document.images["stage"].src = S[n].src;
			if (document.all && navigator.userAgent.indexOf("Opera") < 0 && navigator.userAgent.indexOf("Windows") >= 0) {
				document.images["stage"].style.visibility = "hidden";
				document.images["stage"].filters.item(0).apply();
				document.images["stage"].filters.item(0).transition = effect;
				document.images["stage"].style.visibility = "visible";
				document.images["stage"].filters(0).play(duration);
			}
			f.currSlide.value = n;
			t = setTimeout("runSS(" + f.currSlide.value + ")", f.delay.value * 1000);
		}

		function stopSS() {
			if (t) {
				t = clearTimeout(t);
			}
		}

		function nextSS() {
			stopSS();
			n = f.currSlide.value;
			n++;
			if (n >= SLIDES.length) {
				n = 0;
			}
			if (n < 0) {
				n = SLIDES.length - 1;
			}
			document.images["stage"].src = S[n].src;
			f.currSlide.value = n;
			if (document.all && navigator.userAgent.indexOf("Opera") < 0 && navigator.userAgent.indexOf("Windows") >= 0) {
				document.images["stage"].style.visibility = "hidden";
				document.images["stage"].filters.item(0).apply();
				document.images["stage"].filters.item(0).transition = effect;
				document.images["stage"].style.visibility = "visible";
				document.images["stage"].filters(0).play(duration);
			}
		}

		function prevSS() {
			stopSS();
			n = f.currSlide.value;
			n--;
			if (n >= SLIDES.length) {
				n = 0;
			}
			if (n < 0) {
				n = SLIDES.length - 1;
			}
			document.images["stage"].src = S[n].src;
			f.currSlide.value = n;

			if (document.all && navigator.userAgent.indexOf("Opera") < 0 && navigator.userAgent.indexOf("Windows") >= 0) {
				document.images["stage"].style.visibility = "hidden";
				document.images["stage"].filters.item(0).apply();
				document.images["stage"].filters.item(0).transition = effect;
				document.images["stage"].style.visibility = "visible";
				document.images["stage"].filters(0).play(duration);
			}
		}

		function selected(n) {
			stopSS();
			document.images["stage"].src = S[n].src;
			f.currSlide.value = n;

			if (document.all && navigator.userAgent.indexOf("Opera") < 0 && navigator.userAgent.indexOf("Windows") >= 0) {
				document.images["stage"].style.visibility = "hidden";
				document.images["stage"].filters.item(0).apply();
				document.images["stage"].filters.item(0).transition = effect;
				document.images["stage"].style.visibility = "visible";
				document.images["stage"].filters(0).play(duration);
			}
		}

		function zoom(dim1, dim2) {
			if (dim1) {
				if (document.images["stage"].width < oW) {
					document.images["stage"].width = oW;
					document.images["stage"].height = oH;
				} else {
					document.images["stage"].width += dim1;
					document.images["stage"].height += dim2;
				}
				if (dim1 < 0) {
					if (document.images["stage"].width < oW) {
						document.images["stage"].width = oW;
						document.images["stage"].height = oH;
					}
				}
			} else {
				document.images["stage"].width = oW;
				document.images["stage"].height = oH;
			}
		}

		// start slideshow right once dom is ready (uses mootools)

		Window.onDomReady(function () {
			runSS(f.currSlide.value);
		});

		// -->
	</script>

	<?php if ($this->isAutoStart): ?>
		<script type="text/javascript">
			startSS();
		</script>
	<?php endif; ?>

</div>
