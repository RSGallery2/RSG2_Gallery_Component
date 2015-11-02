<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<form action="<?php echo JRoute::_('index.php?option=com_rsg2&view=uploadsingle'); ?>" method="post" name="adminForm" id="adminForm">

	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>
		
		<div class="clearfix"> </div>

		<?php echo 'rsg2.php content update single view: shall be control center ....'; ?>

		
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
	
