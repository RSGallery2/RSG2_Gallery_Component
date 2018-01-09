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

use Joomla\Utilities\ArrayHelper;
use Joomla\String\StringHelper;

/**
 * Single image model
 * Db functions
 *
 * @since 4.3.0
 */
class Rsgallery2ModelImage extends JModelAdmin
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
	public function getTable($type = 'Image', $prefix = 'Rsgallery2Table', $config = array())
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
		$form    = $this->loadForm('com_rsgallery2.images', 'image', $options);

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
		$data = $app->getUserState('com_rsgallery2.edit.image.data', array());
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
                $table->userid = JFactory::getUser()->id;
            }
	        /**/

			$table->ordering = $table->getNextOrder('gallery_id = ' . (int) $table->gallery_id); // . ' AND state >= 0');

            // Set the values
            $table->date = $date;
            $table->uid  = JFactory::getUser()->id;
		}
		else
		{
			// Set the values
			$table->date   = $date;
			$table->userid = JFactory::getUser()->id;
		}

		// Increment the content version number.
		// $table->version++;
	}

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
		$condition[] = 'gallery_id = ' . (int) $table->gallery_id;

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

				// Create unique alias and name
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
			return true;
		}

		return false;
	}

	/**
	 * Method to change the title & alias.
	 *
	 * @param   integer $category_id The id of the category.
	 * @param   string  $alias       The alias.
	 * @param   string  $title       The title.
	 *
	 * @return    array  Contains the modified title and alias.
	 *
	 * @since    12.2
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

	/**
	 * Method to retrive unused image name from database
	 *
	 * @param   string $name image name.
	 *
	 * @return    array  Contains the modified title and alias.
     *
     * @since 4.3.0
	 */
	public function generateNewImageName($name, $galleryId)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('name' => $name)))
		{
			$fileName = pathinfo($name, PATHINFO_FILENAME);
			$ext      = pathinfo($name, PATHINFO_EXTENSION);

			// change name
			$name = StringHelper::increment($fileName, 'dash');
			$name = $name . "." . $ext;
		}

		return $name;
	}

	/**
	 * Create a new item in database for image
	 *
	 * @param $imageName
	 *
	 * @return bool true if successful
	 *
	 * @since 4.3.0
	 */
	public function createImageDbBaseItem($imageName)
	{
		$IsImageDbCreated = false;

		//--- Create new item -------------------

		$item = $this->getTable();
		$item->load(0);

		//----------------------------------------------------
		// image properties
		//----------------------------------------------------

		//--- image name -------------------------------------

		$item->name = $imageName; // ToDo: check for unique or remove unique. It may already be there

		//--- unique image title and alias -------------------
		$path_parts = pathinfo($imageName);
		$fileName   = $path_parts['filename'];

		$item->title = $this->generateNewImageName($fileName);
		$item->alias = $item->title;
		$this->alias = JFilterOutput::stringURLSafe($this->alias);

		// Create unique alias and title
		list($title, $alias) = $this->generateNewTitle(null, $item->alias, $item->title);
		$item->title = $title;
		$item->title = $title;
		$item->alias = $alias;

		//--- date -------------------------------------------

		$date       = JFactory::getDate();
		$item->date = JHtml::_('date', $date, 'Y-m-d H:i:s');

		//--- user id -------------------------------------------

		$user         = JFactory::getUser();
		$userId       = $user->id;
		$item->userid = $userId;

		//---  -------------------------------------------

		$item->approved = 0; // dont know why, all images end up with zero ....

		//----------------------------------------------------
		// save new object
		//----------------------------------------------------

		// Lets store it!
		$item->check();

		if (!$item->store())
		{
			// ToDo: collect erorrs and display over enque .... with errr type
			$UsedNamesText = '<br>SrcImage: ' . $fileName . '<br>DstImage: ' . $item->name;
			JFactory::getApplication()->enqueueMessage(JText::_('copied image name could not be inseted in database') . $UsedNamesText, 'warning');

			// $IsImageDbCreated = false;

			$this->setError($this->_db->getErrorMsg());
		}
		else
		{

			$IsImageDbCreated = true;
		}

		return $IsImageDbCreated;
	}

	/**
	 * Create a new item in database for image
	 *
	 * @param $imageName
	 *
	 * @return bool true if successful
	 *
	 * @since 4.3.0
	 */
	public function createImageDbItem($imageName, $title='', $galleryId=0, $description='')
	{
		$ImageId = -1;

		//--- Create new item -------------------

		$item = $this->getTable();
		$item->load(0);

		//----------------------------------------------------
		// image properties
		//----------------------------------------------------

		//--- image name -------------------------------------

		$item->name = $imageName; // ToDo: check for unique or remove unique. It may already be there

		//--- unique image title and alias -------------------
		$path_parts = pathinfo($imageName);
		$fileName   = $path_parts['filename'];

		//--- title, alias -------------------------------------------

		if(! empty($title)) {
			$item->title = $title;
		}
		else
		{
			$item->title = $this->generateNewImageName($fileName);
		}
		$item->alias = $item->title;
		$item->alias = JFilterOutput::stringURLSafe($item->alias);

		// Create unique alias and title
		list($title, $alias) = $this->generateNewTitle(null, $item->alias, $item->title);
		$item->title = $title;
		$item->alias = $alias;

		//--- date -------------------------------------------

		$date       = JFactory::getDate();
		$item->date = JHtml::_('date', $date, 'Y-m-d H:i:s');

		//--- gallery -------------------------------------------

		$item->gallery_id = $galleryId;

		//--- description ---------------------------------------

		$item->descr = $description;

		//--- user id -------------------------------------------

		$user         = JFactory::getUser();
		$userId       = $user->id;
		$item->userid = $userId;

		//--- ordering -------------------------------------------

		$item->ordering = $item->getNextOrder('gallery_id = ' . (int) $item->gallery_id); // . ' AND state >= 0');

		//---  -------------------------------------------

		$item->approved = 0; // don't know why, all images end up with zero ....

		//----------------------------------------------------
		// save new object
		//----------------------------------------------------

		// Lets store it!
		$item->check();

		if (!$item->store())
		{
			// ToDo: collect erorrs and display over enque .... with errr type
			$UsedNamesText = '<br>SrcImage: ' . $fileName . '<br>DstImage: ' . $item->name;
			JFactory::getApplication()->enqueueMessage(JText::_('copied image name could not be inserted in database') . $UsedNamesText, 'warning');

			$this->setError($this->_db->getErrorMsg());
		}
		else
		{

            $ImageId= $item->id;
		}

		return $ImageId;
	}

	/**
	 * Move already defined images to a different gallery
	 * in database and care for new ordering
	 *
	 * @return bool true if successful
     *
     * @since 4.3.0
	 */
	public function moveImagesTo()
	{
		$IsMoved = false;

		try
		{

			$input = JFactory::getApplication()->input;
			$cids  = $input->get('cid', array(), 'ARRAY');
			ArrayHelper::toInteger($cids);

			$NewGalleryId = $input->get('SelectGallery4MoveCopy', -1, 'INT');

			// Destination gallery selected ?
			if ($NewGalleryId > 0)
			{
				// Source images selected ?
				if (count($cids) > 0)
				{

					$item = $this->getTable();

					// All selected images
					foreach ($cids as $cid)
					{

						$item->load($cid);

						// Item is already in this gallery:
						if ($item->gallery_id == $NewGalleryId)
						{
							continue;
						}

						$item->gallery_id = $NewGalleryId;
						$item->ordering   = $this->nextOrdering($NewGalleryId);

						if (!$item->store())
						{
							// ToDo: collect erorrs and display over enque .... with errr type

							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}

					// Success
					$IsMoved = true;

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
			$OutTxt .= 'Error executing moveImagesTo: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsMoved;
	}

    /**
     * Return the next ordering for a new image in selected galllery
     * (Max known ordering +1)
     *
     * @param $GalleryId
     *
     * @return int next ordering, 1 on error
     *
     * @since 4.3.0
     */
	private function nextOrdering($GalleryId)
	{
        $max = 0;

		try
		{
			$db    = $this->getDbo();
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
			$OutTxt .= 'Error executing nextOrdering for GalleryId: "' . $GalleryId . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		$next = $max +1;

		return $next;
	}

	/**
	 * Copy already defined images to a different gallery
	 * Both database and image file will be copied
	 *
	 * @return bool true if successful
     *
     * @since 4.3.0
	 */
	public function copyImagesTo()
	{
		global $rsgConfig;

		$IsOneNotCopied = false;
		$IsOneCopied    = false;

		try
		{
			$input = JFactory::getApplication()->input;
			$cids  = $input->get('cid', array(), 'ARRAY');
			ArrayHelper::toInteger($cids);

			$NewGalleryId = $input->get('SelectGallery4MoveCopy', -1, 'INT');

			// Destination gallery selected ?
			if ($NewGalleryId > 0)
			{
				// Source images selected ?
				if (count($cids) > 0)
				{
					$item = $this->getTable();

					// All selected images
					foreach ($cids as $cid)
					{
						$item->load($cid);

						/* Item is already in this gallery:
						if ($item->gallery_id == $NewGalleryId)
						{
							JFactory::getApplication()->enqueueMessage(
								JText::_('Display image could not be copied. It is already assigned to the destination gallery') . $row->title, 'warning');
							$IsOneNotCopied = true;

							continue;
						}
						*/

						//----------------------------------------------------
						// db: new image name
						//----------------------------------------------------

						// Create unique image file name
						$oldName    = $item->name;
						$item->name = $this->generateNewImageName($oldName);

						// Create unique alias and title
						list($title, $alias) = $this->generateNewTitle(null, $item->alias, $item->name);
						$item->title = $title;
						$item->alias = $alias;

						//----------------------------------------------------
						// Copy files
						//----------------------------------------------------

						// copy original
						$fullPath_original = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/';
						$srcFile           = $fullPath_original . $oldName;
						$dstFile           = $fullPath_original . $item->name;
						if (!copy($srcFile, $dstFile))
						{
							// ToDo: what ToDo if it fails ?
							$UsedNamesText = '<br>SrcPath: ' . $srcFile . '<br>DstPath: ' . $srcFile;
							JFactory::getApplication()->enqueueMessage(JText::_('Original image could not be copied') . $UsedNamesText, 'warning');
						}
						else
						{
							;
						}

						// copy display
						// must function !!!
						$fullPath_display = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/';
						$srcFile          = $fullPath_display . $oldName . '.jpg';
						$dstFile          = $fullPath_display . $item->name . '.jpg';
						if (!copy($srcFile, $dstFile))
						{
							// ToDo: what ToDo if it fails ?
							$UsedNamesText = '<br>SrcPath: ' . $srcFile . '<br>DstPath: ' . $srcFile;
							JFactory::getApplication()->enqueueMessage(JText::_('Display image could not be copied') . $UsedNamesText, 'error');

							$IsOneNotCopied = true;
						}
						else
						{
							$IsOneCopied = true;
						}

						// copy thumb
						$fullPath_thumb = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/';
						$srcFile        = $fullPath_thumb . $oldName . '.jpg';
						$dstFile        = $fullPath_thumb . $item->name . '.jpg';
						if (!copy($srcFile, $dstFile))
						{
							// ToDo: what ToDo if it fails ?
							$UsedNamesText = '<br>SrcPath: ' . $srcFile . '<br>DstPath: ' . $srcFile;
							JFactory::getApplication()->enqueueMessage(JText::_('Thumb image could not be copied') . $UsedNamesText, 'warning');
						}

						//----------------------------------------------------
						// db: insert new item
						//----------------------------------------------------

						$item->gallery_id = $NewGalleryId;
						$item->ordering   = $this->nextOrdering($NewGalleryId);
						$item->id         = 0; // it is new item

						if (!$item->store())
						{
							$UsedNamesText = '<br>SrcImage: ' . $oldName . '<br>DstImage: ' . $item->name;
							JFactory::getApplication()->enqueueMessage(JText::_('copied image name could not be inseted in database') . $UsedNamesText, 'error');

							// return false;
							$IsOneNotCopied = false;
						}
					}

					if (!$IsOneNotCopied)
					{
						JFactory::getApplication()->enqueueMessage(JText::_('Copy is successful. Please check order of images in destination gallery'), 'notice');
					}
					else
					{
						if ($IsOneCopied)
						{
							JFactory::getApplication()->enqueueMessage(JText::_('Some images were copied. Please check order of images in destination gallery'), 'notice');
						}
					}
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
			$OutTxt .= 'Error executing copyImagesTo: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsOneCopied;
	}

    /**
     * Assign given image (by id) a gallery (by id)
     *
     * @param int $imageId
     * @param int $galleryId
     *
     * @return bool true if successful
     *
     * @since 4.3.0
     */
	public function assignGalleryId($imageId, $galleryId)
	{
		$IsGalleryAssigned = false;

		try
		{
			$item = $this->getTable();
			$item->load($imageId);

			$item->gallery_id = $galleryId;

			if ($item->store())
			{
				$IsGalleryAssigned = true;
			}
			else
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing assignGalleryId: "' . $imageId . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Catched Error executing assignGalleryId: "' . $imageId . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsGalleryAssigned;
	}

    /**
     * Retrieve image id by image name
     *
     * @param $imageName
     *
     * @return int image id
     *
     * @since version
     */
	public function ImageIdFromName($imageName)
	{
		$imageId = 0;

		try
		{
			$db    = $this->getDbo();
			$query = $db->getQuery(true)
				->select('id')
				->from($db->quoteName('#__rsgallery2_files'))
				->where($db->quoteName('name') . ' = ' . $db->quote($imageName));
			$db->setQuery($query);

			$imageId = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing ImageIdFromName for image name: "' . $imageName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $imageId;
	}

	/**
	 * Delete database entry (item) for given image name
	 *
	 * @param $imageName
	 *
	 * @return bool true if successful
	 *
	 * @since 4.3.0
	 */
	public function deleteImageDbItem($imageName)
	{
		$IsRowDeleted = false;

		try
		{
			$db = $this->getDbo();

			$query = $db->getQuery(true)
				->delete($db->quoteName('#__rsgallery2_files'))
				->where($db->quoteName('name') . ' = ' . $db->quote($imageName));

			$db->setQuery($query);
			$IsRowDeleted = $db->execute();
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing deleteImageDbItem for image name: "' . $imageName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsRowDeleted;
	}


    /**
     * Save user input from image parameter annotation in database
     *
     * @return bool
     *
     * @since 4.3.2
     */
    public function save_imageProperties ($imageProperties)
    {
        $IsSaved = false;

        try {
            $id = $imageProperties->cid;
            // ToDo: On changed title change alias
            $title = $imageProperties->title;
            $description = $imageProperties->description;

            //--- Db create image object -------------------

            if ($id > 0) {
                $item = $this->getTable();
                $isImgFound = $item->load($id);

                // Image found
                if (!empty ($isImgFound)) {
                    $item->title = $title;
                    $item->descr = $description;

                    //----------------------------------------------------
                    // save changed object
                    //----------------------------------------------------

                    // Lets store it!
                    $item->check();
                    $IsSaved = $item->store();
                    if (!$IsSaved) {
                        $OutTxt = '';
                        $OutTxt .= 'Model image: Error executing store in save_imageProperties: for image id: "' . $id . '"<br>';
                        //$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                        $app = JFactory::getApplication();
                        $app->enqueueMessage($OutTxt, 'error');
                    }
                }
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Model image: Error executing save_imageProperties: for image id: "' . $id . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsSaved;
    }
}