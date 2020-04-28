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
 * Image list model
 * ToDo: Check if it is needed
 * ToDo: ??? Handle Limit of image numbers shown on one page ???
 *
 * @since 4.3.0
 */
class Rsgallery2ModelImagesProperties extends JModelList
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
		/**
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
		/**/
		
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
		/**
		// $app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search',
			'', 'string');
		$this->setState('filter.search', $search);

//		$authorId = $this->getUserStateFromRequest($this->context . '.filter.userid', 'filter_author_id');
//		$this->setState('filter.author_id', $authorId);

		$gallery_id = $this->getUserStateFromRequest($this->context . '.filter.gallery_id', 'filter_gallery_id');
		$this->setState('filter.gallery_id', $gallery_id);
		/**/
		
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
	 * state,ordering user, ? gallery name ?
	 * // ToDO: Function just copied from image model. Do strip not needed ...
	 *
	 * @return  string  An SQL query
     *
     * @since   4.3.0
	 */
	protected function getListQuery()
	{
		global $Rsg2DebugActive, $Rsg2DevelopActive;

		// Create a new query object.
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		/**/
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

		// parent gallery name 
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

		// cid's in URL ?
		$input = JFactory::getApplication()->input;
		$cids = $input->get('cid', -1, 'int');

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo 'cids: "' . json_encode($cids) . '"<br>';
		}

		if ($Rsg2DebugActive) {
			// identify active file
			JLog::add('cids: "' . json_encode($cids));
		}


		if (is_array ($cids))
		{
			$strCids = implode(", ", $cids);
			$query->where('a.id IN (' . $strCids . ')');
		}
		else
		{
			if (empty ($cids))
			{
				//$query->where('a.id = ' .  (int) $cids . '');
				$query->where('a.id = 0');
			}
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
		/**/
		
		return $query;
	}


}  // class

