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

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

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

            <form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=galleries'); ?>"
                  method="post" name="adminForm" id="adminForm"class="form-validate form-horizontal" >

	            <?php
	            // Search tools bar
	            //$data = $displayData;
	            //echo JLayoutHelper::render('joomla.searchtools.default.bar', $data, null, array('component' => 'none'));
	            //echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	            ?>

	            <?php if (empty($this->items)) : ?>
                    <div class="alert alert-no-items">
                        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>

				<table class="table table-striped" id="commentsList">
		            <tbody>
		            <tr>

			            <th width="1%" class="center">
				            <?php echo JHtml::_('grid.checkall'); ?>
			            </th>

						<th width="1%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
						</th>

			            <th width="1%" class="center">
				            <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $listDirn, $listOrder); ?>
			            </th>

			            <th width="1%" class="center">
				            <?php echo JText::_('COM_RSGALLERY2_IMAGES'); ?>&nbsp;
			            </th>

			            <th width="10%" class="nowrap hidden-phone">
				            <?php echo JHtml::_('searchtools.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
			            </th>

			            <th width="10%" class="nowrap hidden-phone">
				            <?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
			            </th>

			            <th width="10%" class="nowrap hidden-phone">
				            <?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
			            </th>


			            <th width="1%" class="nowrap hidden-phone">
				            <?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
			            </th>

			            <th width="1%" class="nowrap hidden-phone">
				            <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
			            </th>


			            <!--

			            <th width="1%" class="center">
				            `parent`
			            </th>
			            <th width="1%" class="center">
				            `alias`
			            </th>
			            <th width="1%" class="center">
				            `description`
			            </th>
			            <th width="1%" class="center">
				            `checked_out`
			            </th>
			            <th width="1%" class="center">
				            `checked_out_time`
			            </th>
			            <th width="1%" class="center">
				            `ordering`
			            </th>
			            <th width="1%" class="center">
			                `date`
			            </th>
			            <th width="1%" class="center">
				            <?php echo JText::_('COM_RSGALLERY2_HITS')?>
			            </th>
			            <th width="1%" class="center">
			                `params`
			            </th>
			            <th width="1%" class="center">
				            `user`
			            </th>
			            <th width="1%" class="center">
				            `uid`
			            </th>
			            <th width="1%" class="center">
				            `allowed`
			            </th>
			            <th width="1%" class="center">
				            `thumb_id`
			            </th>
			            <th width="1%" class="center">
			                `asset_id`
			            </th>
			            <th width="1%" class="center">
				            <?php echo JText::_('JGRID_HEADING_ACCESS')?>
			            </th>
-->
			         </tr>

		            <?php

		            foreach ($this->items as $i => $item) {
		            //	                    echo json_encode($comment) . '<br>';

			            //$canChange  = $user->authorise('core.edit.state', 'com_content.article.' . $item->id) && $canCheckin;
			            $canChange  = true;
			            $canEdit  = true;
			            $canEditOwn  = true;

			            $authorName = JFactory::getUser($item->uid);



		            ?>

			            <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->id; ?>">

							<td width="1%" class="center">
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							</td>

							<td width="1%" class="center">
								<?php echo JHtml::_('jgrid.published', $item->published, $i); //, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
							</td>
				            <td width="10%" class="left has-context">
					            <!--div class="pull-left break-word" -->
						            <?php
						            if ($canEdit || $canEditOwn)
						            {
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&id=" . $item->id);
							            $link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=galleries&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
							            // echo '<a class="gallery-link" name="Edit Gallery" href="' . $link . '">' . $item->name . '</a>';

							            echo '<a class="hasTooltip" href="' . $link  . '" title="' . JText::_('JACTION_EDIT') . '">';
										echo '    ' . $this->escape($item->name);
							            echo '</a>';
						            } else {
							            echo '<span title="' . JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)) . '">';
						                echo '    ' . $this->escape($item->name);
							            echo '</span>';
						            }

						            ?>
				            </td>

				            <td width="5%" class="center">
						            <a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=images&gallery_id='.$item->id); ?>"
							            title="<?php echo JText::_('COM_RSGALLERY2_IMAGES_LIST'); ?>"
						            >
							            (&nbsp;<sub><span class="icon-image" style="font-size: 1.6em;"></span></sub>)
						            </a>
				            </td>

				            <td class="small hidden-phone">
					            ??
					            <!--?php echo $item->access_level;?-->
					            <!--?php echo $this->escape($item->access_level); ?-->
				            </td>

				            <td class="small hidden-phone">
					            <?php echo $this->escape($authorName->name); ?></a>
				            </td>

				            <td class="nowrap small hidden-phone">
					            <?php echo JHtml::_('date', $item->date, JText::_('DATE_FORMAT_LC4')); ?>
				            </td>



				            <td class="hidden-phone">
					            <?php echo (int) $item->hits; ?>
				            </td>

				            <td class="hidden-phone">
					            <?php echo (int) $item->id; ?>
				            </td>




				            <!--


														<td width="1%" class="center">
															<?php echo $item->parent; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->alias; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->description; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->checked_out; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->checked_out_time; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->ordering; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->hits; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->params; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->user; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->uid; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->allowed; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->thumb_id; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->asset_id; ?>
														</td>
														<td width="1%" class="center">
															<?php echo $item->access; ?>
														</td>

							-->


















			            </tr>

		            <?php
		            }
		            ?>

		            </tr>
		            </tbody>
	            </table>

                <?php endif;?>

            </form>

        </div>

	<div id="loading"></div>
</div>

