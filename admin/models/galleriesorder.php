<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2019 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

/**
 * Gallery list model
 *
 * @since 4.3.0
 */
class rsgallery2ModelGalleriesOrder extends JModelList
{
    /**
     * orderRsg2ByOld15Method
     *
     * @return bool
     *
     * @since 4.3.1
     */
    public function orderRsg2ByOld15Method ()
    {
        $IsSuccessful = false;

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $app = JFactory::getApplication();

            // Collect all galleries ordered by parent and then by ordering
            $Ids2Ordering = $this->GalleriesByParentAndOrdering ();

            // Prepare start value
            $LastParentId = -1;

            foreach ($Ids2Ordering as $Id2Ordering) {
                $ParentId = $Id2Ordering->parent;

                // New set with same ...
                if ($ParentId != $LastParentId)
                {
                    $LastParentId = $ParentId;
                    $orderIdx = 0;
                }
                $orderIdx++;

/**
                $app->enqueueMessage('$Id2Ordering->id: ' . $Id2Ordering->id
                    . '$Id2Ordering->parent: ' . $Id2Ordering->parent . ' '
                    . '$Id2Ordering->ordering: ' . $Id2Ordering->ordering . ' '
                    . '$orderIdx: ' . $orderIdx . ' '
                    , 'notice');
/**/

                // new ordering needs to be saved ? (Differnet ot old ordering)
                if ($Id2Ordering->ordering != $orderIdx)
                {
                    // Save new ordering
                    $query->update($db->quoteName('#__rsgallery2_galleries'))
                        ->set($db->quoteName('ordering') . '=' . $db->quote((int) $orderIdx))
                        ->where(array($db->quoteName('id') . '=' . $db->quote((int) $Id2Ordering->id)));
                    $db->setQuery($query);

                    $result = $db->execute();
                    if (empty($result))
                    {
                        $IsSuccessful = false;

                        $app->enqueueMessage('$Id2Ordering->id: ' . $Id2Ordering->id
                            . '$Id2Ordering->parent: ' . $Id2Ordering->parent . ' '
                            . '$Id2Ordering->ordering: ' . $Id2Ordering->ordering . ' '
                            . '$orderIdx: ' . $orderIdx . ' '
                            , 'notice');
                        break;
                    }

                    $app->enqueueMessage('$result (2): ' . $result);
                }

            }

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'orderRsg2ByOld15Method: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsSuccessful;
    }

    /**
     * orderRsg2ByNewMethod
     *
     * @return bool
     *
     * @since 4.3.1
     */
    public function orderRsg2ByNewMethod ()
    {
        $IsSuccessful = false;

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $app = JFactory::getApplication();
            $app->enqueueMessage('orderRsg2ByNewMethod (B)', 'notice');

            // Collect data
            $dbOrdering = $this->OrderedGalleries ();

            // Do standard ordering with save
            $IsSuccessful = $this->doOrdering($dbOrdering);

            // $IsSuccessful = true;
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'orderRsg2ByNewMethod: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsSuccessful;
    }

    /**
     * orderRsg2ByUnorderMethod
     *
     * @return bool
     *
     * @since 4.3.1
     */
    public function orderRsg2ByUnorderMethod ()
    {
        $IsSuccessful = false;

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $app = JFactory::getApplication();
            $app->enqueueMessage('orderRsg2ByUnorderMethod (B)', 'notice');

            // Reorder all parent galleries (which have no own parent assigned)
            $Galleries = $this->OrderedGalleries ();
            shuffle($Galleries);

            $orderIdx = 1;

            $IsSuccessful = true; // true until further notice

            // ordering of parents
            foreach ($Galleries as $Gallery)
            {
/**
                $app->enqueueMessage('$Parent->id: ' . $Parent->id . ' '
                    . '$Parent->ordering: ' . $Parent->ordering . ' '
                    . '$orderIdx: ' . $orderIdx . ' '
                    , 'notice');
/**/
                $OutText = '';
                $OutText .= 'Id: ' . $Gallery->id;
//                    $OutText .= ' ordering: ' . $Gallery->ordering;
//                    $OutText .= ' ==> $NewOrdering: ' . $NewOrdering;
                $OutText .= ' ==> ' . $orderIdx;
                $OutText .= ' (' .  $Gallery->ordering . ')';
//                    $OutText .= ' (' . $Gallery->ordering . ")";
                $app->enqueueMessage($OutText, 'notice');

                $query->clear();
                $query->update($db->quoteName('#__rsgallery2_galleries'))
                    ->set($db->quoteName('ordering') . '=' . $db->quote((int) $orderIdx))
                    ->where(array($db->quoteName('id') . '=' . $db->quote((int) $Gallery->id)));
                $db->setQuery($query);

                $result = $db->execute();
                if (empty($result))
                {
                    $app->enqueueMessage('$Parent->id: ' . $Gallery->id . ' '
                        . '$orderIdx: ' . $orderIdx . ' '
                        . '$Gallery->ordering: ' . $Gallery->ordering . ' '
                        , 'notice');

                    $IsSuccessful = false;
                    break;
                }

                $orderIdx++;
            }

            // $IsSuccessful = true;
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'orderRsg2ByUnorderMethod: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsSuccessful;
    }


