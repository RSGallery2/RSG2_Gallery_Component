<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
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
class rsgallery2ModelGalleries extends JModelList
{
    /**
     * Create list of usable filter fields
     *
     * @param array $config Field on which be sorting is available
     *
     * @since 4.3.0
     */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'parent', 'a.parent',
				'name', 'a.name',
				'alias', 'a.alias',
				'description', 'a.description',
				'published', 'a.published',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'ordering', 'a.ordering',
				'date', 'a.date',
				'hits', 'a.hits',
				'params', 'a.params',
				'user', 'a.user',
				'uid', 'a.uid',
				'allowed', 'a.allowed',
				'thumb_id', 'a.thumb_id',
				'asset_id', 'a.asset_id',
				'access', 'a.access'
                //, 'image_count', 'a.image_count'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
     * @since   4.3.0
	 */
	protected function populateState($ordering = 'a.id', $direction = 'desc')
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search',
			'', 'string');
		$this->setState('filter.search', $search);

//		$authorId = $this->getUserStateFromRequest($this->context . '.filter.user_id', 'filter_author_id');
//		$this->setState('filter.author_id', $authorId);

		$uid = $this->getUserStateFromRequest($this->context . '.filter.uid', 'filter_uid');
		$this->setState('filter.uid', $uid);

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access');
		$this->setState('filter.access', $access);

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string $id A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   4.3.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.uid');
		$id .= ':' . $this->getState('filter.access');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
     *
     * @since   4.3.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.           
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Query for gallery data.
		$actState =
			$this->getState(
				'list.select',
				'a.id, a.parent, a.name, a.alias, a.description, a.published, '
				. 'a.checked_out, a.checked_out_time, a.ordering, a.date, '
				. 'a.hits, a.params, a.user, a.uid, a.allowed, a.thumb_id, '
				. 'a.asset_id, a.access '
//				. ', b.gallery_id'
			);
		$query->select($actState);
		$query->from('#__rsgallery2_galleries as a');

		/* Count child images */
		$query->select('COUNT(img.gallery_id) as image_count')
			->join('LEFT', '#__rsgallery2_files AS img ON img.gallery_id = a.id'
			);

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the access groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		$query->group($query->qn('a.id'));

		// Filter on the user Id.
		if ($uid = $this->getState('filter.uid'))
		{
			$query->where('a.uid = ' . $db->quote($uid));
		}

		// Filter on the access type.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . $db->quote($access));
		}

		//
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'a.name LIKE ' . $search
				. ' OR a.user LIKE ' . $search
				. ' OR a.date LIKE ' . $search
			);
		}

		// Add the list ordering clause.

		// changes needs change above too -> populateState

		// 2017.02.28 Standard ordering by ID:
        // $orderCol = $this->state->get('list.ordering', 'a.id');

        //  RESG old  . " ORDER BY parent, ordering"
		$orderCol = $this->state->get('list.ordering', 'a.parent, a.ordering');
		$orderDirn = $this->state->get('list.direction', 'desc');
		/**
        if ($orderCol == 'a.parent')
        {
            $orderCol = 'a.parent ' . $orderDirn . ', a.ordering';
        }
        /**
        if ($orderCol == 'a.title')
        {
            $orderCol = 'a.parent ' . $orderDirn . ', a.ordering';
        }
        /**/
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

    /**
     * Saves changed manual ordering of galleries
     *
     * @return bool true if successful
     *
     * @since 4.3.0
     *  old
    public function saveOrdering()
    {
        $IsSaved = false;

        try
        {

            $input  = JFactory::getApplication()->input;
            $orders = $input->post->get('order', array(), 'ARRAY');
            $ids    = $input->post->get('ids', array(), 'ARRAY');

            // $CountOrders = count($ids);
            $CountIds = count($ids);

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
    /**/


    /**
	 * This function will retrieve the data of the n last uploaded images
	 *
	 * @param int $limit > 0 will limit the number of lines returned
	 *
	 * @return array rows with image name, gallery name, date, and user name as rows
     *
     * @since   4.3.0
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

			$db->setQuery($query, 0, $limit);
			$rows = $db->loadObjectList();

			foreach ($rows as $row)
			{
				$ImgInfo         = array();
				$ImgInfo['name'] = $row->name;
				$ImgInfo['id']   = $row->id;

				//$ImgInfo['user'] = rsgallery2ModelGalleries::getUsernameFromId($row->uid);
				$user            = JFactory::getUser($row->uid);
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
	 *
	 * @param int $limit > 0 will limit the number of lines returned
	 *
	 * @return array rows with image name, gallery name, date, and user name as rows
     *
     * @since   4.3.0
	 */
	static function lastWeekGalleries($limit)
	{
		$latest = array();

        try {
            $lastWeek = mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"));
            $lastWeek = date("Y-m-d H:m:s", $lastWeek);

            // Create a new query object.
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query
                ->select('*')
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->where($db->quoteName('date') . '> = ' . $db->quoteName($lastWeek))
                ->order($db->quoteName('id') . ' DESC');

            $db->setQuery($query, 0, $limit);
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
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'lastWeekGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

		return $latest;
	}

	/**
     * Count images in gallery
     *
     * @param $galleryId
	 *
	 * @return int returns the total number of items in the given gallery.
     *
     * @since   4.3.0
	 */
	public static function countImages($galleryId)
	{
		$imageCount = 0;

		try
		{
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);

			$query->select('count(1)');
			$query->from('#__rsgallery2_files');
			$query->where('gallery_id=' . (int) $galleryId);
			// Only for superadministrators this includes the unpublished items
			if (!JFactory::getUser()->authorise('core.admin', 'com_rsgallery2'))
			{
				$query->where('published = 1');
			}
			$db->setQuery($query);

			$imageCount = $db->loadResult();

			// ToDo: use following instead of above
            // get the count
            //$imageCount = $db->getNumRows();
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

