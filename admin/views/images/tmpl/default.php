<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2020 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$doc = JFactory::getDocument();
$script = JUri::root(true) . '/administrator/components/com_rsgallery2/js/imagesOrdering.js';
$doc->addScript($script);

global $Rsg2DebugActive;

$sortColumn    = $this->escape($this->state->get('list.ordering')); //Column
$sortDirection = $this->escape($this->state->get('list.direction'));

$user   = JFactory::getUser();
$userId = $user->id;

//$canAdmin = $user->authorise('core.admin', 'com_rsgallery2');
//$canEditStateGallery = $user->authorise('core.edit.state','com_rsgallery2.gallery.'.$row->id);

?>
<script type="text/javascript">

    // Change request from order element of gallery row:
    jQuery(document).ready(function ($) {
        var IsActive = false;

        jQuery(".changeOrder").change(
            function (event) {
                var Idx;
                var element;
                var Count;

                // Don't handle "Enter" otherwise for this control
                event.preventDefault();

                // alert ("Change ?");

                // Exit for reentry check
                if (IsActive == true)
                {
                    alert ("Already started !!!");
                    return;
                }

                // activate re entrance check
                IsActive = true;

                var actElement = event.target;

                // Empty input
                if (actElement.value == '') {
                    alert ("Empty yes");
                    return;
                }

                var Ordering = ImagesOrdering;

                //--- User element order value --------------------------------------

                var strUserOrdering = actElement.value;
                var UserOrdering = parseInt(actElement.value);
                // var UserId = Ordering.GetGalleryId(actElement.id);
                var UserIdString = actElement.id; //

                UserIdString = UserIdString.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
                var UserId = parseInt(UserIdString);

                //--- Check limit user value --------------------------------------

                // Negative value will be corrected to lowest value
                if (UserOrdering < 0) {
                    UserOrdering = 0;
                    actElement.value = UserOrdering;
                }

                // Value higher than the count will be set to highest possible
                /* ==> may be set behind to ensure as last element
                if (UserOrdering > Count) {
                    UserOrdering = Count;
                    actElement.value = UserOrdering;
                }
                /**/

                //--- Fetch database ordering --------------------------------------

                var serverDbOrderingElement = jQuery("#dbOrdering");

                var serverDbOrderingValue = serverDbOrderingElement.val();
                if ((typeof(serverDbOrderingValue) === 'undefined') || (serverDbOrderingValue === null)) {
                    alert("serverDbOrdering is not defined ==> Server ordering values not exsisting");
                    return;
                }

                var oServerDbOrdering = jQuery.parseJSON (serverDbOrderingValue);

                //-----------------------------------------
                // Order by parent / child
                //-----------------------------------------

                // Empty debug array
                Ordering.clearDebugTextArea ();

                //
                Ordering.initialize (oServerDbOrdering);
                //Ordering.displayDbOrderingArray ("(01) initialize");

                // Assign changed ordering to element
                Ordering.InsertUserOrdering (UserId, UserOrdering);
                //Ordering.displayDbOrderingArray ("(03) User ordering added");

                // Sort array by (old) ordering
                Ordering.SortByOrdering ();
                //Ordering.displayDbOrderingArray ("(05) SortByOrdering");

                // Reassign as Versions of $.3.0 may contain no parent child order
                Ordering.ReAssignOrdering (1, 0); // actIdx=1, parentId=0
                //Ordering.displayDbOrderingArray ("(06) ReAssignOrdering");

                // Sort array by (new) ordering
                Ordering.SortByOrdering ();
                //Ordering.displayDbOrderingArray ("(05) SortByOrdering");

                // Values for Get input in PHP
                serverDbOrderingElement.val(JSON.stringify(Ordering.dbOrdering));
                //Ordering.displayDbOrderingArray ("Saved back to 'INSERT'");

                // Save Ordering in HTML elements
                Ordering.AssignNewOrdering ();

                // Deactivate re entrance check
                IsActive = false;
            }
        );

        // For debug purposes: If activated it tells if jscript is working
        // alert ("assign successful");
    });

