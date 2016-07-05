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
							`user_id`
							</th>
							<th width="1%" class="center">
							`user_name`
							</th>
							<th width="1%" class="center">
							`user_ip`
							</th>
							<th width="1%" class="center">
							`parent_id`
							</th>
							<th width="1%" class="center">
							`item_id`
							</th>
							<th width="1%" class="center">
							`item_table`
							</th>
							<th width="1%" class="center">
							`datetime`
							</th>
							<th width="1%" class="center">
							`subject`
							</th>
							<th width="1%" class="center">
							`comment`
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
							`params`
							</th>
							<th width="1%" class="center">
							`hits`
							</th>
						</tr>
					</thead>
					<tbody>

				<?php

                    foreach ($this->items as $comment) {
//	                    echo json_encode($comment) . '<br>';
				?>

	                    <tr>

		                    <td width="1%" class="center">
			                    <?php echo JHtml::_('grid.checkall'); ?>
		                    </td>

		                    <td width="1%" class="center">
								<?php
								$link = JRoute::_("index.php?option=com_rsgallery2&view=comment&id=".$comment->id);
								echo '<a href="' . $link . '"">' . $comment->id . '</a>';
								?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->user_id; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->user_name; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->user_ip; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->parent_id; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->item_id; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->item_table; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->datetime; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->subject; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->comment; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->published; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->checked_out; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->checked_out_time; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->ordering; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->params; ?>
		                    </td>
		                    <td width="1%" class="center">
			                    <?php echo $comment->hits; ?>
		                    </td>
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

