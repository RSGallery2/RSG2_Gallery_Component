<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
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
    public static function orderRsg2ByOld15Method ()
    {
        $IsSuccessful = false;

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $app = JFactory::getApplication();
//           $app->enqueueMessage('orderRsg2ByOld15Method (B)', 'notice');

            // Reorder all parent galleries (which have no own parent assigned)
            $Parents = rsgallery2ModelGalleriesOrder::CollectParentGalleries ();
            $orderIdx = 1;

            // ordering of parents
            foreach ($Parents as $Parent)
            {
                $app->enqueueMessage('$Parent->id: ' . $Parent->id . ' '
                    . '$Parent->ordering: ' . $Parent->ordering . ' '
                    . '$orderIdx: ' . $orderIdx . ' '
                    , 'notice');

                if ($Parent->ordering != $orderIdx)
                {
                    $query->clear();
                    $query->update($db->quoteName('#__rsgallery2_galleries'))
                        ->set($db->quoteName('ordering') . '=' . $db->quote((int) $orderIdx))
                        ->where(array($db->quoteName('id') . '=' . $db->quote((int) $Parent->id)));
                    $db->setQuery($query);

                    $result = $db->execute();
                    if (empty($result))
                    {
                        $app->enqueueMessage('orderRsg2ByOld15Method (b--)', 'notice');
                        break;
                    }

                    $app->enqueueMessage('$result (1): ' . $result);
                }

                $orderIdx++;
            }

            $app->enqueueMessage('orderRsg2ByOld15Method (c)', 'notice');

            // Collect all galleries which are not sub galleries
            $ChildIds2Ordering = rsgallery2ModelGalleriesOrder::CollectAllChildGalleries ();

            $LastParentId = -1;
            $orderIdx = 1;
            foreach ($ChildIds2Ordering as $Child) {
                $ActParentId = $Child->parent;

                // New set ...
                if ($ActParentId != $LastParentId)
                {
                    $LastParentId = $ActParentId;
                    $orderIdx = 1;
                }

                $app->enqueueMessage('$Child->id: ' . $Child->id
                    . '$Child->parent: ' . $Child->parent . ' '
                    . '$Child->ordering: ' . $Child->ordering . ' '
                    . '$orderIdx: ' . $orderIdx . ' '
                    , 'notice');

                if ($Child->ordering != $orderIdx)
                {

                    $query->update($db->quoteName('#__rsgallery2_galleries'))
                        ->set($db->quoteName('ordering') . '=' . $db->quote((int) $orderIdx))
                        ->where(array($db->quoteName('id') . '=' . $db->quote((int) $Child->id)));
                    $db->setQuery($query);

                    $result = $db->execute();
                    if (empty($result))
                    {
                        $app->enqueueMessage('orderRsg2ByOld15Method (d--)', 'notice');
                        break;
                    }

                    $app->enqueueMessage('$result (2): ' . $result);
                }

                $orderIdx++;
            }

/**

            // ToDo: Use function  DISTINCT

            $count = rsgallery2ModelGalleriesOrder::countGalleries ();

            $groupings = array();

            // Collect all galleries which are not sub galleries
            // Collect order and sort by order



            // Prepare has parent gallery list to be checked
            -> call function


            // for each gallery


            // Increase local order +1




            // Handle has parent list

yyyy

            // update ordering values
            for ($idx = 0; $idx < $count; $idx++)
            {
                $row->load((int) $idx);
                $groupings[] = $row->parent;
                if ($row->ordering != $order[$i])
                {
                    $row->ordering = $order[$i];
                    if (!$row->store())
                    {
                        JError::raiseError(500, $mainframe->getErrorMsg());
                    } // if
                } // if
            } // for

            // reorder each group
            $groupings = array_unique($groupings);
            foreach ($groupings as $group)
            {
                $row->reorder('parent = ' . $database->Quote($group));
            } // foreach


/**/




             $IsSuccessful = true;

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
    public static function orderRsg2ByNewMethod ()
    {

        $IsSuccessful = false;

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $app = JFactory::getApplication();
            $app->enqueueMessage('orderRsg2ByNewMethod (B)', 'notice');

            // Reorder all parent galleries (which have no own parent assigned)
            $Parents = rsgallery2ModelGalleriesOrder::CollectParentGalleries ();
            $orderIdx = 1;

            // ordering of parents
            foreach ($Parents as $Parent)
            {
                $app->enqueueMessage('$Parent->id: ' . $Parent->id . ' '
                    . '$Parent->ordering: ' . $Parent->ordering . ' '
                    . '$orderIdx: ' . $orderIdx . ' '
                    , 'notice');

                if ($Parent->ordering != $orderIdx)
                {
                    $query->clear();
                    $query->update($db->quoteName('#__rsgallery2_galleries'))
                        ->set($db->quoteName('ordering') . '=' . $db->quote((int) $orderIdx))
                        ->where(array($db->quoteName('id') . '=' . $db->quote((int) $Parent->id)));
                    $db->setQuery($query);

                    $result = $db->execute();
                    if (empty($result))
                    {
                        $app->enqueueMessage('orderRsg2ByNewMethod (b--)', 'notice');
                        break;
                    }

                    $app->enqueueMessage('$result (1): ' . $result);
                }

                $orderIdx++;

                // Collect all galleries which are not sub galleries
                $ChildIds2Ordering = rsgallery2ModelGalleriesOrder::CollectChildGalleries ($Parent->id);

                foreach ($ChildIds2Ordering as $Child) {
                    $app->enqueueMessage('$Child->id: ' . $Child->id
                        . '$Child->parent: ' . $Child->parent . ' '
                        . '$Child->ordering: ' . $Child->ordering . ' '
                        . '$orderIdx: ' . $orderIdx . ' '
                        , 'notice');

                    $query->update($db->quoteName('#__rsgallery2_galleries'))
                        ->set($db->quoteName('ordering') . '=' . $db->quote((int) $orderIdx))
                        ->where(array($db->quoteName('id') . '=' . $db->quote((int) $Child->id)));
                    $db->setQuery($query);

                    $result = $db->execute();
                    if (empty($result))
                    {
                        $app->enqueueMessage('orderRsg2ByNewMethod (d--)', 'notice');
                        break;
                    }

                    $app->enqueueMessage('$result (2): ' . $result);

                    $orderIdx++;
                }

           }

           /* ToDo: Collect galleries with parent id but parent deleted







           /**/





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
    public static function orderRsg2ByUnorderMethod ()
    {
        $IsSuccessful = false;

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $app = JFactory::getApplication();
            $app->enqueueMessage('orderRsg2ByUnorderMethod (B)', 'notice');

            $app = JFactory::getApplication();
            $app->enqueueMessage('orderRsg2ByOld15Method (B)', 'notice');

            // Reorder all parent gallies (which have no own parent assigned)
            $Parents = rsgallery2ModelGalleriesOrder::OrderedGalleries ();
            shuffle($Parents);

            $orderIdx = 1;

            // ordering of parents
            foreach ($Parents as $Parent)
            {
                $app->enqueueMessage('$Parent->id: ' . $Parent->id . ' '
                    . '$Parent->ordering: ' . $Parent->ordering . ' '
                    . '$orderIdx: ' . $orderIdx . ' '
                    , 'notice');

                $query->update($db->quoteName('#__rsgallery2_galleries'))
                    ->set($db->quoteName('ordering') . '=' . $db->quote((int) $orderIdx))
                    ->where(array($db->quoteName('id') . '=' . $db->quote((int) $Parent->id)));
                $db->setQuery($query);

                $result = $db->execute();
                if (empty($result))
                {
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


    private function displayDbOrderingArray ($Title='') {

        $app = JFactory::getApplication();
        // $app->enqueueMessage('this->dbOrdering : ' . json_encode($this->dbOrdering), 'notice');

        $OutText = "Title: " . $Title . ": ";
        $Idx = 0;
        foreach ($this->dbOrdering as $dbGallery) {
            $Idx++;
            $OutText = $OutText . 'Idx:' . Idx  . ' ' .json_encode($dbGallery) + "<br>";
        }

        $app->enqueueMessage($OutText, 'notice');
    }


    /**
     * Saves changed manual ordering of galleries
     *
     * @return bool true if successful
     *
     * @since 4.3.0
     */
    public function saveOrdering()
    {
        $IsSaved = false;

        try
        {
            $input  = JFactory::getApplication()->input;
            $newOrderingHtml = $input->post->get('dbOrdering', '', 'STRING');

            $app = JFactory::getApplication();
            // $app->enqueueMessage('newOrderingHtml: ' . json_encode($newOrderingHtml), 'notice');

            /** toDO: ? empty ? wrong data ? *
            if ((typeof(serverDbOrderingValue) === 'undefined') || (serverDbOrderingValue === null)) {
            alert("serverDbOrdering is not defined ==> Server ordering values not exsisting");
            return;
            }
            /**/

            // alert(serverDbOrderingValue);

            //alert ("Before DbOrdering object");
            //$newOrdering = jQuery.parseJSON (serverDbOrderingValue);
            //var_dump(json_decode($json));
            //var_dump(json_decode($json, true));
            $newOrdering =json_decode($newOrderingHtml, true);

            $app->enqueueMessage('newOrdering: ' . json_encode($newOrdering), 'notice');

            $this->dbOrdering = $newOrdering;
            $this->displayDbOrderingArray ("NewOrdering");

            return;

            RemoveOrphanIds ();
            //displayDbOrderingArray ("Remove Orphans");

            // Reassign as Versions of $.3.0 may contain no parent child order
            DoOrdering ();
            //displayDbOrderingArray ("After DoOrdering");

            // Sort array by (new) ordering
            SortByOrdering ();
            //displayDbOrderingArray ("After sort (1)");

            // Save Ordering in HTML elements
            AssignNewOrdering (OrderingElements);


            $db    = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($query);

            for ($idx = 0; $idx < $CountIds; $idx++)
            {
                $id       = $ids[$idx];
                $orderIdx = $orders[$idx];
                // $msg .= "<br>" . '$id: ' . $id . '$orderIdx: ' . $orderIdx;

                $query->clear();

                $query->update($db->quoteName('#__rsgallery2_galleries'))
                    ->set(array($db->quoteName('ordering') . '=' . $orderIdx))
                    ->where(array($db->quoteName('id') . '=' . $id));

                $result = $db->execute();
                if (empty($result))
                {
                    break;
                }
            }
            if (!empty($result))
            {
                $IsSaved = true;
            }

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
            $OutTxt .= 'CollectParentGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $Ids2Ordering;
    }


    public static function CollectAllChildGalleries ()
    {
        $Ids2Ordering = array();

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select($db->quoteName(array ('id', 'ordering', 'parent')))
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->where($db->quoteName('parent').'!=0')
                ->order('parent ASC');
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
            $OutTxt .= 'CollectParentGalleries: Error executing query: "' . $query . '"' . '<br>';
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
            $OutTxt .= 'CollectParentGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $OrderedGalleries;
    }



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

