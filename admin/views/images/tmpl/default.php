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

            <form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=images'); ?>"
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

	                <table class="table table-striped" id="imagessList">
		                <thead>
			                <tr>

				                <th width="1%" class="center">
					                <?php echo JHtml::_('grid.checkall'); ?>
				                </th>

								<th width="1%" style="min-width:55px" class="nowrap center">
									<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
								</th>

				                <th width="10%" class="center">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_TITLE', 'a.title', $listDirn, $listOrder); ?>
				                </th>

				                <th width="10%" class="center">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $listDirn, $listOrder); ?>
				                </th>

				                <th width="1%" class="center">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_GALLERY', 'a.gallery', $listDirn, $listOrder); ?>
				                </th>


				                <th width="1%" class="center">
					                <?php echo JHtml::_('searchtools.sort',  'COM_RSGALLERY2_ORDER', 'a.ordering', $listDirn, $listOrder); ?>
					                &nbsp;<button id="filter_go" onclick="this.form.submit();" class="btn btn-micro" title="<?php echo "test title"; ?>"><i class="icon-save"></i></button>
				                </th>


				                <th width="10%" class="nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.date', $listDirn, $listOrder); ?>
				                </th>


				                <th width="1%" class="nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_VOTES', 'a.votes', $listDirn, $listOrder); ?>
				                </th>

				                <th width="1%" class="nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_RATING', 'a.rating', $listDirn, $listOrder); ?>
				                </th>

				                <th width="1%" class="nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_COMMENTS', 'a.votes', $listDirn, $listOrder); ?>
				                </th>

				                <th width="1%" class="nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
				                </th>

				                <th width="1%" class="nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				                </th>

			                </tr>
		                </thead>
		                <tbody>

			                <?php

				            foreach ($this->items as $i => $item) {
		//	                    echo json_encode($comment) . '<br>';

					            //$canChange  = $user->authorise('core.edit.state', 'com_content.article.' . $item->id) && $canCheckin;
					            $canChange  = true;
					            $canEdit  = true;
					            $canEditOwn  = true;

					            if($i > 5)
					            {
						        //    break;
					            }
		//			            $authorName = JFactory::getUser($item->uid);
					            ?>

					            <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->id; ?>">

						            <td width="1%" class="center">
							            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
						            </td>

						            <td width="1%" class="center">
							            <?php
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=item&id=".$item->id);

							            $link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=galleries&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
							            echo '<a href="' . $link . '"">' . $item->id . '</a>';
							            ?>
						            </td>
						            <td width="1%" class="center">
							            <?php
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=item&id=".$item->id);
							            echo '<a href="' . $link . '"">' . $item->title . '</a>';
							            ?>
						            </td>
						            <td width="1%" class="center">
							            <?php
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=item&id=".$item->id);
							            echo '<a href="' . $link . '"">' . $item->name . '</a>';
							            ?>
						            </td>

						            <td width="1%" class="center">
							            <?php
							            $link = 'index.php?option=com_rsgallery2&rsgOption=galleries&task=editA&hidemainmenu=1&id='. $item->gallery_id;
							            echo '<a href="' . $link . '"">' . $item->gallery_id . '</a>';
							            ?>
						            </td>

						            <td width="1%" class="center">
							            <div class="form-group">
								            <label class="hidden" for="ordering_<?php echo $i; ?>">Ordering</label>
								            <input class="input-mini" type="number" min="0" step="1" class="form-control" id="ordering_<?php echo $i; ?>"
									            placeholder="<?php echo $item->ordering; ?>">
								            </input>
							            </div>
						            </td>


						            <td class="nowrap small hidden-phone">
							            <?php echo JHtml::_('date', $item->date, JText::_('DATE_FORMAT_LC4')); ?>
						            </td>













						            <td class="hidden-phone">
							            <?php echo (int) $item->votes; ?>
						            </td>


						            <td class="hidden-phone">
							            <?php echo (int) $item->rating; ?>
						            </td>

						            <td class="hidden-phone">
							            <?php echo (int) $item->comments; ?>
						            </td>


						            <td class="hidden-phone">
							            <?php echo (int) $item->hits; ?>
						            </td>

						            <td class="hidden-phone">
							            <?php echo (int) $item->id; ?>
						            </td>





					            </tr>

	                        <?php
                            }
	                        ?>

		                </tbody>
	                </table>

                <?php endif;?>
            </form>

        </div>

	<div id="loading"></div>
</div>
