<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');

global $Rsg2DebugActive;


/**
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root(true) . "administrator/components/com_rsgallery2/css/maintConsolidateDB.css");
$doc->addStyleSheet(JURI::root(true) . "/administrator/components/com_rsgallery2/css/ConsolidateDb.css");
/**/
?>

<script type="text/javascript">

</script>

<?php

/**
 * @param ImageReferences $ImageReferences
 * @param $form not used
 *
 * @since 4.3.0
 */

/**/
function DisplayFolderAndImagesTable($FolderReferenceList)
{
	// exit if no data given
	if (count($FolderReferenceList) == 0)
	{
		echo '<h2>' . JText::_('COM_RSGALLERY2_NO_INCONSISTENCIES_IN_DATABASE') . '</h2><br>';

		return;
	}

//-------------------------------------
// Header
//-------------------------------------
	echo '<br>';

	echo '<table class="table table-striped table-condensed">';
	echo '    <caption><h3>' . JText::_('COM_RSGALLERY2_MAINT_LEFT_OVER_REFERENCES_LIST') . '</h3></caption>';
	echo '    <thead>';
	echo '        <tr>';

	/**
	$html = array (); // Counter empty
	$html[] = '<th class="center" width="1%">';
	//$html[] = '1'; // empty
	$html[] = '</th>';
	echo implode(' ', $html);
	/**/
	$html   = array(); // Counter
	$html[] = '<th>';
	//$html[] = '';
	$html[] = JText::_('COM_RSGALLERY2_NUM');
	$html[] = '</th>';
	echo implode(' ', $html);

	/**
	$html = array (); // Check all empty
    $html[] = '<th class="center" width="1%">';
    //$html[] = '2'; // empty
    $html[] = '</th>';
    echo implode(' ', $html);
	/**/
	$html   = array(); // Check all
	$html[] = '<th>';
	//$html[] = '';
	$html[] = JHtml::_('grid.checkall');
	$html[] = '</th>';
	echo implode(' ', $html);

	/**/
	$html   = array(); // filename
	$html[] = '<th class="align-left" width="95%">';
	// $html[] =  '3';
	$html[] = JText::_('COM_RSGALLERY2_FOLDERS_IMAGES');
	$html[] = '</th>';
	echo implode(' ', $html);
    /**
	$html   = array(); // In Database
	$html[] = '<th class="center">';
	//$html[] = '4';
	$html[] = JText::_('COM_RSGALLERY2_IN_BR_DATABASE');
	$html[] = '</th>';
	echo implode(' ', $html);

	$html   = array(); // display
	$html[] = '<th class="center">';
	//$html[] = '5';
	$html[] = JText::_('COM_RSGALLERY2_DISPLAY_BR_FOLDER');
	$html[] = '</th>';
	echo implode(' ', $html);

	$html   = array(); // In original
	$html[] = '<th class="center">';
	//$html[] =  '6';
	$html[] = JText::_('COM_RSGALLERY2_ORIGINAL_BR_FOLDER');
	$html[] = '</th>';
	echo implode(' ', $html);

	$html   = array(); // thumb
	$html[] = '<th class="center">';
	//$html[] = '7';
	$html[] = JText::_('COM_RSGALLERY2_THUMB_FOLDER');
	$html[] = '</th>';
	echo implode(' ', $html);

	// watermarked
	if ($ImageReferences->UseWatermarked)
	{
		$html   = array(); // watermarked
		$html[] = '<th class="center">';
		//$html[] =  '8';
		$html[] = JText::_('COM_RSGALLERY2_WATERMARK_BR_FOLDER');
		$html[] = '</th>';
		echo implode(' ', $html);
	}

	$html   = array(); // action
	$html[] = '<th class="center">';
	//$html[] =  '9';
	$html[] = JText::_('COM_RSGALLERY2_ACTION');
	$html[] = '</th>';
	echo implode(' ', $html);

	$html   = array(); // parent gallery
	$html[] = '<th class="center" width="20%">';
	//$html[] = '10';
	$html[] = JText::_('COM_RSGALLERY2_GALLERY'); // COM_RSGALLERY2_PARENT_BR_GALLERY
	$html[] = '</th>';
	echo implode(' ', $html);

	$html   = array(); // image
	$html[] = '<th class="center" width="10%">';
	//$html[] = '11';
	$html[] = JText::_('COM_RSGALLERY2_IMAGE');
	$html[] = '</th>';
	echo implode(' ', $html);

// COM_RSGALLERY2_DELETE_IMAGES
	echo '        </tr>';
    /**/
//-------------------------------------
// Second row with command buttons
//-------------------------------------

	/**
	echo '<tr>'; // start of row

    $html = array (); // Counter
    $html[] = '<th>';
    //$html[] = '';
	$html[] =      JText::_( 'COM_RSGALLERY2_NUM' );
    $html[] = '</th>';
    echo implode(' ', $html);

    $html = array (); // Check all
    $html[] = '<th>';
    //$html[] = '';
	$html[] =      JHtml::_('grid.checkall');
    $html[] = '</th>';
    echo implode(' ', $html);


    $html = array (); // filename 2
	$html[] = '<th >';
	//$html[] = '3'; // empty
	$html[] = '</th>';
	echo implode(' ', $html);

	$html = array ();
	$html[] = '<th class="center">'; // In database
	if($ImageReferences->IsAnyImageMissingInDB){
		//$html[] = '4';
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
		$html[] = '         title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_DATABASE_ENTRIES').'" ';
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.createDbEntries();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-database"></span>';
		$html[] = '     </a>';
	}
	$html[] = '</th>';
	echo implode(' ', $html);

	$html = array ();

	$html[] = '<th class="center">'; // display
	//$html[] =    '5';
	$html[] =  '</th>';
	$html[] = '<th class="center">';// original
	//$html[] =    '6';
	$html[] = '</th>';
	$html[] = '<th class="center">';// thumb
	//$html[] =     '7';
	$html[] = '</th>';

    // watermarked
	if($ImageReferences->UseWatermarked)
	{
		$html[] = '<th class="center">'; // watermarked
		//$html[] =      '8';
		$html[] = '</th>';
	}
	echo implode(' ', $html);

	$html = array (); // action
	$html[] = '<th class="center">';
	if($ImageReferences->IsAnyOneImageMissing)
	{
		//$html[] = '9';
		$html[] = '     <a class="btn btn-micro jgrid hasTooltip header_button" ';
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
		$html[] = '         onclick="Joomla.checkNone(this); return Joomla.assignSelectedGallery();"';
		$html[] = '     >';
		$html[] = '         <span class="icon-images"></span>';
		$html[] = '     </a>';
	}
	$html[] = '</th>';
	echo implode(' ', $html);

	// ToDo: Button hide column and mobile hide
	$html = array (); // parent gallery
	$html[] = '<th class="center">';
	//$html[] =    '10';
	$html[] = '</th>';
	echo implode(' ', $html);

	// ToDo: Button hide column and mobile hide
	$html = array (); // image
	$html[] = '<th class="center">';
	//$html[] =      '11';
	$html[] = '</th>';
	/**/

	$html   = array();
	$html[] = '    </tr>'; // end of row
	$html[] = '</thead>';

	echo implode(' ', $html);

//-------------------------------------
// table body
//-------------------------------------

	echo '    <tbody>';

	$Idx = -1;
	foreach ($FolderReferenceList as $FolderReference)
	{
		$Idx += 1;

//-------------------------------------
// Next data row
//-------------------------------------

		echo '        <tr>'; // start of row

		$html   = array(); // row index
		$html[] = '<td>';
		//$html[] = '$this->pagination->getRowOffset($i);';
		$html[] = '' . (string) $Idx;
		$html[] = '</td>';
		echo implode(' ', $html);

		// Self made pagination from list ?

		$html   = array(); // row index
		$html[] = '<td>';
		// $html[] =     JHtml::_('grid.id', $i, $item->id);
		//$html[] =     JHtml::_('grid.id', $ImageData, $Idx);
		$html[] = '' . JHtml::_('grid.id', '' . (string) $Idx, $Idx);
		$html[] = '</td>';
		echo implode(' ', $html);

		$html   = array(); // filename
		$html[] = '<td>';
		//$html[] =     '3';
		$html[] = $FolderReference;
		$html[] = '</td>';
		echo implode(' ', $html);

		/**
		// database entry found
		if ($ImageData->IsImageInDatabase)
		{
			$html   = array(); // database
			$html[] = '<td class="center">';
			//$html[] =    '4';
			$html[] = '    <i class="icon-ok hasTooltip" data-original-title="database entry found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_DATABASE_ENTRY_FOUND') . '" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}
		else
		{
			// Not found -> button
			$html   = array(); // database
			$html[] = '<td class="center">';
			//$html[] =    '4';
			$html[] = '    <a class="btn btn-micro jgrid hasTooltip db_missing" ';
			$html[] = '         data-original-title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_DATABASE_ENTRY') . '" ';
			$html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.createImageDbItems\')" ';
			$html[] = '         href="javascript:void(0);"';
			$html[] = '     >';
			$html[] = '         <span class="icon-database"></span>';
			$html[] = '     </a>';
			$html[] = '</td>';
			echo implode(' ', $html);
		}
        /**/

		/**
		// display entry found
		if ($ImageData->IsDisplayImageFound)
		{
			$html   = array();
			$html[] = '<td class="center">';
			//$html[] = '5';
			$html[] = '    <i class="icon-ok hasTooltip" data-original-title="display image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_DISPLAY_IMAGE_FOUND') . '" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}
		else
		{
			$html   = array(); // database
			$html[] = '<td class="center">';
			//$html[] = '5';
			$html[] = '    <i class="icon-cancel hasTooltip" data-original-title="display image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_DISPLAY_IMAGE_NOT_FOUND') . '" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

		// original image found
		if ($ImageData->IsOriginalImageFound)
		{
			$html   = array();
			$html[] = '<td class="center">';
			//$html[] = '6';
			$html[] = '    <i class="icon-ok hasTooltip" data-original-title="original image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_ORIGINAL_IMAGE_FOUND') . '" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}
		else
		{
			$html   = array(); // database
			$html[] = '<td class="center">';
			//$html[] = '6';
			$html[] = '    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_ORIGINAL_IMAGE_NOT_FOUND') . '" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

		// thumb image found
		if ($ImageData->IsThumbImageFound)
		{
			$html   = array();
			$html[] = '<td class="center">';
			//$html[] = ' 7;';
			$html[] = '    <i class="icon-ok hasTooltip" data-original-title="thumb image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_THUMB_IMAGE_FOUND') . '" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}
		else
		{
			$html   = array(); // database
			$html[] = '<td class="center">';
			//$html[] = ' 7';
			$html[] = '    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_THUMB_IMAGE_NOT_FOUND') . '" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

		// Watermark
		if ($ImageData->IsWatermarkedImageFound)
		{
			$html   = array();
			$html[] = '<td class="center">';
			//$html[] = '8';
			$html[] = '    <i class="icon-ok hasTooltip" data-original-title="thumb image found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_WATERMARK_IMAGE_FOUND') . '" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}
		else
		{
			$html   = array(); // database
			$html[] = '<td class="center">';
			//$html[] = '8';
			$html[] = '    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
			$html[] = '      title="' . JHtml::tooltipText('COM_RSGALLERY2_WATERMARK_IMAGE_NOT_FOUND') . '" ';
			$html[] = '    />';
			$html[] = '</td>';
			echo implode(' ', $html);
		}

		$html   = array(); // action
		$html[] = '<td class="center">';
		if ($ImageData->IsMainImageMissing(ImageReference::dontCareForWatermarked))
		{
			//$html[] = '9';
			$html[] = '    <a class="btn btn-micro jgrid hasTooltip inside_button" ';
			$html[] = '         data-original-title="' . JHtml::tooltipText('COM_RSGALLERY2_CREATE_MISSING_IMAGES_IN_ROW') . '" ';
			$html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.createMissingImages\')" ';
			$html[] = '         href="javascript:void(0);"';
			$html[] = '     >';
			$html[] = '         <span class="icon-image"></span>';
			$html[] = '     </a>';
		}
		// if($ImageReferences->)
		{
			$html[] = '     <a class="btn btn-micro jgrid hasTooltip inside_button" ';
			$html[] = '         data-original-title="' . JHtml::tooltipText('COM_RSGALLERY2_ASSIGN_GALLLERY_IN_ROW') . '" ';
			$html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.assignParentGallery\')" ';
			$html[] = '         href="javascript:void(0);"';
			$html[] = '     >';
			$html[] = '         <span class="icon-images"></span>';
			$html[] = '     </a>';
		}
		//if($ImageReferences->)
		{
			$html[] = '     <a class="btn btn-micro jgrid hasTooltip inside_button" ';
			$html[] = '         data-original-title="' . JHtml::tooltipText('COM_RSGALLERY2_REPAIR_ISSUES_IN_ROW') . '" ';
			$html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.repairAllIssuesItems\')" ';
			$html[] = '         href="javascript:void(0);"';
			$html[] = '     >';
			$html[] = '         <span class="icon-refresh"></span>';
			$html[] = '     </a>';
		}
		//if($ImageReferences->)
		{
			$html[] = '     <a class="btn btn-micro jgrid hasTooltip inside_button" ';
			$html[] = '         data-original-title="' . JHtml::tooltipText('COM_RSGALLERY2_DELETE_SUPERFLOUS_ITEMS_IN_ROW') . '" ';
			$html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.deleteRowItems\')" ';
			$html[] = '         href="javascript:void(0);"';
			$html[] = '     >';
			$html[] = '         <span class="icon-delete"></span>';
			$html[] = '     </a>';
		}
		$html[] = '</td>';
		echo implode(' ', $html);

		$html   = array(); //  parent gallery
		$html[] = '<td class="center">';
		//$html[] = '    10';

		// google (1) joomla formfield array
		// google (2) joomla display array of form fields

//		$field = $form->getFieldset('maintConsolidateDB');
//	    if ($ImageData->ParentGalleryId > -1) {
		if ($ImageData->IsGalleryAssigned)
		{

			//$html[] = '' . $ImageData->ParentGalleryId . ' ';
			$html[] = '' . $ImageData->ParentGallery . ' ';
		}
		else
		{
			$html[] = '<span class="icon-cancel">';
		}

		//$html[] = $form->renderFieldset('maintConsolidateDB');
//	    $field = $form->getFieldset('maintConsolidateDB');
//	    $html[] = $field->input;

		$html[] = '</td>';
		echo implode(' ', $html);

		$html = array(); // image

		// Image is defined
		if ($ImageData->imagePath !== '')
		{
			$html[] = '   <td class="center">';
			$html[] = '       <div class="img_border">';
			//$html[] =         '11';
			$html[] = '       <img  class="img_thumb" alt="' . $ImageData->imageName . '" '
				. 'name="image" src="' . JUri::root(true) . $ImageData->imagePath . '">';
			$html[] = '       </div>';
			$html[] = '   </td>';
		}
		else
		{
			$html[] = '   <td class="center">';
			//$html[] =         '11';
			$html[] = '        <span class="icon-cancel">';
			$html[] = '   </td>';
		}
		echo implode(' ', $html);
		//echo '            <td>' . 'Buttons' . '</td>'; JURI_SITE
		echo '        </tr>';
        /**/
	}
    /**/

	echo '    </tbody>';

	//--- footer ----------------------------------
	echo '</table>';

	return;
}
/**/

