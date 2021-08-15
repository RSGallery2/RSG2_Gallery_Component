/**
 * @package     RSGallery2
 *
 * resorting images in images view
 *
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2021 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * @since       4.3.0
 */

var ImagesOrdering = {
    /**
     * Keeps server database images objects (Id,Parent, ordering, name)
     */
    dbOrdering: [],
    gallery_id: -1,
    dbGalleries: [],

    //  @param {Array.<{myNumber: Number, myString: String, myArray: Array}>} myObjects

    /**
     * initialize
     * @param {Array} dbOrdering array ('id', 'ordering', 'parent', 'name')
     */
    // constructor (dbOrdering) {
    initialize: function (dbOrdering, GalleryId, dbGalleries) {
        this.dbOrdering = dbOrdering;
        this.gallery_id = GalleryId;
        this.dbGalleries = dbGalleries;
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

        OutText = Title + ": (length:" + this.dbOrdering.length + ") \n";
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
     * @param {number} ImageId Used to find the source HTML element and previous ordering
     * @param {number} UserOrdering required changed ordering
     */
    InsertUserOrdering: function (ImageId, UserOrdering) {
        var LimitLower;
        var LimitUpper;
        var PrevOrdering;
        var bDirMoveUp;
        var ActOrdering;
        var MovedOrdering;

        // gallery is defined
        if (typeof this.dbOrdering[this.gallery_id] !== 'undefined') {

            var images = this.dbOrdering[this.gallery_id];

            // No change ?
            PrevOrdering = parseInt(this.GetOrderingValue(images, ImageId));
            UserOrdering = parseInt(UserOrdering);
            if (PrevOrdering == UserOrdering) {
                return;
            }

            if (PrevOrdering < UserOrdering) {
                LimitLower = PrevOrdering;
                LimitUpper = UserOrdering;
                bDirMoveUp = true;
            } else {
                LimitLower = UserOrdering;
                LimitUpper = PrevOrdering;
                bDirMoveUp = false;
            }

            // Move elements between lower and upper
            for (var idx = 0; idx < images.length; idx++) {

                // Assign new ordering on user element
                if (images[idx].id == ImageId) {
                    images[idx].ordering = UserOrdering;
                } else {
                    ActOrdering = parseInt(images[idx].ordering);

                    // Moving area
                    if (LimitLower <= ActOrdering && ActOrdering <= LimitUpper) {

                        if (bDirMoveUp) {
                            // Make space below new ordering
                            MovedOrdering = parseInt(images[idx].ordering) - 1;
                            /**
                             alert ("idx: " + idx
                             + " Id:" + [idx].id
                             + " Up"
                             + " ActOrdering: " + ActOrdering
                             + " MovedOrdering" + MovedOrdering);
                             /**/
                        } else {
                            // Make space above new ordering
                            MovedOrdering = parseInt(images[idx].ordering) + 1;
                            /**
                             alert ("idx: " + idx
                             + " Id:" + [idx].id
                             + " Up"
                             + " ActOrdering: " + ActOrdering
                             + " MovedOrdering" + MovedOrdering);
                             /**/
                        }

                        images[idx].ordering = MovedOrdering;
                        /**
                         alert ("idx: " + idx
                         + " Id:" + [idx].id
                         + " After"
                         + " ActOrdering: " + ActOrdering
                         + " MovedOrdering" + MovedOrdering
                         + " Changed: " + [idx].ordering);
                         /**/
                    }
                }
            }
        }
        /**/

        // alert ("exit");

        return;
    },

    /**
     * sorts element of array regarding
     * actual ordering settings
     */
    SortByOrdering: function () {
        var images = this.dbOrdering[this.gallery_id];

        var SortedOrdering = images.slice(0);

        SortedOrdering.sort(this.SortByIntOrdering);

        this.dbOrdering[this.gallery_id] = SortedOrdering;

        return;
    },


    /**
     * Returns ordering value of given image ID
     *
     * @param {array} ImageId
     * @param {number} ImageId
     * @returns {number} Ordering number if found
     */
    GetOrderingValue: function (images, ImageId) {
        var ordering = -1;

        var isFound = false;


        // all images in gallery
        for (var idx = 0; idx < images.length; idx++) {
            // image item found
            if (images[idx].id == ImageId) {
                ordering = images[idx].ordering;

                isFound = true;
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
    GetImageId: function (ElementId) {
        var ImageIdString;
        var ImageId;

        //var ImageIdString = actElement.id; //
        ImageIdString = ElementId.replace(/^\D+/g, ''); // replace all leading non-digits with nothing
        ImageId = parseInt(ImageIdString);

        return ImageId;
    },


    /**
     * Write back the changed ordering from
     * internal array to HTML element
     *
     */
    AssignNewOrdering: function () {
        /**/
        var self = this; // save "this" for jquery overwrite
        var images = [];
        var GalleryId;

        // gallery is defined
        if (typeof this.dbOrdering[this.gallery_id] !== 'undefined') {

            images = this.dbOrdering[this.gallery_id];
            GalleryId = this.gallery_id;

            //--- all input variables ----------------------

            jQuery(".changeOrder").each(function () {
                Element = jQuery(this);
                //Element = this;

                var ElementGalleryId = Element.attr('gallery_id');

                if (ElementGalleryId == GalleryId)
                {
                    var UserOrdering = parseInt(Element.val());
                    var imageId = self.GetImageId(Element.attr('id'));
                    var newOrdering = self.GetOrderingValue(images, imageId);
                    if (newOrdering != UserOrdering) {
                        Element.val(newOrdering);
                    }
                }
            });
        }
        /**/
        return;
        /**/
    },


	
// Reassign as Versions of $.3.0 may contain no parent child order
// Recursive assignment of ordering  (child direct after parent)
// May leave out some ordering numbers
    /**
     * ResetOrdering using position in array
     * In the array field after sorting may be gaps or doubles ...
     * Here the ordering will be standardized to 1... n with step 1
     *
     * First call of function should use actIdx=1, parentId=0
     * @param {number} actIdx
     * @param {number} parentId
     * @returns {*}
     * @constructor
     */
    // ToDo active when all browser supporst initialised variables: ResetOrdering: function (actIdx=1, parentId=0) {
    ResetOrdering: function (actIdx) {

        // Assign Order 1..n to each parent.
        // Children get the ordering direct after parent.
        // So the next parent may have bigger distance
        // than one to the previous parent

        // gallery is defined
        if (typeof this.dbOrdering[this.gallery_id] !== 'undefined') {

            var images = this.dbOrdering[this.gallery_id];

            // Move elements between lower and upper
            for (var idx = 0; idx < images.length; idx++) {

                images[idx].ordering = actIdx;
                actIdx++;

            }
        }
        
        return actIdx;
    }


};
