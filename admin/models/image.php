<?php
// No direct access to this file
defined('_JEXEC') or die;

/**
 * 
 */
class Rsgallery2ModelImage extends  JModelAdmin
{
    protected $text_prefix = 'COM_RSGALLERY2';

    /**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param       string $type    The table type to instantiate
	 * @param       string $prefix A prefix for the table class name. Optional.
	 * @param       array  $config Configuration array for model. Optional.
	 * @return      JTable  A database object
	 * @since       2.5
	 */
	public function getTable($type = 'Image', $prefix = 'Rsgallery2Table', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param       array   $data           Data for the form.
	 * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
	 * @return      mixed   A JForm object on success, false on failure
	 * @since       2.5
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		$options = array('control' => 'jform', 'load_data' => $loadData);
		$form = $this->loadForm('com_rsgallery2.images', 'image', 
			array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return      mixed   The data for the form.
	 * @since       2.5
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_rsgallery2.edit.image.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}

    // Transform some data before it is displayed ? Saved ?
    /* extension development 129 bottom */
    protected function prepareTable ($table)
    {
/**
        $table->name = htmlspecialchars_decode ($table->name, ENT_Quotes);

		$table->generateAlias();
/**/

        $date = JFactory::getDate()->toSql();

        $table->name = htmlspecialchars_decode ($table->name, ENT_QUOTES);

        // $table->generateAlias();

        if (empty($table->id))
        {
            // Set the values
            $table->date = $date;

	        /**
            // Set ordering to the last item if not set
            if (empty($table->ordering))
            {
                $db = $this->getDbo();
                $query = $db->getQuery(true)
                    ->select('MAX(ordering)')
                    ->from($db->quoteName('#__rsgallery2_files'));
                $db->setQuery($query);
                $max = $db->loadResult();

                $table->ordering = $max + 1;

                // Set the values
                $table->date = $date;
                $table->user_id = JFactory::getUser()->id;
            }
	        /**/

	        $table->ordering = $table->getNextOrder('gallery_id = ' . (int) $table->gallery_id); // . ' AND state >= 0');
        }
        else
        {
            // Set the values
            $table->date = $date;
            $table->user_id = JFactory::getUser()->id;
        }

        // Increment the content version number.
        // $table->version++;
    }
    /**/

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object  $table A record object.
	 *
	 * @return  array   An array of conditions to add to add to ordering queries.
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'gallery_id = ' . (int) $table->gallery_id;

		return $condition;
	}

	/**
	 * function edit -> checkout .... http://joomla.stackexchange.com/questions/5333/how-is-content-locking-handled-in-custom-components
	 */


	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$input = JFactory::getApplication()->input;

		$task = $input->get('task');

