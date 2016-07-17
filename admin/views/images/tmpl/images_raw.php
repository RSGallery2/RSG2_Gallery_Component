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

            <form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=images'); ?>"
                  method="post" name="adminForm" id="adminForm"class="form-validate form-horizontal" >
            
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

		                foreach ($this->items as $image) {
//	                    echo json_encode($comment) . '<br>';
			                ?>

			                <tr>

				                <td width="1%" class="center">
					                <?php echo JHtml::_('grid.checkall'); ?>
				                </td>

				                <td width="1%" class="center">
									<?php
									$link = JRoute::_("index.php?option=com_rsgallery2&view=image&layout=edit&id=".$image->id);
									echo '<a href="' . $link . '"">' . $image->id . '</a>';
									?>
				                </td>
				                <td width="1%" class="center">
									<?php
									$link = JRoute::_("index.php?option=com_rsgallery2&view=image&layout=edit&id=".$image->id);
									echo '<a href="' . $link . '"">' . $image->name . '</a>';
									?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->alias; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->descr; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->gallery_id; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->title; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->hits; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->date; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->rating; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->votes; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->comments; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->published; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->checked_out; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->checked_out_time; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->ordering; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->approved; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->userid; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->params; ?>
				                </td>
				                <td width="1%" class="center">
					                <?php echo $image->asset_id; ?>
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
