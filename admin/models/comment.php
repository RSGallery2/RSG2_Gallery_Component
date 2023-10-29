<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;
use Joomla\String\StringHelper;

/**
 * Class Rsgallery2ModelComment
 *
 * @since 4.3.0
 */
class Rsgallery2ModelComment extends JModelAdmin
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
	public function getTable($type = 'Comment', $prefix = 'Rsgallery2Table', $config = array())
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
		$form    = $this->loadForm('com_rsgallery2.comment', 'comment', $options);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return array|bool|JObject|mixed
	 * @since       4.3.0
	 * @throws Exception
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_rsgallery2.edit.comment.data', array());
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
		$table->subject = htmlspecialchars_decode($table->subject, ENT_QUOTES);
		$table->comment = htmlspecialchars_decode($table->comment, ENT_QUOTES);
	}
	/**/
}
