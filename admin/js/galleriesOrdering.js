





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
var dbOrdering;

    displayDbOrderingArray: function  (Title) {
    var OutText;

    OutText = Title + ": (lenght:" + dbOrdering.length + ") \n";
    for(var idx = 0; idx < dbOrdering.length; idx++) {
        OutText += JSON.stringify(dbOrdering[idx]) + "\n";
    }
    OutText += "\n";

    // alert (OutText);
    add2DebugTextArea (OutText)
}
/**/
clearDebugTextArea: function  ()
{
    //jQuery("#debug").val("Clear:\n");
    //jQuery("#debug").val("");
    jQuery("#debug").append("");
}
/**/

add2DebugTextArea: function  (OutText)
{
    var ElementValue;
    ElementValue = jQuery("#debug").text();
    ElementValue += OutText;

    jQuery("#debug").val(ElementValue);
    jQuery("#debug").append(OutText);
}
/**/


/**
 * Every
 *
 * It may create
 * .
 *
 */
InsertUserOrdering: function  (UserId, UserOrdering)
{
    var LimitLower;
    var LimitUpper;
    var PrevOrdering;
    var bDirMoveUp;
    var ActOrdering;
    var MovedOrdering;

    // alert ("UserOrdering: " + UserOrdering);
    // alert ("dbOrdering.length: " + dbOrdering.length);

    // No change ?
    PrevOrdering = parseInt(GetOrderingValue (UserId));
    UserOrdering = parseInt(UserOrdering);
    if (PrevOrdering == UserOrdering) {
        return;
    }

    // alert ("PrevOrdering: " + PrevOrdering);

    if (PrevOrdering < UserOrdering){
        LimitLower = PrevOrdering;
        LimitUpper = UserOrdering;
        bDirMoveUp = true;
    }
    else
    {
        LimitLower = UserOrdering;
        LimitUpper = PrevOrdering;
        bDirMoveUp = false;
    }

    // alert ("bDirMoveUp: " + bDirMoveUp);

    // Move elements between lower and upper
    for(var idx = 0; idx < dbOrdering.length; idx++) {

        // Assign new ordering on user element
        if (dbOrdering[idx].id == UserId) {
            dbOrdering[idx].ordering = UserOrdering;
        }
        else {
            ActOrdering = parseInt (dbOrdering[idx].ordering);
//                alert ("idx: " + idx + " Id:" + dbOrdering[idx].id + " ActOrdering: " + ActOrdering);

            // Moving area
            if (LimitLower <= ActOrdering && ActOrdering <= LimitUpper) {

//                    alert ("idx: " + idx + " Id:" + dbOrdering[idx].id + " ActOrdering: " + ActOrdering);
                if (bDirMoveUp) {
                    // Make space below new ordering
                    MovedOrdering = 0 + parseInt(dbOrdering[idx].ordering) - 1;
                    /**
                     alert ("idx: " + idx
                     + " Id:" + dbOrdering[idx].id
                     + " Up"
                     + " ActOrdering: " + ActOrdering
                     + " MovedOrdering" + MovedOrdering);
                     /**/
                }
                else {
                    // Make space above new ordering
                    MovedOrdering = 0 + parseInt(dbOrdering[idx].ordering) + 1;
                    /**
                     alert ("idx: " + idx
                     + " Id:" + dbOrdering[idx].id
                     + " Up"
                     + " ActOrdering: " + ActOrdering
                     + " MovedOrdering" + MovedOrdering);
                     /**/
                }

                dbOrdering[idx].ordering = MovedOrdering;
                /**
                 alert ("idx: " + idx
                 + " Id:" + dbOrdering[idx].id
                 + " After"
                 + " ActOrdering: " + ActOrdering
                 + " MovedOrdering" + MovedOrdering
                 + " Changed: " + dbOrdering[idx].ordering);
                 /**/
            }
        }
    }
    /**/

    // alert ("exit");

    return;
}

/**
 * Remove child parent value if parent doesn't exist
 */
RemoveOrphanIds: function  ()
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
}

IsParentExisting: function  (ParentId)
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
}

SortByOrdering: function  ()
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

GetOrderingValue: function  (GalleryId)
{
    var ordering = -1;

    //for (var dbGallery of dbOrdering) {
    for(var idx = 0; idx < dbOrdering.length; idx++) {
        // Gallery item found
        if(dbOrdering[idx].id == GalleryId) {
            ordering = dbOrdering[idx].ordering;
            break;
        }
    }

    return ordering;
}

GetGalleryId: function (ElementId) {
    var GalleryIdString;
    var GalleryId;

    //var GalleryIdString = actElement.id; //
    GalleryIdString = ElementId.replace(/^\D+/g, ''); // replace all leading non-digits with nothing
    var GalleryId = parseInt(GalleryIdString);

    return GalleryId;
}


AssignNewOrdering: function  ()
{
    var bIsParentExisting = false;

    var OutText = "AssignNewOrdering: \n\r";

    //--- all input variables ----------------------

    jQuery(".changeOrder").each (function () {
        Element = jQuery(this);
        var UserOrdering = parseInt(Element.val());
        var galleryId = GetGalleryId(Element.attr('id'));
        var newOrdering = GetOrderingValue(galleryId);

        if (newOrdering != UserOrdering)
        {
            Element.val (newOrdering);
        }
    });

    return;
}

// ToDo: collect ParentId. array{users} field and work with it to sort
// Reassign as Versions of $.3.0 may contain no parent child order
// Recursive assignment of ordering  (child direct after parent)
// May leave out some ordering numbers
ReAssignOrdering: function (actIdx=1, parentId=0) {

    // Assign Order 1..n to each parent.
    // Children get the ordering direct after parent.
    // So the next parent may have bigger distance
    // than one to the previous parent
    //for (var dbGallery of dbOrdering) {
    for(var idx = 0; idx < dbOrdering.length; idx++)
    {
        if (dbOrdering[idx].parent == parentId) {
            dbOrdering[idx].ordering = actIdx;
            actIdx++;

            // recursive call of ordering on child
            actIdx = ReAssignOrdering(actIdx, dbOrdering[idx].id);
        }
    }

    // alert("exit actIdx: " + actIdx);
    return actIdx;
}

};
