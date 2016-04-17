<?php
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined( '_JEXEC' ) or die();

JHtml::_('behavior.tooltip');

global $Rsg2DebugActive;

JHtml::_('formbehavior.chosen', 'select');


/**
 * @param $ImageData
 */
function DisplayImageDataTable ($ImagesData) {
/**
    // exit if no data given
    if (count($ImagesData) == 0)
    {
        echo JText::_('COM_RSGALLERY2_NO_INCONSISTENCIES_IN_DATABASE');
        return;
    }
/**/

// Header ----------------------------------

    echo '<table class="table table-striped table-condensed">';
    echo '    <caption>'.JText::_('COM_RSGALLERY2_CONSDB_NOTICE????').'</caption>';
    echo '    <thead>';
    echo '        <tr>';
    echo '            <th>' . 'Index checkbox'. '</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_FILENAME Image name').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_IN_BR_DATABASE').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_DISPLAY_BR_FOLDER').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_ORIGINAL_BR_FOLDER').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_THUMB_FOLDER').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_WATERMARK_FOLDER').'</th>';
    echo '            <th>'.'&nbsp; parent gallery'.'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_IMAGE').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_ACTION').'</th>';
    echo '        </tr>';
    echo '    </thead>';

        //--- data ----------------------------------

    echo '    <tbody>';

    $Idx = -1;
    foreach ($ImagesData as $ImageData) {
        $Idx += 1;

        echo '        <tr>';
        echo '            <td>' . 'Index checkbox'. '</td>';
        echo '            <td>' . $ImageData['imageName'] . '</td>';
        echo '            <td>' . $ImageData['IsImageInDatabase'] . '</td>';
        echo '            <td>' . $ImageData['IsDisplayImageFound'] . '</td>';
        echo '            <td>' . $ImageData['IsOrignalImageFound'] . '</td>';
        echo '            <td>' . $ImageData['IsThumbImageFound'] . '</td>';
        echo '            <td>' . $ImageData['IsWatermarkImageFound'] . '</td>';
        echo '            <td>' . $ImageData['ParentGalleryId'] . '</td>';
        echo '            <td>' . 'Image' . '</td>';
        echo '            <td>' . 'Buttons' . '</td>';
        echo '        </tr>';
    }
/**/	
    echo '    </tbody>';

    //--- footer ----------------------------------
    echo '</table>';

    return;
}





?>

<div id="installer-install" class="clearfix">
	<?php if (!empty( $this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
	        <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
    <?php else : ?>
		<div id="j-main-container">
	<?php endif;?>

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=maintConsolidateDB'); ?>"
				  method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" >

			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'consolidateDB')); ?>

				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'consolidateDB', JText::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE', true)); ?>

 					<fieldset class="regenerateImages">
						<legend><?php echo JText::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE'); ?></legend>

                        <!-- List info  -->
                        <div class="control-group">
                            <label for="zip_file" class="control-label"> </label>
                            <div class="controls">
                                <!--input type="text" id="zip_file" name="zip_file" class="span5 input_box" size="70" value="http://" /-->
                                <div class="span5">
                                    <strong><?php echo JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT');?></strong>
                                </div>
                            </div>
                        </div>



                        <!-- div class="control-group">
                            <label for="xxx" class="control-label"><?php echo JText::_('COM_RSGALLERY2_CONFIGURATION');?>:</label>
                            <div class="controls" class="span5">
                                <?php echo JText::sprintf('COM_RSGALLERY2_NEW_WIDTH_DISPLAY', $this->imageWidth)?>.
                                <?php echo JText::sprintf('COM_RSGALLERY2_NEW_WIDTH_THUMB', $this->thumbWidth)?>
                            </div>
                        </div-->

                        <!-- Action button -->
						<!--div class="form-actions">
							<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('maintRegenerate.RegenerateImagesDisplay')"><?php echo JText::_('COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY'); ?></button>
                            &nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('maintRegenerate.RegenerateImagesThumb')"><?php echo JText::_('COM_RSGALLERY2_MAINT_REGEN_THUMBS'); ?></button>
						</div-->
					</fieldset>
					
					<div class="span12">
						<div class="row-fluid">
							<div class="span4 clsInfoAccordion">
							 <?php
								// Info about last uploaded galleries
                                DisplayImageDataTable ($this->DisplayImageData);
							?>
							</div>
						</div>
					</div>
					<br>

                       <a href="index.php?option=com_rsgallery2&rsgOption=maintenance&task=consolidateDB">Refresh</a>



                <?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.endTabSet'); ?>

			<input type="hidden" value="" name="task">

			<?php echo JHtml::_('form.token'); ?>

           </form>
        </div>
	<div id="loading"></div>
</div>
</div>
