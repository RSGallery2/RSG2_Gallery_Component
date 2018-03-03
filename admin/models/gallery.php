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

//use Joomla\Utilities\ArrayHelper;
use Joomla\String\StringHelper;

/**
 * Single GALLERY model
 *
 * @since 4.3.0
 */
class Rsgallery2ModelGallery extends JModelAdmin
{
    protected $text_prefix = 'COM_RSGALLERY2';

    /**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param       string $type   The table type to instantiate
	 * @param       string $prefix A prefix for the table class name. Optional.
	 * @param       array  $config Configuration array for model. Optional.
	 *
	 * @return      JTable  A database object
	 * @since       4.3.0
	 */
	public function getTable($type = 'Gallery', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param       array   $data     Data for the form.
	 * @param       boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return      mixed   A JForm object on success, false on failure
	 * @since       4.3.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$options = array('control' => 'jform', 'load_data' => $loadData);
		$form    = $this->loadForm('com_rsgallery2.gallery', 'gallery', $options);

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
	 * @since       4.3.0
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_rsgallery2.edit.gallery.data', array());
		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

    /**
     * Transform some data before it is displayed ? Saved ?
     * extension development 129 bottom
     *
     * @param JTable $table
     *
     * @since 4.3.0
     */
	protected function prepareTable($table)
	{
		$date = JFactory::getDate()->toSql();
		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);

		if (empty($table->id))
		{
			// Set ordering to 1 increment the others
			if (empty($table->ordering))
			{
				/**
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__rsgallery2_galleries'));
				$db->setQuery($query);
				$max = $db->loadResult();

                // Set the values
                $table->date = $date;
                $table->uid = JFactory::getUser()->id;
                /**/

				//$table->ordering = 0;
			}

			//$table->ordering = 0; // $table->getNextOrder('parent = ' . (int) $table->parent); // . ' AND state >= 0');
			//$table->reorder();

			// Set the values
			$table->date = $date;
			$table->uid  = JFactory::getUser()->id;
		}
		else
		{
			// Set the values
			$table->date = $date;
			$table->uid  = JFactory::getUser()->id;
		}

		// Increment the content version number.
		// $table->version++;
	}
	/**/

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object $table A record object.
	 *
	 * @return  array   An array of conditions to add to add to ordering queries.
     *
     * @since 4.3.0
	 */
	protected function getReorderConditions($table)
	{
		$condition   = array();
		$condition[] = 'parent = ' . (int) $table->parent;

		return $condition;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array $data The form data.
	 *
	 * @return  boolean  True on success.
     *
     * @since 4.3.0
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
				$data['name']  = $name;

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

			// reorder on new element
			// (when ordering is not defined or zero)
			$table = $this->getTable();
			if (empty($table->ordering))
			{
				$table->reorder();
			}

			return true;
		}

		return false;
	}

	/**
	 * Method to change the title & alias.
	 *
	 * @param   integer $dummy  not used.
	 * @param   string  $alias  The alias.
	 * @param   string  $title  The title.
	 *
	 * @return    array  Contains the modified title and alias.
     *
     * @since 4.3.0
	 */
	protected function generateNewTitle($dummy, $alias, $title)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('alias' => $alias)))
		{
            $title = StringHelper::increment($title);
            $alias = StringHelper::increment($alias, 'dash');
		}

		return array($title, $alias);
	}

}
