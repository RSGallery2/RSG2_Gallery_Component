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

$doc = JFactory::getDocument();
$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/ConsolidateDb.css");

/**
 * @param $ImageData
 */
function DisplayImageDataTable ($ImagesData) {

    // exit if no data given
    if (count($ImagesData) == 0)
    {
        echo '<h2>' . JText::_('COM_RSGALLERY2_NO_INCONSISTENCIES_IN_DATABASE').'</h2><br>';
        return;
    }

// Header ----------------------------------

    echo '<table class="table table-striped table-condensed">';
	// echo '    <caption>'.JText::_('COM_RSGALLERY2_MISSING_IMAGE_REFERENCES_LIST').'</caption>';
	echo '    <caption><h3>'.JText::_('COM_RSGALLERY2_MISSING_IMAGE_REFERENCES_LIST').'</h3></caption>';
    echo '    <thead>';
    echo '        <tr>';
//    echo '            <th>' . 'Index checkbox'. '</th>'; class="center" width="1%"

	echo '            <th>' . '<input class="hasTooltip" type="checkbox" onclick="Joomla.checkAll(this)" title="" value="" 
					name="checkall-toggle" data-original-title="Check All">';

    echo '            <th>'.JText::_('COM_RSGALLERY2_FILENAME').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_IN_BR_DATABASE').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_DISPLAY_BR_FOLDER').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_ORIGINAL_BR_FOLDER').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_THUMB_FOLDER').'</th>';
//    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_WATERMARK_FOLDER').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_PARENT_BR_GALLERY').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_IMAGE').'</th>';



//    echo '            <th>'.JText::_('COM_RSGALLERY2_ACTION').'</th>';
    echo '        </tr>';
    echo '    </thead>';

        //--- data ----------------------------------

    echo '    <tbody>';
/**/


// ToDo: Notify for icomoon images
//       alt="'.JText::_('COM_RSGALLERY2_IMAGE_IN_FOLDER').'"
//       alt="'.JText::_('COM_RSGALLERY2_IMAGE_NOT_IN_FOLDER').'"



    $Idx = -1;
    foreach ($ImagesData as $ImageData) {
        $Idx += 1;

        echo '        <tr>';
        //echo '            <td>' . 'Index checkbox'. '</td>';
	    echo '            <td>' .
		    '<input id="cb' . $Idx . '2" type="checkbox" onclick="Joomla.isChecked(this.checked);" value="'
		            . $Idx . '5" name="cid[]">';


        echo '            <td>' . $ImageData['imageName'] . '</td>';

	    // echo '            <td>' . $ImageData['IsImageInDatabase'] . '</td>';
	    if ($ImageData['IsImageInDatabase']) {
		    echo '<td class="center"><span class="icon-ok "> </span> </td>';
	    }
	    else
	    {
		    echo '<td class="center"><span class="icon-cancel "> </span> </td>';
	    }

	    //echo '            <td>' . $ImageData['IsDisplayImageFound'] . '</td>';
	    if ($ImageData['IsDisplayImageFound']) {
		    echo '<td class="center"><span class="icon-ok"> </span> </td>';
	    }
	    else
	    {
		    echo '<td class="center"><span class="icon-cancel"> </span> </td>';
	    }

	    //echo '            <td>' . $ImageData['IsOriginalImageFound'] . '</td>';
	    if ($ImageData['IsOriginalImageFound']) {
		    echo '<td class="center"><span class="icon-ok"> </span> </td>';
	    }
	    else
	    {
		    echo '<td class="center"><span class="icon-cancel"> </span> </td>';
	    }

	    //echo '            <td>' . $ImageData['IsThumbImageFound'] . '</td>';
	    if ($ImageData['IsThumbImageFound']) {
		    echo '<td class="center"><span class="icon-ok"> </span> </td>';
	    }
	    else
	    {
		    echo '<td class="center"><span class="icon-cancel"> </span></td>';
	    }

/*
      //echo '            <td>' . $ImageData['IsWatermarkImageFound'] . '</td>';
	    if ($ImageData['IsWatermarkImageFound']) {
		    echo '<td class="center"><span class="icon-ok" </td>';
	    }
	    else
	    {
		    echo '<td class="center"><span class="icon-cancel" </td>';
	    }
*/

	    echo '            <td class="center">' . $ImageData['ParentGalleryId'] . '</td>';



//	    echo '            <td>' . 'Image' . '</td>';
	    echo '            <td class="center">'.'<img width="80" alt="'. $ImageData['imageName']
		                            . '" name="image" src="'.$ImageData['ImagePath'].'">'.'</td>';

        //echo '            <td>' . 'Buttons' . '</td>';
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

                        <div>
                            <strong><?php echo JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT');?></strong>
                        </div>
                        <!-- List info  -->
                        <div class="control-group">
                            <label for="zip_file" class="control-label"> </label>
                            <div class="controls">
                                <!--input type="text" id="zip_file" name="zip_file" class="span5 input_box" size="70" value="http://" /-->
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
							<!-- div class="span4 clsInfoAccordion" -->
							 <?php
								// Info about last uploaded galleries
                                DisplayImageDataTable ($this->DisplayImageData);
							?>
							<!-- /div -->
						</div>
					</div>

					<div class="form-actions">
						<br>
					</div>

	                <div class="form-actions">
		                <button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_CREATE_MISSING_IMAGES'); ?></button>
		                <button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_DELETE_FROM_FILESYSTEM'); ?></button>
		                <button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_CREATE_DATABASE_ENTRIES'); ?></button>
		                <button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_DELETE_IMAGES'); ?></button>
		                <button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_ASSIGN_GALLLERY'); ?></button>
	                </div>
					<div class="form-actions">
		                <button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_REFRESH'); ?></button>
		                <button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_(''); ?></button>
	                </div>



                <?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.endTabSet'); ?>

			<input type="hidden" value="" name="task">

			<?php echo JHtml::_('form.token'); ?>

           </form>
        </div>
	<div id="loading"></div>
</div>
</div>
