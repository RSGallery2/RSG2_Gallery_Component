<?php
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined( '_JEXEC' ) or die();

JHtml::_('behavior.tooltip');

global $Rsg2DebugActive;

// JHtml::_('formbehavior.chosen', 'select');

?>

<div id="maintenance-database" class="clearfix">
	<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=maintDatabase'); ?>"
		method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" >

	<?php if (!empty( $this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
	        <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
    <?php else : ?>
		<div id="j-main-container">
	<?php endif;?>

		<?php if ($this->errorCount === 0) : ?>
			<div class="alert alert-info">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<?php echo JText::_('COM_RSGALLERY2_MSG_DATABASE_OK'); ?>
			</div>
			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'other')); ?>
		<?php else : ?>

			<div class="alert alert-error">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<?php echo JText::_('COM_RSGALLERY2_MSG_DATABASE_ERRORS'); ?>
			</div>
			
			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'problems')); ?>

				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'problems', JText::plural('COM_RSGALLERY2_MSG_N_DATABASE_ERROR_PANEL', $this->errorCount)); ?>

 					<fieldset class="panelform">
					    <ul>
						    <?php foreach ($this->errors as $line)
						    {
							    echo '<li>' . $line . '</li>';
						    }

						    ?>
					    </ul>
					</fieldset>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		<input type="hidden" value="" name="task">

		<?php echo JHtml::_('form.token'); ?>

		</div>
	</form>
</div>
