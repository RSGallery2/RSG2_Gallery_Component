<?php // no direct access
defined( '_JEXEC' ) or die();

// JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip'); 

global $Rsg2DebugActive;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive'); 
//JHtml::_('formbehavior.chosen', 'select');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold'=>3));

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

<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=gallery&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="item-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<?php
		// echo '<span style="color:red">Task: Toolbar: Link for Upload, (More rows for combo box)</span><br><br>';
	?>

	<div class="test">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', empty($this->item->id) ? JText::_('COM_RSGALLERY2_NEW') : JText::_('COM_RSGALLERY2_EDIT')); ?>
		<div class="row-fluid">
			<div class="span6">
				<fieldset class="adminform">
					<?php
					echo $this->form->renderField('description');

					echo $this->form->renderField('id');
					?>
				</fieldset>
			</div>
			<div class="span3">
				<fieldset class="adminform">
					<?php
					echo $this->form->renderField('');
					echo $this->form->renderField('published');
					echo $this->form->renderField('ordering');
					echo $this->form->renderField('thumb_id');
					echo $this->form->renderField('uid');
					echo $this->form->renderField('access');
					echo $this->form->renderField('parent');
					?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', '2nd_col', JText::_('COM_RSGALLERY2_PERMISSIONS')); ?>
		<div class="row-fluid">
			<div class="span10">
				<fieldset class="panelform">
					<?php
					echo $this->form->getControlGroups('permission_col');
					?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

</form>