</script>
<?php
    // echo '$OrderedImages: ' . json_encode($this->dbOrdering) . '<br><br><br><br>';
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
                <?php
                // Search tools bar
                echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
                // I managed to add options as always open
                //echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filtersHidden' => false ($hidden) (true/false) )));
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
                                <?php echo JText::_('COM_RSGALLERY2_NUM'); ?>
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

                            <th width="20%" class="hidden-phone">
                                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $sortDirection, $sortColumn); ?>
                            </th>

                            <th width="10%" class="">
                                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_GALLERY', 'gallery_name', $sortDirection, $sortColumn); ?>
                            </th>

                            <th width="4%" class="center">
                                <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_ORDER', 'a.ordering', $sortDirection, $sortColumn); ?>
                                &nbsp
                                <?php if ($user->authorise('core.edit.state')): ?>
                                    <button id="filter_go" class="btn btn-micro"
                                            onclick="Joomla.submitbutton('images.saveOrdering')"
                                            title="<?php echo JText::_('COM_RSGALLERY2_ASSIGN_CHANGED_ORDER'); ?>">
                                        <i class="icon-save"></i>
                                    </button>
                                <?php endif; ?>
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
                                <?php
                                // echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_COMMENTS', 'a.comments', $sortDirection, $sortColumn);
                                echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_COMMENTS', 'comment_count', $sortDirection, $sortColumn);
                                ?>
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

                        foreach ($this->items as $i => $item)
                        {
                            /**/
                            // Get permissions
                            $canEditOwnImage = $user->authorise('core.edit.own', 'com_rsgallery2.image.' . $item->id) AND ($item->userid == $userId);
                            $canEditImage = $user->authorise('core.edit', 'com_rsgallery2.image.' . $item->id) || $canEditOwnImage;

                            $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;

                            $canEditStateOwnImage = $user->authorise('core.edit.state.own', 'com_rsgallery2.image.' . $item->id) AND ($item->userid == $userId);
                            $canEditStateImage = $user->authorise('core.edit.state', 'com_rsgallery2.image.' . $item->id) || $canEditStateOwnImage;

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
                                        <?php
                                        if ($item->checked_out)
                                        {
                                            echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'images.', $canCheckin);
                                        }
                                        ?>
                                        <strong>

                                            <?php
                                            $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=" . $item->id);

                                            $src   = $this->HtmlPathThumb . $this->escape($item->name) . '.jpg';
                                            $style = '';
                                            //$style .= 'max-width:' . '200' . 'px;';
                                            //$style .= 'max-height:' . '200' . 'px;';
                                            //$style .= 'width:' . '100' . 'px;';
                                            //$style .= ' height:' . '100' . 'px;';
                                            $img = '<img src="' . $src . '" alt="' . $this->escape($item->name) . '" style="' . $style . '" />';
                                            //$img = '<img src=\'' . $src . '\' alt=\'' . $this->escape($item->name) . '\' style=\'' . $style . '\' />';

                                            /**/
                                            echo JHtml::tooltip($img,
                                                JText::_('COM_RSGALLERY2_EDIT_IMAGE'),
                                                $this->escape($item->title),
//                                                      htmlspecialchars(stripslashes($item->title), ENT_QUOTES),
                                                $this->escape($item->title),
                                                $link
                                            ); // display link yes / no
                                            /**/
                                            ?>
                                        </strong>

                                        <?php
                                        /**
                                            <span class="small break-word">
                                                <?php
                                                // echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));
                                                ?>
                                            </span>
                                         */
                                        ?>
                                    </div>
                                </td>
                                <td class="left hidden-phone ">
                                    <div class="pull-left break-word">
                                        <?php
                                        $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=" . $item->id);
                                        //$link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
                                        if ($canEditImage)
                                        {
                                            echo '<a href="' . $link . '">' . $this->escape($item->name) . '</a>';
                                        }
                                        else
                                        {
                                            echo $this->escape($item->name);
                                        }
                                        ?>
                                    </div>
                                </td>

                                <td class="left">
                                    <?php
                                    $link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&task=gallery.edit&id=" . $item->gallery_id);
                                    //$link = JRoute::_("index.php?option=com_rsgallery2&rsgOption=galleries&task=editA&hidemainmenu=1&id=". $item->gallery_id);
                                    //echo '<a href="' . $link . '">' . $item->gallery_id . '</a>';
                                    echo '<a href="' . $link . '">' . $this->escape($item->gallery_name) . '</a>';
                                    ?>
                                </td>

                                <td class="center">
                                    <?php if ($canEditStateImage): ?>
                                        <div class="form-group">
                                            <label class="hidden" for="order[]">Ordering</label>
                                            <input name="order[]" type="number"
                                                    class="input-mini form-control changeOrder"
                                                    min="0" step="1"
                                                    id="ordering_<?php echo $item->id; ?>"
                                                    value="<?php echo $item->ordering; ?>"
                                                    gallery_id="<?php echo $item->gallery_id; ?>">
                                            </input>
                                        </div>
                                    <?php else : ?>
                                        <div class="form-group">
                                            <?php echo $item->ordering; ?>
                                        </div>
                                    <?php endif; ?>
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
                                    <?php
                                    //echo (int) $item->comments;
                                    ?>
                                    <?php echo (int) $item->comment_count; ?>
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

                    <?php // Load the batch processing form. ?>
                    <?php if ($user->authorise('core.create', 'com_rsgallery2')
                        && $user->authorise('core.edit', 'com_rsgallery2')
                        && $user->authorise('core.edit.state', 'com_rsgallery2')
                    ) : ?>
                        <?php echo JHtml::_(
                            'bootstrap.renderModal',
                            'collapseModal',
                            array(
                                'title'  => JText::_('COM_RSGALLERY2_IMAGES_BATCH_OPTIONS'),
                                'footer' => $this->loadTemplate('batch_footer')
                            ),
                            $this->loadTemplate('batch_body')
                        ); ?>
                    <?php endif; ?>

                <?php endif; ?>

                <div>
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="boxchecked" value="0" />

					<!-- keeps the ordering for sending to server -->
					<label for="dbOrdering" style="font-weight: bold; display: none;">dbOrdering:</label>
					<textarea id="dbOrdering" name="dbOrdering" cols="140" rows="15" class="span10"
							  style="display: none;"><?php
							$JsonEncoded = json_encode($this->dbOrdering);
							$HtmlOut = htmlentities($JsonEncoded, ENT_QUOTES, "UTF-8");
							echo  $HtmlOut;
							?></textarea>
					<br>

                    <?php echo JHtml::_('form.token'); ?>
                </div>

            </form>

        </div>

        <div id="loading"></div>
    </div>

