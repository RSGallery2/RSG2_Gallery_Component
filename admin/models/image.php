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

            // Set ordering to the last item if not set
            if (empty($table->ordering))
            {
                $db = $this->getDbo();
                $query = $db->getQuery(true)
                    ->select('MAX(ordering)')
                    ->from($db->quoteName('#__contact_details'));
                $db->setQuery($query);
                $max = $db->loadResult();

                $table->ordering = $max + 1;
                $table->user_id = JFactory::getUser()->id;
            }
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


	// ToDO: try to do it more elegant

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
    $data['userid']  = $userid;
    $data['title']   = $title;
    $data['content'] = $content;
    $data['state']   = $state;

    // Lets store it!
    $row             = JTable::getInstance('Message','BestiaTable');
    $row->bind($data);
    $row->check();
    $store           = $row->store();
    if($store)                        return $row->id;

        return HasError;
    }


}