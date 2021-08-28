<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

$doc = JFactory::getDocument();
$doc->addscript(JUri::root() . '/administrator/components/com_rsgallery2/views/maintslideshows/js/maintslideshows.js');

JHtml::_('bootstrap.tooltip');

global $Rsg2DebugActive;

/**
 * tabHeader
 * Prepared for view where all slideshows are displayed side by side
 *
 * @param string $sliderName
 *
 * @since 4.4.2
 */
function tabHeader ($sliderName='')
{
    //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'tab_' . $sliderName, $sliderName); //, true);
}

/**
 * tabContent
 * Displays the form fields of the slideshow
 * and in an text area the parameter
 * definitions with values name="value"
 *
 * @param $formSlide
 * @param $testForm
 *
 *
 * @since 4.4.2
 */
function tabContent ($formSlide, $testForm)
{
	$sliderName = $formSlide->name;

	// following Prepared for view where all slideshows are displayed side by side
	//echo '<div class="well">';
    //echo '<h3>Slideshow: ' . $formSlide->name . '<h3>';

	//--- show slideshow form fields ------------------------

    echo JHtml::_('bootstrap.startAccordion', 'slide_slideshow_parameters',
        array('active' => 'parameters_id_1'));

    echo JHtml::_('bootstrap.addSlide', 'slide_slideshow_parameters',
        JText::_('COM_RSGALLERY2_SLIDESHOW_PARAMETER'), 'parameters_id_1');

    if ( ! empty ($formSlide->formFields))
    {
	    // display fields
	    echo $testForm->renderFieldset('advanced');

	    // button to submit the changed data
	    createSaveParameterButton($sliderName);

	    echo JHtml::_('bootstrap.endSlide');
	    echo JHtml::_('bootstrap.endAccordion');

	    //--- show slideshow parameter as text ------------------------

	    $params = $formSlide->parameterValues; // Jregistry

	    // field inside xml file
	    if ($params->exists('params'))
	    {
		    $params = $params->extract('params');
	    }

	    // One line for each parameter: name="value"
	    $parameterLines = $params->toString('INI');


	    echo JHtml::_('bootstrap.startAccordion', 'slide_slideshow_file',
		    array('active' => 'file_id_1'));

	    echo JHtml::_('bootstrap.addSlide', 'slide_slideshow_file',
		    JText::_('COM_RSGALLERY2_FILE') . ' params.ini', 'file_id_1');

	    echo '<div class="control-group">';

	    echo '    <div class="control-label">';
	    echo '        <label  id="params_ini_' . $sliderName . '-lbl" for="params_ini_' . $sliderName . '"  class="hasPopover" ';
	    echo '           title="" ';
	    echo '           data-original-title="' . JText::_('COM_RSGALLERY2_FILE') . ' params.ini' . '"';
	    echo '           data-content="' . JText::_('COM_RSGALLERY2_MAINT_SLIDESHOW_PARAMS_INI_CONTENT') . '"';
	    echo '        >';
	    echo          JText::_('COM_RSGALLERY2_CONTENT') . ' params.ini';
	    echo '        </label>';
	    echo '    </div>';
	    echo '    <div class="controls">';
	    echo '        <textarea id="params_ini_' . $sliderName . '" class="input-xxlarge" name="params_ini_' . $sliderName . '" rows="20">'
                           . $parameterLines . '</textarea>';
	    echo '    </div>';
	    echo '</div>';

	    // button to submit the changed data
	    createSaveParamsIniFileButton($sliderName);
    }
    else
    {
	    echo '<br><br><h4>Slideshow <strong>"' . $sliderName . '"</strong> has no parameters</h4><br><br>';
    }

	echo '<div class="control-group">';

	echo '    <div class="control-label">';
	echo '        <label  id="user_css_' . $sliderName . '-lbl" for="user_css_' . $sliderName . '"  class="hasPopover" ';
	echo '           title="" ';
	echo '           data-original-title="' . JText::_('COM_RSGALLERY2_FILE') . ' user.css' . '"';
	echo '           data-content="' . JText::_('COM_RSGALLERY2_MAINT_SLIDESHOW_USER_CSS_CONTENT') . '"';
	echo '        >';
	echo          JText::_('COM_RSGALLERY2_CONTENT') . ' user.css';
	echo '        </label>';
	echo '    </div>';
	echo '    <div class="controls">';
	echo '        <textarea id="user_css_' . $sliderName . '" class="input-xxlarge" name="user_css_' . $sliderName . '" rows="20">'
                        . $formSlide->userCssText. '</textarea>';
	echo '    </div>';
	echo '</div>';

	// button to submit the changed data
	createSaveUserCssFileButton($sliderName);

	echo JHtml::_('bootstrap.endSlide');
    echo JHtml::_('bootstrap.endAccordion');

	// following Prepared for view where all slideshows are displayed side by side
    //echo '</div>'; // well
}

