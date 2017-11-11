<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

global $Rsg2DebugActive;
// global $rsgConfig;

/**
$sortColumn    = $this->escape($this->state->get('list.ordering')); //Column
$sortDirection = $this->escape($this->state->get('list.direction'));
/**/

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
			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=imagesProperties'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />

						<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
	</div>

