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

            <form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=galleries'); ?>"
                  method="post" name="adminForm" id="adminForm"class="form-validate form-horizontal" >
            
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

			            <th width="1%" class="center">
				            `id`
			            </th>
			            <th width="1%" class="center">
				            `user_id`
			            </th>
			            <th width="1%" class="center">
				            `parent`
			            </th>
			            <th width="1%" class="center">
				            `name`
			            </th>
			            <th width="1%" class="center">
				            `alias`
			            </th>
			            <th width="1%" class="center">
				            `description`
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
			                `date`
			            </th>
			            <th width="1%" class="center">
			                `hits`
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
			                `access`
			            </th>

			            <?php

			            foreach ($this->items as $gallery) {
			            //	                    echo json_encode($comment) . '<br>';
			            ?>

		            <tr>

			            <td class="center">
				            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
			            </td>

			            <td width="1%" class="center">
							<?php
							$link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&layout=edit&id=".$gallery->id);
							echo '<a href="' . $link . '"">' . $gallery->id . '</a>';
							?>
			            </td>

			            <td width="1%" class="center">
				            <?php echo $gallery->parent; ?>
			            </td>
			            <td width="1%" class="center">
							<?php
							$link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&layout=edit&id=".$gallery->id);
							echo '<a href="' . $link . '"">' . $gallery->name . '</a>';
							?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->alias; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->description; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->published; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->checked_out; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->checked_out_time; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->ordering; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->date; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->hits; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->params; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->user; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->uid; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->allowed; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->thumb_id; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->asset_id; ?>
			            </td>
			            <td width="1%" class="center">
				            <?php echo $gallery->access; ?>
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

