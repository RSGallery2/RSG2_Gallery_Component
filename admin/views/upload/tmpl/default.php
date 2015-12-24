<?php 
/**
* @package RSGallery2
* @copyright (C) 2003 - 2015 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
 */

 // https://techjoomla.com/blog/beyond-joomla/jquery-basics-getting-values-of-form-inputs-using-jquery.html

 
defined( '_JEXEC' ) or die(); 

JHtml::_('bootstrap.tooltip'); 

//$doc = JFactory::getDocument(); //only include if not already included
//$doc->addScript(JUri::root() . 'templates/' . $template . '/template.js');
//JHtml::_('bootstrap.tooltip');
//JHtml::_('behavior.multiselect');

JHtml::_('formbehavior.chosen', 'select');
 
JText::script('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN');
JText::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST');
JText::script('COM_RSGALLERY2_FTP_UPLOAD_CHOSEN_BUT_NO_FTP_PATH_PROVIDED'); 

/*
$js = <<<SCRIPTHERE
	jQuery(document).ready(function() {
		
	}); 
SCRIPTHERE;

		jQuery("#SelectGalleries_01").on("change", "#chosen", function() {
			console.log("onchange: " + this.value);
			alert ("onchange: " + this.value);
		};
JFactory::getDocument()->addScriptDeclaration($js)		
*/

/*
//	var_dump ($this->form);
//	print_r ( $this->form);
print_r ( $this);

echo "Upload: " . $this->form.UploadLimit;
echo "Upload: " . $this->form->UploadLimit;

*/
/**/
echo "<pre>";
/**/
//foreach ($form->getGroup($fieldsname) as $field){
foreach ($this->form->getGroup('upload_zip') as $field) {
	echo $field->label . ' ' . $field->input;
}
/**/
echo 'test';
echo "</pre>";
/**/

?>

<script type="text/javascript">

	// Add spindle-wheel for installations:
	jQuery(document).ready(function($) {
		var outerDiv = $('#installer-install');

		$('#loading').css({
			'top': 		outerDiv.position().top - $(window).scrollTop(),
			'left': 	outerDiv.position().left - $(window).scrollLeft(),
			'width': 	outerDiv.width(),
			'height': 	outerDiv.height(),
			'display':  'none'
		});
	}); 


	/*
	Joomla.submitbuttonSingle = function()
	{
		alert('Upload single images: use ...');
	};
	*/
	
	Joomla.submitbuttonZipPc = function()
	{
		var form = document.getElementById('adminForm');
		
		var zip_path = form.install_url.value;
		var GalleryId = jQuery('#SelectGalleries_01').chosen().val();		
		var bOneGalleryName4All = jQuery('input[name="all_img_in_step1"]:checked').val();		
		
		// No file path given
		if (zip_path == "") {
			alert(Joomla.JText._('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN'));
		}
		else
		{
			// Is invalid gallerId selected ? 
			if (bOneGalleryName4All && (GalleryId < 1)) {
				alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
			}
			else
			{
				// ==> base.rsgallery2.php ---------- $task: batchupload $option: com_rsgallery2 $catid: $firstCid: 0 $id: 0 $rsgOption: images $view: 
				// ==> images.batchupload.php ---------- $batchmethod: zip $uploaded: 1 $xcat: 1 $xcat: 19 $ftppath: 
				
				// yes transfer files ...
				form.task.value = 'batchupload'; // upload.uploadZipFile
				form.batchmethod.value = 'zip';
				form.zip_file.value = zip_path;
				form.ftp_path.value = "";
				form.xcat.value = GalleryId;
				form.selcat.value= bOneGalleryName4All;
				
				//jQuery('#loading').css('display', 'block');
				//form.submit();
			}
		}
	};
	
	Joomla.submitbuttonFolderServer = function()
	{		
		var form = document.getElementById('adminForm');

		var ftp_path = form.install_directory.value;
		var GalleryId = jQuery('#SelectGalleries_01').chosen().val();		
		var bOneGalleryName4All = jQuery('input[name="all_img_in_step1"]:checked').val();		
				
		// ftp path is not given
		if (ftp_path == "") {
			alert(Joomla.JText._('COM_RSGALLERY2_FTP_UPLOAD_CHOSEN_BUT_NO_FTP_PATH_PROVIDED'));
		}
		else
		{
			// Is invalid gallerId selected ? 
			if (bOneGalleryName4All && (GalleryId < 1)) {
				alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
			}
			else
			{
				// yes transfer files ...
				form.task.value = 'batchupload'; // upload.uploadZipFile
				form.batchmethod.value = 'FTP';
				form.zip_file.value = "";
				form.ftp_path.value = ftp_path;
				form.xcat.value = GalleryId;				
				form.selcat.value= "0";

				//jQuery('#loading').css('display', 'block');
				//form.submit();
			}
		}				
	};
	
</script>

<style type="text/css">
	#loading {
		background: rgba(255, 255, 255, .8) url('<?php echo JHtml::_('image', 'jui/ajax-loader.gif', '', null, true, true); ?>') 50% 15% no-repeat;
		position: fixed;
		opacity: 0.8;
		-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity = 80);
		filter: alpha(opacity = 80);
	}
