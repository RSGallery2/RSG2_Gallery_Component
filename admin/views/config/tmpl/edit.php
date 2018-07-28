<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

global $Rsg2DebugActive;

// JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.formvalidator');
//JHtml::_('behavior.keepalive'); 
//JHtml::_('formbehavior.chosen', 'select');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 3));

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

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=config&amp;task=config.edit'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

				<?php echo JHtml::_('bootstrap.startTabSet', 'Config', array('active' => 'Images')); ?>

				<!--- general --->

				<?php echo JHtml::_('bootstrap.addTab', 'Config', 'General', JText::_('COM_RSGALLERY2_GENERAL', true)); ?>

				<?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_general_group', array('active' => 'cfg_general_id_1')); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_general_group',
					JText::_('COM_RSGALLERY2_GENERAL_SETTINGS'), 'cfg_general_id_1'); ?>
				<div class="span6 form-horizontal">
					<fieldset class="adminform">

						<div class="control-group">
							<label class="control-label" for="VersionId"><?php echo JText::_('COM_RSGALLERY2_VERSION') . ':&nbsp;' . $this->rsgVersion; ?></label>
							<div class="controls">
								<label id="VersionId" class="span5 input_box" type="text"></label>
							</div>
						</div>
						<?php
						//--- render general ------------------------------------------------------>
						echo $this->form->renderFieldset('General_Description');
						?>
					</fieldset>
				</div>
				<div class="span3">
					<fieldset class="adminform">
						<?php
						//--- render general ------------------------------------------------------>
						echo $this->form->renderFieldset('General');
						?>
					</fieldset>
				</div>
				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.endAccordion'); ?>

				<?php echo JHtml::_('bootstrap.endTab'); ?>

				<!--- images --->

				<?php echo JHtml::_('bootstrap.addTab', 'Config', 'Images', JText::_('COM_RSGALLERY2_IMAGES', false)); ?>

				<?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_images_group', array('active' => 'cfg_images_id_1')); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group',
					JText::_('COM_RSGALLERY2_IMAGE_MANIPULATION'), 'cfg_images_id_1'); ?>

				<?php
				echo $this->form->renderFieldset('Images_manipulation');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_IMAGE_UPLOAD'), 'cfg_images_id_2'); ?>
				<?php
				/*
				<td width="200">
					<?php echo JText::_('COM_RSGALLERY2_FTP_PATH') ?>
				</td>
				<td>
					<?php echo JText::sprintf('COM_RSGALLERY2_FTP_BASE_PATH', JPATH_SITE.DS); ?><br />
					<input class="text_area" type="text" name="ftp_path" size="50" style="width: 98%;" value="<?php echo $config->ftp_path?>"/><br/><br/>
					<div style="color:#FF0000;font-weight:bold;font-size:smaller;margin-top: 0px;padding-top: 0px;">
						<?php echo JText::_('COM_RSGALLERY2_PATH_MUST_START_WITH_BASE_PATH');?>
					</div>
				</td>
				*/
				?>
				<?php
				echo $this->form->renderFieldset('Images_upload');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_IMAGES_GRAFICS_LIBRARIES'), 'cfg_images_id_3'); ?>

				<?php
				echo $this->form->renderFieldset('Images_graficsLibrary');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_IMAGE_STORAGE'), 'cfg_images_id_4'); ?>

				<?php
				echo $this->form->renderFieldset('Images_storage');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_COMMENTS'), 'cfg_images_id_5'); ?>

				<?php
				echo $this->form->renderFieldset('Images_comments');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_VOTING'), 'cfg_images_id_6'); ?>

				<?php
				echo $this->form->renderFieldset('Images_voting');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.endAccordion'); ?>

				<?php echo JHtml::_('bootstrap.endTab'); ?>

				<!--- display --->

				<?php echo JHtml::_('bootstrap.addTab', 'Config', 'Display', JText::_('COM_RSGALLERY2_DISPLAY', false)); ?>

				<?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_display_group', array('active' => 'cfg_display_id_1')); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group',
					JText::_('COM_RSGALLERY2_FRONT_PAGE'), 'cfg_display_id_1'); ?>

				<?php
				echo $this->form->renderFieldset('Display_frontPage');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_IMAGE_DISPLAY'), 'cfg_display_id_2'); ?>

				<?php
				echo $this->form->renderFieldset('Display_imageDisplay');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_IMAGE_ORDER'), 'cfg_display_id_3'); ?>

				<?php
				echo $this->form->renderFieldset('Display_imageOrder');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_EXIF_SETTINGS'), 'cfg_display_id_4'); ?>

				<?php
				echo $this->form->renderFieldset('Display_exifSettings');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_GALLERY_VIEW'), 'cfg_display_id_5'); ?>

				<?php
				echo $this->form->renderFieldset('Display_galleryView');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_IMAGE_WATERMARK'), 'cfg_display_id_6'); ?>

				<?php
				echo $this->form->renderFieldset('Display_imageWatermark');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.endAccordion'); ?>

				<?php echo JHtml::_('bootstrap.endTab'); ?>

				<!--- My galleries --->

				<?php echo JHtml::_('bootstrap.addTab', 'Config', 'Mygalleries', JText::_('COM_RSGALLERY2_MY_GALLERIES', false)); ?>

				<?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_my_galleries_group_1', array('active' => 'cfg_my_galleries_id_1')); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_my_galleries_group_1',
					JText::_('COM_RSGALLERY2_MY_GALLERIES_SETTINGS'), 'cfg_my_galleries_id_1'); ?>

				<?php
				echo $this->form->renderFieldset('Mygalleries_setting');
				?>
				<?php echo JHtml::_('bootstrap.endSlide'); ?>


				<?php echo JHtml::_('bootstrap.endAccordion'); ?>

				<?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_my_galleries_group_2', array('active' => 'cfg_my_galleries_id_2')); ?>

				<?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_my_galleries_group_2', JText::_('COM_RSGALLERY2_IMAGE_UPLOAD'), 'cfg_my_galleries_id_2'); ?>

				<?php
				echo $this->form->renderFieldset('Mygalleries_upload');
				?>

				<?php echo JHtml::_('bootstrap.endSlide'); ?>

				<?php echo JHtml::_('bootstrap.endAccordion'); ?>

				<?php echo JHtml::_('bootstrap.endTab'); ?>

				<!--- end --->

				<?php echo JHtml::_('bootstrap.endTabSet'); ?>

				<div>
					<input type="hidden" name="task" value="" />

					<?php echo JHtml::_('form.token'); ?>
				</div>

			</form>

		</div>

		<div id="loading"></div>
	</div>

	          