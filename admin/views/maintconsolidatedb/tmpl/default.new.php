<?php
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined( '_JEXEC' ) or die();

// ToDo: remove all JHtml::_('behavior.tooltip'); use JHtml::_('bootstrap.tooltip');
// JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip'); 

global $Rsg2DebugActive;

JHtml::_('formbehavior.chosen', 'select');

$doc = JFactory::getDocument();
$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/ConsolidateDb.css");

?>

<script type="text/javascript">

	/*
	Deselect all (checkboxes)
	*/
	Joomla.checkNone = function() {

		alert ('checkNone');

/**
		if (typeof jQuery == 'undefined') {
			// jQuery is not loaded
			alert ('jquery not loaded');
		} else {
			// jQuery is loaded
			alert ('jquery loaded');
		}
/**/
		var ids = '';

		// alert ('a');
		// window.parent.jQuery("a").each(function() {
		window.parent.jQuery(".db_missing").each(function() {
			// alert ('b1');
			var id = window.parent.jQuery(this).attr('id');
			// alert ('b2');
			if (id != undefined && id != "") {
				// alert ('b3');
				// alert('id: ' + id);
				// alert ('b4');
				ids = ids + (ids.length > 0 ? "," + id : id);
				// alert ('b5');
				// deactivate the image in row
				this.checked = false;
				// alert ('b6');
			}
		});
		alert(ids);
		
		return true;
	};

	// create database entry for one image item
	// Other checkboxes are already disabled
	Joomla.createDbEntry = function(checkbox) {

		alert ('createDbEntry: ' +  checkbox.id);

		// Activate the image in row
		window.parent.jQuery("#" + checkbox.id).each(function() {
//			alert ('b1');
			this.checked = true;
//			alert ('b2');
		});
//		alert ('c');

		var form = document.getElementById('adminForm');
		form.task.value = 'MaintConsolidateDb.createDbEntries';
//		alert ('d');

		form.submit();
//		alert ('e');
	}

</script>

<?php

//  );
/**
 * @param ImageReferences $ImageReferences
 */
function DisplayImageDataTable ($ImageReferences) {

	$ImageReferenceList = $ImageReferences->getImageReferenceList();

    // exit if no data given
    if (count($ImageReferenceList) == 0)
    {
        echo '<h2>' . JText::_('COM_RSGALLERY2_NO_INCONSISTENCIES_IN_DATABASE').'</h2><br>';
        return;
    }

//-------------------------------------
// Header
//-------------------------------------

    echo '<table class="table table-striped table-condensed">';
	echo '    <caption><h3>'.JText::_('COM_RSGALLERY2_MISSING_IMAGE_REFERENCES_LIST').'</h3></caption><br>';
    echo '    <thead>';
    echo '        <tr>';
	echo '		      <th width="1%">';
    echo                  JText::_( 'COM_RSGALLERY2_NUM' );
	echo '			  </th>';

	echo '			  <th width="1%" class="center">';
	echo 			      JHtml::_('grid.checkall');
	echo '			  </th>';

	// echo '            <th>'.JText::_('Select all').'</th>';
	echo '            <th>'.JText::_('COM_RSGALLERY2_FILENAME').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_IN_BR_DATABASE').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_DISPLAY_BR_FOLDER').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_ORIGINAL_BR_FOLDER').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_THUMB_FOLDER').'</th>';
	if($ImageReferences->UseWatermarked)
	{
		echo '            <th class="center">'.JText::_('COM_RSGALLERY2_WATERMARK_BR_FOLDER').'</th>';
	}

	echo '            <th class="center">'.JText::_('COM_RSGALLERY2_ACTION').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_PARENT_BR_GALLERY').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_IMAGE').'</th>';
// COM_RSGALLERY2_DELETE_IMAGES
    echo '        </tr>';

