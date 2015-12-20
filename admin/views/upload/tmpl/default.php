<?php 
/**
* @package RSGallery2
* @copyright (C) 2003 - 2012 RSGallery2
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

$js = <<<SCRIPTHERE
	jQuery(document).ready(function() {
		
	}); 
SCRIPTHERE;
/*
		jQuery("#SelectGalleries_01").on("change", "#chosen", function() {
			console.log("onchange: " + this.value);
			alert ("onchange: " + this.value);
		};
*/

JFactory::getDocument()->addScriptDeclaration($js)

/*
// $checked  = empty($this->value) ? ' checked="checked"' : '';
JFactory::getDocument()->addScriptDeclaration(
/*
	/* Select first tab
	jQuery(document).ready(function() {
		jQuery("#configTabs a:first").tab("show");
	});
	'
	* /
'	
jQuery(document).ready(function() {
	if ($("#TestTask").length){
		alert("found");
	}
	else
	{
		alert("not found");
	}

	$("#TestTask").val("border-color");
	$("#TestTask").css("border-color","red");
	$("#TestTask").css("border-width","10px");
	 $("#TestTask").css("border-width","10px");
}
'
); 
*/
?>

<script type="text/javascript">
	Joomla.submitbuttonSingle = function()
	{
/*		var form = document.getElementById('adminForm');

		// do field validation
		if (form.install_package.value == "") {
			alert(Joomla.JText._('COM_INSTALLER_MSG_INSTALL_PLEASE_SELECT_A_PACKAGE'));
		}
		else
		{
			jQuery('#loading').css('display', 'block');

			form.installtype.value = 'upload';
			form.submit();
		}
*/
		alert('Upload single images: use ...');
	};
	
	Joomla.submitbuttonZipPc = function()
	{
		// alert('Upload from local Zip PC: use ...');

		var form = document.getElementById('adminForm');

//		alert('install_url.value= "' + form.install_url.value + '"');
		// do field validation input id="install_url" class="input_box" type="file" size="57" name="install_url"
		if (form.install_url.value == "") {
			alert(Joomla.JText._('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN'));
		}
		else
		{
			// $('#selectBox > option:selected').text();
			// SelectGalleries_01_chzn result-selected
			
			// alert('SelectGalleries_01.value= "' + String(form.SelectGalleries_01.value) + '"');
			
			var GalleryId = -1; // form.SelectGalleries_01.value == ""			
			
			GalleryId = jQuery('#SelectGalleries_01').chosen().val();
			// GalleryId = jQuery("#SelectGalleries_01 .chosen-select").val();
			 
			alert('GalleryId: ' + GalleryId);
			
			if (GalleryId < 1) {
				alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
			}
			else
			{
				// jQuery('#loading').css('display', 'block');

				form.TestTask.value = 'xxx'; // upload.uploadZipFile
				form.batchmethod.value = 'zip';
				form.zip_file.value = form.install_url.value;
				form.selcath.value = GalleryId;
				// form.submit();
			}
		}
/*
		// $('input[type=button][value~=task]')

		$("#TestTask").val('border-color');
		// $("#TestTask").css('border-color','red');
*/
		// alert('Upload from local Zip PC: use ...');
	};
	
	Joomla.submitbuttonFolderServer = function()
	{		
		var form = document.getElementById('adminForm');

//		alert('install_directory.value= "' + form.install_directory.value + '"');
		// do field validation input id="install_directory" class="input_box" type="file" size="57" name="install_directory"
		if (form.install_directory.value == "") {
			alert(Joomla.JText._('COM_RSGALLERY2_FTP_UPLOAD_CHOSEN_BUT_NO_FTP_PATH_PROVIDED'));
		}
		else
		{
			// $('#selectBox > option:selected').text();
			// SelectGalleries_01_chzn result-selected
			
			// $selcat      = $input->get( 'selcat', null, 'INT');
			var GalleryId = -1; // form.SelectGalleries_02.value == ""
			
			GalleryId = jQuery('#SelectGalleries_02').chosen().val();
			// ? two selections ? GalleryId = jQuery("#SelectGalleries_02 .chosen-select").val();
			 
			alert('GalleryId: ' + GalleryId);
			
//			alert('SelectGalleries_02.value= "' + String(form.SelectGalleries_02.value) + '"');
			if (GalleryId < 1) {
				alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
			}
			else
			{
				// jQuery('#loading').css('display', 'block');

				form.TestTask.value = 'folder';
				batchmethod = 'FTP';
				form.ftp_path.value = form.install_directory.value;
				form.selcath.value = GalleryId;
				// form.submit();
			}
		}		
		
		
//		$("#TestTask").val('border-color');

		alert('Upload images from remote server : use ...');
	};
	
/*
	Joomla.submitbuttonZipPc = function()
	{
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.install_directory.value == "") {
			alert(Joomla.JText._('COM_INSTALLER_MSG_INSTALL_PLEASE_SELECT_A_DIRECTORY'));
		}
		else
		{
			jQuery('#loading').css('display', 'block');

			form.installtype.value = 'folder';
			form.submit();
		}
	};
	
	.....
	
 */
 
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

/**	
	$(document).ready(function()
	{
		$('*[rel=tooltip]').tooltip()

		// Turn radios into btn-group
		$('.radio.btn-group label').addClass('btn');
		$(".btn-group label:not(.active)").click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));

			if (!input.prop('checked')) {
				label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
			}
		});
		$(".btn-group input[checked=checked]").each(function()
		{
			if ($(this).val() == '') {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
			} else if ($(this).val() == 0) {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
			} else {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
			}
		});
	})
/**/

</script>
<style type="text/css">
	#loading {
		background: rgba(255, 255, 255, .8) url('<?php echo JHtml::_('image', 'jui/ajax-loader.gif', '', null, true, true); ?>') 50% 15% no-repeat;
		position: fixed;
		opacity: 0.8;
		-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity = 80);
		filter: alpha(opacity = 80);
	}
	
	.j-jed-message {
		margin-bottom: 40px;
		line-height: 2em;
		color:#333333;
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
			<input id="TestTask" value="" name="task">
			<input type="hidden" value="0" name="boxchecked">

			<input value="" name="zip_file">
			<input value="" name="ftp_path">
			<input value="" name="batchmethod">
			<input value="" name="selcat">
			
		</form>
	</div>
	<div id="loading"></div>
</div>

