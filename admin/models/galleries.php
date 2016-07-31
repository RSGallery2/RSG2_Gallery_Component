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
			// todo Use own db variables
			$config['filter_fields'] = array(
				'id', // 'a.id',
				'user_id', // 'a.user_id',
				'user_name', // 'a.user_name',
				'user_ip', // 'a.user_ip',
				'parent_id', // 'a.parent_id',
				'item_id', // 'a.item_id',
				'item_table', // 'a.item_table',
				'datetime', // 'a.datetime',
				'subject', // 'a.subject',
				'comment', // 'a.comment',
				'published', // 'a.published',
				'checked_out', // 'a.checked_out',
				'checked_out_time', // 'a.checked_out_time',
				'ordering', // 'a.ordering',
				'params', // 'a.params',
				'hits' //, 'a.hits'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	// ToDo: protected function populateState($ordering = 'item_id, datetime', $direction = 'desc')
	protected function populateState($ordering = 'item_id', $direction = 'desc')
	{
		// $app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search',
			'', 'string');
		$this->setState('filter.search', $search);

//		$authorId = $this->getUserStateFromRequest($this->context . '.filter.user_id', 'filter_author_id');
//		$this->setState('filter.author_id', $authorId);


		// List state information.
		parent::populateState($ordering, $direction);
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
		$actState =
			$this->getState(
				'list.select',
				'id, state, name, images, , , '
				. ', datetime, subject, comment, published, '
				. 'checked_out, checked_out_time, ordering, params, hits'
		 	);

		$query->select($actState);
        $query->from('#__rsgallery2_galleries');

		$search = $this->getState('filter.search');
		if(!empty($search)) {
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'comment LIKE ' . $search
				. ' OR user_name LIKE ' . $search
				. ' OR user_ip LIKE ' . $search
				. ' OR item_id LIKE ' . $search
			);
		}

		// Add the list ordering clause.
//		$orderCol = $this->state->get('list.ordering', 'item_id, datetime');
		$orderCol = $this->state->get('list.ordering', 'item_id');
		$orderDirn = $this->state->get('list.direction', 'desc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
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

                //$ImgInfo['user'] = rsgallery2ModelGalleries::getUsernameFromId($row->uid);
                $user = JFactory::getUser($row->uid);
                $ImgInfo['user'] = $user->get('username');

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

            //$ImgInfo['user'] = rsgallery2ModelGalleries::getUsernameFromId($row->uid);
            $user = JFactory::getUser($row->uid);
            $ImgInfo['user'] = $user->get('username');

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

} // class

