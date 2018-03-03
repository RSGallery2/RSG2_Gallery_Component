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
 * May not be needed ToDo: Delete table when one user has had a problem and we know how to move local acl to standard acl
 *
 * ToDo: Acl ... is not ready yet -> improve / delete
 *
 * acl list model
 *
 * @since 4.3.0
 */
class Rsgallery2ModelAcl_items extends JModelList
{
	/**
	
	 * @since 4.3.0
    
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', // 'a.id',
				'gallery_id', // 'a.gallery_id', 
				'parent_id', // 'a.parent_id', 
				'public_view', // 'a.public_view', 
				'public_up_mod_img', // 'a.public_up_mod_img', 
				'public_del_img', // 'a.public_del_img', 
				'public_create_mod_gal', // 'a.public_create_mod_gal', 
				'public_del_gal', // 'a.public_del_gal', 
				'public_vote_view', // 'a.public_vote_view', 
				'public_vote_vote', // 'a.public_vote_vote', 
				'registered_view', // 'a.registered_view', 
				'registered_up_mod_img', // 'a.registered_up_mod_img', 
				'registered_del_img', // 'a.registered_del_img', 
				'registered_create_mod_gal', // 'a.registered_create_mod_gal', 
				'registered_del_gal', // 'a.registered_del_gal', 
				'registered_vote_view', // 'a.registered_vote_view', 
				'registered_vote_vote', // 'a.registered_vote_vote', 
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
	 * @since   1.6
	 */
	// ToDo: protected function populateState($ordering = 'item_id, datetime', $direction = 'desc')
	protected function populateState($ordering = 'id', $direction = 'desc')
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
	 * @since 4.3.0
     */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Query for all galleries.
		$actState =
			$this->getState(
				'list.select',
				'id, gallery_id, parent_id, public_view, '
				. 'public_up_mod_img, public_del_img, '
				. 'public_create_mod_gal, public_del_gal, '
				. 'public_vote_view, public_vote_vote, '
				. 'registered_view, registered_up_mod_img, '
				. 'registered_del_img, registered_create_mod_gal, '
				. 'registered_del_gal, registered_vote_view, '
				. 'registered_vote_vote'
			);
		$query->select($actState);

		$query->from('#__rsgallery2_acl');
		
		$search = $this->getState('filter.search');
		if(!empty($search)) {
/**
				$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'comment LIKE ' . $search
				. ' OR user_name LIKE ' . $search
				. ' OR user_ip LIKE ' . $search
				. ' OR item_id LIKE ' . $search
			);
/**/
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'desc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}



}