	/**
	 * @param string $Title
	 *
	 *
	 * @since 4.3.0
    */
    private function displayDbOrderingArray ($Title='') {

        $app = JFactory::getApplication();
        //$app->enqueueMessage('this->dbOrdering : ' . json_encode($this->dbOrdering), 'notice');

        $OutText = 'Title: ' . $Title . ': ' . '<br>';
        $Idx = 0;
        foreach ($this->dbOrdering as $dbGallery) {
            // ...
            $OutText = $OutText . 'Idx:' . $Idx  . ' ' . json_encode($dbGallery) . '<br>';
            //$OutText = $OutText . 'Idx:' . $Idx  . ' ' . json_encode($this->dbOrdering[$Idx]) . '<br>';
            $Idx++;
        }

        $app->enqueueMessage($OutText, 'notice');
    }

	/**
	 * @param $ParentId
	 *
	 * @return bool
	 *
	 * @since 4.3.0
	 */
    function IsParentExisting ($ParentId)
    {
        $bIsParentExisting = false;

        /**/
        foreach ($this->dbOrdering as $dbGallery) {
            if ($dbGallery->id == $ParentId)
            {
                $bIsParentExisting = True;
                break;
            }
        }
        /**/

        return $bIsParentExisting;
    }

	/**
	 * @param $HtmlArray
	 *
	 * @return array
	 *
	 * @since 4.3.0
    */
    function ConvertOrderingHtml2PhpObject ($HtmlArray)
    {
        //  init arrays
        $dbOrdering = array ();

        // all Html gallery data
        foreach ($HtmlArray as $HtmlGallery) {

            // New object
            $PhpGallery = new stdClass();
            $PhpGallery->id = $HtmlGallery['id'];
            $PhpGallery->parent = $HtmlGallery['parent'];
            $PhpGallery->ordering = $HtmlGallery['ordering'];

            $dbOrdering [] = $PhpGallery;
        }

        /**
        $OutTxt = '';
        $OutTxt .= '$HtmlArray: "' . json_encode($HtmlArray) . '<br>';
        $OutTxt .= '$dbOrdering: "' . json_encode($dbOrdering) . '<br>';

        $app = JFactory::getApplication();
        $app->enqueueMessage($OutTxt, 'notice');
        /**/

        return $dbOrdering;
    }


    /**
     * Remove child parent value if parent doesn't exist
     * @since 4.3.0
     */
    private function RemoveOrphanIds ()
    {
        $app = JFactory::getApplication();

        /**
        $OutText = '';
        $OutText .= ' $this->dbOrdering: ' . json_encode($this->dbOrdering);
        //$OutText .= ' Parent (2): ' . $dbGallery.parent;
        //$OutText .= ' Parent (3): ' . $dbGallery->parent;
        $app->enqueueMessage($OutText, 'notice');
        /**/

        foreach ($this->dbOrdering as $dbGallery) {
/**
            $OutText = '$dbGallery :' . json_encode($dbGallery);
            $app->enqueueMessage($OutText, 'notice');
/**/
            $parent = $dbGallery->parent;

/**
            $OutText = '';
            $OutText .= ' Parent (1): ' . $parent ;
            $app->enqueueMessage($OutText, 'notice');
/**/
            if ($parent != 0) {
/**
                $OutText = '';
                $OutText .= ' Parent (1): ' . $parent ;
                //$OutText .= ' Parent (2): ' . $dbGallery.parent;
                //$OutText .= ' Parent (3): ' . $dbGallery->parent;
                $app->enqueueMessage($OutText, 'notice');
 /**/

                $IsParentExisting = $this->IsParentExisting ($parent);
                if (!$IsParentExisting) {
                    $OutText = 'Orphan without parent :' . json_encode($dbGallery);
                    $app->enqueueMessage($OutText, 'notice');

                    $dbGallery->parent = 0;
                }
            }
        }

        return;
    }