// ToDo: Use buttons on red icons to immediately do actions
// http://stackoverflow.com/questions/14413916/custom-status-button-in-joomla-component
//<td class="center">
//	<a class="btn btn-micro active hasTooltip" href="javascript:void(0);" onclick="return listItemTask('cb0','categories.unpublish')" title="Unpublish Item"><span class="icon-publish"></span></a>
//</td>

?>

<div id="installer-install" class="clearfix">
	<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=MaintRemoveInstallLeftOvers'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

				<div>
					<strong><?php echo JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT'); ?></strong>
				</div>

				<?php
                    // if (empty($this->ImageReferences)) :
                    $FolderReferenceList = $this->FolderReferences;
                    if (count($FolderReferenceList) == 0) :
                ?>
					<div class="alert alert-no-items">
						<?php
                            echo JText::_('COM_RSGALLERY2_NO_LEFT_OVER_IMAGES');
                        ?>
					</div>
				<?php else : ?>

					<div class="span12">
						<div class="row-fluid">
                            <?php
                            DisplayFolderAndImagesTable($FolderReferenceList);
                            ?>
                        </div>
					</div>

				<?php endif; ?>

				<div class="form-actions">
					<br>
				</div>

				<fieldset class="refresh">
					<!--legend><?php echo JText::_('COM_RSGALLERY2_REFRESH_TEXT'); ?>XXXXX</legend-->
                    <div class="form-actions">
                        <a class="btn btn-primary"
                           title="<?php echo JText::_('COM_RSGALLERY2_REPEAT_CHECKING_INCONSITENCIES_DESC'); ?>"
                           href="index.php?option=com_rsgallery2&amp;view=MaintRemoveInstallLeftOvers">
							<?php echo JText::_('COM_RSGALLERY2_REPEAT_CHECKING'); ?>
                        </a>
                        <a class="btn btn-primary"
                           href="index.php?option=com_rsgallery2&amp;view=maintenance">
		                    <?php echo JText::_('COM_RSGALLERY2_CANCEL'); ?>
                        </a>
                    </div>
 				</fieldset>

				<input type="hidden" value="" name="task" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="FolderReferenceList" value="<?php
                    $JsonEncoded        = json_encode($FolderReferenceList);
                    //$JsonEncoded = json_encode($ImageReferenceList, JSON_HEX_QUOT);
                    //$HtmlOut = htmlentities($JsonEncoded, ENT_QUOTES, "UTF-8");
                    $HtmlOut = htmlentities($JsonEncoded, ENT_QUOTES, "UTF-8");
                    echo $HtmlOut;
                    /**/
				?>" />

				<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
	</div>
