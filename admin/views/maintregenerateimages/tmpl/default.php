<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();


JHtml::_('bootstrap.tooltip');

global $Rsg2DebugActive;

JHtml::_('formbehavior.chosen', 'select');

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

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=maintRegenerateImages'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

				<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'regenerateImages')); ?>

				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'regenerateImages', JText::_('COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY', true)); ?>

				<fieldset class="regenerateImages">
					<legend><?php echo JText::_('COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY'); ?></legend>

					<!-- List info  -->
					<div class="control-group">
						<label for="zip_file" class="control-label"> </label>
						<div class="controls">
							<!--input type="text" id="zip_file" name="zip_file" class="span5 input_box" size="70" value="http://" /-->
							<div class="span5">
								<strong><?php echo JText::_('COM_RSGALLERY2_SELECT_GALLERIES_TO_REGENERATE_THUMBNAILS_FROM'); ?></strong>
							</div>
						</div>
					</div>

					<!-- Specify galleries  -->
					<?php
					echo $this->form->renderFieldset('regenerateGallerySelection');
					?>

					<div class="control-group">
						<label for="xxx" class="control-label"><?php echo JText::_('COM_RSGALLERY2_CONFIGURATION'); ?>:</label>
						<div class="controls" class="span5">
							<?php echo JText::sprintf('COM_RSGALLERY2_NEW_WIDTH_DISPLAY', $this->imageWidth) ?>.
							<?php echo JText::sprintf('COM_RSGALLERY2_NEW_WIDTH_THUMB', $this->thumbWidth) ?>
						</div>
					</div>

					<!-- Action button -->
					<div class="form-actions">
						<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('maintRegenerate.RegenerateImagesDisplay')"><?php echo JText::_('COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY'); ?></button>
						&nbsp;&nbsp;&nbsp;
						<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('maintRegenerate.RegenerateImagesThumb')"><?php echo JText::_('COM_RSGALLERY2_MAINT_REGEN_THUMBS'); ?></button>
					</div>
				</fieldset>
				<?php echo JHtml::_('bootstrap.endTab'); ?>

				<?php echo JHtml::_('bootstrap.endTabSet'); ?>

				<input type="hidden" value="" name="task">

				<?php echo JHtml::_('form.token'); ?>

			</form>
		</div>
		<div id="loading"></div>
	</div>
</div>