		// Automatic handling of alias for empty fields
		if (in_array($task, array('apply', 'save', 'save2new'))
				// && (!isset($data['id']) || (int) $data['id'] == 0) // <== only for new item
        )
		{
			if (empty ($data['alias']))
			{
				if (JFactory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['name']);
				}
				else
				{
					$data['alias'] = JFilterOutput::stringURLSafe($data['name']);
				}

                // check for existing alias
				$table = $this->getTable();

				//if ($table->load(array('alias' => $data['alias'], 'catid' => $data['catid'])))
                // Warning on existing alias
				if ($table->load(array('alias' => $data['alias'])))
				{
					$msg = JText::_('COM_RSGALLERY2_NAME_CHANGED_AS_WAS_EXISTING');
				}

				/* Create unique alias and ? name ? **/
                // article : list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
                list($name, $alias) = $this->generateNewTitle(null, $data['alias'], $data['name']);
                $data['alias'] = $alias;
                $data['name'] = $name;

                if (isset($msg))
                {
                    JFactory::getApplication()->enqueueMessage($msg, 'warning');
                }

			}
		}

		if (parent::save($data))
		{
			/**
			$new_pk = (int) $this->getState($this->getName() . '.id');

			if ($app->input->get('task') == 'save2copy')
			{
				// Reorder table so that new record has a unique ordering value
				$table->load($new_pk);
				$conditions_array = $this->getReorderConditions($table);
				$conditions = implode(' AND ', $conditions_array);
				$table->reorder($conditions);
			}
			/**/
			return true;
		}

		return false;
	}


	/**
	 * Method to change the title & alias.
	 *
	 * @param   integer  $category_id  The id of the category.
	 * @param   string   $alias        The alias.
	 * @param   string   $title        The title.
	 *
	 * @return	array  Contains the modified title and alias.
	 *
	 * @since	12.2
	 */
	protected function generateNewTitle($dummy, $alias, $title)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('alias' => $alias)))
		{
			$title = JString::increment($title);
			$alias = JString::increment($alias, 'dash');
		}

		return array($title, $alias);
	}

	// ToDo: try to do it more elegant 
	// Called by maintenance -> Consolidate image database
	// load table (? may init all varioables )
	// Change what is necessary and use save see above

    public function CreateImage ($imageName, $galleryId)
    {
        $HasError = 0;

        $OutTxt = 'Model Image: CreateImage "' . $imageName . '""  galleryId' . $galleryId;
        JFactory::getApplication()->enqueueMessage($OutTxt, 'warning');

		/**

			CREATE TABLE IF NOT EXISTS `#__rsgallery2_files` (
		`id` int(9) unsigned NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  `alias` varchar(255) NOT NULL DEFAULT '',
	  `descr` text,
	  `gallery_id` int(9) unsigned NOT NULL default '0',
	  `title` varchar(255) NOT NULL default '',
	  `hits` int(11) unsigned NOT NULL default '0',
	  `date` datetime NOT NULL default '0000-00-00 00:00:00',
	  `rating` int(10) unsigned NOT NULL default '0',
	  `votes` int(10) unsigned NOT NULL default '0',
	  `comments` int(10) unsigned NOT NULL default '0',
	  `published` tinyint(1) NOT NULL default '1',
	  `checked_out` int(11) NOT NULL default '0',
	  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
	  `ordering` int(9) unsigned NOT NULL default '0',
	  `approved` tinyint(1) unsigned NOT NULL default '1',
	  `userid` int(10) NOT NULL,
	  `params` text NOT NULL,
	  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',

		/**
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->insert($db->quoteName('#__my_users'))
			->columns(array('name', 'username'))
			->values(implode(',', array($db->quote('Joe'), $db->quote('jlipman')) ));
		$db->setQuery($query);
		$result = $db->query();
		/**/

		/**
		// Create and populate an object.
		$image_record = new stdClass();
		$image_record->name = 'Joel';
		$image_record->username = 'jlipman';

		// Insert the object into the user table.
		$result = JFactory::getDbo()->insertObject('#__rsgallery2_files', $image_record);


		/**/


		$item = $this->getTable('');

		$item->load(0);

		$item->gallery_id= $galleryId;

		$item->ordering = maxOrdering ($galleryId);;

		$user = JFactory::getUser();
		$userId = $user->id;
		$item->userid  = $userId;
		$item->title   = $imageName;
		$item->content = "";
		$item->state   = 0; // "not published ??? published ?"$state;

		// Lets store it!
		$row             = JTable::getInstance('Message','BestiaTable');
		// $row->bind($data);
		$row->check();


		if (!$item->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$store           = $row->store();
		if($store)
			return $row->id;

			return HasError;
    }


	public function moveImagesTo ()
	{
		$IsMoved = false;

		try {

			$input = JFactory::getApplication()->input;
			$cids = $input->get( 'cid', array(), 'ARRAY');
			JArrayHelper::toInteger($cids);

			$NewGalleryId = $input->get( 'SelectGallery4MoveCopy', -1, 'INT');

			// Destination gallery selected ?
			if ($NewGalleryId > 0) {
				// Source images selected ?
				if (count($cids) > 0) {

					$row = $this->getTable();

					foreach ($cids as $cid) {
						$row->load($cid);

						$row->gallery_id= $NewGalleryId;

						$row->ordering = $this->maxOrdering ($NewGalleryId);;
						if (!$row->store())
						{
							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}

					JFactory::getApplication()->enqueueMessage(JText::_('Move is successful. Please check order of images in destination gallery'), 'notice');
				}
				else
				{
					JFactory::getApplication()->enqueueMessage(JText::_('No valid image(s) selected'), 'warning');
				}
			}
			else
			{
				JFactory::getApplication()->enqueueMessage(JText::_('No valid gallery selected'), 'warning');
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing moveTo: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsMoved;
	}

	/**
	 * copy the item to an other gallery
	 * @param int id of the target gallery
	 * @return rsgItem newly created rsgItem
	 *

	function copy($target_gallery){

	if($target_gallery == null) return null;

	global $database,$rsgConfig;

	$new_item = clone($this);
	$new_item->gallery_id = $target_gallery;

	if( !$database->insertObject('#__rsgallery2_files', $new_item, 'id') ) {
	$this->setError( $database->getErrorMsg() );
	return null;
	}

	if ( $rsgConfig->get('gallery_folders') ){

	// TODO: copy files from source to target gallery folder

	}

	return $this;

	}
	/**/

	public function maxOrdering ($GalleryId)
	{
		$max = 0;

		try {
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->select('MAX(ordering)')
				->from($db->quoteName('#__rsgallery2_files'))
				->where($db->quoteName('gallery_id') . ' = ' . $db->quote($GalleryId));
			$db->setQuery($query);
			$max = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing maxOrdering for GalleryId: "' . $GalleryId . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $max+1;
	}


// ToDo: see above   ? alias ? clone ids php function or ...

	public function copyImagesTo ()
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		$msg = "copyTo: ";
		$msgType = 'notice';

		$IsCopied = false;

		try {

			$input = JFactory::getApplication()->input;

			$input = JFactory::getApplication()->input;
			$cid = $input->get( 'cid', array(), 'ARRAY');
			$NewGalleryId = $input->get( 'SelectGallery4MoveCopy', -1, 'INT');

			// Destination gallery selected ?
			if ($NewGalleryId > 0) {
				// Source images selected ?
				if (count($cid) > 0) {
					// Single ids
					$cids = implode(',', $cid);






				}
			}

			// $msg .= "<br>";
			$msg .= JText::_( 'COM_RSGALLERY2_YYY_done' );
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing copyTo: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsCopied;
	}



}