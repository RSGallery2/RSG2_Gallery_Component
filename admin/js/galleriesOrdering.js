/**
 * resorting galleries in galleries view
 */
//class GalleriesOrdering {
var GalleriesOrdering = {
    /**
     * Keeps server database gallery objects (Id,Parent, ordering, name)
     */
    dbOrdering: [],

    /**
     * initialize
     * @param dbOrdering array ('id', 'ordering', 'parent', 'name')
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
    /**/
    clearDebugTextArea: function () {
        jQuery("#debug").val("");
    },
    /**/

    add2DebugTextArea: function (OutText) {
        var ElementValue;
        var Element;
        Element = jQuery("#debug");
        ElementValue = Element.text() + OutText;
        Element.val (ElementValue);
    },
    /**/


    /**
     * Every
     *
     * It may create
     * .
     *
     */
    InsertUserOrdering: function (UserId, UserOrdering) {
        var LimitLower;
        var LimitUpper;
        var PrevOrdering;
        var bDirMoveUp;
        var ActOrdering;
        var MovedOrdering;

        // alert ("UserOrdering: " + UserOrdering);
        // alert ("dbOrdering.length: " + this.dbOrdering.length);

        // No change ?
        PrevOrdering = parseInt(this.GetOrderingValue(UserId));
        UserOrdering = parseInt(UserOrdering);
        if (PrevOrdering == UserOrdering) {
            return;
        }

        // alert ("PrevOrdering: " + PrevOrdering);

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

        // alert ("bDirMoveUp: " + bDirMoveUp);

        // Move elements between lower and upper
        for (var idx = 0; idx < this.dbOrdering.length; idx++) {

            // Assign new ordering on user element
            if (this.dbOrdering[idx].id == UserId) {
                this.dbOrdering[idx].ordering = UserOrdering;
            }
            else {
                ActOrdering = parseInt(this.dbOrdering[idx].ordering);
//                alert ("idx: " + idx + " Id:" + this.dbOrdering[idx].id + " ActOrdering: " + ActOrdering);

                // Moving area
                if (LimitLower <= ActOrdering && ActOrdering <= LimitUpper) {

//                    alert ("idx: " + idx + " Id:" + this.dbOrdering[idx].id + " ActOrdering: " + ActOrdering);
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
     */
    RemoveOrphanIds: function () {

        //for (var dbGallery of this.dbOrdering) {
        for (var idx = 0; idx < this.dbOrdering.length; idx++) {

            if (this.dbOrdering[idx].parent != 0) {

                if (!this.IsParentExisting(this.dbOrdering[idx].parent)) {
                    var outText = "Orphan:" + JSON.stringify(this.dbOrdering[idx]) + "\n"
                    this.add2DebugTextArea(outText);
                    alert(outText);
                    this.dbOrdering[idx].parent = 0;
                }
            }
        }

        return;
    },

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

    SortByOrdering: function () {
        var SortedOrdering = this.dbOrdering.slice(0);

        // alert ("SortedOrdering");
        SortedOrdering.sort(this.SortByIntOrdering);

        this.dbOrdering = SortedOrdering;
        // alert ("Return SortedOrdering");

        return;
    },

    /**
    // Array must be ordered before
    // Array must be sorted after

    InsertChangedOrdering: function (UserId, UserOrdering) {
        var IsGalleryHandled = false;

        // All
        var OutText = "";

        OutText += "dbOrdering.length: " + this.dbOrdering.length + ", ";
        OutText += "UserId: " + UserId + ", ";
        OutText += "UserOrdering: " + UserOrdering + "\n\r";
        // alert(OutText);

        // OutText = "";
        for (var ActIdx = 0; ActIdx < this.dbOrdering.length; ActIdx++) {
            var Gallery = this.dbOrdering[ActIdx];
            var ActOrdering = Number(ActIdx) + Number(1)
            OutText += "'" + ActOrdering + "': ";

            // Element may be late or early, so initialize it
            if (Gallery.id == UserId) {
                Gallery.ordering = UserOrdering;
                IsGalleryHandled = true;

                OutText += "(==)" + Gallery.ordering + ", ";
            }
            else {
                if (!IsGalleryHandled) {
                    // Element above user ordering is one higher then index
                    if (ActOrdering >= UserOrdering) {
                        Gallery.ordering = Number(ActOrdering) + Number(1);

                        OutText += "(akt>)" + Gallery.ordering + ", ";
                    }
                    else {
                        OutText += "(!=akt>)" + Gallery.ordering + ", ";
                    }
                }
                else {
                    if (ActOrdering >= UserOrdering) {
                        Gallery.ordering = ActOrdering;

                        OutText += "==>" + Gallery.ordering + ", ";
                    }
                    else {
                        OutText += "(!=)" + Gallery.ordering + ", ";
                    }
                }
            }
        }
        OutText += "\n\r";
        // alert("ActOrdering: " + OutText);

        return;
    },
    /**/

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

    GetGalleryId: function (ElementId) {
        var GalleryIdString;
        var GalleryId;

        //var GalleryIdString = actElement.id; //
        GalleryIdString = ElementId.replace(/^\D+/g, ''); // replace all leading non-digits with nothing
        GalleryId = parseInt(GalleryIdString);

        return GalleryId;
    },


    AssignNewOrdering: function () {
        /**/
        var self = this; // save "this" for jquery overwrite

        // alert ("ANO01");

        //--- all input variables ----------------------

        jQuery(".changeOrder").each(function () {
            // alert ("ANO02");
            Element = jQuery(this);
            alert ("Element.attr('id')" + Element.attr('id'));
            // alert ("ANO03");
            var UserOrdering = parseInt(Element.val());
            // alert ("ANO03");
            var galleryId = self.GetGalleryId(Element.attr('id'));
            //alert ("ANO05");
            var newOrdering = self.GetOrderingValue(galleryId);
            //alert ("ANO06");

            if (newOrdering != UserOrdering) {
                alert ("ANO07");
                Element.val(newOrdering);
            }
        });
        /**/
        return;
        /**/
    },

// ToDo: collect ParentId. array{users} field and work with it to sort
// Reassign as Versions of $.3.0 may contain no parent child order
// Recursive assignment of ordering  (child direct after parent)
// May leave out some ordering numbers
    /**
     * ReAssignOrdering
     * First call should be with actIdx=1, parentId=0
     * @param actIdx
     * @param parentId
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

        // alert("exit actIdx: " + actIdx);
        return actIdx;
    }

};
