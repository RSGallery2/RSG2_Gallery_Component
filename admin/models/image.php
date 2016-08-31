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


}