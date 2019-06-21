<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2019 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

global $Rsg2DebugActive;

// public static $extension = 'COM_RSG2';

$sortColumn    = $this->escape($this->state->get('list.ordering')); //Column
$sortDirection = $this->escape($this->state->get('list.direction'));

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

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=comments'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
				<?php
				// Search tools bar
				echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				//echo JLayoutHelper::render('joomla.searchtools.default', $data, null, array('component' => 'none'));
				// I managed to add options as always open
				//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filtersHidden' => false ($hidden) (true/false) )));
				?>

				<?php if (empty($this->items)) : ?>
					<div class="alert alert-no-items">
						<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</div>
				<?php else : ?>

					<table class="table table-striped table-hover" id="commentsList">
						<thead>
						<tr>
							<th width="1%">
								<?php echo JText::_('COM_RSGALLERY2_NUM'); ?>
							</th>

							<th width="1%" class="center">
								<?php echo JHtml::_('grid.checkall'); ?>
							</th>

							<th width="5%" class="center">
								<?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_IMAGE', 'item_id', $sortDirection, $sortColumn); ?>
							</th>

							<th width="5%" class="center">
								<?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_TITLE', 'subject', $sortDirection, $sortColumn); ?>
							</th>

							<th width="20%" class="center">
								<?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_COMMENTS', 'comment', $sortDirection, $sortColumn); ?>
							</th>

							<th width="10%" class="center">
								<?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'user_name', $sortDirection, $sortColumn); ?>
							</th>

							<th width="10%" class="center">
								<?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_USER_IP', 'user_ip', $sortDirection, $sortColumn); ?>
							</th>

							<th width="10%" class="center">
								<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_FIELD_CREATED_DESC', 'datetime', $sortDirection, $sortColumn); ?>
							</th>

							<th width="10%" class="center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'published', $sortDirection, $sortColumn); ?>
							</th>

							<th width="1%" class="nowrap hidden-phone">
								<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'hits', $sortDirection, $sortColumn); ?>
							</th>

							<th width="1%" class="nowrap hidden-phone">
								<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $sortDirection, $sortColumn); ?>
							</th>

						</tr>

						</thead>
						<tfoot>
						<tr>
							<td colspan="15">
								<?php echo $this->pagination->getListFooter(); ?>
							</td>
						</tr>
						</tfoot>
						<tbody>
						<?php

						// $canCheckin = $user->authorise('core.manage',    'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;

						foreach ($this->items as $i => $item)
						{
							?>
							<tr>
								<td>
									<?php echo $this->pagination->getRowOffset($i); ?>
								</td>

								<td class="center">
									<?php echo JHtml::_('grid.id', $i, $item->id); ?>
								</td>

								<td width="1%" class="center">
									<?php
									$link = JRoute::_("index.php?option=com_rsgallery2&view=comment&task=comment.edit&id=" . $item->id);
									echo '<a href="' . $link . '"">' . $item->item_id . '</a>';
									?>
								</td>

								<td width="1%" class="">
									<?php
									$link = JRoute::_("index.php?option=com_rsgallery2&view=comment&task=comment.edit&id=" . $item->id);
									echo '<a href="' . $link . '"">' . $item->subject . '</a>';
									?>
								</td>

								<td width="1%" class="">
									<?php echo $item->comment; ?>
								</td>

								<td width="1%" class="center">
									<?php echo $item->user_name; ?>
								</td>

								<td width="1%" class="center">
									<?php echo $item->user_ip; ?>
								</td>

								<td width="1%" class="center">
									<?php echo $item->datetime; ?>
								</td>

								<td width="1%" class="center">
									<?php echo $item->published; ?>
								</td>

								<td class="hidden-phone">
									<?php echo (int) $item->hits; ?>
								</td>

								<td class="hidden-phone">
									<?php echo (int) $item->id; ?>
									<input type="hidden" name="ids[]" value="<?php echo (int) $item->id; ?>" />
								</td>

							</tr>

							<?php
						}
						?>

						</tbody>
					</table>

				<?php endif; ?>

				<div>
					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />

					<?php echo JHtml::_('form.token'); ?>
				</div>

			</form>

		</div>

		<div id="loading"></div>
	</div>

