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
				'access', 'a.access',
				'image_count', 'a.image_count'
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
	protected function populateState($ordering = 'a.id', $direction = 'desc')
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
				'a.id, a.parent, a.name, a.alias, a.description, a.published, '
				. 'a.checked_out, a.checked_out_time, a.ordering, a.date, '
				. 'a.hits, a.params, a.user, a.uid, a.allowed, a.thumb_id, '
				. 'a.asset_id, a.access '
//				. ', b.gallery_id'
		 	);
		$query->select($actState);

        $query->from('#__rsgallery2_galleries as a'); // as a');

		/*  */
		$query->select('COUNT(img.gallery_id) as image_count')
			->join('LEFT', '#__rsgallery2_files AS img ON img.gallery_id = a.id'
			);
		/**/

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		$query->group($query->qn('a.id'));


		$search = $this->getState('filter.search');
		if(!empty($search)) {
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'a.name LIKE ' . $search
				. ' OR a.user LIKE ' . $search
				. ' OR a.date LIKE ' . $search
			);
		}

		// Add the list ordering clause.

        // changes need change above too -> populateState
        $orderCol = $this->state->get('list.ordering', 'a.id');
        // $orderCol = $this->state->get('list.ordering', 'a.parent, a.ordering');
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

/*
	function saveOrder($cid)
	{
		JArrayHelper::toInteger($cid);
		$total = count($cid);
		$order = JRequest::getVar( ‘order’, array(0), ‘post’, ‘array’ );

		JArrayHelper::toInteger($order, array(0));
		$row = $this->getTable(”);
		// update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load((int) $cid[$i]);
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		return true;
	}
/**/

    /**
     * Saves changed manual ordering of galleries
     *
     * @throws Exception
     */
    public function saveOrdering()
    {
        $msg = "Model:saveOrdering: ";

        try {

            $input = JFactory::getApplication()->input;
            $orders = $input->post->get( 'order', array(), 'ARRAY');
            $ids = $input->post->get( 'ids', array(), 'ARRAY');

            // $CountOrders = count($ids);
            $CountIds = count($ids);

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($query);

            for ($idx = 0; $idx < $CountIds; $idx++) {
                $id = $ids[$idx];
                $orderIdx = $orders[$idx];
                // $msg .= "<br>" . '$id: ' . $id . '$orderIdx: ' . $orderIdx;

                $query->clear();

                $query->update($db->quoteName('#__rsgallery2_galleries'))
                    ->set(array($db->quoteName('ordering') . '=' . $orderIdx))
                    ->where(array($db->quoteName('id') . '='. $id));

                $result = $db->execute($query);
                //$msg .= "<br>" . "Query : " . $query->__toString();
                //$msg .= "<br>" . 'Query  $result: : ' . json_encode($result);
            }
            // $msg .= "<br>";


//	         parent::reorder();




            $msg .= JText::_( 'COM_RSGALLERY2_NEW_ORDERING_SAVED' );
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing saveOrdering: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

    }

    /**
     * Method to save the reordered nested set tree.
     * First we save the new order values in the lft values of the changed ids.
     * Then we invoke the table rebuild to implement the new ordering.
     *
     * @param   array    $idArray    An array of primary key ids.
     *
     * @return  boolean  False on failure or error, True otherwise
     *
     * @since   1.6
     */
    public function saveOrder($idArray = null, $lft_array = null)
    {
        // Get an instance of the table object.
        $table = $this->getTable();

        if (!$table->saveorder($idArray, $lft_array))
        {
            $this->setError($table->getError());

            return false;
        }

        // Clear the cache
        $this->cleanCache();

        return true;
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


    /**
     * @param $items : like items in $this->items = $this->get('Items');
     * The assoc list shall enable to easily create an parent depth
     * returns assoc list gallery_id -> parent
     * @return list[]
     */
    public static function createParentList ($items)
    {
        $ParentReferences = new object;

        foreach ($items as $item) {
            $ParentReferences [$item->id] = $item->parent;
        }

        return $ParentReferences;
    }


} // class

