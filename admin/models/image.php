<?php
// No direct access to this file
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Class Rsgallery2ModelImage
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
                $table->userid = JFactory::getUser()->id;
            }
	        /**/

	        $table->ordering = $table->getNextOrder('gallery_id = ' . (int) $table->gallery_id); // . ' AND state >= 0');
        }
        else
        {
            // Set the values
            $table->date = $date;
            $table->userid = JFactory::getUser()->id;
        }

        // Increment the content version number.
        // $table->version++;
    }

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

				// Create unique alias and name
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

	/**
	 * Method to retrive unused image name from database
	 *
	 * @param   string   $name        image name.
	 *
	 * @return	array  Contains the modified title and alias.
	 *
	 * @since	12.2
	 */
	protected function generateNewImageName($name)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('name' => $name)))
		{
			$fileName = pathinfo($name, PATHINFO_FILENAME );
			$ext = pathinfo($name, PATHINFO_EXTENSION);

			// change name
			$name = JString::increment($fileName, 'dash');
			$name = $name . "." . $ext;
		}

		return $name;
	}

	// ToDo: try to do it more elegant 
	// Called by maintenance -> Consolidate image database
	// load table (? may init all varioables )
	// Change what is necessary and use save see above

    public function createImageDbItem($imageName)
    {
        $IsImageDbCreated = 0;

        //$OutTxt = 'Model Image: CreateImage "' . $imageName . '"  ';
        //JFactory::getApplication()->enqueueMessage($OutTxt, 'notice');

		$item = $this->getTable();
		$item->load(0);

		// $item->gallery_id= $galleryId;
		// $item->ordering = maxOrdering ($galleryId);;

		$user = JFactory::getUser();
		$userId = $user->id;
		$item->userid  = $userId;

        //----------------------------------------------------
        // image properties
        //----------------------------------------------------

	    //--- image name -------------------------------------

	    $item->name = $imageName;

	    //--- unique image title and alias -------------------
	    $path_parts = pathinfo($imageName);
        $fileName = $path_parts['filename'];

	    $item->title = $this->generateNewImageName($fileName);
	    $item->alias = $item->title ;
	    $this->alias = JFilterOutput::stringURLSafe($this->alias);

        // Create unique alias and title
        list($title, $alias) = $this->generateNewTitle(null, $item->alias, $item->title);
        $item->title = $title;
        $item->alias = $alias;

	    //--- date -------------------------------------------

	    $date = JFactory::getDate();
	    $item->date = JHtml::_('date', $date, 'Y-m-d H:i:s');

	    //---  -------------------------------------------

	    $item->approved = 0; // dont know why, all images end up with zero ....

	    //----------------------------------------------------
	    // save new object
	    //----------------------------------------------------

	    // Lets store it!
		$item->check();

		if (!$item->store())
		{
            // toDO: collect erorrs and display over enque .... with errr type
            $UsedNamesText = '<br>SrcImage: ' . $fileName . '<br>DstImage: ' . $item->name;
            JFactory::getApplication()->enqueueMessage(JText::_('copied image name could not be inseted in database') . $UsedNamesText, 'warning');

            $IsImageDbCreated = false;

			$this->setError($this->_db->getErrorMsg());
		} else {

            $IsImageDbCreated = true;
        }

        return $IsImageDbCreated;
    }

	/**
	 * moveImagesTo ()
	 *
	 * Move already defined images to different gallery
	 * Both database and image files will be moved
	 * @return bool
	 */
	public function moveImagesTo ()
	{
		$IsMoved = false;

		try {

			$input = JFactory::getApplication()->input;
			$cids = $input->get( 'cid', array(), 'ARRAY');
            ArrayHelper::toInteger($cids);

			$NewGalleryId = $input->get( 'SelectGallery4MoveCopy', -1, 'INT');

			// Destination gallery selected ?
			if ($NewGalleryId > 0) {
				// Source images selected ?
				if (count($cids) > 0) {

					$item = $this->getTable();

                    // All selected images
					foreach ($cids as $cid) {

						$item->load($cid);

                        // Item is already in this gallery:
                        if ($item->gallery_id == $NewGalleryId) {
                            continue;
                        }

						$item->gallery_id= $NewGalleryId;
						$item->ordering = $this->maxOrdering ($NewGalleryId);

                        /**
                        $user = JFactory::getUser();
                        $userId = $user->id;
                        $item->userid  = $userId;
						/***/

						if (!$item->store())
						{
							// toDO: collect erorrs and display over enque .... with errr type

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

	/**
	 * Copy already defined images to different gallery
	 * Both database and image file will be copied
	 * @return bool
	 */
	public function copyImagesTo ()
	{
		global $rsgConfig;

		$IsOneNotCopied = false;
		$IsOneCopied = false;

		try
        {
			$input = JFactory::getApplication()->input;
			$cids = $input->get( 'cid', array(), 'ARRAY');
            ArrayHelper::toInteger($cids);

			$NewGalleryId = $input->get ('SelectGallery4MoveCopy', -1, 'INT');

			// Destination gallery selected ?
			if ($NewGalleryId > 0) {
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
						$oldName   = $item->name;
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
							// toDo: what todo if it fails ?
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
							// toDo: what todo if it fails ?
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
							// toDo: what todo if it fails ?
							$UsedNamesText = '<br>SrcPath: ' . $srcFile . '<br>DstPath: ' . $srcFile;
							JFactory::getApplication()->enqueueMessage(JText::_('Thumb image could not be copied') . $UsedNamesText, 'warning');
						}

						//----------------------------------------------------
						// db: insert new item
						//----------------------------------------------------

						$item->gallery_id = $NewGalleryId;
						$item->ordering   = $this->maxOrdering($NewGalleryId);
						$item->id         = 0; // it is new item

						if (!$item->store())
						{
							// toDO: collect erorrs and display over enque .... with errr type
							$UsedNamesText = '<br>SrcImage: ' . $oldName . '<br>DstImage: ' . $item->name;
							JFactory::getApplication()->enqueueMessage(JText::_('copied image name could not be inseted in database') . $UsedNamesText, 'warning');

							$this->setError($this->_db->getErrorMsg());

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
						if ($IsOneCopied) {
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


	public function ImageIdFromName ($imageName)
    {
        $imageId = 0;

        try {
            $db = $this->getDbo();
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


    public function createDisplayImageFile ($imageName)
    {
        global $rsgConfig;

        $IsImageCreated = false;

        try {

            $imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $imageName;
            $imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $imageName . '.jpg';

            $width = getimagesize($imgSrcPath);
            $height = $width[1];
            $width = $width[0];
            if ($height > $width) {
                $maxSideImage = $height;
            } else {
                $maxSideImage = $width;
            }

            $userWidth = $rsgConfig->get('image_width');

            // if original is wider or higher than display size, create a display image
            if ($maxSideImage > $userWidth) {
                $IsImageCreated = $this->resizeImage($imgSrcPath, $imgDstPath, $userWidth);
            } else {
                $IsImageCreated = $this->resizeImage($imgSrcPath, $imgDstPath, $maxSideImage);
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing createDisplayImageFile for image name: "' . $imageName . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsImageCreated;
    }

    /**
     * generic image resize function
     * @param string $source full path of source image
     * @param string $target full path of target image
     * @param int $targetWidth width of target
     * @return $targetWidth, true if successfull, false if error
     * @todo only writes in JPEG, this should be given as a user option
     */
    static function resizeImage($imgSrcPath, $imgDstPath, $targetWidth){
        global $rsgConfig;

        $IsImageCreated = false;
        $graphicsLib = $rsgConfig->get( 'graphicsLib' );

        try {
            switch ($graphicsLib) {
                case 'gd2':
                    $IsImageCreated = GD2::resizeImage($imgSrcPath, $imgDstPath, $targetWidth);
                    break;
                case 'imagemagick':
                    $IsImageCreated = ImageMagick::resizeImage($imgSrcPath, $imgDstPath, $targetWidth);
                    break;
                case 'netpbm':
                    $IsImageCreated = Netpbm::resizeImage($imgSrcPath, $imgDstPath, $targetWidth);
                    break;
                default:
                    //JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_INVALID_GRAPHICS_LIBRARY') . $rsgConfig->get( 'graphicsLib' ));
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_INVALID_GRAPHICS_LIBRARY') . $rsgConfig->get('graphicsLib'), 'error');
                //return false;
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing resizeImage with library "' . $graphicsLib
                . '" for image source: "' . $imgSrcPath . '"<br>'
                . '" for image desti.: "' . $imgDstPath . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsImageCreated;
    }

    public function createThumbImageFile ($imageName)
    {
        global $rsgConfig;

        $IsImageCreated = false;

        try {
            $imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $imageName;
            $imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/' . $imageName . '.jpg';

            $thumbWidth = $rsgConfig->get('thumb_width');

            // Is thumb style square // Todo: Thumb style -> enum  // Todo: general: Config enums
            if ( $rsgConfig->get('thumb_style') == 1 && $rsgConfig->get('graphicsLib') == 'gd2'){
                // google GD2 crop square
                // https://return-true.com/making-cropping-thumbnails-square-using-php-gd/


                $IsImageCreated = $this->GD2_createSquareThumb ( $imgSrcPath, $imgDstPath, $thumbWidth );
            } else { //

                // google: ImageMagick square thumb // imagemagick crop square

                //http://superuser.com/questions/275476/square-thumbnails-with-imagemagick-convert/

                // a script which allows me to upload an image, square it and then resize it all in one "move"... Even with GD?!? Any suggestions?
                // ImageMagick will do that
                //Code:
                //convert input.jpg -thumbnail x200 -resize "200x<" -resize 50% -gravity center -crop 100x100+0+0 +repage -format jpg -quality 91 square.jpg
                // convert input.jpg -thumbnail \"100x100^\" -gravity center -crop 100x100+0+0 +repage -quality 91 crop.jpg 


                // WideImage library is very elegant and higher level PHP library for image processing.
                // it's at wideimage sourceforge net (can't post links yet)
                // "WideImage, an object-oriented PHP image library"
                // sample
                // wiImage::load('image.png')->resize(50, 30)->saveToFile('new-image.jpg', 30);


                $IsImageCreated = $this->resizeImage($imgSrcPath, $imgDstPath, $thumbWidth);
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing createThumbImageFile for image name: "' . $imageName . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsImageCreated;
    }

    /**
     * createSquareThumb
     * Just a container for try catch
     */
    private function GD2_createSquareThumb ($imgSrcPath, $imgDstPath, $width)
    {
        $IsImageCreated = false;

        try
        {
            $IsImageCreated = GD2::createSquareThumb ($imgSrcPath, $imgDstPath, $width);
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing GD2::createSquareThumb for image name: "' . $imgSrcPath . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsImageCreated;
    }

    public function deleteImageDbItem ($imageName)
    {
        $IsRowDeleted = false;

        try {
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
    public function createWaterMarkImageFile ($imageName)
    {
    global $rsgConfig;

    $IsImageCreated = false;

    try {

    $imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . $imageName;
    $imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . $imageName . '.jpg';

    $width = getimagesize($imgSrcPath);
    $height = $width[1];
    $width = $width[0];
    if ($height > $width) {
    $maxSideImage = $height;
    } else {
    $maxSideImage = $width;
    }

    $userWidth = $rsgConfig->get('image_width');

    // if original is wider or higher than display size, create a display image
    if ($maxSideImage > $userWidth) {
    $IsImageCreated = $this->resizeImage($imgSrcPath, $imgDstPath, $userWidth);
    } else {
    $IsImageCreated = $this->resizeImage($imgSrcPath, $imgDstPath, $maxSideImage);
    }
    }
    catch (RuntimeException $e)
    {
    $OutTxt = '';
    $OutTxt .= 'Error executing createDisplayImageFile for image name: "' . $imageName . '"<br>';
    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

    $app = JFactory::getApplication();
    $app->enqueueMessage($OutTxt, 'error');
    }

    return $IsImageCreated;

    }
    **/






}