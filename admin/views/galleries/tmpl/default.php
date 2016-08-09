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

$sortColumn = $this->escape($this->state->get('list.ordering')); //Column
$sortDirection  = $this->escape($this->state->get('list.direction'));

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

				event.preventDefault();

//				alert($( this ).text());
//				alert(event.target.nodeName);
				var actElement = event.target;
//				var actId = actElement.id;

				// Empty input
				if (actElement.value == '') {
					return;
				}

				var strActValue = actElement.value;
				var actValue = parseInt(actElement.value);

//				alert('Id: ' + actElement.id + ' value: ' + actElement.value);

				// Negative value will be corrected to lowest value
				if (actValue < 1) {
					actValue = 1;
					actElement.value = actValue;
				}
/**/
				var Ordering = jQuery(".changeOrder");
				var Count = Ordering.length;

				// Value higher than the count will be set to highest possible
				if (actValue > Count) {
					actValue = Count;
					actElement.value = actValue;
				}


				var OutTxt ='';
/**
				for (Idx = 0; Idx < Count; Idx++) {
					element = Ordering[Idx];

					OutTxt += element.value + ",";
//					alert ("Idx: " + Idx + " " + "Value:" + element.value)
//					if (Idx == 3)
//						break;
				}
				alert(OutTxt);
/**/

				// Sort array asc
				Ordering.sort(SortByIntValue);
/**
				// Print array order
				OutTxt +='\n';
				for (Idx = 0; Idx < Count; Idx++) {
					element = Ordering[Idx];

					OutTxt += element.value + ",";
//					alert ("Idx: " + Idx + " " + "Value:" + element.value)
//					if (Idx == 3)
//						break;
				}
//				alert(OutTxt);

/**/

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
//					alert ("Idx: " + Idx + " " + "Value:" + element.value)
//					if (Idx == 3)
//						break;
				}
//				alert(OutTxt);

/**/

//				alert("changed");
			}
		);

		//alert ("done");
	});

</script>

<?php
// ToDo: <strong>Needed changes:</strong>&nbsp;Status as renamed variable:different in article&nbsp;publish_up/don as sql var &nbsp;Parts !! <br> <br>

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

				<table class="table table-striped table-hover" id="galleriesList">
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

			            <th width="10%" class="">
				            <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $sortDirection, $sortColumn); ?>
			            </th>

			            <th width="1%" class="center">
				            <?php echo JHtml::_('searchtools.sort',  'COM_RSGALLERY2_IMAGES', 'image_count', $sortDirection, $sortColumn); ?>
			            </th>

			            <th width="1%" class="center nowrap hidden-phone">
				            <?php echo JHtml::_('searchtools.sort',  'JGRID_HEADING_ACCESS', 'a.access', $sortDirection, $sortColumn); ?>
			            </th>

			            <th width="1%" class="center nowrap hidden-phone">
				            <?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $sortDirection, $sortColumn); ?>
			            </th>


						<th width="1%" class="center">
							<?php echo JHtml::_('searchtools.sort',  'COM_RSGALLERY2_ORDER', 'a.ordering', $sortDirection, $sortColumn); ?>
							&nbsp
							<button id="filter_go" class="btn btn-micro"
								onclick="Joomla.submitbutton('galleries.saveOrdering')"
								title="<?php echo JText::_( 'COM_RSGALLERY2_ASSIGN_CHANGED_ORDER'); ?>">
                                <i class="icon-save"></i>
                            </button>
						</th>

						<th width="1%" class="center nowrap hidden-phone">
				            <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_DATE__TIME', 'a.created', $sortDirection, $sortColumn); ?>
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

			            $authorName = JFactory::getUser($item->uid);
			            
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
                                <?php
                                /**
                                ?>
                                <div class="btn-group">
									<?php echo JHtml::_('jgrid.published', $item->state, $i, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
									<!--?php echo JHtml::_('contentadministrator.featured', $item->featured, $i, $canChange); ?-->
									<?php // Create dropdown items and render the dropdown list.
									if ($canChange)
									{
										JHtml::_('actionsdropdown.' . ((int) $item->state === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'articles');
										JHtml::_('actionsdropdown.' . ((int) $item->state === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'articles');
										echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
									}
									?>
								</div>
                                /**/
                                ?>
							</td>
				            <td width="10%" class="left has-context">
					            <!--div class="pull-left break-word" -->
					            <strong>
						            <?php
						            if ($canEdit || $canEditOwn)
						            {
							            $link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&layout=edit&id=" . $item->id);
							            //$link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=galleries&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
							            echo '<a class="hasTooltip" href="' . $link  . '" title="' . JText::_('JACTION_EDIT') . '">';
										echo '    ' . $this->escape($item->name);
							            echo '</a>';
						            } else {
							            echo '<span title="' . JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)) . '">';
						                echo '    ' . $this->escape($item->name);
							            echo '</span>';
						            }

						            ?>
						        </strong>
				            </td>

				            <td width="5%" class="center">
					            <?php
								$imageCount = 0;
					            // $imageCount = $this->GalleriesModel->countImages ($item->id);
								if (!empty($item->image_count))
					                $imageCount = $item->image_count;

								if ($imageCount == 0) {
									?>
									<a class="badge badge-inverse">
										0
									</a>
									<?php
								} else {
									?>
						            <a class="badge badge-success"
									   href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=images&gallery_id='.$item->id); ?>"
									   title="<?php echo JText::_('COM_RSGALLERY2_IMAGES_LIST'); ?>"
									>
										<?php
										echo $imageCount;
										?>
					        	    </a>
									<!--
									&nbsp;
									<a
									   href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=images&gallery_id='.$item->id); ?>"
									   title="<?php echo JText::_('COM_RSGALLERY2_IMAGES_LIST'); ?>"
									>
										<sub><span class="icon-image" style="font-size: 1.6em;"></span></sub>
									</a>
									-->
								<?php
								} ?>


				            </td>

				            <td class="small hidden-phone center">
					            <!--?php echo $item->access_level;?-->
					            <?php echo $this->escape($item->access_level); ?>
				            </td>

							<td class="small hidden-phone center">
								<?php echo $this->escape($authorName->name); ?></a>
							</td>


							<td width="1%" class="center">
								<div class="form-group">
									<label class="hidden" for="ordering_<?php echo $i; ?>">Ordering</label>
                                    <!--input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " /-->
                                    <input name="order[]" type="number"
                                        class="input-mini form-control changeOrder"
	                                    min="0" step="1"
	                                    id="ordering_<?php echo $item->id; ?>"
										value="<?php echo $item->ordering; ?>">
									</input>
								</div>
							</td>


				            <td class="nowrap small hidden-phone center">
					            <?php echo JHtml::_('date', $item->date, JText::_('COM_RSGALLERY2_DATE_FORMAT_WITH_TIME')); ?>
				            </td>

				            <td class="hidden-phone center">
					            <?php echo (int) $item->hits; ?>
				            </td>

				            <td class="hidden-phone center">
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

