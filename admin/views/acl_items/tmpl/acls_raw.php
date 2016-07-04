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

		                    <td width="1%" class="center">
			                    <?php echo JHtml::_('grid.checkall'); ?>
		                    </td>

		                    <td width="1%" class="center">
	                            <?php echo $acl->id; ?>
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
            </form>

        </div>

	<div id="loading"></div>
</div>

