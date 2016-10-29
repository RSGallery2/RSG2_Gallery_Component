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
// global $rsgConfig;

$sortColumn = $this->escape($this->state->get('list.ordering')); //Column
$sortDirection  = $this->escape($this->state->get('list.direction'));

$user = JFactory::getUser();
$userId = $user->id;

?>
<script type="text/javascript">


    //This will sort your array
    function SortByIntValue(a, b){
        var aValue = parseInt($(a).value, 10);
        var bValue = parseInt($(b).value, 10);
        return aValue - bValue;
    }


    // :
    jQuery(document).ready(function($) {
        //alert ("assign");

        jQuery(".changeOrder").on('keyup mouseup',
            function (event) {
                var Idx;
                var element;
                var Count;

                event.preventDefault();

                var actElement = event.target;

                // Empty input
                if (actElement.value == '') {
                    alert("Test 01 out");
                    return;
                }

                var strActValue = actElement.value;
                var actValue = parseInt(actElement.value);
                var actGallery_id = actElement.getAttribute("gallery_id");

                // Negative value will be corrected to lowest value
                if (actValue < 1) {
                    actValue = 1;
                    actElement.value = actValue;
                }

                var OrderingAll = jQuery(".changeOrder");

                if(OrderingAll === null) {
                    alert("OrderingAll === null");
                }

                Count = OrderingAll.length;

                var Ordering = new Array;
                for (Idx = 0; Idx < Count; Idx++) {

                    element = OrderingAll[Idx];

                    var gallery_id = element.getAttribute("gallery_id");
                    if(actGallery_id == gallery_id) {
//                        alert("Test 03.04");

                        Ordering.push (element);
                    }
                }

                if(Ordering.length == 0)
                {
                    return;
                }

                Count = Ordering.length;

                // Value higher than the count will be set to highest possible
                if (actValue > Count) {
                    actValue = Count;
                    actElement.value = actValue;
                }

                var OutTxt ='';

                // Sort array asc
                Ordering.sort(SortByIntValue);

                // assign changed ordering values
                var ChangeOld = 0;
                for (Idx = 1; Idx <= Count; Idx++) {
                    element = Ordering[Idx-1];

                    var strIdx = Idx.toString();
                    // not matching the changed element
                    if (strActValue != element.value)
                    {
                        // Value different to expected so set it
                        // The orderingIdx should be the Idx value
                        if(element.value != strIdx)
                        {
                            element.value = strIdx;
                        }
                    }
                    else
                    {
                        // Undefined up or down ?
                        // UP: Missing
                        if (ChangeOld == 0)
                        {
    //							alert ("IDX: " + Idx + " " + "Value: " + parseInt(element.value));

                            // New id moved up, hole found
                            if (Idx < parseInt(element.value))
                            {
                                ChangeOld = Idx;
                            }
                            else
                            {
                                // Down: Move old element up
                                ChangeOld = Idx+1;
                            }
                        }

                        // On Old element assign changed value
                        if (actElement.id != element.id)
                        {
                            element.value = ChangeOld.toString();
                        }
                    }
                }

                // Print array order
                OutTxt +='\n';
                for (Idx = 0; Idx < Count; Idx++) {
                    element = Ordering[Idx];

                    OutTxt += element.value + ",";
                }

                /**/

            }
        );

    //alert ("done");
    });

