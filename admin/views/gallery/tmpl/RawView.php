<?php // no direct access
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined( '_JEXEC' ) or die();

JHtml::_('behavior.tooltip');

global $Rsg2DebugActive;

// public static $extension = 'COM_RSG2';

//$doc = JFactory::getDocument();
//$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/Maintenance.css");


?>

<div id="installer-install" class="clearfix">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=config'); ?>"
			      method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" >

				<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'ConfigRawView')); ?>

					<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'ConfigRawView', JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW', true)); ?>

						<legend><?php echo JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'); ?></legend>
						<strong><?php echo JText::_('COM_RSGALLERY2_CONFIG_MINUS_VIEW_TXT'); ?></strong>
						<?php

						//echo '<pre>';
						//    print_r( get_object_vars($this->rsgConfigData) );
						//echo '</pre>';
						echo '<pre>';
							echo json_encode(get_object_vars($this->rsgConfigData), JSON_PRETTY_PRINT);
					    echo '</pre>';
						?>

					<?php echo JHtml::_('bootstrap.endTab'); ?>

				<?php echo JHtml::_('bootstrap.endTabSet'); ?>

				<!--input type="hidden" name="option" value="com_rsgallery2" />
				<input type="hidden" name="rsgOption" value="maintenance" />

				<input type="hidden" name="task" value="" /-->
		            <?php echo JHtml::_('form.token'); ?>
			    </div>
			</form>
		</div>
	<div id="loading"></div>
</div>
</div>

