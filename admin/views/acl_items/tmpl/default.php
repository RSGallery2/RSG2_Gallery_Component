<?php // no direct access
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined( '_JEXEC' ) or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');

global $Rsg2DebugActive;

// public static $extension = 'COM_RSG2';

//$doc = JFactory::getDocument();
//$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/Maintenance.css");

$listOrder = '';
$ListDirn = '';

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

				<fieldset class="filter">
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
					<hr class="hr-condensed" />
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
					</div>
				</fieldset>

				<?php if (empty($this->items)) : ?>
                    <div class="alert alert-no-items">
                        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>


				<table class="table table-striped" id="commentsList">
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

                    foreach ($this->items as $acl) {
//	                    echo json_encode($comment) . '<br>';
				?>

	                    <tr>

		                    <td class="center">
			                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
		                    </td>

		                    <td width="1%" class="center">
								<?php
								$link = JRoute::_("index.php?option=com_rsgallery2&view=acl_item&id=".$acl->id);
								echo '<a href="' . $link . '"">' . $acl->id . '</a>';
								?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->gallery_id; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->parent_id; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->public_view; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->public_up_mod_img; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->public_del_img; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->public_create_mod_gal; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->public_del_gal; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->public_vote_view; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->public_vote_vote; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->registered_view; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->registered_up_mod_img; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->registered_del_img; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->registered_create_mod_gal; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->registered_del_gal; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->registered_vote_view; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $acl->registered_vote_vote; ?>
		                    </td>
	                    </tr>

				<?php
                    }
					?>

				</tr>
					</tbody>
				</table>

                <?php endif;?>


				<div>
					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />
					<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />

					<?php echo JHtml::_('form.token'); ?>



				</div>




			</form>

        </div>

	<div id="loading"></div>
</div>

