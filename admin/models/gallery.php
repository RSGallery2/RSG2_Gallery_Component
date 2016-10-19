<?php
// No direct access to this file
defined('_JEXEC') or die;

/**
 * 
 */
class Rsgallery2ModelGallery extends  JModelAdmin
{
	/**
	 * Returns a reference to a Table object, always creating it.
	 *
	 * @param       type    The table type to instantiate
	 * @param       string  A prefix for the table class name. Optional.
	 * @param       array   Configuration array for model. Optional.
	 * @return      JTable  A database object
	 * @since       2.5
	 */
	public function getTable($type = 'Gallery', $prefix = 'Rsgallery2Table', $config = array()) 
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
		$form = $this->loadForm('com_rsgallery2.gallery', 'gallery', 
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
		$data = $app->getUserState('com_rsgallery2.edit.gallery.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}

    // Transform some data before it is displayed
    /* extension development 129 bottom  */
    protected function prepareTable ($table)
    {
		$date = JFactory::getDate()->toSql();

		$table->name = htmlspecialchars_decode ($table->name, ENT_QUOTES);

		// $table->generateAlias();

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			/**
			if (empty($table->ordering))
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__rsgallery2_galleries'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;

                // Set the values
                $table->date = $date;
                $table->uid = JFactory::getUser()->id;
            }
			 * /**/
			$table->ordering = $table->getNextOrder('parent = ' . (int) $table->parent); // . ' AND state >= 0');
		}
		else
		{
			// Set the values
			$table->date = $date;
			$table->uid = JFactory::getUser()->id;
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
		$condition[] = 'parent = ' . (int) $table->parent;

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
					$msg = JText::_('COM_CONTENT_SAVE_WARNING');
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

}
