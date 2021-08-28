<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');

global $Rsg2DebugActive;

// public static $extension = 'COM_RSG2';

//$doc = JFactory::getDocument();
//$doc->addStyleSheet (JURI::root(true) . '/administrator/components/com_rsgallery2/css/Maintenance.css');

$listOrder = '';
$ListDirn  = '';

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

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=images'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

				<?php if (empty($this->items)) : ?>
					<div class="alert alert-no-items">
						<?php echo JText::_('COM_RSGALLERY2_GALLERY_HAS_NO_IMAGES_ASSIGNED'); ?>
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
                                    `name`
                                </th>
                                <th width="1%" class="center">
                                    `alias`
                                </th>
                                <th width="1%" class="center">
                                    `descr`
                                </th>
                                <th width="1%" class="center">
                                    `gallery_id`
                                </th>
                                <th width="1%" class="center">
                                    `title`
                                </th>
                                <th width="1%" class="center">
                                    `hits`
                                </th>
                                <th width="1%" class="center">
                                    `date`
                                </th>
                                <th width="1%" class="center">
                                    `rating`
                                </th>
                                <th width="1%" class="center">
                                    `votes`
                                </th>
                                <th width="1%" class="center">
                                    `comments`
                                </th>
                                <th width="1%" class="center">
                                    `published`
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
                                    `approved`
                                </th>
                                <th width="1%" class="center">
                                    `userid`
                                </th>
                                <th width="1%" class="center">
                                    `params`
                                </th>
                                <th width="1%" class="center">
                                    `asset_id`
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
                                        $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=" . $item->id);
                                        echo '<a href="' . $link . '"">' . $item->id . '</a>';
                                        ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php
                                        $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=" . $item->id);
                                        echo '<a href="' . $link . '"">' . $item->name . '</a>';
                                        ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->alias; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->descr; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->gallery_id; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->title; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->hits; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->date; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->rating; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->votes; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->comments; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->published; ?>
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
                                        <?php echo $item->approved; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->userid; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->params; ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php echo $item->asset_id; ?>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>
						</tbody>
					</table>

				<?php endif; ?>
			</form>

		</div>

		<div id="loading"></div>
	</div>
