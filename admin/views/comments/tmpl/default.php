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

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

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
									<?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_IMAGE', 'a.item_id', $listDirn, $listOrder); ?>
								</th>

								<th width="20%" class="center">
									<?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_COMMENTS', 'a.comment', $listDirn, $listOrder); ?>
								</th>

								<th width="10%" class="center">
									<?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $listDirn, $listOrder); ?>
								</th>






								<th width="1%" class="nowrap hidden-phone">
								<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
							</th>

						</tr>

					</thead>
					<tbody>
						<?php

                        foreach ($this->items as $i => $comment) {
//	                        echo json_encode($comment) . '<br>';
							if($i > 5)
							{
//							    break;
							}
	                    
							//			            $authorName = JFactory::getUser($item->uid);
							?>


	                        <tr>

			                    <td width="1%" class="center">
				                    <?php echo JHtml::_('grid.checkall'); ?>
			                    </td>

		                        <td width="1%" class="center">
			                        <?php echo $comment->item_id; ?>
		                        </td>

		                        <td width="1%" class="center">
			                        <?php echo $comment->comment; ?>
		                        </td>


		                        <td width="1%" class="center">
			                        <?php echo $comment->user_name; ?>
		                        </td>






		                        <td width="1%" class="center">
			                        <?php echo $comment->id; ?>
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

