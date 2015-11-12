<?php 
/**
* @package RSGallery2
* @copyright (C) 2003 - 2012 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
 */
 
defined( '_JEXEC' ) or die(); 

JHtml::_('bootstrap.tooltip'); 

JText::script('COM_INSTALLER_MSG_INSTALL_PLEASE_SELECT_A_PACKAGE');
JText::script('COM_INSTALLER_MSG_INSTALL_PLEASE_SELECT_A_DIRECTORY');
JText::script('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL'); 

?>

<script type="text/javascript">
	Joomla.submitbutton = function()
	{
		var form = document.getElementById('adminForm');

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
	};
/*
	Joomla.submitbutton3 = function()
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

			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'upload')); ?> 
			
			<?php JEventDispatcher::getInstance()->trigger('onInstallerViewBeforeFirstTab', array()); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload', JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES', true)); ?>
			<fieldset class="uploadform">
				<legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_MORE'); ?></legend>
				<div class="control-group">
					<label for="install_package" class="control-label"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_MOREYYY'); ?></label>
					<div class="controls">
						<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
					</div>
				</div>
				<div class="form-actions">
					<button class="btn btn-primary" type="button" onclick="Joomla.submitbutton()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?></button>
				</div>
			</fieldset>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'directory', JText::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP', true)); ?>
			<fieldset class="uploadform">
				<legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP_FROM_LOCAL_PC'); ?></legend>
					<div class="control-group">
						<label for="install_url" class="control-label"><?php echo JText::_('COM_RSGALLERY2_ZIP_MINUS_FILE'); ?></label>
						<div class="controls">
							<!--input type="text" id="install_url" name="install_url" class="span5 input_box" size="70" value="http://" /-->
							<input class="input_box" id="install_url" name="install_url" type="file" size="57" />
						</div>
					</div>
					<div class="form-actions">
						<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton4()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'); ?></button>
					</div>
			</fieldset>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'url', JText::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_SERVER', true)); ?>
				<fieldset class="uploadform">
				<legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_PATH_ON_SERVER'); ?></legend>
				<div class="control-group">
					<label for="install_directory" class="control-label"><?php echo JText::_('COM_RSGALLERY2_FTP_PATH'); ?></label>
					<div class="controls">
						<input type="text" id="install_directory" name="install_directory" class="span5 input_box" size="70" value="install.directory" />
					</div>
				</div>
				<div class="form-actions">
					<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton3()"><?php echo JText::_('COM_INSTALLER_INSTALL_BUTTON'); ?></button>
				</div>
				</fieldset>
				<?php echo JHtml::_('bootstrap.endTab'); ?>

				<?php JEventDispatcher::getInstance()->trigger('onInstallerViewAfterLastTab', array()); ?>

				<?php 
					// if ($this->ftp) : 
					?>
			<?php echo JHtml::_('bootstrap.endTabSet'); ?>

			<input type="hidden" name="type" value="" />
			<input type="hidden" name="installtype" value="upload" />
			<input type="hidden" name="task" value="rsgallery2.upload" /> 			
							
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
	<div id="loading"></div>
</div>
	
