/**
 * @package     RSGallery2
 *
 * resorting galleries in galleries view
 *
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * @since       4.3.0
 */

var GalleriesOrdering = {
    /**
     * Keeps server database gallery objects (Id,Parent, ordering, name)
     */
    dbOrdering: [],

    //  @param {Array.<{myNumber: Number, myString: String, myArray: Array}>} myObjects


    /**
     * initialize
     * @param {Array} dbOrdering array ('id', 'ordering', 'parent', 'name')
     */
    // constructor (dbOrdering) {
    initialize: function (dbOrdering) {
        this.dbOrdering = dbOrdering;
    },

    // This will sort the array
    SortByIntOrdering: function (a, b) {
        /**
         var aValue = parseInt($(a).ordering, 10);
         var bValue = parseInt($(b).ordering, 10);
         return aValue - bValue;
         /**/
        return a.ordering - b.ordering;
    },

    /**
     * Debug displays the actual values of the array leaded by
     * @param {string} Title
     */
    displayDbOrderingArray: function (Title) {
        var OutText;

        OutText = Title + ": (lenght:" + this.dbOrdering.length + ") \n";
        for (var idx = 0; idx < this.dbOrdering.length; idx++) {
            OutText += JSON.stringify(this.dbOrdering[idx]) + "\n";
        }
        OutText += "\n";

        alert (OutText);

        this.add2DebugTextArea(OutText)
    },

    /**
     * Do empty the debug view area
     */
    clearDebugTextArea: function () {
        jQuery("#debug").val("");
    },

    /**
     * Add Text to debug view area
     *
     * @param {string} OutText string Text to be displayed
     */
    add2DebugTextArea: function (OutText) {
        var ElementValue;
        var Element;
        Element = jQuery("#debug");
        ElementValue = Element.text() + OutText;
        Element.val (ElementValue);
    },

    /**
     * Move the changed element from previous 'ordering'
     * to the place indicated by UserOrdering
     * It will move up or down all elements within
     *
     * @param {number} UserId Used to find the source HTML element and previous ordering
     * @param {number} UserOrdering required changed ordering
     */
    InsertUserOrdering: function (UserId, UserOrdering) {
        var LimitLower;
        var LimitUpper;
        var PrevOrdering;
        var bDirMoveUp;
        var ActOrdering;
        var MovedOrdering;

        // No change ?
        PrevOrdering = parseInt(this.GetOrderingValue(UserId));
        UserOrdering = parseInt(UserOrdering);
        if (PrevOrdering == UserOrdering) {
            return;
        }

        if (PrevOrdering < UserOrdering) {
            LimitLower = PrevOrdering;
            LimitUpper = UserOrdering;
            bDirMoveUp = true;
        }
        else {
            LimitLower = UserOrdering;
            LimitUpper = PrevOrdering;
            bDirMoveUp = false;
        }

        // Move elements between lower and upper
        for (var idx = 0; idx < this.dbOrdering.length; idx++) {

            // Assign new ordering on user element
            if (this.dbOrdering[idx].id == UserId) {
                this.dbOrdering[idx].ordering = UserOrdering;
            }
            else {
                ActOrdering = parseInt(this.dbOrdering[idx].ordering);

                // Moving area
                if (LimitLower <= ActOrdering && ActOrdering <= LimitUpper) {

                    if (bDirMoveUp) {
                        // Make space below new ordering
                        MovedOrdering = parseInt(this.dbOrdering[idx].ordering) - 1;
                        /**
                         alert ("idx: " + idx
                         + " Id:" + this.dbOrdering[idx].id
                         + " Up"
                         + " ActOrdering: " + ActOrdering
                         + " MovedOrdering" + MovedOrdering);
                         /**/
                    }
                    else {
                        // Make space above new ordering
                        MovedOrdering = parseInt(this.dbOrdering[idx].ordering) + 1;
                        /**
                         alert ("idx: " + idx
                         + " Id:" + this.dbOrdering[idx].id
                         + " Up"
                         + " ActOrdering: " + ActOrdering
                         + " MovedOrdering" + MovedOrdering);
                         /**/
                    }

                    this.dbOrdering[idx].ordering = MovedOrdering;
                    /**
                     alert ("idx: " + idx
                     + " Id:" + this.dbOrdering[idx].id
                     + " After"
                     + " ActOrdering: " + ActOrdering
                     + " MovedOrdering" + MovedOrdering
                     + " Changed: " + this.dbOrdering[idx].ordering);
                     /**/
                }
            }
        }
        /**/

        // alert ("exit");

        return;
    },

    /**
     * Remove child parent value if parent doesn't exist
     * Will have no parent now locally.
     * Replace it with parent id '0'.
     */
    RemoveOrphanIds: function () {

        //for (var dbGallery of this.dbOrdering) {
        for (var idx = 0; idx < this.dbOrdering.length; idx++) {

            if (this.dbOrdering[idx].parent != 0) {

                if (!this.IsParentExisting(this.dbOrdering[idx].parent)) {
                    var outText = "Orphan:" + JSON.stringify(this.dbOrdering[idx]) + "\n"
                    this.add2DebugTextArea(outText);
                    this.dbOrdering[idx].parent = 0;
                }
            }
        }

        return;
    },

    /**
     * Determines if given ID does exist in gallery array
     * @param {number} ParentId ID which is searched
     * @returns {boolean} true if exists
     */
    IsParentExisting: function (ParentId) {
        var bIsParentExisting = false;

        //for (var dbGallery of this.dbOrdering) {
        for (var idx = 0; idx < this.dbOrdering.length; idx++) {
            if (this.dbOrdering[idx].id == ParentId) {
                bIsParentExisting = true;
                break;
            }
        }

        return bIsParentExisting;
    },

    /**
     * sorts element of array regarding
     * actual ordering settings
     */
    SortByOrdering: function () {
        var SortedOrdering = this.dbOrdering.slice(0);

        SortedOrdering.sort(this.SortByIntOrdering);

        this.dbOrdering = SortedOrdering;

        return;
    },


    /**
     * Returns ordering value of given gallery ID
     *
     * @param {number} GalleryId
     * @returns {number} Ordering number if found
     */
    GetOrderingValue: function (GalleryId) {
        var ordering = -1;

        //for (var dbGallery of dbOrdering) {
        for (var idx = 0; idx < this.dbOrdering.length; idx++) {
            // Gallery item found
            if (this.dbOrdering[idx].id == GalleryId) {
                ordering = this.dbOrdering[idx].ordering;
                break;
            }
        }

        return ordering;
    },

    /**
     * Extract gallery ID from given HTML element
     *
     * @param {number} ElementId
     * @returns {Number|*}
     * @constructor
     */
    GetGalleryId: function (ElementId) {
        var GalleryIdString;
        var GalleryId;

        //var GalleryIdString = actElement.id; //
        GalleryIdString = ElementId.replace(/^\D+/g, ''); // replace all leading non-digits with nothing
        GalleryId = parseInt(GalleryIdString);

        return GalleryId;
    },


    /**
     * Write back the changed ordering from
     * internal array to HTML element
     *
     */
    AssignNewOrdering: function () {
        /**/
        var self = this; // save "this" for jquery overwrite

        //--- all input variables ----------------------

        jQuery(".changeOrder").each(function () {
            Element = jQuery(this);
            var UserOrdering = parseInt(Element.val());
            var galleryId = self.GetGalleryId(Element.attr('id'));
            var newOrdering = self.GetOrderingValue(galleryId);

            if (newOrdering != UserOrdering) {
                Element.val(newOrdering);
            }
        });
        /**/
        return;
        /**/
    },

// Reassign as Versions of $.3.0 may contain no parent child order
// Recursive assignment of ordering  (child direct after parent)
// May leave out some ordering numbers
    /**
     * ReAssignOrdering using position in array
     * In the array field after sorting may be gaps or doubles ...
     * Here the ordering will be standardized to 1... n with step 1
     *
     * First call of function should use actIdx=1, parentId=0
     * @param {number} actIdx
     * @param {number} parentId
     * @returns {*}
     * @constructor
     */
    // ToDo active when all browser supporst initialised variables: ReAssignOrdering: function (actIdx=1, parentId=0) {
    ReAssignOrdering: function (actIdx, parentId) {

        // Assign Order 1..n to each parent.
        // Children get the ordering direct after parent.
        // So the next parent may have bigger distance
        // than one to the previous parent
        //for (var dbGallery of dbOrdering) {
        for (var idx = 0; idx < this.dbOrdering.length; idx++) {
            if (this.dbOrdering[idx].parent == parentId) {
                this.dbOrdering[idx].ordering = actIdx;
                actIdx++;

                // recursive call of ordering on child
                actIdx = this.ReAssignOrdering(actIdx, this.dbOrdering[idx].id);
            }
        }

        return actIdx;
    }

};
