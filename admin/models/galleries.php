<?php
// No direct access to this file
defined('_JEXEC') or die;

/**
 * Galleries Model
 */
class rsgallery2ModelGalleries extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{

			$config['filter_fields'] = array(
				'id', 'a.id',
				'user_id', 'a.user_id',
				'user_name', 'a.user_name',
				'user_ip', 'a.user_ip',
				'parent_id', 'a.parent_id',
				'item_id', 'a.item_id',
				'item_table', 'a.item_table',
				'datetime', 'a.datetime',
				'subject', 'a.subject',
				'comment', 'a.comment',
				'published', 'a.published',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'ordering', 'a.ordering',
				'params', 'a.params',
				'hits', 'a.hits'
			);
		}

		parent::__construct($config);
	}


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
            $OutTxt .= 'latestGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $latest;
    }

    /**
     * This function will retrieve the data of the n last uploaded galleries
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

    /**
     * @param $galleryId
     * returns the total number of items in the given gallery.
     * @return int
     */
    public static function countImages ($galleryId)
    {
        $imageCount = 0;

        try
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('count(1)');
            $query->from('#__rsgallery2_files');
            $query->where('gallery_id='. (int) $galleryId);
            // Only for superadministrators this includes the unpublished items
            if (!JFactory::getUser()->authorise('core.admin','com_rsgallery2'))
                $query->where('published = 1');
            $db->setQuery($query);

            $imageCount = $db->loadResult();
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'countImages: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }


        return $imageCount;
    }
}