//-------------------------------------
// Second row with command buttons
//-------------------------------------

	echo '<tr>'; // start of row

	$html = array (); // Check all
	$html[] = '<th class="center">';
	$html[] = '    <input class="hasTooltip" type="checkbox" onclick="Joomla.checkAll(this)" ';
	$html[] = '        value="" name="checkall-toggle" data-original-title="Select All" ';
	$html[] = '        title="' . JHtml::tooltipText('COM_RSGALLERY2_SELECT_DESELECT_ALL').'" ';
	$html[] = '    >';
	$html[] = '</th>';
	echo implode(' ', $html);

	$html = array (); // filename
	$html[] = '<th class="align-left">';
	$html[] = ''; // empty
	$html[] = '</th>';
	echo implode(' ', $html);

	$html = array ();
	$html[] = '<th class="center">'; // In database
	if($ImageReferences->IsAnyImageMissingInDB){
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_DATABASE_ENTRIES').'" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntries();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-database"></span>';
		$html[] = '     </a>';
	}
	$html[] = '</th>';
	echo implode(' ', $html);

	echo '            <th class="center">'.''.'</th>'; // display
	echo '            <th class="center">'.''.'</th>'; // original
	echo '            <th class="center">'.''.'</th>'; // thumb

	if($ImageReferences->UseWatermarked)
	{
		echo '            <th class="center">' . '' . '</th>'; // watermarked
	}

	$html = array (); // action
	$html[] = '<th class="center">';
	if($ImageReferences->IsAnyOneImageMissing)
	{
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_SELECTED_MISSING_IMAGES') . '" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createAllImages();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-image"></span>';
		$html[] = '     </a>';
	}
	//if($ImageReferences->)
	{
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_DELETE_SELECTED_IMAGES') . '" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.deleteAllImages();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-delete"></span>';
		$html[] = '     </a>';
	}
	// if($ImageReferences->)
	{
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_ASSIGN_SELECTED_GALLLERIES') . '" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.assignAllGalleries();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-images"></span>';
		$html[] = '     </a>';
	}
	$html[] = '</th>';
	echo implode(' ', $html);

	// ToDo: Button hide column and Mobile hide
	$html = array (); // parent gallery
	$html[] = '<th class="center">';
	$html[] = '</th>';
	echo implode(' ', $html);

	// ToDo: Button hide column and Mobile hide
	$html = array (); // image
	$html[] = '<th class="center">';
	$html[] = '</th>';
	echo implode(' ', $html);

	echo '        </tr>'; // end of row
    echo '    </thead>';

