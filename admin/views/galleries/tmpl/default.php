<?php // no direct access
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

global $Rsg2DebugActive;

$sortColumn    = $this->escape($this->state->get('list.ordering')); //Column
$sortDirection = $this->escape($this->state->get('list.direction'));

$user   = JFactory::getUser();
$userId = $user->id;

//$canAdmin = $user->authorise('core.admin', 'com_rsgallery2');
//$canEditStateGallery = $user->authorise('core.edit.state','com_rsgallery2.gallery.'.$row->id);

?>
<script type="text/javascript">

	// This will sort the array
	function SortByIntOrdering(a, b) {
	    /**
		var aValue = parseInt($(a).ordering, 10);
		var bValue = parseInt($(b).ordering, 10);
		return aValue - bValue;
        /**/
        return a.ordering - b.ordering;
    }

    /**
     * Keeps server database gallery objects (Id,Parent, ordering, name)
     */
    var dbOrdering;


    function displayDbOrderingArray (Title) {
        OutText = Title + ":\n";
        for (var dbGallery of dbOrdering) {
            OutText += JSON.stringify(dbGallery) + "\n";
        }

        alert (OutText);
    }


	/**
     * Remove child parent value if parent doesn't exist
     */
    function RemoveOrphanIds ()
    {
        for (var dbGallery of dbOrdering) {


            if (dbGallery.parent != 0) {

                if (!IsParentExisting (dbGallery.parent)) {
                    outText = "Orphan:" + JSON.stringify(dbGallery)
                    alert(outText);
//                    dbGallery.parent = 0;
                }
            }
        }

        return;
    }

    function IsParentExisting (ParentId)
    {
        var bIsParentExisting = false;

        for (var dbGallery of dbOrdering) {
            if (dbGallery.Id == ParentId)
            {
                bIsParentExisting = True;
                break;
            }
        }

        return bIsParentExisting;
    }

    function SortByOrdering ()
    {
        var SortedOrdering = dbOrdering.slice(0);

        // alert ("SortedOrdering");
        SortedOrdering.sort(SortByIntOrdering);

        dbOrdering = SortedOrdering;
        // alert ("Return SortedOrdering");

        return;
    }

    // Array must be ordered before
    // Array must be sorted after

    function InsertChangedOrdering (UserId, UserOrdering)
    {
        var IsGalleryHandled = false;

        // All
        var OutText = "";

        OutText += "dbOrdering.length: " + dbOrdering.length + ", ";
        OutText += "UserId: " + UserId + ", ";
        OutText += "UserOrdering: " + UserOrdering  + "\n\r";
        // alert(OutText);

        // OutText = "";
        for (var ActIdx = 0; ActIdx < dbOrdering.length; ActIdx++) {
            var Gallery = dbOrdering[ActIdx];
            var ActOrdering = Number(ActIdx) + Number(1)
            OutText += "'" + ActOrdering + "': ";

            // Element may be late or early, so initialize it
            if (Gallery.id == UserId)
            {
                Gallery.ordering = UserOrdering;
                IsGalleryHandled = true;

                OutText += "(==)" + Gallery.ordering + ", ";
            }
            else
            {
                if (!IsGalleryHandled) {
                    // Element above user ordering is one higher then index
                    if (ActOrdering >= UserOrdering)
                    {
                        Gallery.ordering = Number(ActOrdering) + Number(1);

                        OutText += "(akt>)" + Gallery.ordering + ", ";
                    }
                    else
                    {
                        OutText += "(!=akt>)" + Gallery.ordering + ", ";
                    }
                }
                else
                {
                    if (ActOrdering >= UserOrdering)
                    {
                        Gallery.ordering = ActOrdering;

                        OutText += "==>" + Gallery.ordering + ", ";
                    }
                    else
                    {
                        OutText += "(!=)" + Gallery.ordering + ", ";
                    }
                }
            }
        }
        OutText += "\n\r";
        // alert("ActOrdering: " + OutText);

        return;
    }

    function GetOrderingValue (GalleryId)
    {
        var ordering = -1;

        for (var dbGallery of dbOrdering) {
            // Gallery item found
            if(dbGallery.Id == GalleryId) {
                ordering = dbGallery.ordering;
                break;
            }
        }

        return ordering;
    }

    function AssignNewOrdering (OrderingElements, OrderingElementsCount)
    {
        var bIsParentExisting = false;

        var OutText = "AssignNewOrdering: \n\r";

        OrderingElements.each(function (ActIdx, value) {

            OutText += "ActIdx: " + ActIdx + ": ";
            OutText += "OrderingElement: " + $( this ).value;
            // + JSON.stringify (value) + "\r\n";
            //OutText += "key: " + key + " ";
            OutText += "\r\n";
        });

        alert(OutText);

        return;
    }

    // ToDo: collect ParentId. array{users} field and work with it to sort
    // Reassign as Versions of $.3.0 may contain no parent child order
    // Recursive assignment of ordering  (child direct after parent)
    // May leave out some ordering numbers
    function PreAssignOrdering(actIdx=1, parentId=0) {

        // Assign Order 1..n to each parent.
        // Children get the ordering direct after parent.
        // So the next parent may have bigger distance
        // than one to the previous parent
        for (var dbGallery of dbOrdering) {
            //alert("dbGallery " + JSON.stringify(dbGallery));

            if (dbGallery.parent == parentId) {
                dbGallery.ordering = actIdx;
                actIdx++;

                // recursive call of ordering on child
                actIdx = PreAssignOrdering(actIdx, dbGallery.id);

            }
        }

        return actIdx;
    }
    
	// Change request from order element of gallery row:
	jQuery(document).ready(function ($) {
		// alert ("before event assign");

		jQuery(".changeOrder").on('keyup mouseup',
			function (event) {
				var Idx;
				var element;
				var Count;

                //alert ("event happening");

				event.preventDefault();

				var actElement = event.target;

				// Empty input
				if (actElement.value == '') {
					return;
				}

                //--- User element order value --------------------------------------

				var strUserOrdering = actElement.value;
                var UserOrdering = parseInt(actElement.value);
                var UserIdString = actElement.id; //
                UserIdString = UserIdString.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
                var UserId = parseInt(UserIdString);

                //--- Check limit user value --------------------------------------

                // Negative value will be corrected to lowest value
				if (UserOrdering < 1) {
					UserOrdering = 1;
					actElement.value = UserOrdering;
				}

                var OrderingElements = jQuery(".changeOrder");
                var OrderingElementsCount = OrderingElements.length;

                // Value higher than the count will be set to highest possible
                if (UserOrdering > Count) {
                    UserOrdering = Count;
                    actElement.value = UserOrdering;
                }

                //--- Fetch database ordering --------------------------------------

                var serverDbOrderingElement = jQuery("#dbOrdering");
                // alert("Value: '" + serverDbOrderingElement.val() + "'");

                var serverDbOrderingValue = serverDbOrderingElement.val();
                if ((typeof(serverDbOrderingValue) === 'undefined') || (serverDbOrderingValue === null)) {
                    alert("serverDbOrdering is not defined ==> Server ordering values not exsisting");
                    return;
                }

                //alert ("Before DbOrdering object");
                oServerDbOrdering = jQuery.parseJSON (serverDbOrderingValue);

                //-----------------------------------------
                // Order by parent / child
                //-----------------------------------------

                // Global value for following functions
                dbOrdering = oServerDbOrdering;
                //displayDbOrderingArray ("Orginal");

                RemoveOrphanIds ();
                //displayDbOrderingArray ("Remove Orphans");

                // Reassign as Versions of $.3.0 may contain no parent child order
                PreAssignOrdering ();
                //displayDbOrderingArray ("After PreAssignOrdering");

                // Sort array by (new) ordering
                SortByOrdering ();
                //displayDbOrderingArray ("After sort (1)");

                // Use user assigned ordering value
                InsertChangedOrdering (UserId, UserOrdering);
                //displayDbOrderingArray ("InsertChangedOrdering");

                // Sort array by (new) ordering
                SortByOrdering ();
                displayDbOrderingArray ("After sort(2)");

                serverDbOrderingElement.val(JSON.stringify(dbOrdering));
                //alert("AssignNewOrdering + Save back");
                displayDbOrderingArray ("Save back to 'INSERT'");

                // Save Ordering in HTML elements
                AssignNewOrdering (OrderingElements);

			}
		);
		/**/

		alert ("assign succesful");
	});

