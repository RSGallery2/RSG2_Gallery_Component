<?php // no direct access
defined( '_JEXEC' ) or die();

// JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip'); 

global $Rsg2DebugActive;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive'); 
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "comment.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
		{
			Joomla.submitform(task, document.getElementById("item-form"));
		}
	};
');

?>

<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=edit&layout=edit&id=' . (int) $this->item->id); ?>"
		method="post" name="adminForm" id="item-form" class="form-validate">

	<?php // echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', empty($this->item->id) ? JText::_('COM_RSGALLERY2_NEW_COMMENT') : JText::_('COM_RSGALLERY2_COMMENT')); ?>
		<div class="row-fluid">
			<div class="span6">
				<?php
				echo $this->form->getControlGroups('image_1st_col');
				?>
			</div>
			<!--div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div-->
		</div>
		<div class="row-fluid">
			<div class="span6">
				<?php
				echo $this->form->getControlGroups('image_2nd_col');
				?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', '2nd_col', JText::_('COM_RSGALLERY2_FIELDSET_RULES')); ?>
		<div class="row-fluid">
			<div class="span6">
				<fieldset class="panelform">
				<?php
					// echo $this->form->getControlGroups('image_2nd_col');
				?>
                <?php echo $this->form->getLabel('rules'); ?>
				<?php echo $this->form->getInput('rules'); ?>

				</fieldset>
			</div>
			<!--div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div-->
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