//-------------------------------------
// table body
//-------------------------------------

	echo '    <tbody>';

    $Idx = -1;
    foreach ($ImageReferenceList as $ImageData) {
		$Idx += 1;

//-------------------------------------
// Next data row
//-------------------------------------

	    echo '        <tr>'; // start of row
/**
	    $html = array (); // Check all
	    $html[] = '<td class="center">';
	    $html[] = '    <input id="cb' . $Idx . '" class="hasTooltip" type="checkbox" ';
	    $html[] = '        value="' . $Idx . '" name="cid[]" data-original-title="Select row" ';
	    $html[] = '        onclick="Joomla.isChecked(this.checked);"';
	    $html[] = '        title="' . JHtml::tooltipText('COM_RSGALLERY2_SELECT_DESELECT_ROW').'" ';
	    $html[] = '    >';
	    $html[] = '</td>';
	    echo implode(' ', $html);
/**/

		$html = array (); // row index
		$html[] = '<td>';
	    //$html[] = '$this->pagination->getRowOffset($i);';
		$html[] = (string)$Idx;
		$html[] = '</td>';
		echo implode(' ', $html);

		$html = array (); // row index
		$html[] = '<td width="1%" class="center">';
		$html[] =     JHtml::_('grid.id', $i, $item->id); ?>
		$html[] = '</td>';
		echo implode(' ', $html);

		$html = array (); // filename
		echo '            <td>' . $ImageData->imageName . '</td>';

	    // database entry found
		if ($ImageData->IsImageInDatabase) {
			$html = array (); // database
			$html[] = '<td class="center">';
			$html[] = '    <i class="icon-ok hasTooltip" data-original-title="database entry found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_DATABASE_ENTRY_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		} else
		{
			// Not found -> button
			$html   = array(); // database
			$html[] = '<td class="center">';
			$html[] = '     <a class="btn btn-micro jgrid hasTooltip db_missing" ';
			$html[] = '         id="db' . $Idx . '" ';
			$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_DATABASE_ENTRY') . '" ';
			$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntry(this);"';
			$html[] = '     >';
			$html[] = '         <span class="icon-database"></span>';
			$html[] = '     </a>';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

	    // display entry found
		if ($ImageData->IsDisplayImageFound) {
			$html = array ();
			$html[] = '<td class="center">';
			$html[] = '    <i class="icon-ok hasTooltip" data-original-title="display image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_DISPLAY_IMAGE_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		} else {
			$html = array (); // database
			$html[] = '<td class="center">';
			$html[] = '    <i class="icon-cancel hasTooltip" data-original-title="display image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_DISPLAY_IMAGE_NOT_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

		// original image found
		if ($ImageData->IsOriginalImageFound) {
			$html = array ();
			$html[] = '<td class="center">';
			$html[] = '    <i class="icon-ok hasTooltip" data-original-title="original image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_ORIGINAL_IMAGE_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		} else {
			$html = array (); // database
			$html[] = '<td class="center">';
			$html[] = '    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_ORIGINAL_IMAGE_NOT_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

	    // thumb image found
		if ($ImageData->IsThumbImageFound) {
			$html = array ();
			$html[] = '<td class="center">';
			$html[] = '    <i class="icon-ok hasTooltip" data-original-title="thumb image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_THUMB_IMAGE_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		} else {
			$html = array (); // database
			$html[] = '<td class="center">';
			$html[] = '    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_THUMB_IMAGE_NOT_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

	    // Watermark
        if ($ImageData->IsWatermarkedImageFound) {
	        $html = array ();
	        $html[] = '<td class="center">';
	        $html[] = '    <i class="icon-ok hasTooltip" data-original-title="thumb image found" ';
	        $html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_WATERMARK_IMAGE_FOUND') .'" ';
	        $html[] = '    />';
	        $html[] = '</td>';
	        echo implode(' ', $html);
        } else {
	        $html = array (); // database
	        $html[] = '<td class="center">';
	        $html[] = '    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
	        $html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_WATERMARK_IMAGE_NOT_FOUND') .'" ';
	        $html[] = '    />';
	        $html[] = '</td>';
	        echo implode(' ', $html);
        }

	    $html = array (); // action
	    $html[] = '<th class="center">';
	    if($ImageData->IsMainImageMissing (ImageReference::dontCareForWatermarked) )
	    {
		    $html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		    $html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_MISSING_IMAGES_IN_ROW') . '" ';
		    $html[] = '         onclick="Joomla.checkNone(this); return Joomla.createRowDbEntry();"';
		    $html[] = '     >';
		    $html[] = '         <span class="icon-image"></span>';
		    $html[] = '     </a>';
	    }
	    //if($ImageReferences->)
	    {
		    $html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		    $html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_DELETE_IMAGES_IN_ROW') . '" ';
		    $html[] = '         onclick="Joomla.checkNone(this); return Joomla.deleteRowDbEntry();"';
		    $html[] = '     >';
		    $html[] = '         <span class="icon-delete"></span>';
		    $html[] = '     </a>';
	    }
	    // if($ImageReferences->)
	    {
		    $html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		    $html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_ASSIGN_GALLLERY_IN_ROW') . '" ';
		    $html[] = '         onclick="Joomla.checkNone(this); return Joomla.assignRowGalleries();"';
		    $html[] = '     >';
		    $html[] = '         <span class="icon-images"></span>';
		    $html[] = '     </a>';
	    }
	    $html[] = '</th>';
	    echo implode(' ', $html);


	    $html = array (); // parent gallery

	    if ($ImageData->ParentGalleryId > -1) {
			echo '            <td class="center">' . $ImageData->ParentGalleryId . '</td>';
		}
		else {
			echo '            <td class="center"><span class="icon-cancel"></td>';
		}

	    $html = array (); // image

		// Image is defined
	    if ($ImageData->imagePath !== '') {
			echo '            <td class="center">' . '<img width="80" alt="' . $ImageData->imageName
				. '" name="image" src="' . JUri::root(true) . $ImageData->imagePath . '">' . '</td>';
		}
		else{
			echo '            <td class="center"><span class="icon-cancel"></td>';
		}
        //echo '            <td>' . 'Buttons' . '</td>'; JURI_SITE
        echo '        </tr>';
    }
/**/	
    echo '    </tbody>';

    //--- footer ----------------------------------
    echo '</table>';

    return;
}

// ToDo: Use buttons on red icons to immediately do actions
// http://stackoverflow.com/questions/14413916/custom-status-button-in-joomla-component
//<td class="center">
//	<a class="btn btn-micro active hasTooltip" href="javascript:void(0);" onclick="return listItemTask('cb0','categories.unpublish')" title="Unpublish Item"><span class="icon-publish"></span></a>
//</td>


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
                                DisplayImageDataTable ($this->ImageReferences);
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
		                <button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_create complete image set'); ?></button>
	                </div>

					<fieldset class="refresh">
						<!--legend><?php echo JText::_('COM_RSGALLERY2_REFRESH_TEXT'); ?></legend-->
						<div class="form-actions">
							<a class="btn btn-primary" title="<?php echo JText::_('COM_RSGALLERY2_REFRESH'); ?>"
								href="index.php?option=com_rsgallery2&amp;view=maintConsolidateDB">
								<?php echo JText::_('COM_RSGALLERY2_REFRESH'); ?>
							</a>

						</div>
					</fieldset>

                <?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.endTabSet'); ?>

			<input type="hidden" value="" name="task" />
			<input type="hidden" name="boxchecked" value="0" />

			<?php echo JHtml::_('form.token'); ?>
        </form>
	</div>
</div>
