<?php
// No direct access to this file
defined('_JEXEC') or die;

/**
 * CommentsList Model
 */
class Rsgallery2ModelComments extends JModelList
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

	/*
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id',
				'a.user_id',
				'a.user_name',
				'a.user_ip',
				'a.parent_id',
				'a.item_id',
				'a.item_table',
				'a.datetime',
				'a.subject',
				'a.comment',
				'a.published',
				'a.checked_out',
				'a.checked_out_time',
				'a.ordering',
				'a.params',
				'a.hits'
			)
		);
		$query->from('#__rsgallery2_comments AS a');
/**/
		// Query for all galleries.
		$query
			->select('*')
			->from('#__rsgallery2_comments')
			->order('item_id')
			;

		return $query;
	}




	/**

	public function getTable($type = 'Comments', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	// save raw ...
	public function save() {
		$msg = "Rsgallery2ModelCommentsSave: ";

		$input =JFactory::getApplication()->input;
		//$jform = $input->get( 'jform', array(), 'ARRAY');
		$data  = $input->post->get('jform', array(), 'array');

//		echo json_encode ($jform);
/*
		// Complete data array if needed
		$oldData = $model->getData();
		$data = array_replace($oldData, $data);
* /
		
// ToDo: Remove bad injected code		

		$row = $this->getTable ();
		foreach ($data as $key => $value)
		{
/*
fill an array, bind and check and store ?
 * /
			$row->id = null;
			$row->name = $key;
			$row->value = $value;
			$row->id = null;

//			$msg .= '    name = ' . $key . ' value = ' . $value . '<br>';

			$row->check ();
			$row->store ();

		}

		return $msg;
	}

/**/

}
