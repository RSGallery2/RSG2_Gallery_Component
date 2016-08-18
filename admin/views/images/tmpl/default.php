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

$sortColumn = $this->escape($this->state->get('list.ordering')); //Column
$sortDirection  = $this->escape($this->state->get('list.direction'));

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
                  method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" >

				<?php
				// Search tools bar
				echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				//echo JLayoutHelper::render('joomla.searchtools.default', $data, null, array('component' => 'none'));
				// I managed to add options as always open
				//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filtersHidden' => false ($hidden) (true/false) )));
				?>


	            <?php if (empty($this->items)) : ?>
                    <div class="alert alert-no-items">
                        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>

	                <table class="table table-striped table-hover" id="imagessList">
		                <thead>
			                <tr>
								<th width="1%">
									<?php echo JText::_( 'COM_RSGALLERY2_NUM' ); ?>
								</th>

				                <th width="1%" class="center">
					                <?php echo JHtml::_('grid.checkall'); ?>
				                </th>

								<th width="1%" style="min-width:55px" class="nowrap center">
									<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.published', $sortDirection, $sortColumn); ?>
								</th>

				                <th width="10%" class="center">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_TITLE', 'a.title', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="10%" class="center">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="1%" class="center">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_GALLERY', 'gallery_name', $sortDirection, $sortColumn); ?>
				                </th>


				                <th width="1%" class="center">
					                <?php echo JHtml::_('searchtools.sort',  'COM_RSGALLERY2_ORDER', 'a.ordering', $sortDirection, $sortColumn); ?>
					                &nbsp
					                <button id="filter_go" class="btn btn-micro"
						                onclick="Joomla.submitbutton('images.saveOrdering')"
						                title="<?php echo JText::_( 'COM_RSGALLERY2_ASSIGN_CHANGED_ORDER'); ?>">
						                <i class="icon-save"></i>
					                </button>

				                </th>


				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_DATE__TIME', 'a.date', $sortDirection, $sortColumn); ?>
				                </th>


				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_VOTES', 'a.votes', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_RATING', 'a.rating', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_COMMENTS', 'a.votes', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="1%" class="center nowrap hidden-phone">
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

				            foreach ($this->items as $i => $item) {
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

								<tr>
									<td>
										<?php echo $this->pagination->getRowOffset($i); ?>
									</td>


						            <td width="1%" class="center">
							            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
						            </td>

									<td width="1%" class="center">
										<?php echo JHtml::_('jgrid.published', $item->published, $i); //, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
									</td>
						            <!--td width="1%" class="center">
							            <?php
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=image&layout=edit&id=".$item->id);
							            $link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
							            echo '<a href="' . $link . '"">' . $item->id . '</a>';
							            ?>
						            </td-->
						            <td width="1%" class="center">
							            <?php
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=image&layout=edit&id=".$item->id);
							            $link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
							            echo '<a href="' . $link . '"">' . $item->title . '</a>';
										// <!--img class= "tooltips-link" title="TESTING" etc etc etc code /-->
							            // <?php echo JHTML::tooltip('This is a tooltip attached to text', 'Text Tooltip Title', 'tooltip.png');? >

							            ?>
						            </td>
						            <td width="1%" class="center">
							            <?php
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=image&layout=edit&id=".$item->id);
							            $link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
							            echo '<a href="' . $link . '"">' . $item->name . '</a>';
							            ?>
						            </td>

						            <td width="1%" class="center">
							            <?php
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&layout=edit&id=".$item->gallery_id);
							            $link = JRoute::_("index.php?option=com_rsgallery2&rsgOption=galleries&task=editA&hidemainmenu=1&id=". $item->gallery_id);
										//echo '<a href="' . $link . '"">' . $item->gallery_id . '</a>';
										echo '<a href="' . $link . '"">' . $item->gallery_name . '</a>';
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
							            <?php echo JHtml::_('date', $item->date, JText::_('COM_RSGALLERY2_DATE_FORMAT_WITH_TIME')); ?>
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

	            <div>
		            <input type="hidden" name="task" value="" />
		            <input type="hidden" name="boxchecked" value="0" />

		            <?php echo JHtml::_('form.token'); ?>
	            </div>

            </form>

        </div>

	<div id="loading"></div>
</div>

