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
 
JText::script('COM_INSTALLER_MSG_INSTALL_PLEASE_SELECT_A_PACKAGE');
JText::script('COM_INSTALLER_MSG_INSTALL_PLEASE_SELECT_A_DIRECTORY');
JText::script('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL'); 


// $checked  = empty($this->value) ? ' checked="checked"' : '';
 

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
/*		var form = document.getElementById('adminForm');

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
*/
		alert('Upload from local Zip PC: use ...');
	};
	
	Joomla.submitbuttonFolderServer = function()
	{
/*		var form = document.getElementById('adminForm');

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
*/
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
					<button class="btn btn-primary" type="button" onclick="Joomla.submitbuttonSingle()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?></button>
					<!--a href="" /-->
				</div>
							
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
					
					<!-- One gallery for all images -->
					<div class="control-group">
						<div class="control-label">
							<label id="" class="hasTooltip" for="specify_all_img" title="" 
								data-original-title="<strong>Strong text</strong><br />Select whether . If Yes, the ."
								><?php echo JText::_('COM_RSGALLERY2_ONE_GALLERY_FOR_ALL_IMAGES'); ?></label>
						</div>
						<div class="controls">
							<!--fieldset id="jform_offline" class="radio btn-group btn-group-yesno">
								<input id="jform_offline0" type="radio" value="1" name="jform[offline]">
								<label class="btn" for="jform_offline0">Yes</label>
								<input id="jform_offline1" type="radio" checked="checked" value="0" name="jform[offline]">
								<label class="btn active btn-danger" for="jform_offline1">No</label>
							</fieldset  btn active btn-success -->
							<fieldset id="specify_all_img" class="radio btn-group btn-group-yesno">
								<input id="specify_all_img0" type="radio" value="1" name="specify_all_img"
								
								<?php 
										if ($this->bYesAllImgInStep1)
										{
											echo ' checked="checked"';
										}	
									?>
								>
								
								<label 
								class="btn <?php 
										if ($this->bYesAllImgInStep1)
										{
											echo " active btn-success";
										}	
									?>" for="specify_all_img0">Yes</label>
								<input id="specify_all_img1" type="radio" value="0" name="specify_all_img"<?php 
										if (! $this->bYesAllImgInStep1)
										{
											echo ' checked="checked"';
										}	
									?>
								>
								<label 
								class="btn"<?php 
										if (! $this->bYesAllImgInStep1)
										{
											echo " active btn-danger";
										}	
									?>" for="specify_all_img1">No</label>
									
							</fieldset>
							
							<fieldset id="myEdit" class="radio btn-group btn-group-yesno">
                                        <input type="radio" id="myEdit0" value="1" name="myEdit">
                                        <label for="myEdit0" class="btn">Yes</label>
                                        <input type="radio" id="myEdit1" value="0" name="myEdit">
                                        <label for="myEdit1" class="btn btn-danger">No</label>
							</fieldset>

							<fieldset id="specify_all_img" class="radio btn-group">
										<input id="specify_all_img0" type="radio" value="1" name="specify_all_img">
										<label class="btn" for="specify_all_img0">Yes</label>
										<input id="specify_all_img1" type="radio" value="0" name="specify_all_img">
										<label class="btn" for="specify_all_img1">No</label>									
							</fieldset>
							
							
								<!-- black base path -->
								<!-- div style="color:#000000;font-weight:bold;font-size:smaller;margin-top: 0px;padding-top: 0px;">
									<?php echo JText::sprintf('COM_RSGALLERY2_NO_SPECIFY_GALLERY_PER_IMAGE_IN_STEP_2', ""); ?>
								</div-->
								<div style="color:#000000;font-size:smaller;margin-top: 0px;padding-top: 0px;">
									<?php echo JText::sprintf('COM_RSGALLERY2_NO_SPECIFY_GALLERY_PER_IMAGE_IN_STEP_2', ""); ?>
								</div>
									
							
							<!-- select gallery combo -->
							
						</div>
					</div>
					
					<!-- Specify gallery -->
					<div class="control-group">
						<label class="control-label" for="zip_upload_galleries"><?php echo JText::_('COM_RSGALLERY2_SPECIFY_GALLERY'); ?></label>
						<div class="controls">
							<select id="zip_upload_galleries" class="chzn-done" name="select_zip_upload_galleries" data-placeholder="Only when first is empty)*" style="display: none;">
								<option value="-1" selected="selected" >- Please select -</option>
								<option value="0">Theater 1</option>
								<option value="1">Theater 2</option>
							</select>
							<div id="zip_upload_galleries_chzn" class="chzn-container chzn-container-single chzn-container-single-nosearch" style="width: 420px;" title="">
								<!-- header --> 
								<a class="chzn-single" tabindex="-1">
									<span>- Please select 2 -</span>
									<div>
										<b></b>
									</div>
								</a>
								<div class="chzn-drop">
									<div class="chzn-search">
										<input type="text" autocomplete="off" readonly="">
									</div>
									<ul class="chzn-results">
										<li class="active-result result-selected" data-option-array-index="0" style="">- Please select -<li>
										<li class="active-result" data-option-array-index="1" style="">Theater 1</li>
										<li class="active-result" data-option-array-index="2" style="">Theater 2</li>
									</ul>
								</div>
							</div>

						</div>
					</div>
					
					 
					<div class="control-group">
						<div class="control-label"><?php 
						var_dump($this->form);
//						echo $this->form->getLabel ('SelectGalleries01'); ?></div>
						<div class="controls"><?php 
						//echo $this->form->getInput ('SelectGalleries01'); 
						?></div>
					</div>
					
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
							
<div class="controls">
    <div class="radio btn-group">
        <input type="radio" checked="checked"><label class="btn active btn-danger">Male</label>
        <input type="radio"><label class="btn">Female</label>
    </div>
</div>


    <fieldset id="multilang" class="radio btn-group btn-group-yesno">
                            <input type="radio" id="multilang0" name="editor[multilang]" value="1">
                            <label for="multilang0" class="btn active btn-success">????????</label>
                            <input type="radio" id="multilang1" name="editor[multilang]" value="0">
                            <label for="multilang1" class="btn">?????????</label>
      </fieldset>

	  
<fieldset id="myEdit" class="radio btn-group">
                                        <input type="radio" id="myEdit0" value="1" name="myEdit">
                                        <label for="myEdit0" class="btn">Yes</label>
                                        <input type="radio" id="myEdit1" value="0" name="myEdit">
                                        <label for="myEdit1" class="btn btn-danger">No</label>
</fieldset>

	  
							<!-- black base path -->
                            <!--div style="color:#000000;font-weight:bold;font-size:smaller;margin-top: 0px;padding-top: 0px;">
	                            <?php echo JText::sprintf('COM_RSGALLERY2_FTP_BASE_PATH', ""); ?>&nbsp;<?php echo JPATH_SITE; ?>
                            </div -->
                            <div style="color:#000000;font-size:smaller;margin-top: 0px;padding-top: 0px;">
	                            <?php echo JText::sprintf('COM_RSGALLERY2_FTP_BASE_PATH', ""); ?><!-- br -->&nbsp;<?php echo JPATH_SITE; ?>
                            </div>
						</div>
					</div>
					<div class="form-actions">
						<button type="button" class="btn btn-primary" onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES'); ?></button>
					</div>
					</fieldset>
				<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.endTabSet'); ?>

			<input type="hidden" name="type" value="" />
			<input type="hidden" name="installtype" value="upload" />
			<input type="hidden" name="task" value="rsgallery2.upload" /> 			
							
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
	<div id="loading"></div>
</div>

