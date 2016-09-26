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

</script>

<?php

//  );
/**
 * @param ImageReferences $ImageReferences
 */
function DisplayImageDataTable ($ImageReferences, $form) {

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
	echo '<br>';

    echo '<table class="table table-striped table-condensed">';
	echo '    <caption><h3>'.JText::_('COM_RSGALLERY2_MISSING_IMAGE_REFERENCES_LIST').'</h3></caption>';
    echo '    <thead>';
    echo '        <tr>';

    $html = array (); // Counter empty
    $html[] = '<th class="center" width="1%">';
    $html[] = '1'; // empty
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // Check all empty
    $html[] = '<th class="center" width="1%">';
    $html[] = '2'; // empty
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // filename
    $html[] = '<th class="align-left" width="20%">';
    $html[] =  '3'.   JText::_( 'COM_RSGALLERY2_FILENAME' );
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // In Database
    $html[] = '<th class="center">';
    $html[] = '4'.     JText::_( 'COM_RSGALLERY2_IN_BR_DATABASE' );
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // display
    $html[] = '<th class="center">';
    $html[] = '5'.     JText::_( 'COM_RSGALLERY2_DISPLAY_BR_FOLDER' );
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // In original
    $html[] = '<th class="center">';
    $html[] =  '6'.    JText::_( 'COM_RSGALLERY2_ORIGINAL_BR_FOLDER' );
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // thumb
    $html[] = '<th class="center">';
    $html[] = '7'.     JText::_( 'COM_RSGALLERY2_THUMB_FOLDER' );
    $html[] = '</th>';
    echo implode(' ', $html);

    // watermarked
	if($ImageReferences->UseWatermarked)
	{
        $html = array (); // watermarked
        $html[] = '<th class="center">';
        $html[] =  '8'.    JText::_( 'COM_RSGALLERY2_WATERMARK_BR_FOLDER' );
        $html[] = '</th>';
        echo implode(' ', $html);
    }

    $html = array (); // action
    $html[] = '<th class="center">';
    $html[] =  '9'.    JText::_( 'COM_RSGALLERY2_ACTION' );
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // parent gallery
    $html[] = '<th class="center" width="20%">';
    $html[] = '10'.     JText::_( 'COM_RSGALLERY2_PARENT_BR_GALLERY' );
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // image
    $html[] = '<th class="center" width="10%">';
    $html[] = '11'.     JText::_( 'COM_RSGALLERY2_IMAGE' );
    $html[] = '</th>';
    echo implode(' ', $html);


// COM_RSGALLERY2_DELETE_IMAGES
    echo '        </tr>';

//-------------------------------------
// Second row with command buttons
//-------------------------------------

	echo '<tr>'; // start of row

    $html = array (); // Counter
    $html[] = '<th>';
    $html[] = ''.     JText::_( 'COM_RSGALLERY2_NUM' );
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // Check all
    $html[] = '<th>';
    $html[] = ''.     JHtml::_('grid.checkall');
    $html[] = '</th>';
    echo implode(' ', $html);


    $html = array (); // filename 2
	$html[] = '<th >';
	$html[] = '3'; // empty
	$html[] = '</th>';
	echo implode(' ', $html);

	$html = array ();
	$html[] = '<th class="center">'; // In database
	if($ImageReferences->IsAnyImageMissingInDB){
		$html[] = '4     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_DATABASE_ENTRIES').'" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntries();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-database"></span>';
		$html[] = '     </a>';
	}
	$html[] = '</th>';
	echo implode(' ', $html);

	echo '            <th class="center">'.'5'.'</th>'; // display
	echo '            <th class="center">'.'6'.'</th>'; // original
	echo '            <th class="center">'.'7'.'</th>'; // thumb

    // watermarked
	if($ImageReferences->UseWatermarked)
	{
		echo '            <th class="center">' . '8' . '</th>'; // watermarked
	}

	$html = array (); // action
	$html[] = '<th class="center">';
	if($ImageReferences->IsAnyOneImageMissing)
	{
		$html[] = '9     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_SELECTED_MISSING_IMAGES') . '" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.assignJoomla.createMissingImages();"';
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
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.assignGallery();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-images"></span>';
		$html[] = '     </a>';
	}
	$html[] = '</th>';
	echo implode(' ', $html);

	// ToDo: Button hide column and Mobile hide
	$html = array (); // parent gallery
	$html[] = '<th class="center">';
	$html[] = '10</th>';
	echo implode(' ', $html);

	// ToDo: Button hide column and Mobile hide
	$html = array (); // image
	$html[] = '<th class="center">';
	$html[] = '11</th>';
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

		$html = array (); // row index
		$html[] = '<td>';
	    //$html[] = '$this->pagination->getRowOffset($i);';
		$html[] = ''. (string)$Idx;
		$html[] = '</td>';
		echo implode(' ', $html);


        // Self made pagination from list ?

        $html = array (); // row index
		$html[] = '<td>';
        // $html[] =     JHtml::_('grid.id', $i, $item->id);
        //$html[] =     JHtml::_('grid.id', $ImageData, $Idx);
        $html[] =  ''.    JHtml::_('grid.id', '' . (string) $Idx, $Idx);
        $html[] = '</td>';
		echo implode(' ', $html);

	    $html = array (); // filename
		echo '            <td>3' . $ImageData->imageName . '</td>';

	    // database entry found
		if ($ImageData->IsImageInDatabase) {
			$html = array (); // database
			$html[] = '<td class="center">';
			$html[] = '4    <i class="icon-ok hasTooltip" data-original-title="database entry found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_DATABASE_ENTRY_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		} else
		{
			// Not found -> button
			$html   = array(); // database
			$html[] = '<td class="center">';
			$html[] = '4     <a class="btn btn-micro jgrid hasTooltip db_missing" ';
			$html[] = '         data-original-title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_DATABASE_ENTRY') . '" ';
			$html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.createImageDbItems\')" ';
			$html[] = '         href="javascript:void(0);"';
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
			$html[] = '5    <i class="icon-ok hasTooltip" data-original-title="display image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_DISPLAY_IMAGE_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		} else {
			$html = array (); // database
			$html[] = '<td class="center">';
			$html[] = '5    <i class="icon-cancel hasTooltip" data-original-title="display image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_DISPLAY_IMAGE_NOT_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

		// original image found
		if ($ImageData->IsOriginalImageFound) {
			$html = array ();
			$html[] = '<td class="center">';
			$html[] = '6    <i class="icon-ok hasTooltip" data-original-title="original image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_ORIGINAL_IMAGE_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		} else {
			$html = array (); // database
			$html[] = '<td class="center">';
			$html[] = '6    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_ORIGINAL_IMAGE_NOT_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

	    // thumb image found
		if ($ImageData->IsThumbImageFound) {
			$html = array ();
			$html[] = '<td class="center">';
			$html[] = ' 7   <i class="icon-ok hasTooltip" data-original-title="thumb image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_THUMB_IMAGE_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		} else {
			$html = array (); // database
			$html[] = '<td class="center">';
			$html[] = ' 7   <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_THUMB_IMAGE_NOT_FOUND') .'" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

	    // Watermark
        if ($ImageData->IsWatermarkedImageFound) {
	        $html = array ();
	        $html[] = '<td class="center">';
	        $html[] = '8    <i class="icon-ok hasTooltip" data-original-title="thumb image found" ';
	        $html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_WATERMARK_IMAGE_FOUND') .'" ';
	        $html[] = '    />';
	        $html[] = '</td>';
	        echo implode(' ', $html);
        } else {
	        $html = array (); // database
	        $html[] = '<td class="center">';
	        $html[] = '8    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
	        $html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_WATERMARK_IMAGE_NOT_FOUND') .'" ';
	        $html[] = '    />';
	        $html[] = '</td>';
	        echo implode(' ', $html);
        }

	    $html = array (); // action
	    $html[] = '<td class="center">';
	    if($ImageData->IsMainImageMissing (ImageReference::dontCareForWatermarked) )
	    {
		    $html[] = '9     <a class="btn btn-micro jgrid hasTooltip" ';
		    $html[] = '         data-original-title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_MISSING_IMAGES_IN_ROW') . '" ';
		    $html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.createMissingImages\')" ';
		    $html[] = '         href="javascript:void(0);"';
		    $html[] = '     >';
		    $html[] = '         <span class="icon-image"></span>';
		    $html[] = '     </a>';
	    }
	    //if($ImageReferences->)
	    {
		    $html[] = '     <a class="btn btn-micro jgrid hasTooltip" ';
		    $html[] = '         data-original-title="' . JHtml::tooltipText('COM_RSGALLERY2_DELETE_IMAGES_IN_ROW') . '" ';
		    $html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.deleteAllImages\')" ';
		    $html[] = '         href="javascript:void(0);"';
		    $html[] = '     >';
		    $html[] = '         <span class="icon-delete"></span>';
		    $html[] = '     </a>';
	    }
	    // if($ImageReferences->)
	    {
		    $html[] = '     <a class="btn btn-micro jgrid hasTooltip" ';
		    $html[] = '         data-original-title="' . JHtml::tooltipText('COM_RSGALLERY2_ASSIGN_GALLLERY_IN_ROW') . '" ';
		    $html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.assignGalleries\')" ';
		    $html[] = '         href="javascript:void(0);"';
		    $html[] = '     >';
		    $html[] = '         <span class="icon-images"></span>';
		    $html[] = '     </a>';
	    }
	    $html[] = '</td>';
	    echo implode(' ', $html);


	    $html = array (); // action
	    $html[] = '<td class="center">10 ';

        // google (1) joomla formfield array
        // google (2) joomla display array of form fields

//		$field = $form->getFieldset('maintConsolidateDB');
	    if ($ImageData->ParentGalleryId > -1) {
		    $html[] = '' . $ImageData->ParentGalleryId . ' ';
		}
		else {
			$html[] = '<span class="icon-cancel">';
		}

	    //$html[] = $form->renderFieldset('maintConsolidateDB');
//	    $field = $form->getFieldset('maintConsolidateDB');
//	    $html[] = $field->input;

/**
<?php foreach ($this->form->getFieldset('myfields') as $field) : ?>
	<div class="control-group">
		<div class="control-label">
			<?php echo $field->label; ?>
		</div>
		<div class="controls">
			<?php echo $field->input; ?>
		</div>
	</div>
<?php endforeach; ?>
/**/



	    $html[] = '</td>';
	    echo implode(' ', $html);

	    $html = array (); // image

		// Image is defined
	    if ($ImageData->imagePath !== '') {
			echo '           <td class="center">11' . '<img width="80" alt="' . $ImageData->imageName
				. '" name="image" src="' . JUri::root(true) . $ImageData->imagePath . '">' . '</td>';
		}
		else{
			echo '            <td class="center">11<span class="icon-cancel"></td>';
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

			<br><br><span style="color:red">Task: match columns</span><br>

			<div>
				<strong><?php echo JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT');?></strong>
			</div>

			<div class="span12">
				<div class="row-fluid">
					<!-- div class="span4 clsInfoAccordion" -->
					 <?php
						// Info about last uploaded galleries
                        DisplayImageDataTable ($this->ImageReferences, $this->form);
					?>
					<!-- /div -->
				</div>
			</div>

			<div class="form-actions">
				<br>
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

			<input type="hidden" value="" name="task" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="ImageReferenceList" value="<?php
				$ImageReferenceList = $this->ImageReferences->ImageReferenceList;
				$JsonEncoded = json_encode($ImageReferenceList);
				//$JsonEncoded = json_encode($ImageReferenceList, JSON_HEX_QUOT);
				//$HtmlOut = htmlentities($JsonEncoded, ENT_QUOTES, "UTF-8");
				$HtmlOut = htmlentities($JsonEncoded, ENT_QUOTES, "UTF-8");
				echo $HtmlOut;
			?>" />

			<?php echo JHtml::_('form.token'); ?>
        </form>
	</div>
</div>
