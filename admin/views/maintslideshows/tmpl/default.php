<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

$doc = JFactory::getDocument();
// $doc->addStyleSheet(JUri::root() . '/administrator/components/com_rsgallery2/views/maintslideshows/css/maintslideshows.css');
$doc->addscript(JUri::root() . '/administrator/components/com_rsgallery2/views/maintslideshows/js/maintslideshows.js');
JHtml::_('bootstrap.tooltip');
//JHtml::_('jquery.framework', false);

global $Rsg2DebugActive;

// JHtml::_('formbehavior.chosen', 'select');


function tabHeader ($sliderName)
{
    // ? Sanitize name ?

    echo JHtml::_('bootstrap.addTab', 'slidersTab', 'tab_' . $sliderName, $sliderName); //, true);
}

function tabContent ($xmlFileInfo, $testForm)
{
	echo '<div class="well">';
	// $xmlFileInfo->name;
    //<h3><?php echo $this->item->title;</h3>

    // echo '<h3>Content: ' . $xmlFileInfo->name . '<h3>';
    //echo '<h4>Form Fields (XML Config part of templateDetails.xml -> J2.5++ style)</h4>';

	$sliderName = $xmlFileInfo->name;

	//--- add parameter values from xml file ------------------------

	$params = $xmlFileInfo->parameterValues; // Jregistry ?

	//--- show controls ------------------------

	// parameter input
    echo $testForm->renderFieldset('advanced');
	// button to submit the changed data
	slideshowSaveConfigParaButton ($xmlFileInfo->name);

	//--- show controls ------------------------

	echo '<hr>';
	echo '<h4>file params.ini:</h4>';

	// field inside xml file
	if ($params->exists('params'))
	{
		$params = $params->extract('params');
	}

	$parameterLines = $params->toString ('INI');
	// echo $parameterLines;

    echo '<div class="control-group">';

	echo '    <div class="control-label">';
	echo '        <label  id="params_ini_' . $sliderName . '-lbl" for="params_ini_' . $sliderName . '"  class="hasPopover" ';
	echo '           title="" ';
	echo '           data-original-title="params.ini file" ';
	echo '           data-content="Content of file params.ini in slideshow folder. Line structure parameter=\"values\"" ';
	echo '        >';
	echo 'params.ini content';
	echo '        </label>';
	echo '    </div>';
	echo '    <div class="controls">';
    echo '        <textarea id="params_ini_' . $sliderName . '" class="input-xxlarge" name="params_ini_' . $sliderName . '" rows="20">'. $parameterLines . '</textarea>';
	echo '    </div>';
    echo '</div>';

	// button to submit the changed data
	slideshowSaveConfigFileButton ($xmlFileInfo->name);

	// echo json_encode($xmlFileInfo) ;
	//echo $this->form->renderFieldset('regenerateGallerySelection');
    echo '</div>'; // well
}

function tabFooter ($sliderName)
{
	echo JHtml::_('bootstrap.endTab');
	//echo " //end tab " . $sliderName;
}

function slideshowSaveConfigParaButton ($sliderName)
{

	echo '<!-- Action button save config: ' . $sliderName . ' -->';
	echo '<div class="form-actions">';
	echo '    <button id="btnConfigPara_' . $sliderName . '" name="btnConfigPara" type="button" class="btn btn-primary"';
	//echo '        onclick="Joomla.submitbutton(\'maintslideshows.saveConfigParameter\')"';
	echo '    >';
	echo          JText::_('COM_RSGALLERY2_MAINT_SLIDESHOW_SAVE_CONFIG');
	echo '    </button>';
	echo '</div>';

}

function slideshowSaveConfigFileButton ($sliderName)
{

	echo '<!-- Action button save config file: ' . $sliderName . ' -->';
	echo '<div class="form-actions">';
	echo '    <button id="btnConfigFile_' . $sliderName . '" name="btnConfigFile" type="button" class="btn btn-primary"';
	//echo '        onclick="Joomla.submitbutton(\'maintslideshows.saveConfigFile\')"';
	echo '    >';
	echo          JText::_('COM_RSGALLERY2_MAINT_SLIDESHOW_SAVE_CONFIG_FILE');
	echo '    </button>';
	echo '</div>';

}

function SimpleXMLElement_append($parent, $child)
{
	// get all namespaces for document
	$namespaces = $child->getNamespaces(true);

	// check if there is a default namespace for the current node
	$currentNs = $child->getNamespaces();
	$defaultNs = count($currentNs) > 0 ? current($currentNs) : null;
	$prefix = (count($currentNs) > 0) ? current(array_keys($currentNs)) : '';
	$childName = strlen($prefix) > 1
		? $prefix . ':' . $child->getName() : $child->getName();

	// check if the value is string value / data
	if (trim((string) $child) == '') {
		$element = $parent->addChild($childName, null, $defaultNs);
	} else {
		$element = $parent->addChild(
			$childName, htmlspecialchars((string)$child), $defaultNs
		);
	}

	foreach ($child->attributes() as $attKey => $attValue) {
		$element->addAttribute($attKey, $attValue);
	}
	foreach ($namespaces as $nskey => $nsurl) {
		foreach ($child->attributes($nsurl) as $attKey => $attValue) {
			$element->addAttribute($nskey . ':' . $attKey, $attValue, $nsurl);
		}
	}

	// add children -- try with namespaces first, but default to all children
	// if no namespaced children are found.
	$children = 0;
	foreach ($namespaces as $nskey => $nsurl) {
		foreach ($child->children($nsurl) as $currChild) {
			SimpleXMLElement_append($element, $currChild);
			$children++;
		}
	}
	if ($children == 0) {
		foreach ($child->children() as $currChild) {
			SimpleXMLElement_append($element, $currChild);
		}
	}
}

?>

<div id="slidshow-edit" class="clearfix">
	<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=maintSlideshows'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

                <!--legend><?php echo JText::_('COM_RSGALLERY2_SLIDESHOWS_CONFIGURATION'); ?></legend-->

                <?php

                //---- show slideshow selection ---------------------------

                $formSlideshowSelection = $this->formSlideshowSelection;

                echo $formSlideshowSelection->renderFieldset('maintslideshows');

                //---- show slideshow parameters ---------------------------

                $xmlFileInfo = $this->slideConfigFile;

                $activeName = $xmlFileInfo->name;
                echo JHtml::_('bootstrap.startTabSet', 'slidersTab', array('active' => 'tab_' . $activeName));

                // forms fields could be extracted from templateDetails.xml file
                if ( ! empty ($xmlFileInfo->formFields))
                {
                    $sliderName = $xmlFileInfo->name;

                    // extract parameter
                    //tabHeader($sliderName);

                    tabContent($xmlFileInfo, $this->formSlide);

                    tabFooter($sliderName);
                }
                else
                {
	                echo '<br><br><h4>Slideshow has no parameters</h4>';
                }

                echo JHtml::_('bootstrap.endTabSet');

                ?>

                <input type="hidden" value="" name="task">
                <input type="hidden" value="<?php echo $sliderName; ?>" name="usedSlideshow">
                <input type="hidden" value="" name="paramsIniText">

				<?php

                echo JHtml::_('form.token');

                ?>

			</form>
		</div>
		<div id="loading"></div>
	</div>



</div>
