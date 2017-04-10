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

            $galleryCount = $db->loadResult();

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

