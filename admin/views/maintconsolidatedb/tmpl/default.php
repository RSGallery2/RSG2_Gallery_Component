<?php
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined( '_JEXEC' ) or die();

// ToDo: remove all JHtml::_('behavior.tooltip'); use JHtml::_('bootstrap.tooltip');
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

//	echo '            <th>' . '<input class="hasTooltip" type="checkbox" onclick="Joomla.checkAll(this)" title="" value=""
//					name="checkall-toggle" data-original-title="Check All">'.'</th>';

	echo '            <th>'.JText::_('Select all').'</th>';
	echo '            <th>'.JText::_('COM_RSGALLERY2_FILENAME').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_IN_BR_DATABASE').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_DISPLAY_BR_FOLDER').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_ORIGINAL_BR_FOLDER').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_THUMB_FOLDER').'</th>';
// ToDo:   echo '            <th class="center">'.JText::_('COM_RSGALLERY2_WATERMARK_FOLDER').'</th>';
	echo '            <th class="center">'.JText::_('COM_RSGALLERY2_DELETE_IMAGES').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_PARENT_BR_GALLERY').'</th>';
    echo '            <th class="center">'.JText::_('COM_RSGALLERY2_IMAGE').'</th>';

    echo '        </tr>';

// Second row with command buttons

	echo '        <tr>';
	echo '            <th>' . '<input class="hasTooltip" type="checkbox" onclick="Joomla.checkAll(this)" title="" value="" 
					name="checkall-toggle" data-original-title="Check All">'.'</th>';
	echo '            <th class="align-right">'.JText::_('Tasks').'</th>';

	if(true){ //$this->IsHeaderActive4DB){
		$html = array ();
		$html[] = '<th class="center">';
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_DATABASE_ENTRIES').'" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntries();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-database"></span>';
		$html[] = '     </a>';
		$html[] = '</th>';

		echo implode(' ', $html);
	} else {
		echo '            <th class="center">'.''.'</th>';
	}
	if(true){ //$this->IsHeaderActive4Display){
		$html = array ();
		$html[] = '<th class="center">';
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_MISSING_IMAGES').'" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntries();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-image"></span>';
		$html[] = '     </a>';
		$html[] = '</th>';

		echo implode(' ', $html);
	} else {
		echo '            <th class="center">'.''.'</th>';
	}
	if(true){ //$this->IsHeaderActive4Original){
		$html = array ();
		$html[] = '<th class="center">';
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_MISSING_IMAGES').'" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntries();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-image"></span>';
		$html[] = '     </a>';
		$html[] = '</th>';

		echo implode(' ', $html);
	} else {
		echo '            <th class="center">'.''.'</th>';
	}
	if(true){ //$this->IsHeaderActive4Thumb){
		$html = array ();
		$html[] = '<th class="center">';
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_MISSING_IMAGES').'" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntries();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-image"></span>';
		$html[] = '     </a>';
		$html[] = '</th>';

		echo implode(' ', $html);
	} else {
		echo '            <th class="center">'.''.'</th>';
	}
	if (true){ //$this->IsHeaderActive4Parent){
		$html = array ();
		$html[] = '<th class="center">';
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_ASSIGN_GALLLERY').'" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntries();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-images"></span>';
		$html[] = '     </a>';
		$html[] = '</th>';

		echo implode(' ', $html);
	} else {
		echo '            <th class="center">'.''.'</th>';
	}

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
			'<input id="cb' . $Idx . '" type="checkbox" onclick="Joomla.isChecked(this.checked);" value="'
			. $Idx . '5" name="cid[]">';


		echo '            <td>' . $ImageData['imageName'] . '</td>';

		// echo '            <td>' . $ImageData['IsImageInDatabase'] . '</td>';
		if ($ImageData['IsImageInDatabase']) {
			echo '<td class="center"><span class="icon-ok "> </span> </td>';
		} else
		{
			// echo '<td class="center"><span class="icon-cancel "> </span> </td>';

			$html = array ();
			$html[] = '<td class="center">';
			$html[] = '     <a class="btn btn-micro jgrid hasTooltip db_missing" ';
			$html[] = '         id="db' . $Idx . '" ';
			$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_DATABASE_ENTRY').'" ';
			$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntry(this);"';
			$html[] = '     >';
			$html[] = '         <span class="icon-database"></span>';
			$html[] = '     </a>';
			$html[] = '</td>';

			echo implode(' ', $html);

			$html = array ();
			$html[] = '';
			$html[] = '';
			$html[] = '';
			$html[] = '';
			$html[] = '';
			$html[] = '';
			$html[] = '';

			//echo htmlentities($html);

			/*
			echo '<td class="center">';
			echo '		<a class="btn btn-micro active hasTooltip" title=""';
			echo '			onclick="return listItemTask(\'cb1\',\'categories.unpublish\')"';
			echo '            href="javascript:void(0);" data-original-title="Unpublish Item">';
			echo '			<span class="icon-publish"></span>';
			echo '		</a>';
			echo '	</td>';
			/**/
			/**
			 * $html[] = '<a class="btn btn-micro' . ($active_class == 'publish' ? ' active' : '') . ($tip ? ' hasTooltip' : '') . '"';
			 * $html[] = ' href="javascript:void(0);" onclick="return listItemTask(\'' . $checkbox . $i . '\',\'' . $prefix . $task . '\')"';
			 * $html[] = $tip ? ' title="' . $title . '"' : '';
			 * $html[] = '>';
			 * $html[] = '<span class="icon-' . $active_class . '"></span>';
			 * $html[] = '</a>';
			 *
			 * $html[] = '<td class="order" align="center">';
			 * $html[] = '     <span class="order-up">';
			 * $html[] = '         <a title="<?php echo WFText::_(\'WF_PROFILES_MOVE_UP\');?>" href="#" class="btn btn-micro jgrid"><i class="icon-uparrow icon-chevron-up"></i></a>';
			 * $html[] = '     </span>';
			 * $html[] = '      <span class="order-down">';
			 * $html[] = '         <a title="<?php echo WFText::_(\'WF_PROFILES_MOVE_DOWN\');?>" href="#" class="btn btn-micro jgrid"><i class="icon-downarrow icon-chevron-down"></i></a>';
			 * $html[] = '     </span>';
			 * $html[] = '     <?php $disabled = $n > 1 ? \'\' : \'disabled="disabled"\'; ?>';
			 * $html[] = '<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />';
			 * $html[] = '</td>';
			 * $html[] = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip'
			 * . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><span class="icon-' . $icon . '"></span></a>';
			 * $html[] = '<a class="btn btn-micro hasTooltip disabled' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2])
			 * . '"><span class="icon-' . $icon . '"></span></a>';
			 * /**/
		}

		//echo '            <td>' . $ImageData['IsDisplayImageFound'] . '</td>';
		if ($ImageData['IsDisplayImageFound']) {
			// echo '<td class="center"><span class="icon-ok"> </span> </td>';
			echo '<td class="center"><i class="icon-ok hasTooltip" title="" data-original-title="display image found"></i></td>';
		} else {
			echo '<td class="center"><span class="icon-cancel"> </span> </td>';
		}

		//echo '            <td>' . $ImageData['IsOriginalImageFound'] . '</td>';
		if ($ImageData['IsOriginalImageFound']) {
			echo '<td class="center"><span class="icon-ok"> </span> </td>';
		} else {
			echo '<td class="center"><span class="icon-cancel"> </span> </td>';
		}

		//echo '            <td>' . $ImageData['IsThumbImageFound'] . '</td>';
		if ($ImageData['IsThumbImageFound']) {
			echo '<td class="center"><span class="icon-ok"> </span> </td>';
		} else {
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

		if ($ImageData['ParentGalleryId'] > -1) {
			echo '            <td class="center">' . $ImageData['ParentGalleryId'] . '</td>';
		}
		else {
			echo '            <td class="center"><span class="icon-cancel"></td>';
		}
		// Image is defined
		if ($ImageData['ImagePath'] != '') {
			echo '            <td class="center">' . '<img width="80" alt="' . $ImageData['imageName']
				. '" name="image" src="' . JUri::root(true) . $ImageData['ImagePath'] . '">' . '</td>';
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

			<input type="hidden" value="" name="task" />
			<input type="hidden" name="boxchecked" value="0" />

			<?php echo JHtml::_('form.token'); ?>
        </form>
	</div>
</div>
