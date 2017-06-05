





/**
 * resorting galleries in galleries view
 */
//class GalleriesOrdering {
var GalleriesOrdering = {
    dbOrdering : [],

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
     * Keeps server database gallery objects (Id,Parent, ordering, name)
     */
    displayDbOrderingArray : function (Title) {
        var OutText;

        OutText = Title + ": (lenght:" + dbOrdering.length + ") \n";
        for(var idx = 0; idx < dbOrdering.length; idx++) {
            OutText += JSON.stringify(dbOrdering[idx]) + "\n";
        }
        OutText += "\n";

        // alert (OutText);
        add2DebugTextArea (OutText)
    },

    /**
     * 
     */
    clearDebugTextArea : function ()
    {
        //jQuery("#debug").val("Clear:\n");
        jQuery("#debug").val("");
        jQuery("#debug").append("");
    },

 /**/

    add2DebugTextArea : function (OutText)
    {
        //jQuery("#debug").append(OutText);
//        jQuery("#debug").append("Test");
//        alert("OutText: '" + OutText + "'")
        var ElementValue;
        ElementValue = jQuery("#debug").text();
        // alert ("ElementValue: " + ElementValue);
        ElementValue += OutText;

        jQuery("#debug").val(ElementValue);
        jQuery("#debug").append(OutText);
    },
/**/

    /**
     *
     */
    AssignUserOrdering : function (UserId, UserOrdering)
    {
        /**
        outText = "AssignUserOrdering (UserId: " + UserId + ", UserOrdering: " + UserOrdering + ")"+ "\n";
        add2DebugTextArea (outText);
        alert(outText);
        /**/

        //for (var dbGallery of dbOrdering) {
        for(var idx = 0; idx < dbOrdering.length; idx++) {
            if (dbOrdering[idx].id == UserId) {

                //alert("found idx: " + idx);
                /**
                outText = "AssignUserOrdering (dbOrdering[idx].id == UserId)" + "\n";
                add2DebugTextArea (outText);
                alert(outText);
                /**/
                dbOrdering[idx].ordering = UserOrdering;
                // alert("exit (1)");
                break;
            }
        }

        // alert("exit (2)");
        return;
    },

	/**
     * Remove child parent value if parent doesn't exist
     */
    RemoveOrphanIds : function ()
    {

        //for (var dbGallery of dbOrdering) {
        for(var idx = 0; idx < dbOrdering.length; idx++) {

            if (dbOrdering[idx].parent != 0) {

                if (!IsParentExisting (dbOrdering[idx].parent)) {
                    var outText = "Orphan:" + JSON.stringify(dbOrdering[idx]) + "\n"
                    add2DebugTextArea (outText);
                    alert(outText);
                    dbOrdering[idx].parent = 0;
                }
            }
        }

        return;
    },

    IsParentExisting : function (ParentId)
    {
        var bIsParentExisting = false;

        //for (var dbGallery of dbOrdering) {
        for(var idx = 0; idx < dbOrdering.length; idx++) {
            if (dbOrdering[idx].id == ParentId)
            {
                bIsParentExisting = true;
                break;
            }
        }

        return bIsParentExisting;
    },

    SortByOrdering : function ()
    {
        var SortedOrdering = dbOrdering.slice(0);

        // alert ("SortedOrdering");
        SortedOrdering.sort(SortByIntOrdering);

        dbOrdering = SortedOrdering;
        // alert ("Return SortedOrdering");

        return;
    },

    // Array must be ordered before
    // Array must be sorted after

    InsertChangedOrdering : function (UserId, UserOrdering)
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
    },

    GetOrderingValue : function (GalleryId)
    {
        var ordering = -1;

        //for (var dbGallery of dbOrdering) {
        for(var idx = 0; idx < dbOrdering.length; idx++) {
            // Gallery item found
            if(dbOrdering[idx].Id == GalleryId) {
                ordering = dbOrdering[idx].ordering;
                break;
            }
        }

        return ordering;
    },

    elementId: function (actElement) {

        var UserIdString = actElement.id; //
        UserIdString = UserIdString.replace(/^\D+/g, ''); // replace all leading non-digits with nothing
        var UserId = parseInt(UserIdString);

        return UserId;
    },


    AssignNewOrdering : function (OrderingElements)
    {
        var bIsParentExisting = false;

        var OutText = "AssignNewOrdering: \n\r";

        alert ("1:");

        OrderingElements.each(function(ActIdx) {

            alert ("ActIdx: " + ActIdx + " value" + value);

            var actOrdering = this.val();
            alert ("actOrdering: " + actOrdering);
            // alert ("value: " + value);

            var galleryId = elementId(this);
            alert ("galleryId: " + galleryId);

            OutText += "ActIdx: " + ActIdx + ": ";
            OutText += "galleryId: " + galleryId;
            OutText += "actOrdering: " + actOrdering;

            var newOrdering = GetOrderingValue(galleryId);
            OutText += "newOrdering: " + newOrdering + " ";

            if (newOrdering != actOrdering)
            {
                alert ("galleryId: " + galleryId + " newOrdering: " + newOrdering);

                OutText += "Assign: ";
                jquery( this ).val (newOrdering);
            }

            //OutText += "key: " + key + " ";
            OutText += "\r\n";
        });

        alert(OutText);

        return;
    },

    // ToDo: collect ParentId. array{users} field and work with it to sort
    // Reassign as Versions of $.3.0 may contain no parent child order
    // Recursive assignment of ordering  (child direct after parent)
    // May leave out some ordering numbers
    ReAssignOrdering: function (actIdx, parentId) {
        /**
        outText = "ReAssignOrdering (actIdx: " + actIdx + ", parentId: " + parentId + ")"+ "\n";
        add2DebugTextArea (outText);
        alert (outText);
        /**/

        if (typeof actIdx === 'undefined') {
            actIdx = 1;
        }
        if (!actIdx) {
            actIdx = 1;
        }

        if (typeof parentId === 'undefined') {
            parentId = 0;
        }

        // Assign Order 1..n to each parent.
        // Children get the ordering direct after parent.
        // So the next parent may have bigger distance
        // than one to the previous parent
        //for (var dbGallery of dbOrdering) {
        for(var idx = 0; idx < dbOrdering.length; idx++) {
            //alert("dbGallery " + JSON.stringify(dbGallery));

            if (dbOrdering[idx].parent == parentId) {
                dbOrdering[idx].ordering = actIdx;
                actIdx++;

                // recursive call of ordering on child
                actIdx = ReAssignOrdering(actIdx, dbOrdering[idx].id);
            }
        }

        // alert("exit actIdx: " + actIdx);
        return actIdx;
    },

    DoOrdering: function () {
        //-----------------------------------------
        // Order by parent / child
        //-----------------------------------------

        alert ("clearDebugTextArea");

        this.clearDebugTextArea();


        alert("03");

        // Assign changed ordering to element
        this.AssignUserOrdering(UserId, UserOrdering)
        //displayDbOrderingArray ("(03) User ordering added");

        alert("04");

        this.RemoveOrphanIds();
        //displayDbOrderingArray ("(4) Remove Orphans");

        alert("05");

        // Sort array by (old) ordering
        this.SortByOrdering();
        //displayDbOrderingArray ("(05) SortByOrdering");

        // alert ("06");

        // Reassign as Versions of $.3.0 may contain no parent child order
        this.ReAssignOrdering();
        //displayDbOrderingArray ("(06) ReAssignOrdering");

        // alert ("07");

        // Sort array by (new) ordering
        this.SortByOrdering();
        //displayDbOrderingArray ("(05) SortByOrdering");

        alert("08");

        // Values for Get input in PHP
        serverDbOrderingElement.val(JSON.stringify(dbOrdering));
        this.displayDbOrderingArray("Saved back to 'INSERT'");

        alert("10");
        /**/
        // Save Ordering in HTML elements
        this.AssignNewOrdering(OrderingElements);
        /**/
        alert("20 exit");
    }


};