</script>

<?php
//    echo '$OrderedGalleries: ' . json_encode($this->dbOrdering) . '<br><br><br><br>';
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

        <form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=galleries'); ?>"
                method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
            <?php
            // Search tools bar
            echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
            //echo JLayoutHelper::render('joomla.searchtools.default', $data, null, array('component' => 'none'));
            // I managed to add options as always open
            //echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filtersHidden' => false ($hidden) (true/false) )));
            ?>

            <?php if (empty($this->items)) : ?>
                <div class="alert alert-no-items">
                    <?php echo JText::_('COM_RSGALLERY2_NO_GALLERY_ASSIGNED'); ?>
                </div>
            <?php else : ?>

                <table class="table table-striped table-hover" id="galleriesList">
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

                        <th width="10%" class="">
                            <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $sortDirection, $sortColumn); ?>
                        </th>

                        <th width="1%" class="center">
                            <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_IMAGES', 'image_count', $sortDirection, $sortColumn); ?>
                        </th>

                        <th width="1%" class="center">
                            <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_PARENT_ID', 'a.parent', $sortDirection, $sortColumn); ?>
                        </th>

                        <th width="1%" class="center nowrap hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'a.access', $sortDirection, $sortColumn); ?>
                        </th>

                        <th width="1%" class="center nowrap hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', 'JAUTHOR', 'a.created_by', $sortDirection, $sortColumn); ?>
                        </th>

                        <th width="1%" class="center">
                            <?php
                            echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_ORDER', 'a.ordering', $sortDirection, $sortColumn);
                            ?>
                            &nbsp
                            <?php if ($user->authorise('core.edit.state')): ?>
                                <button id="filter_go" class="btn btn-micro"
                                        onclick="Joomla.submitbutton('galleries.saveOrdering')"
                                        title="<?php echo JText::_('COM_RSGALLERY2_ASSIGN_CHANGED_ORDER'); ?>">
                                    <i class="icon-save"></i>
                                </button>
                            <?php endif; ?>
                        </th>

                        <th width="1%" class="center nowrap hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', 'COM_RSGALLERY2_DATE__TIME', 'a.date', $sortDirection, $sortColumn); ?>
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

                        // Get permissions
                        $canEditOwnGallery = $user->authorise('core.edit.own', 'com_rsgallery2.gallery.' . $item->id) AND ($item->uid == $userId);
                        $canEditGallery = $user->authorise('core.edit', 'com_rsgallery2.gallery.' . $item->id) || $canEditOwnGallery;

                        $canCheckIn = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;

                        //$canChange  = $user->authorise('core.edit.state', 'com_content.article.' . $item->id) && $canCheckIn;
                        $canEditStateOwnGallery = $user->authorise('core.edit.state.own', 'com_rsgallery2.gallery.' . $item->id) AND ($item->uid == $userId);
                        $canEditStateGallery = $user->authorise('core.edit.state', 'com_rsgallery2.gallery.' . $item->id) || $canEditStateOwnGallery || $canCheckIn;

                        // ToDo: Use function
                        $Depth    = 0;
                        $PreTitle = '';
                        $parent   = $item;
                        while ($parent->parent != 0 && $Depth < 10)
                        {
                            // $PreTitle = '[' . $parent->parent  . '] ' . $PreTitle ;
                            $PreTitle =
                                '&nbsp;<span class="icon-arrow-right-3"></span>'
                                . '(' . $parent->parent . ')'
                                . $PreTitle;
                            $Depth += 1;
                            $found = false;
                            foreach ($this->items as $checkItem)
                            {
                                if ($checkItem->id == $parent->parent)
                                {
                                    $parent = $checkItem;
                                    $found  = true;

                                    break;
                                }
                            }

                            if (!$found)
                            {
                                break;
                            }
                        }

                        if ($PreTitle != '')
                        {
                            //$PreTitle .= ' ';
                            //$PreTitle = '.   ' . $PreTitle . ' ';
                            $PreTitle = '&nbsp;&nbsp;&nbsp;&nbsp;' . $PreTitle . ' ';
                            // $PreTitle = ' <span class="icon-arrow-right-3"></span>' . $PreTitle;
                        }

                        /**
                         * $CanOrder = .. Check parent
                         * $treename
                         * /**/
                        $authorName = JFactory::getUser($item->uid);
                        /**/
                        ?>
                        <tr>
                            <td>
                                <?php echo $this->pagination->getRowOffset($i); ?>
                            </td>

                            <td width="1%" class="center">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>

                            <td width="1%" class="center">
                                <?php
                                echo JHtml::_('jgrid.published', $item->published, $i); //, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down);
                                //echo JHtml::_('jgrid.published', $item->published, $i, 'galleries.', $canChange); //, 'cb', $item->publish_up, $item->publish_down);
                                ?>
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
                                <div class="pull-left break-word">
                                    <?php
                                    if ($item->checked_out)
                                    {
                                        echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'galleries.', $canCheckIn);
                                    }
                                    ?>
                                    <strong>
                                        <?php
                                        /*
                                        // Checked out and not owning this item OR not allowed to edit (own) gallery: show name, else show linked name
                                        if ( $row->checked_out && ( $row->checked_out != $user->id ) OR !($can['EditGallery'] OR $can['EditOwnGallery'])) {
                                        echo stripslashes($row->treename);
                                        /**/
                                        if ($canEditGallery)
                                        {
                                            echo $PreTitle;

                                            $link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&task=gallery.edit&id=" . $item->id);
                                            //$link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=galleries&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
                                            echo '<a class="hasTooltip" href="' . $link . '" title="' . JText::_('JACTION_EDIT') . '">';
                                            //echo '    ' . $PreTitle . $this->escape($item->name);
                                            echo $this->escape($item->name);
                                            echo '</a>';
                                        }
                                        else
                                        {
                                            echo $PreTitle;

                                            echo '<span title="' . JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)) . '">';
                                            //echo '    ' . $PreTitle . $this->escape($item->name);
                                            echo $this->escape($item->name);
                                            echo '</span>';
                                        }

                                        ?>
                                    </strong>
                                    <span class="small break-word">
                                    <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                                </span>
                                </div>
                            </td>

                            <td width="5%" class="center">
                                <?php
                                $imageCount = 0;
                                if (!empty($item->image_count))
                                {
                                    $imageCount = $item->image_count;
                                }

                                //$link = JRoute::_("index.php?option=com_rsgallery2&view=images&gallery_id=" . $item->id);
                                $link = JRoute::_("index.php?option=com_rsgallery2&view=images&filter[gallery_id]=" . (int) $item->id);
                                //$link = JRoute::_('index.php?option=com_rsgallery2&rsgOption=images&gallery_id='.$item->id);
                                // &filter[search]=uid:' . (int) $userId

                                if ($imageCount == 0)
                                {
                                    ?>
                                    <a class="badge ">
                                        0
                                    </a>
                                    <a disabled="disabled" onclick="return false;" class="disabled"
                                            href="<?php echo $link; ?>"
                                    >
                                        <sub><span class="icon-arrow-right-2" style="font-size: 1.6em;"></span></sub>
                                    </a>

                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a class="badge badge-success"
                                            href="<?php echo $link; ?>"
                                            title="<?php echo JText::_('COM_RSGALLERY2_IMAGES_LIST'); ?>"
                                    >
                                        <?php
                                        echo $imageCount;
                                        ?>
                                    </a>

                                    <a
                                        href="<?php echo $link; ?>"
                                        title="<?php echo JText::_('COM_RSGALLERY2_IMAGES_LIST'); ?>"
                                    >
                                        <sub><span class="icon-arrow-right-2" style="font-size: 1.6em;"></span></sub>
                                    </a>

                                    <?php
                                } ?>
                            </td>

                            <td class="small hidden-phone center">
                                <?php echo $this->escape($item->parent); ?>
                            </td>

                            <td class="small hidden-phone center">
                                <?php echo $this->escape($item->access_level); ?>
                            </td>

                            <td class="small hidden-phone center">
                                <?php echo $this->escape($authorName->name); ?>
                            </td>

                            <td width="1%" class="center">
                                <?php if ($canEditStateGallery): ?>
                                    <div class="form-group">
                                        <label class="hidden" for="order[]">Ordering</label>
                                        <input name="order[]" type="number"
                                                class="input-mini changeOrder form-control"
                                                min="0" step="1"
                                                id="ordering_<?php echo $item->id; ?>"
                                                value="<?php echo $item->ordering; ?>"
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

            <?php endif; ?>

            <div>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />

                <input id="dbOrdering" name="dbOrdering" class="input-xxlarge" value="<?php
                    $JsonEncoded = json_encode($this->dbOrdering);
                    $HtmlOut = htmlentities($JsonEncoded, ENT_QUOTES, "UTF-8");
                    //echo "input dbOrdering start:<br>" . $HtmlOut;
                    echo  $HtmlOut;
                ?>" />

                <?php echo JHtml::_('form.token'); ?>
            </div>

        </form>
	</div>

	<div id="loading"></div>
</div>

