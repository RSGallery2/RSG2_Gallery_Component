<?php // no direct access
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined( '_JEXEC' ) or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

global $Rsg2DebugActive;

// public static $extension = 'COM_RSG2';

//$doc = JFactory::getDocument();
//$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/Maintenance.css");

$sortColumn = $this->escape($this->state->get('list.ordering'));
$sortDirection  = $this->escape($this->state->get('list.direction')); //Column

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

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=comments'); ?>"
				  method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" >


				<?php
				// Search tools bar
				echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				?>

				<!--fieldset class="filter">
					<div class="btn-toolbar">
						<div class="btn-group">
							<label for="filter_search">
								<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
							</label>
						</div>
						<div class="btn-group">
							<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
						</div>
						<div class="btn-group">
							<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>" data-placement="bottom">
								<span class="icon-search"></span><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
							<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" data-placement="bottom" onclick="document.getElementById('filter_search').value='';this.form.submit();">
								<span class="icon-remove"></span><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
						</div>
					</div>
					<!--hr class="hr-condensed" />
					<div class="filters">
						<select name="filter_access" class="input-medium" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
							<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
						</select>

						<select name="filter_published" class="input-medium" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
							<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
						</select>

						<?php if ($this->state->get('filter.forcedLanguage')) : ?>
							<select name="filter_category_id" class="input-medium" onchange="this.form.submit()">
								<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
								<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_content', array('filter.language' => array('*', $this->state->get('filter.forcedLanguage')))), 'value', 'text', $this->state->get('filter.category_id'));?>
							</select>
							<input type="hidden" name="forcedLanguage" value="<?php echo $this->escape($this->state->get('filter.forcedLanguage')); ?>" />
							<input type="hidden" name="filter_language" value="<?php echo $this->escape($this->state->get('filter.language')); ?>" />
						<?php else : ?>
							<select name="filter_category_id" class="input-medium" onchange="this.form.submit()">
								<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
								<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_content'), 'value', 'text', $this->state->get('filter.category_id'));?>
							</select>
							<select name="filter_language" class="input-medium" onchange="this.form.submit()">
								<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
								<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
							</select>
						<?php endif; ?>
					</div >
				</fieldset -->

				<?php if (empty($this->items)) : ?>
	                <div class="alert alert-no-items">
	                    <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
	                </div>
                <?php else : ?>

					<table class="table table-striped table-hover" id="commentsList">
						<thead>
							<tr>
								<th width="1%"><?php echo JText::_( 'COM_RSGALLERY2_NUM' ); ?></th>

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

						// echo json_encode($this->items) . '<br>';

                        foreach ($this->items as $i => $item) {
//	                        echo json_encode($comment) . '<br>';
							if($i > 5)
							{
//							    break;
							}
	                    
							//			            $authorName = JFactory::getUser($item->uid);
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
			                        $link = JRoute::_("index.php?option=com_rsgallery2&view=comment&layout=edit&id=".$item->id);
			                        echo '<a href="' . $link . '"">' . $item->id . '</a>';
			                        ?>
		                        </td>


								<td width="1%" class="">
									<?php
									$link = JRoute::_("index.php?option=com_rsgallery2&view=comment&layout=edit&id=".$item->id);
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

								<td class="hidden-phone">
									<?php echo (int) $item->hits; ?>
								</td>

								<td class="hidden-phone">
			                        <?php echo $item->id; ?>
		                        </td>

	                        </tr>

						<?php
	                    }
						?>

					</tbody>
				</table>

                <?php endif;?>


				<div>
					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />
					<input type="hidden" name="filter_order" value="<?php echo $sortColumn; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php echo $sortDirection; ?>" />

					<?php echo JHtml::_('form.token'); ?>



				</div>

            </form>

        </div>

	<div id="loading"></div>
</div>

