<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . '/components/com_rsgallery2/helpers/rsg2Common.php';

// import the Joomla model list library
jimport('joomla.application.component.modellist');

/**
 * ImagesList Model
 */
class rsgallery2ModelImages extends JModelList
{
    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Query for all images.
        $query
            ->select('*')
            ->from('#__rsgallery2_files');

        return $query;
    }

    /**
     * Fetches the name of the given gallery id
     * @param string $id gallery id ? string or int ?
     * @return string Name of found gallery or nothing
     */
    protected static function getParentGalleryName($id)
    {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //$sql = 'SELECT `name` FROM `#__rsgallery2_galleries` WHERE `id` = '. (int) $id;
        $query
            ->select('name')
            ->from('#__rsgallery2_galleries')
            ->where($db->quoteName('id') .' = ' . (int)$id);

        $db->setQuery($query);
        $db->execute();

        // http://docs.joomla.org/Selecting_data_using_JDatabase
        $name = $db->loadResult();
        $name = $name ? $name : JText::_('COM_RSGALLERY2_GALLERY_ID_ERROR');

        return $name;
    }

    /**
     * This function will retrieve the data of the n last uploaded images
     * @param int $limit > 0 will limit the number of lines returned
     * @return array rows with image name, gallery name, date, and user name as rows
     */
    static function latestImages($limit)
    {
        $latest = array();

        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //$query = 'SELECT * FROM `#__rsgallery2_files` WHERE (`date` >= '. $database->quote($lastweek)
        //	.' AND `published` = 1) ORDER BY `id` DESC LIMIT 0,5';

        $query
            ->select('*')
            ->from($db->quoteName('#__rsgallery2_files'))
            ->order($db->quoteName('id') . ' DESC');

        // $limit > 0 will limit the number of lines returned
        if ($limit && (int) $limit > 0)
        {
            $query->setLimit($limit);
        }

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        foreach ($rows as $row) {
            $ImgInfo = array();
            $ImgInfo['name'] = $row->name;
            $ImgInfo['gallery'] = rsgallery2ModelImages::getParentGalleryName ($row->gallery_id);
            $ImgInfo['date'] = $row->date;
            $ImgInfo['user'] = rsg2Common::getUsernameFromId ($row->userid);

            $latest[] = $ImgInfo;
        }

        return $latest;
    }


    public static function lastWeekImages($limit)
    {
        $latest = array();

        $lastWeek = mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"));
        $lastWeek = date("Y-m-d H:m:s", $lastWeek);

        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //$query = 'SELECT * FROM `#__rsgallery2_files` WHERE (`date` >= '. $database->quote($lastweek)
        //	.' AND `published` = 1) ORDER BY `id` DESC LIMIT 0,5';

        $query
            ->select('*')
            ->from($db->quoteName('#__rsgallery2_files'))
            ->where($db->quoteName('date') . '> = ' . $db->quoteName($lastWeek))
            ->order($db->quoteName('id') . ' DESC');

        // $limit > 0 will limit the number of lines returned
        if ($limit && (int) $limit > 0)
        {
            $query->setLimit($limit);
        }

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        foreach ($rows as $row) {
            $ImgInfo = new stdClass;
            $ImgInfo['name'] = $row->name;
            $ImgInfo['gallery'] = rsgallery2ModelImages::getParentGalleryName ($row->gallery_id);
            $ImgInfo['date'] = $row->date;
            $ImgInfo['user'] = rsg2Common::getUsernameFromId ($row->userid);

            $latest[] = $ImgInfo;
        }

        return $latest;
    }

    public static function getCommentCount ($ImageId)
    {
        $commentCount = 0;

        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select ($db->quoteName('item_id'))
                ->from($db->quoteName('#__rsgallery2_comments'))
                ->where($db->quoteName('item_id') . ' = '. $ImageId);
            $db->setQuery($query);

            $commentRows = $db->loadObjectList();
            $commentCount = count ($commentRows);
        }

        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing query: "' . $query . '" in getCommentCount' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $commentCount;
    }




}