</script>


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
                // OK: echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));

				//echo JLayoutHelper::render('joomla.searchtools.default', $data, null, array('component' => 'none'));
				// I managed to add options as always open
				//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filtersHidden' => false ($hidden) (true/false) )));
				?>
				<div class="pull-right">
					<?php
	                // Specify gallery for move and copy
	                echo $this->form->renderFieldset('Select4MoveCopy');
	                ?>
				</div>

	            <span style="color:red">Task: Search controls, Item preview on hover, owner rights $canChange, $canEdit, order on move, copy</span><br><br>
				<?php
				//echo 'ThumbPath: ' . JPATH_THUMB . '<br>';
				//echo 'ImagePathThumb: ' . $rsgConfig->imgPath_thumb . '<br>';
				//echo 'ImagePathThumb: ' . JURI_SITE . $rsgConfig->get('imgPath_thumb') . '<br>';
				echo $this->HtmlPathThumb . '<br>';
				?>
	            <?php if (empty($this->items)) : ?>
                    <div class="alert alert-no-items">
                        <?php echo JText::_('COM_RSGALLERY2_GALLERY_HAS_NO_IMAGES_ASSIGNED'); ?>
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

				                <th width="20%" class="">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_TITLE', 'a.title', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="20%" class="">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="10%" class="">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_GALLERY', 'gallery_name', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="4%" class="center">
					                <?php echo JHtml::_('searchtools.sort',  'COM_RSGALLERY2_ORDER', 'a.ordering', $sortDirection, $sortColumn); ?>
					                &nbsp
					                <button id="filter_go" class="btn btn-micro"
						                onclick="Joomla.submitbutton('images.saveOrdering')"
						                title="<?php echo JText::_( 'COM_RSGALLERY2_ASSIGN_CHANGED_ORDER'); ?>">
						                <i class="icon-save"></i>
					                </button>
				                </th>

				                <th width="8%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_DATE__TIME', 'a.date', $sortDirection, $sortColumn); ?>
				                </th>


				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_VOTES', 'a.votes', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_RATING', 'a.rating', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_COMMENTS', 'a.comments', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $sortDirection, $sortColumn); ?>
				                </th>

				                <th width="1%" class="center nowrap hidden-phone">
					                <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $sortDirection, $sortColumn); ?>
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
	/**/
                                // Get permissions
                                $canEditGallery      = $user->authorise('core.edit',      'com_rsgallery2.image.'.$item->id);
                                $canEditOwnGallery   = $user->authorise('core.edit.own',  'com_rsgallery2.image.'.$item->id) AND ($item->userid == $userId);
                                $canEditStateGallery = $user->authorise('core.edit.state','com_rsgallery2.image.'.$item->id);
					            $canCheckin          = $user->authorise('core.manage',    'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;

					            ?>
								<tr>
									<td>
										<?php echo $this->pagination->getRowOffset($i); ?>
									</td>

						            <td>
							            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
						            </td>

									<td>
										<?php echo JHtml::_('jgrid.published', $item->published, $i); //, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
									</td>
									<td class="left has-context">
										<div class="pull-left break-word">
											<?php if ($item->checked_out) : ?>
												<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'images.', $canCheckin);
												?>
											<?php endif; ?>

								            <?php
								            $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=".$item->id);

								            $src = $this->HtmlPathThumb. $item->name . '.jpg';
								            $style = '';
								            //$style .= 'max-width:' . '200' . 'px;';
								            //$style .= 'max-height:' . '200' . 'px;';
								            //$style .= 'width:' . '100' . 'px;';
								            //$style .= ' height:' . '100' . 'px;';
								            $img = '<img src="' . $src . '" alt="' . $item->name . '" style="' . $style . '" />';

								            echo '<strong>';
								            if ($canEdit || $canEditOwn)
								            {
									            /**/
									            echo JHtml::tooltip($img,
										            JText::_('COM_RSGALLERY2_EDIT_IMAGE'),
										            $item->title,
										            htmlspecialchars(stripslashes($item->title), ENT_QUOTES),
										            $link,
										            1);
									            /**/
								            }
								            else
								            {
									            /**
									            echo '    <span title="' . JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)) . '">';
									            //echo '    ' . $PreTitle . $this->escape($item->name);
									            echo         $this->escape($item->title);
									            echo '    </span>';
									            /**/
									            echo JHtml::tooltip($img,
										            JText::_('COM_RSGALLERY2_EDIT_IMAGE'),
										            $item->title,
										            htmlspecialchars(stripslashes($item->title), ENT_QUOTES));
								            }
								            echo '</strong>';
								            ?>

											<span class="small break-word">
												<?php
												// echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));
												?>
											</span>
										</div>
						            </td>
						            <td class="left">
							            <div class="pull-left break-word">
								            <?php
								            //$link = JRoute::_("index.php?option=com_rsgallery2&view=image&layout=edit&id=".$item->id);
								            $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=".$item->id);
								            //$link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
								            echo '<a href="' . $link . '"">' . $item->name . '</a>';
								            ?>
							            </div>
						            </td>

						            <td class="left">
							            <?php
							            //$link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&layout=edit&id=".$item->gallery_id);
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&task=gallery.edit&id=".$item->gallery_id);
							            //$link = JRoute::_("index.php?option=com_rsgallery2&rsgOption=galleries&task=editA&hidemainmenu=1&id=". $item->gallery_id);
										//echo '<a href="' . $link . '"">' . $item->gallery_id . '</a>';
										echo '<a href="' . $link . '"">' . $item->gallery_name . '</a>';
										?>
						            </td>

						            <td class="center">
							            <div class="form-group">
								            <label class="hidden" for="order[]">Ordering</label>
								            <input  name="order[]"  type="number"
                                                class="input-mini form-control changeOrder"
                                                min="0" step="1"
                                                id="ordering_<?php echo $item->id; ?>"
                                                value="<?php echo $item->ordering; ?>"
                                                gallery_id="<?php echo $item->gallery_id; ?>"
								            </input>
							            </div>
						            </td>


						            <td class="nowrap small hidden-phone center">
							            <?php echo JHtml::_('date', $item->date, JText::_('COM_RSGALLERY2_DATE_FORMAT_WITH_TIME')); ?>
						            </td>


						            <td class="hidden-phone center">
							            <?php echo (int) $item->votes; ?>
						            </td>


						            <td class="hidden-phone center">
							            <?php echo (int) $item->rating; ?>
						            </td>

						            <td class="hidden-phone center">
							            <?php echo (int) $item->comments; ?>
						            </td>


						            <td class="hidden-phone center">
							            <?php echo (int) $item->hits; ?>
						            </td>

						            <td>
							            <?php echo (int) $item->id; ?>
							            <input type="hidden" name="ids[]" value="<?php echo (int) $item->id; ?>" />
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