    /**
     *
     * Recursive assignment of ordering Gallery with immediate
     * following sub galleries before next pure  parent less gallery
     * As child galleries may be assigned a value but later a different
     * one to follow later found parent the ordering may have some spaces
     *
     * @param int $actIdx
     * @param int $parentId
     * @param int $level
     *
     * @return int
     *
     * @since 4.3.1
     */
    private function PreAssignOrdering($actIdx=1, $parentId=0, $level=0) {

        $app = JFactory::getApplication();

/**
        $OutText = '';
        for ($Idx =0 ; $Idx < $level; $Idx++) {
            $OutText = '&nbsp;&nbsp;&nbsp;';
        }
        $OutText .= ' DoPreOrdering: ';
        $OutText .= ' $actIdx: ' . $actIdx;
        $OutText .= ' $parentId: ' . $parentId;
        $app->enqueueMessage($OutText, 'notice');
/**/
        // Assign Order 1..n to each parent.
        // Children get the ordering direct after parent.
        // So the next parent may have bigger distance
        // than one to the previous parent
//       $arrayIdx=0;
        foreach ($this->dbOrdering as $dbGallery) {
            //alert("dbGallery " + JSON.stringify(dbGallery));

            // gallery has same parent ( or if parent = 0 nop parent)
            if ($dbGallery->parent == $parentId) {
/**
                // info
                $OutText = '';
                for ($Idx =0 ; $Idx < $level; $Idx++) {
                    $OutText .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                $OutText .= ' >>match: ';
                $OutText .= ' id: ' . $dbGallery->id;
                $OutText .= ' parent: ' . $parentId;
                $OutText .= ' ==>actIdx: ' . $actIdx;
                $OutText .= ' level: ' . $level;
                $app->enqueueMessage($OutText, 'notice');
/**/
                // assign actual ordering
                $dbGallery->ordering = $actIdx; // toDo: Check why does it not write into original ay
//                $this->dbOrdering[$arrayIdx]['ordering'] = $actIdx;
                $actIdx++;
/**
                $OutText = '';
                for ($Idx =0 ; $Idx <= $level; $Idx++) {
                    $OutText .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                $OutText .= '$arrayIdx:' . $arrayIdx  . ' ' . json_encode($this->dbOrdering[$arrayIdx]) . '<br>';
                $app->enqueueMessage($OutText, 'notice');
/**/
                // recursive call of ordering on child gallery
                $actIdx = $this->PreAssignOrdering($actIdx, $dbGallery->id, $level +1);
            }

//            $arrayIdx=$arrayIdx+1;
        }

        return $actIdx;
    }


