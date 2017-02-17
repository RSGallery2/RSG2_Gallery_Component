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
 * Comments list model
 *
 * @since 4.3.0
 */
class Rsgallery2ModelComments extends JModelList
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
	 * @since   4.3
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
	 * @return  string  An SQL query
     *
     * @since   4.3.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Query for all comments.
		$actState =
			$this->getState(
				'list.select',
				'a.id, a.user_id, a.user_name, a.user_ip, a.parent_id, a.item_id, '
				. 'a.item_table, a.datetime, a.subject, a.comment, a.published, '
				. 'a.checked_out, a.checked_out_time, a.ordering, a.params, a.hits '
			);
		$query->select($actState);

		$query->from('#__rsgallery2_comments as a');

		/* parent image name */
		$query->select('img.name as image_name')
			->join('LEFT', '#__rsgallery2_files AS img ON img.id = a.item_id'
			);

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		$query->group($query->qn('a.id'));

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'a.comment LIKE ' . $search
				. ' OR a.user_name LIKE ' . $search
				. ' OR a.user_ip LIKE ' . $search
				. ' OR a.item_id LIKE ' . $search
			);
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'item_id');
		//
		$orderDirn = $this->state->get('list.direction', 'desc');

		/** ToDo: when sorted by image id the comments shall be ordered by date ? ...
		if ($orderCol == 'a.ordering' || $orderCol == 'category_title')
		{
			$orderCol = 'c.title ' . $orderDirn . ', a.ordering';
		}
		/**/
		if ($orderCol == 'item_id')
		{
			$orderCol = 'item_id ' . $orderDirn . ', datetime desc';
			// $orderCol = 'item_id ' . $orderDirn . ', datetime asc';
			$orderDirn = '';
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

}