/**
 * tabFooter
 * Prepared for view where all slideshows are displayed side by side
 *
 * @param $sliderName
 *
 *
 * @since 4.4.2
 */
function tabFooter ($sliderName='')
{
	//echo JHtml::_('bootstrap.endTab');
	//echo " //end tab " . $sliderName;
}

/**
 * createSaveParameterButton
 * Displays button to save the slideshow parameters
 *
 * @param string $sliderName
 *
 * @since 4.4.2
 */
function createSaveParameterButton ($sliderName='')
{

	echo '<!-- Action button save config: ' . $sliderName . ' -->';
	echo '<div class="form-actions">';
	echo '    <button id="btnConfigPara_' . $sliderName . '" name="btnConfigPara" type="button" class="btn btn-primary"';
	echo '    >';
	echo          JText::_('COM_RSGALLERY2_MAINT_SAVE_PARAMETER');
	echo '    </button>';
	echo '</div>';

}

/**
 * createSaveParamsIniFileButton
 * Displays button to save the slideshow parameters
 * from text area definition
 *
 * @param $sliderName
 *
 * @since 4.4.2
 */
function createSaveParamsIniFileButton ($sliderName='')
{

	echo '<!-- Action button save config file: ' . $sliderName . ' -->';
	echo '<div class="form-actions">';
	echo '    <button id="btnConfigFile_' . $sliderName . '" name="btnConfigFile" type="button" class="btn btn-primary"';
	echo '    >';
	echo          JText::_('COM_RSGALLERY2_MAINT_SAVE_PARAM_FILE');
	echo '    </button>';
	echo '</div>';

}

/**
 * createSaveUserCssFileButton
 * Displays button to save the slideshow parameters
 * from text area definition
 *
 * @param $sliderName
 *
 * @since 4.4.2
 */
function createSaveUserCssFileButton ($sliderName='')
{

	echo '<!-- Action button save config file: ' . $sliderName . ' -->';
	echo '<div class="form-actions">';
	echo '    <button id="btnUserCssFile_' . $sliderName . '" name="btnUserCssFile" type="button" class="btn btn-primary"';
	echo '    >';
	echo          JText::_('COM_RSGALLERY2_MAINT_SAVE_USER_CSS_FILE');
	echo '    </button>';
	echo '</div>';

}

//============================================================
// Form
//============================================================
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

                //---- show slideshow parameter fields and as name="value" ---------------------------

                $slideshowData = $this->slideshowData;

                //$activeName = $slideshowData->name;
                $sliderName = $slideshowData->name;

                // following Prepared for view where all slideshows are displayed side by side
                //echo JHtml::_('bootstrap.startTabSet', 'slidersTab', array('active' => 'tab_' . $activeName));

                // forms fields could be extracted from templateDetails.xml file
                //if ( ! empty ($slideshowData->formFields))
                //{
	                // following Prepared for view where all slideshows are displayed side by side
                    //tabHeader($sliderName);

                tabContent($slideshowData, $this->formSlide);

	                // following Prepared for view where all slideshows are displayed side by side
                    //tabFooter($sliderName);
                //}
                //

                //tabContentUserCss ($slideshowData)

                // following Prepared for view where all slideshows are displayed side by side
                //echo JHtml::_('bootstrap.endTabSet');

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