    /**
     * sort by ordering and assing new ordering "1..n" from first element
     * @return bool
     *
     * @since 4.3.0
     */
    public function SortByOrdering()
    {
        $IsSaved = false;

        try
        {
            // sort by ordering
            usort($this->dbOrdering, function($a, $b)
            {
                // return strcmp(intval ($a['ordering']), intval ($b['ordering']));
                return intval ($a->ordering) > intval ($b->ordering);
            });

/** ToDo:
            // Close gaps
            for ($arrayIdx=0; $arrayIdx < count($this->dbOrdering); $arrayIdx++) {
                $this->dbOrdering[$arrayIdx]['ordering'] = $arrayIdx+1;
            }
/**/
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing SortByOrdering: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsSaved;
    }

	/**
	 * @param $galleryId
	 *
	 * @return int
	 *
	 * @since 4.3.0
    */
    public function UserOrderingFromId ($galleryId)
    {
        $Order = -1;

        try
        {
            // each user ordering
            foreach ($this->dbOrdering as $dbGallery) {

                $id = $dbGallery->id;

                // Found
                if ($id == $galleryId) {
                    $Order = $dbGallery->ordering;
                }
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing SortByOrdering: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $Order;
    }

    /**
     *
     * Only when order is changed it will be written back
     * @param $dbOrdering
     *
     * @return bool
     *
     * @since version 4.3.1
     */
    public function AssignNewOrdering ($dbOrdering)
    {
        $IsAssigned = false;

        try
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $app = JFactory::getApplication();
            //$app->enqueueMessage('AssignNewOrdering (A)', 'notice');

            // Collect all galleries to check the ordering
            $Galleries = $this->OrderedGalleries ();

            // ordering of parents
            $IsAssigned = true; //  true until further notice
            foreach ($Galleries as $Gallery) {

                $NewOrdering = $this->UserOrderingFromId ($Gallery->id);
/*
                $OutText = '';
                $OutText .= 'Id: ' . $Gallery->id;
                $OutText .= ' ordering: ' . $Gallery->ordering;
                $OutText .= ' ==> $NewOrdering: ' . $NewOrdering;
                $app->enqueueMessage($OutText, 'notice');
/**/
                // Gallery not defined in user view
                if($NewOrdering == -1){
                    continue;
                }

                if($NewOrdering != $Gallery->ordering) {

                    $OutText = '';
                    $OutText .= 'Id: ' . $Gallery->id;
//                    $OutText .= ' ordering: ' . $Gallery->ordering;
//                    $OutText .= ' ==> $NewOrdering: ' . $NewOrdering;
                    $OutText .= ' ==> ' . $NewOrdering;
//                    $OutText .= ' (' . $Gallery->ordering . ")";
//                    $app->enqueueMessage($OutText, 'notice');

                    $query->clear();
                    $query->update($db->quoteName('#__rsgallery2_galleries'))
                        ->set($db->quoteName('ordering') . '=' . $db->quote((int) $NewOrdering))
                        ->where(array($db->quoteName('id') . '=' . $db->quote((int) $Gallery->id)));
                    $db->setQuery($query);

                    $result = $db->execute();
//                    $OutText = 'Result: ' . $result;
//                    $app->enqueueMessage($OutText, 'notice');

                    if (empty($result))
                    {
                        $app->enqueueMessage('AssignNewOrdering failed for $Gallery->id: ' . $Gallery->id . ' '
                            . '$NewOrdering: ' . $NewOrdering . ' '
                            , 'notice');

                        $IsAssigned = false;
                        break;
                    }
                }
            }

        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing AssignNewOrdering: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsAssigned;
    }


    /**
     * Saves changed manual ordering of galleries
     * Does bring user data in standard array and saves it
     * @return bool true if successful
     *
     * @since 4.3.1
     */
    public function saveOrdering() // ... ____
    {
        $IsSaved = false;

        try {

            //--- Collect data -------------------------------

            $input = JFactory::getApplication()->input;
            $newOrderingHtml = $input->post->get('dbOrdering', '', 'STRING');


            /** ToDo: ? empty ? wrong data ? *
             * if ((typeof(serverDbOrderingValue) === 'undefined') || (serverDbOrderingValue === null)) {
             * alert("serverDbOrdering is not defined ==> Server ordering values not exsisting");
             * return;
             * }
             * /**/

            // User changes
            $newOrdering = json_decode($newOrderingHtml, true);

            // ...
            $dbOrdering = $this->ConvertOrderingHtml2PhpObject($newOrdering);
            // $this->displayDbOrderingArray ("NewOrdering");

            // Do standard ordering with save
            $IsSaved = $this->doOrdering($dbOrdering);
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing saveOrdering: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsSaved;
    }

    /**
     * Does the ordering with the given data and saves it
     *
     * @param $dbOrdering Object with IS,Parent id and ordering of a gallery object
     * @return bool true if successful
     *
     * @since 4.3.1
     */
    public function doOrdering ($dbOrdering)
    {
        $IsSaved = false;

        try {
            // ToDo: Remove: Use $dbOrdering always as function parameter
            $this->dbOrdering = $dbOrdering;

            $this->RemoveOrphanIds ();
            // $this->displayDbOrderingArray("Remove Orphans");

            // Sort array by (new) ordering
            $this->SortByOrdering ();
            //$this->displayDbOrderingArray("After sort (1)");

            // Reassign as Versions of $.3.0 may contain no parent child order
            // Recursive assignment of ordering  (child direct after parent)
            $this->PreAssignOrdering ();
            //$this->displayDbOrderingArray("After DoPreOrdering");

            // Save Ordering in HTML elements
            $IsSaved = $this->AssignNewOrdering ($this->dbOrdering);

            // parent::reorder();
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing saveOrdering: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsSaved;
    }

    /**
     * Collect parent galleries
     *
     * @return array|mixed
     *
     * @since 4.3.1
     */
    public static function CollectParentGalleries ()
    {
        $Ids2Ordering = array();

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

//            $query->select(array ($db->quoteName('id'), $db->quoteName('ordering')))
            $query->select($db->quoteName(array ('id', 'ordering')))
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->where($db->quoteName('parent').'=0')
                ->order('ordering ASC');
            $db->setQuery($query);

            $Ids2Ordering = $db->loadObjectList();

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'CollectParentGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $Ids2Ordering;
    }

	/**
	 * @param $ParentId
	 *
	 * @return array
	 *
	 * @since 4.3.0
	 */
    public static function CollectChildGalleries ($ParentId)
    {
        $Ids2Ordering = array();

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select($db->quoteName(array ('id', 'ordering', 'parent')))
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->where($db->quoteName('parent').'='.$db->quote((int)$ParentId))
                ->order('ordering ASC');
            $db->setQuery($query);

            $Ids2Ordering = $db->loadObjectList();

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'CollectChildGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $Ids2Ordering;
    }

	/**
	
	 * @since 4.3.0
    */
    public static function GalleriesByParentAndOrdering ()
    {
        $Ids2Ordering = array();

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select($db->quoteName(array ('id', 'ordering', 'parent')))
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->order('parent ASC, ordering ASC');
            $db->setQuery($query);

            $Ids2Ordering = $db->loadObjectList();

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'CollectAllChildGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $Ids2Ordering;
    }


    /**
     * Collects 'id', 'ordering', 'parent', 'name' of all gelleries in an array
     * @return array|mixed 'id', 'ordering', 'parent', 'name'
     *
     * @since 4.3.0
     */
    public static function OrderedGalleries ()
    {
        $OrderedGalleries = array();

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            /**/
            $query->select($db->quoteName(
                array ('id', 'ordering', 'parent', 'name')))
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->order('ordering ASC');
            $db->setQuery($query);

            $OrderedGalleries = $db->loadObjectList();

            // echo '$OrderedGalleries: ' . json_encode($OrderedGalleries) . '<br>';

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'OrderedGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $OrderedGalleries;
    }

    /***
     * Query a node’s children:
     *   SELECT * FROM Comments c1 LEFT JOIN Comments c2 ON (c2.parent_id = c1.comment_id);
     * Query a node’s parent:
     *   SELECT * FROM Comments c1 JOIN Comments c2 ON (c1.parent_id = c2.comment_id);
     * Can’t Handle Deep Trees
     * SELECT * FROM Comments c1
     *      LEFT JOIN Comments c2 ON (c2.parent_id = c1.comment_id)
     *      LEFT JOIN Comments c3 ON (c3.parent_id = c2.comment_id)
     *      LEFT JOIN Comments c4 ON (c4.parent_id = c3.comment_id)
     *      LEFT JOIN Comments c5 ON (c5.parent_id = c4.comment_id)
     *      LEFT JOIN Comments c6 ON (c6.parent_id = c5.comment_id)
     *      LEFT JOIN Comments c7 ON (c7.parent_id = c6.comment_id)
     *      LEFT JOIN Comments c8 ON (c8.parent_id = c7.comment_id)
     *      LEFT JOIN Comments c9 ON (c9.parent_id = c8.comment_id)
     *      LEFT JOIN Comments c10 ON (c10.parent_id = c9.comment_id)
     *  ... it still doesn’t support unlimited depth!
     *
     *
     * $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select('a.id, a.name, c.id as parent_id, c.name as parent_name')
    ->from('#__records as a');
    $query->select('b.parent as parentid');
    $query->join('LEFT', '#__parents AS b ON b.child = a.id');
    $query->join('LEFT', '#__records AS c ON b.parent = c.id');
    $db->setQuery($query);
    return $query;
    /**/
	
	/**
	
	
	 * @since 4.3.0
    */

    public static function LeftJoinGalleries ()
    {
        $OrderedGalleries = array();

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            /**
            $query->select($db->quoteName(
                array ('a1.id', 'a1.ordering', 'a1.parent', 'a1.name')))
                ->from('#__rsgallery2_galleries as a1')

                ->join('LEFT', '#__rsgallery2_galleries AS a2 ON a2.parent = a1.id')
                ->join('LEFT', '#__rsgallery2_galleries AS a3 ON a2.parent = a2.id')
                /**
                ->join('LEFT', '#__rsgallery2_galleries AS a4 ON a2.parent = a3.id')
                ->join('LEFT', '#__rsgallery2_galleries AS a5 ON a2.parent = a4.id')
                ->join('LEFT', '#__rsgallery2_galleries AS a6 ON a2.parent = a5.id')
                ->join('LEFT', '#__rsgallery2_galleries AS a7 ON a2.parent = a6.id')
                ->join('LEFT', '#__rsgallery2_galleries AS a8 ON a2.parent = a7.id')
                ->join('LEFT', '#__rsgallery2_galleries AS a9 ON a2.parent = a8.id')
                ->join('LEFT', '#__rsgallery2_galleries AS a10 ON a2.parent = a9.id')
                /**
                //->group($query->qn('a1.parent'))
                ->group($query->qn('a1.id'))
            //    ->order('ordering ASC')
            //    ->order('name ASC')
            ;



            /**
            SELECT
                  a.id,
                  a.parentId,
                  a.ordering,
                  a.name
            FROM
                gallery a
            LEFT JOIN gallery b ON a.parentId = b.id
            ORDER BY
                  COALESCE( b.ordering, a.ordering),
                  case when a.parentId = 0 then 1 else 2 end,
                  a.ordering
            /**/

            /** !!! ok for second level  !!! */
            $query->select($db->quoteName(
                array ('parentG.id', 'parentG.ordering', 'parentG.parent', 'parentG.name')))
                ->from('#__rsgallery2_galleries as parentG')
                ->join('LEFT', '#__rsgallery2_galleries AS child ON parentG.parent = child.id')
                ->order('COALESCE( child.ordering, parentG.ordering),
                  case when parentG.parent = 0 then 1 else 2 end,
                  parentG.ordering')
            ;
            /**/

            /**
            $query->select($db->quoteName(
                array ('a1.id', 'a1.ordering', 'a1.parent', 'a1.name')))
                ->from('#__rsgallery2_galleries as a1')

                ->join('LEFT', '#__rsgallery2_galleries AS a2 ON a2.parent = a1.id')
                ->join('LEFT', '#__rsgallery2_galleries AS a3 ON a2.parent = a2.id')

                ->order('COALESCE( a1.ordering, a2.ordering, a3.ordering),
                  case when a1.parent = 0 then 1 else 2 end,
                  a1.ordering')
            ;
            /**/

            $db->setQuery($query);

            // echo $db->getQuery() . '<br>';

            $OrderedGalleries = $db->loadObjectList();

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'LeftJoinGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $OrderedGalleries;
    }


	/**
	
	 * @since 4.3.0
    */
    public static function countGalleries()
    {
        $galleryCount = 0;

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('count(1)');
            $query->from('#__rsgallery2_galleries');

            /* Use other function to leave out not published galleries
             Only for superadministrators this includes the unpublished items
            if (!JFactory::getUser()->authorise('core.admin', 'com_rsgallery2'))
            {
                $query->where('published = 1');
            }
            /**/
            $db->setQuery($query);

            // get the count
            $galleryCount = $db->getNumRows();
            
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'countImages: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $galleryCount;
    }
    /**/

    /**
	
	.
    public static function ()
    {
        $... = array();

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);


        } catch (RuntimeException $e) {


        }

        return $...;
    }
    /**/

} // class

