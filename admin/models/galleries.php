<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . '/components/com_rsgallery2/helpers/rsg2Common.php';

// import the Joomla model list library
jimport('joomla.application.component.modellist');
/**
 * Galleries Model
 */
class rsgallery2ModelGalleries extends JModelList
{
	/**
	 * Method to build an SQL query to load the galleries list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
        // Query for all galleries.
        $query
            ->select('*')
            ->from('#__rsgallery2_galleries');


        return $query;
	}

    /**
     * This function will retrieve the user name based on the user id
     * @param int $uid user id
     * @return string the username
     * @todo isn't there a joomla function for this?
     */
    static function getUsernameFromId($uid) {  // ToDO: Move to somewhere else
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //$query = 'SELECT `username` FROM `#__users` WHERE `id` = '. (int) $uid;

        // Query for user with $uid.
        $query
            ->select('username')
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('id') .' = ' . (int)$uid);

        $db->setQuery($query);
        $name = $db->loadResult();

        return $name;
    }


    /**
     * This function will retrieve the data of the n last uploaded images
     * @param int $limit > 0 will limit the number of lines returned
     * @return array rows with image name, gallery name, date, and user name as rows
     */
    static function latestGalleries($limit)
    {
        $latest = array();

        try
        {
            // Create a new query object.
            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);

            //$query = 'SELECT * FROM `#__rsgallery2_files` WHERE (`date` >= '. $database->quote($lastweek)
            //	.' AND `published` = 1) ORDER BY `id` DESC LIMIT 0,5';

            $query
                ->select('*')
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->order($db->quoteName('id') . ' DESC');

            // $limit > 0 will limit the number of lines returned
            if ($limit && (int) $limit > 0)
            {
                $query->setLimit($limit);
            }

            $db->setQuery($query);
            $rows = $db->loadObjectList();

            foreach ($rows as $row)
            {
                $ImgInfo         = array();
                $ImgInfo['name'] = $row->name;
                $ImgInfo['id']   = $row->id;
                $ImgInfo['user'] = rsg2Common::getUsernameFromId($row->uid);

                $latest[] = $ImgInfo;
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $latest;
    }

    /**
     * This function will retrieve the data of the n last uploaded images
     * @param int $limit > 0 will limit the number of lines returned
     * @return array rows with image name, gallery name, date, and user name as rows
     */
    static function lastWeekGalleries($limit)
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
            ->from($db->quoteName('#__rsgallery2_galleries'))
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
            $ImgInfo = array();
            $ImgInfo['name'] = $row->name;
            $ImgInfo['id'] = $row->id;
            $ImgInfo['user'] = rsg2Common::getUsernameFromId ($row->uid);

            $latest[] = $ImgInfo;
        }

        return $latest;
    }





}



