##Behavior within versions

###Version  4.3.1 ++
* Linear ordering over all galleries, sub galleries will be listed direct after parent gallery

###In between version ? 4.2 - 4.3
* The ordering of a new gallery has value 1
* Ordering of sub galleries (have parent) ignores the parent galleries and

###Version up to RSG2 3.x (Joomla 2.5)
* The ordering of a new gallery has value 1
* Ordering of sub galleries (have parent) is counted within the parent galleries and begins with 1

##Discussion:
###Linear ordering over all galleries
All order IDs are unique. Sub galleries are sorted direct after the parent gallery. They get the following number. Sub galleries will only be ordered within the same parent.

positive:
* The gallery view on ordering will display sub galleries direct after
* New item -> run through increase everything with one. For sub gallery it may start after parent gallery for new item

negative:
* All following galleries have to be updated on new item

##Ordering within sub galleries (parents)
The general order is within parent galleries. Sub galleries will only be ordered within the same parent. Their ordering begins with '1'.

positive:
* New sub gallery has to reorder only few items

negative:
* Sub galleries and galleries may have the same ordering number.
* New parent item still has to move all other parent item

##Update required:
To be tested on changes /development
* Edit gallery -> reassign parent gallery
* New gallery save, no parent gallery is assigned
* New gallery save, parent gallery is assigned
* Gallery view order: New order number,  no parent gallery is assigned
* Gallery view order: New order number,  parent gallery is assigned (attention: ordering stays within parent group whatever the number tried)
* 
