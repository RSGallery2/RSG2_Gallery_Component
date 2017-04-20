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
            $app->enqueueMessage('orderRsg2ByOld15Method (B)', 'notice');

            // Reorder all parent gallies (which have no own parent assigned) 
            $Ids2Ordering = CollectParentGalleries ();
            $orderIdx = 1;
            foreach ($Ids2Ordering as $Id2Ordering)
            {
                if ($Id2Ordering['ordering'] != $orderIdx)
                {

                    $query->update($db->quoteName('#__rsgallery2_galleries'))
                        ->set(array($db->quoteName('ordering') . '=' . $orderIdx))
                        ->where(array($db->quoteName('id') . '=' . $Id2Ordering['id']));

                    $result = $db->execute();
                    if (empty($result))
                    {
                        break;
                    }

                }

                $orderIdx++;
            }

/**

            // ToDo: Use function  DISTINCT

            $count = countGalleries ();

            $groupings = array();

            // Collect all galleries which are not sub galleries
            // Collect order and sort by order



            // Prepare has parent gallery list to be checked
            -> call function


            // for each gallery


            // Increase local order +1




            // Handle has parent lsit

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




            // $IsSuccessful = true;
        } catch (RuntimeException $e) {


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

            // $IsSuccessful = true;
        } catch (RuntimeException $e) {


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

            // $IsSuccessful = true;
        } catch (RuntimeException $e) {


        }

        return $IsSuccessful;
    }


    public static function CollectParentGalleries ()
    {
        $Ids2Ordering = array();

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(array ($db->quoteName('id'), $db->quoteName('order')))
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

            /*
             * mysql select child direct after parent
            SELECT ...
            FROM comments AS parent
            LEFT JOIN comments AS child
            ON child.parent_id = parent.id
            WHERE parent.parent_id IS NULL
            ORDER BY parent.id, child.id;
            */

            /**
            $query->select($db->quoteName(
                array ('parentG.id', 'parentG.ordering', 'parentG.parent', 'parentG.name')))
                ->from('#__rsgallery2_galleries as parentG')
                ->join('LEFT', '#__rsgallery2_galleries AS child ON child.parent = parentG.id')
                ->where($db->quoteName('parentG.parent').'=0')
                ->order('parentG.ordering, child.ordering')
            ;
            /**/

            /**
            $query->select($db->quoteName(
                array ('parentG.id', 'parentG.ordering', 'parentG.parent', 'parentG.name')))
                ->from('#__rsgallery2_galleries as parentG')
 //               ->select($db->quoteName(
 //                   array ('child.id', 'child.ordering', 'child.parent', 'child.name')))
                ->join('LEFT', '#__rsgallery2_galleries AS child ON child.parent = parentG.id')
                ->where($db->quoteName('parentG.parent').'=0')
                ->order('parentG.ordering, child.ordering')
            ;
            /**/
            /**
            $query->select(array ('parentG.*', 'child.*'))
                ->from('#__rsgallery2_galleries as parentG')
                ->join('LEFT', '#__rsgallery2_galleries AS child ON child.parent = parentG.id')
                ->where($db->quoteName('parentG.parent').'=0')
//                ->order('parentG.ordering, child.ordering')
//                ->order('parentG.id, child.id')
//                ->order('parentG.ordering')
            ;
            /**/


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

            /**/
            $query->select($db->quoteName(
                array ('parentG.id', 'parentG.ordering', 'parentG.parent', 'parentG.name')))
                ->from('#__rsgallery2_galleries as parentG')
                ->join('LEFT', '#__rsgallery2_galleries AS child ON parentG.parent = child.id')
                ->order('COALESCE( child.ordering, parentG.ordering),
                  case when parentG.parent = 0 then 1 else 2 end,
                  parentG.ordering')
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

