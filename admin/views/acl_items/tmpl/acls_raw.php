<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

global $Rsg2DebugActive;

// public static $extension = 'COM_RSG2';

//$doc = JFactory::getDocument();
//$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/Maintenance.css");

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

							<th width="1%" class="center">
								<?php echo JHtml::_('grid.checkall'); ?>
							</th>

							<th width="1%" class="center">
								`id`
							</th>
							<th width="1%" class="center">
								`gallery_id`
							</th>
							<th width="1%" class="center">
								`parent_id`
							</th>
							<th width="1%" class="center">
								`public_view`
							</th>
							<th width="1%" class="center">
								`public_up_mod_img`
							</th>
							<th width="1%" class="center">
								`public_del_img`
							</th>
							<th width="1%" class="center">
								`public_create_mod_gal`
							</th>
							<th width="1%" class="center">
								`public_del_gal`
							</th>
							<th width="1%" class="center">
								`public_vote_view`
							</th>
							<th width="1%" class="center">
								`public_vote_vote`
							</th>
							<th width="1%" class="center">
								`registered_view`
							</th>
							<th width="1%" class="center">
								`registered_up_mod_img`
							</th>
							<th width="1%" class="center">
								`registered_del_img`
							</th>
							<th width="1%" class="center">
								`registered_create_mod_gal`
							</th>
							<th width="1%" class="center">
								`registered_del_gal`
							</th>
							<th width="1%" class="center">
								`registered_vote_view`
							</th>
							<th width="1%" class="center">
								`registered_vote_vote`
							</th>
						</tr>
						</thead>
						<tbody>

						<?php

						foreach ($this->items as $i => $item)
						{
//	                    echo json_encode($comment) . '<br>';
							?>

							<tr>

								<td class="center">
									<?php echo JHtml::_('grid.id', $i, $item->id); ?>
								</td>

								<td width="1%" class="center">
									<?php
									$link = JRoute::_("index.php?option=com_rsgallery2&view=acl_item&task=acl_item.edit&id=" . $item->id);
									echo '<a href="' . $link . '"">' . $item->id . '</a>';
									?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->gallery_id; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->parent_id; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->public_view; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->public_up_mod_img; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->public_del_img; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->public_create_mod_gal; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->public_del_gal; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->public_vote_view; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->public_vote_vote; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->registered_view; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->registered_up_mod_img; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->registered_del_img; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->registered_create_mod_gal; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->registered_del_gal; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->registered_vote_view; ?>
								</td>
								<td width="1%" class="center">
									<?php echo $item->registered_vote_vote; ?>
								</td>

							</tr>

							<?php
						}
						?>

						</tr>
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