/*
	.j-jed-message {
		margin-bottom: 40px;
		line-height: 2em;
		color:#333333;
*/
	}
</style>
 
<div id="installer-install" class="clearfix">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>
   
		<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=upload'); ?>" method="post" name="adminForm" id="adminForm"  class="form-horizontal" >

			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'upload_zip_pc')); ?> 
			
			<?php JEventDispatcher::getInstance()->trigger('onInstallerViewBeforeFirstTab', array()); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload_single', JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES', true)); ?>
			<fieldset class="uploadform">
				<legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_MORE'); ?></legend>
				<!--div class="control-group">
					<label for="install_package" class="control-label"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_MOREYYY'); ?></label>
					<div class="controls">
						<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
					</div>
				</div-->
				<div class="form-actions">
					<!--button class="btn btn-primary" type="button" onclick="Joomla.submitbuttonSingle()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?></button -->
					<!-- ok button class="btn btn-primary" type="button" onclick="Joomla.submitbuttonSingle()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?></button -->
					<!--a href="" /-->
					<a class="btn btn-primary" title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?>" 
						href="index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=upload">
						<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?>
					</a>
					
<?php 
/*					
		$html[] = parent::getInput()
			. '<a class="btn modal" title="' . JText::_('COM_MODULES_CHANGE_POSITION_TITLE') . '"  href="' . $link
			. '" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'
			
			. JText::_('COM_MODULES_CHANGE_POSITION_BUTTON') . '</a>'; 			$html[] = '<a'
				. ' class="btn hasTooltip' . ($value ? '' : ' hidden') . '"'
				. ' href="index.php?option=com_categories&layout=modal&tmpl=component&task=category.edit&id=' . $value . '"'
				. ' target="_blank"'
				. ' title="' . JHtml::tooltipText('COM_CATEGORIES_EDIT_CATEGORY') . '" >'
				. '<span class="icon-edit"></span>' . JText::_('JACTION_EDIT')
				. '</a>';
 				</div>
*/
?>							
			</fieldset>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
			
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload_zip_pc', JText::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP', true)); ?>
				<fieldset class="uploadform">
					<legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP_FROM_LOCAL_PC'); ?></legend>

					<!-- Zip filename -->
					<div class="control-group">
						<label for="install_url" class="control-label"><?php echo JText::_('COM_RSGALLERY2_ZIP_MINUS_FILE'); ?></label>
						<div class="controls">
							<!--input type="text" id="install_url" name="install_url" class="span5 input_box" size="70" value="http://" /-->
							<input class="input_box" id="install_url" name="install_url" type="file" size="57" />
                            <div style=color:#FF0000;font-weight:bold;font-size:smaller;>
                                <?php echo JText::_('COM_RSGALLERY2_UPLOAD_LIMIT_IS').' ' . $this->UploadLimit .' '.JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI');?>
                            </div>
						</div>
					</div>
					
					<?php
						// All in one, Specify gallery
						echo $this->form->renderFieldset('upload_zip');
					?>
					 
					<!-- Action button -->
					<div class="form-actions">
						<button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonZipPc()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'); ?></button>
					</div>
				</fieldset>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload_folder_server', JText::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_SERVER', true)); ?>
				<fieldset class="uploadform">
					<legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_PATH_ON_SERVER'); ?></legend>
					<div class="control-group">
						<label for="install_directory" class="control-label"><?php echo JText::_('COM_RSGALLERY2_FTP_PATH'); ?></label>
						<div class="controls">
							<input type="text" id="install_directory" name="install_directory" class="span5 input_box" size="70" value="<?php echo $this->LastFtpUploadPath;?>" />
							<!-- red size -->
                            <div style="color:#FF0000;font-weight:bold;font-size:smaller;margin-top: 0px;padding-top: 0px;">
                                <?php echo JText::_('COM_RSGALLERY2_PATH_MUST_START_WITH_BASE_PATH');?>
                            </div>
                            <div style="color:#000000;font-size:smaller;margin-top: 0px;padding-top: 0px;">
	                            <?php echo JText::sprintf('COM_RSGALLERY2_FTP_BASE_PATH', ""); ?><!-- br -->&nbsp;<?php echo JPATH_SITE; ?>
                            </div>
                        </div>
                    </div>
														
					<?php
						// All in one, Specify gallery
						echo $this->form->renderFieldset('upload_zip');
					?>
	  
					<div class="form-actions">
						<button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES'); ?></button>
					</div>
					</fieldset>
				<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.endTabSet'); ?>

			<?php echo JHtml::_('form.token'); ?>
			
			<!--input type="hidden" value="1" name="uploaded"-->
			<input type="hidden" value="0" name="uploaded">
			<input type="hidden" value="com_rsgallery2" name="option">
			<input type="hidden" value="images" name="rsgOption">
			<input type="hidden" value="0" name="boxchecked">

			<input type="hidden" value="" name="task">
			<input type="hidden" value="" name="zip_file">
			<input type="hidden" value="" name="ftp_path">
			<input type="hidden" value="" name="batchmethod">
			<input type="hidden" value="" name="xcat">
			<input type="hidden" value="" name="selcat">
			
		</form>
	</div>
	<div id="loading"></div>
</div>

