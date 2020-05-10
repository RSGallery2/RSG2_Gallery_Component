<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2020 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

/**
 * Image(s) list model
 *
 * @since 4.3.0
 */
class Rsgallery2ModelImages extends JModelList
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
				'name', 'a.name',
				'alias', 'a.alias',
				'descr', 'a.descr',
				'gallery_id', 'a.gallery_id',
				'title', 'a.title',
				'hits', 'a.hits',
				'date', 'a.date',
				'rating', 'a.rating',
				'votes', 'a.votes',
				'comments', 'a.comments',
				'published', 'a.published',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'ordering', 'a.ordering',
				'approved', 'a.approved',
				'userid', 'a.userid',
				'params', 'a.params',
				'asset_id', 'a.asset_id'
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
		// $app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search',
			'', 'string');
		$this->setState('filter.search', $search);

//		$authorId = $this->getUserStateFromRequest($this->context . '.filter.userid', 'filter_author_id');
//		$this->setState('filter.author_id', $authorId);

		$gallery_id = $this->getUserStateFromRequest($this->context . '.filter.gallery_id', 'filter_gallery_id');
		$this->setState('filter.gallery_id', $gallery_id);

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
		$id .= ':' . $this->getState('filter.gallery_id');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   4.3.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Query for all images data.
		$actState =
			$this->getState(
				'list.select',
				'a.id, a.name, a.alias, a.descr, a.gallery_id, a.title, a.hits, '
				. 'a.date, a.rating, a.votes, a.comments, a.published, '
				. 'a.checked_out, a.checked_out_time, a.ordering, '
				. 'a.approved, a.userid, a.params, a.asset_id'
			);
		$query->select($actState);
		$query->from('#__rsgallery2_files as a');

		/* parent gallery name */
		$query->select('gal.name as gallery_name')
			->join('LEFT', '#__rsgallery2_galleries AS gal ON gal.id = a.gallery_id'
			);

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		$query->group($query->qn('a.id'));

		// Filter on the gallery Id.
		if ($gallery_id = $this->getState('filter.gallery_id'))
		{
			$query->where('a.gallery_id = ' . $db->quote($gallery_id));
		}

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'a.name LIKE ' . $search
				. ' OR a.descr LIKE ' . $search
				. ' OR gal.name LIKE ' . $search
				. ' OR a.date LIKE ' . $search
			);
		}

		// Add the list ordering clause.

		// changes need changes above too -> populateState
		$orderCol  = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'desc');

		if ($orderCol == 'a.ordering' || $orderCol == 'ordering')
		{
			$orderCol = 'a.gallery_id ' . $orderDirn . ', a.ordering';
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Saves changed manual ordering of galleries
	 *
	 * @return bool true if successful
	 *
	 * @since 4.3.0
	 * @throws Exception
	 */
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

				$query->update($db->quoteName('#__rsgallery2_files'))
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
	 * Resets the hits
	 *
	 * @return bool true if successful
	 *
	 * @throws Exception
	 * @since 4.3.0
	 */
	public function resetHits()
	{
		$IsSaved = false;

		try
		{

			$input = JFactory::getApplication()->input;
			$cids  = $input->get('cid', array(), 'ARRAY');

			if (count($cids) > 0)
			{

				//Reset hits
				$cids = implode(',', $cids);

				$db    = JFactory::getDBO();
				$query = $db->getQuery(true);

				$fields = array(
					$db->quoteName('hits') . '=0'
				);

				$conditions = array(
					$db->quoteName('id') . ' IN ( ' . $cids . ' )'
				);

				$query->update($db->quoteName('#__rsgallery2_files'))
					->set($fields)
					->where($conditions);

				$db->setQuery($query);
				$IsSaved = $db->execute();

				/**
				 *
				 * $query = 'UPDATE `#__rsgallery2_files` ' .
				 * ' SET `hits` = 0 ' .
				 * ' WHERE `id` IN ( '.$cids.' )';
				 * $result = $db->execute();
				 *
				 * if (!$database->execute()) {
				 * echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				 * }
				 *
				 * /**/

				// $msg .= "<br>";
				//$msg .= JText::_('COM_RSGALLERY2_resetHits_done');
			}
			else
			{
				/**
				 * // count ($cid) == 0)
				 * $OutTxt = 'resetHits: Selection not defined';
				 * $app = JFactory::getApplication();
				 * $app->enqueueMessage($OutTxt, 'error');
				 */
				$ErrTxt = 'Error model resetHits: Selection not defined';
				throw new Exception(JText::_($ErrTxt), 1);
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = 'Error executing model resetHits: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsSaved;
	}

	/**
	 * Fetches the name of the given gallery id
	 *
	 * @param string $id gallery id ? string or int ?
	 *
	 * @return string Name of found gallery or nothing
	 * @since 4.3.0
     */
	// ToDO: Rename as it may not be parent gallery name :-()
	protected static function getParentGalleryName($id)
	{
		// Create a new query object.
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		//$sql = 'SELECT `name` FROM `#__rsgallery2_galleries` WHERE `id` = '. (int) $id;
		$query
			->select('name')
			->from('#__rsgallery2_galleries')
			->where($db->quoteName('id') . ' = ' . (int) $id);

		$db->setQuery($query);
		$db->execute();

		// http://docs.joomla.org/Selecting_data_using_JDatabase
		$name = $db->loadResult();
		$name = $name ? $name : JText::_('COM_RSGALLERY2_GALLERY_ID_ERROR');

		return $name;
	}

	/**
	 * This function will retrieve the data of the n last uploaded images
	 *
	 * @param int $limit > 0 will limit the number of lines returned
	 *
	 * @return array rows with image name, gallery name, date, and user name as rows
	 *
	 * @since   4.3.0
	 * @throws Exception
	 */
	public static function latestImages($limit)
	{
		$latest = array();

		try
		{
			// Create a new query object.
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);

			$query
				->select('*')
				->from($db->quoteName('#__rsgallery2_files'))
				->order($db->quoteName('id') . ' DESC');

			$db->setQuery($query, 0, $limit);
			$rows = $db->loadObjectList();

			foreach ($rows as $row)
			{
				$ImgInfo            = array();
				$ImgInfo['name']    = $row->name;
				$ImgInfo['gallery'] = rsgallery2ModelImages::getParentGalleryName($row->gallery_id);
				$ImgInfo['date']    = $row->date;

				//$ImgInfo['user'] = rsgallery2ModelGalleries::getUsernameFromId($row->userid);
				$user            = JFactory::getUser($row->userid);
				$ImgInfo['user'] = $user->get('username');

				$latest[] = $ImgInfo;
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'latestImages: Error executing query: "' . $query . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $latest;
	}

	/**
	 * This function will retrieve the data of the n last uploaded images
	 *
	 * @param int $limit > 0 will limit the number of lines returned
	 *
	 * @return array rows with image name, gallery name, date, and user name as rows
	 *
	 * @since   4.3.0
	 */
	public static function lastWeekImages($limit)
	{
		$latest = array();

		$lastWeek = mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"));
		$lastWeek = date("Y-m-d H:m:s", $lastWeek);

		// Create a new query object.
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		//$query = 'SELECT * FROM `#__rsgallery2_files` WHERE (`date` >= '. $database->quote($lastweek)
		//	.' AND `published` = 1) ORDER BY `id` DESC LIMIT 0,5';

		$query
			->select('*')
			->from($db->quoteName('#__rsgallery2_files'))
			->where($db->quoteName('date') . '> = ' . $db->quoteName($lastWeek))
			->order($db->quoteName('id') . ' DESC');

		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row)
		{
			$ImgInfo            = new stdClass;
			$ImgInfo['name']    = $row->name;
			$ImgInfo['gallery'] = rsgallery2ModelImages::getParentGalleryName($row->gallery_id);
			$ImgInfo['date']    = $row->date;
			$ImgInfo['user']    = rsg2Common::getUsernameFromId($row->userid);

			$latest[] = $ImgInfo;
		}

		return $latest;
	}

	/**
	 * Count comments on image
	 *
	 * @param $ImageId
	 *
	 * @return int returns the total number of items in the given gallery.
	 *
	 * @since   4.3.0
	 * @throws Exception
	 */
	public static function getCommentCount($ImageId)
	{
		$commentCount = 0;

		try
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('item_id'))
				->from($db->quoteName('#__rsgallery2_comments'))
				->where($db->quoteName('item_id') . ' = ' . $ImageId);
			$db->setQuery($query);

			$commentRows  = $db->loadObjectList();
			$commentCount = count($commentRows);
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


	/**
	 * Save user input from image parameter annotation in database of each image
	 *
	 * @param $ImageIds List of image ids from database
	 *
	 * @return string
	 *
	 * @since 4.3.2
	 */
	public function save_imagesProperties($ImageIds)
	{
		$ImgCount = 0;

		$msg = "model images: save_imagesProperties: " . '<br>';

		$imgModel = self::getInstance('image', 'RSGallery2Model');

		foreach ($ImageIds as $ImageId)
		{
			$IsSaved = $imgModel->save_imageProperties($ImageId);
			if ($IsSaved){
				$ImgCount++;
			}
		}

		// $msg '... successful assigned .... images ...
		$msg =  ' Successful saved ' . $ImgCount . ' image properties';
		return $msg;
	}

	/**
	 * Fetches base file names identified by the list of given image ids
	 *
	 * @param $ImageIds array List of image ids from database
	 *
	 * @return string [] file names
	 *
	 * @since 4.3.2
	 * @throws Exception
	 */
	public function fileNamesFromIds($ImageIds)
	{
		$fileNames = [];

		$msg = "model images: fileNamesFromIds : " . '<br>';

		try
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('name'))
				->from($db->quoteName('#__rsgallery2_files'))
				->where($db->quoteName('id') . ' IN ' . ' (' . implode(',', $ImageIds) . ')');
			$db->setQuery($query);

			$fileNames = $db->loadColumn(); // wrong $db->loadObjectList();
		}

		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing query: "' . $query . '" in fileNamesFromIds $ImageIds count:' . count ($ImageIds) . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $fileNames;
	}

	/**
	 * Fetches base file name identified by the given image id
	 *
	 * @param $ImageId
	 *
	 * @return string filename
	 *
	 * @since 4.3.2
	 * @throws Exception
	 */
	public function galleryIdFromId($ImageId)
	{
		$galleryId = -1;

		try
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('gallery_id'))
				->from($db->quoteName('#__rsgallery2_files'))
				->where(array($db->quoteName('id') . '=' . $ImageId));
			$db->setQuery($query);
			$db->execute();

			$galleryId = $db->loadResult();
		}

		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing query: "' . $query . '" in galleryIdFromId $ImageId: "' . $ImageId .  '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $galleryId;
	}
}

