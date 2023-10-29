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

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.maintConsolidateDb.php ');
}

jimport('joomla.application.component.controlleradmin');

/**
 * maintenance consolidate image database
 *
 * Checks for all appearances of a images as file or in database
 * On missing database entries or files the user gets a list
 * to choose which part to fix
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerMaintConsolidateDb extends JControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JController
     *
     * @since 4.3.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

    /**
	 * Proxy for getModel
     *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 * 
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 * 
     * @since 4.3.0
     */
	public function getModel($name = 'maintConsolidateDB',
		$prefix = 'rsgallery2Model',
		$config = array())
	{
		$config ['ignore_request'] = true;
		$model                     = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
     * Creates a database entry (row) for all mismatched items
     *
     * @since 4.3.0
	 */
	public function createImageDbItems()
	{
		$msg     = "controller.createImageDbItems: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{
			$model = $this->getModel('maintConsolidateDB');

			$IsAllCreated = false;
			try
			{
				// Retrieve image list with attributes
				$ImageReferences = $model->SelectedImageReferences();

				if (!empty ($ImageReferences))
				{
					$imageDbModel = $this->getModel('image');

					$IsAllCreated = true;
					foreach ($ImageReferences as $ImageReference)
					{
						$IsCreated = $this->createImageDbBaseItem($ImageReference, $imageDbModel);
						if (!$IsCreated)
						{
							$OutTxt = 'Image in DB not created for: ' . $ImageReference->name;
							$app    = JFactory::getApplication();
							$app->enqueueMessage($OutTxt, 'warning');

							$IsAllCreated = false;
						}
					}
					/**
					 * if (!$IsAllCreated) {
					 * $OutTxt = 'Image not created for: ' . $ImageReference->name;
					 * $app = JFactory::getApplication();
					 * $app->enqueueMessage($OutTxt, 'warning');
					 * }
					 * /**/
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing createImageDbItems: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

			if ($IsAllCreated)
			{
				$msg .= "Successful created image references in database";
			}
			else
			{
				$msg .= "Error at creation of image referenes in database";
				$msgType = 'warning';
			}

		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);
	}

    /**
     * Creates a database entry (row) for given item
     *
     * @param ImageReference       $ImageReference
     * @param Rsgallery2ModelImage $imageDbModel
     *
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function createImageDbBaseItem($ImageReference, $imageDbModel)
	{
		$IsImageDbCreated = false;

		try
		{
			// Does not exist in db
			if (!$ImageReference->IsImageInDatabase)
			{
				$IsImageDbCreated = $imageDbModel->createImageDbBaseItem($ImageReference->imageName);
			}
			else
			{
				$OutTxt = 'Database item does already exist for ' . $ImageReference->imageName;
				JFactory::getApplication()->enqueueMessage($OutTxt, 'warning');
			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing createImageDbBaseItem: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsImageDbCreated;
	}


	/**
	 * Creates all missing image files
     * The function tries to find the image with the highest redolution
     * Order: original, display then thumb images (?watermarked?)
	 *
     * @since 4.3.0
	 */
	public function createMissingImages()
	{
		$msg     = "controller.createMissingImages: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintConsolidateDB');

			// $IsAllCreated = false;

			try
			{
				// Retrieve image list with attributes
				$ImageReferences = $model->SelectedImageReferences();

				if (!empty ($ImageReferences))
				{
					$imageFileModel = $this->getModel('imageFile');

					$IsAllCreated = true;
					foreach ($ImageReferences as $ImageReference)
					{
						$IsCreated = $this->createSelectedMissingImage($ImageReference, $imageFileModel);
						if (!$IsCreated)
						{
							$OutTxt = 'Image not created for: ' . $ImageReference->name;
							$app    = JFactory::getApplication();
							$app->enqueueMessage($OutTxt, 'warning');

							$IsAllCreated = false;
						}
					}

					/**
					 * if (!$IsAllCreated) {
					 * $OutTxt = 'Image not created for: ' . $ImageReference->name;
					 * $app = JFactory::getApplication();
					 * $app->enqueueMessage($OutTxt, 'warning');
					 * }
					 * /**/
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing createMissingImages: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

			if ($IsAllCreated)
			{
				$msg .= "Successful created missing image files";
			}
			else
			{
				$msg .= "Error at created missing image files";
				$msgType = 'warning';
			}
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

	}

    /**
     * Creates all missing image files
     * The function tries to find the image with the highest redolution
     * Order: original, display then thumb images (?watermarked?)
     *
     * @since 4.3.0
     */
    public function createWatermarkImages()
    {
        $msg     = "controller.createWatermarkImages: ";
        $msgType = 'notice';

        $canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin)
        {
            //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            $msg = nl2br ($msg);
        }
        else
        {

            // Model tells if successful
            $model = $this->getModel('maintConsolidateDB');

            // $IsAllCreated = false;

            try
            {
                // Retrieve image list with attributes
                $ImageReferences = $model->SelectedImageReferences();

                if (!empty ($ImageReferences))
                {
                    $imageWatermarkModel = $this->getModel('imgWatermark');

                    $IsAllCreated = true;
                    foreach ($ImageReferences as $ImageReference)
                    {
                        $IsCreated = $this->createSelectedWatermarkImage($ImageReference, $imageWatermarkModel);
                        if (!$IsCreated)
                        {
                            $OutTxt = 'Image not created for: ' . $ImageReference->name;
                            $app    = JFactory::getApplication();
                            $app->enqueueMessage($OutTxt, 'warning');

                            $IsAllCreated = false;
                        }
                    }

                }
            }
            catch (RuntimeException $e)
            {
                $OutTxt = '';
                $OutTxt .= 'Error executing createMissingImages: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

            if ($IsAllCreated)
            {
                $msg .= "Successful created missing image files";
            }
            else
            {
                $msg .= "Error at created missing image files";
                $msgType = 'warning';
            }
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

    }

    /**
     * Creates one missing image file
     * The function tries to find the image with the highest redolution
     * Order: original, display then thumb images (?watermarked?)
     *
     * @param ImageReference       $ImageReference
     * @param Rsgallery2ModelImage $imageFileModel
     *
     * @return bool True on success
     *
     * @since 4.3.0
     */
    public function createSelectedMissingImage($ImageReference, $imageFileModel)
    {
        global $rsgConfig;

        $IsImageCreated = false;

        try
        {
            $isOriginalImageFound = $ImageReference->IsOriginalImageFound;

            // Original does not exist in original folder -> copy from other sources

            if (!$isOriginalImageFound)
            {

                $IsAnyImageExists = $ImageReference->IsDisplayImageFound
                    || $ImageReference->IsThumbImageFound
                    || $ImageReference->IsWatermarkedImageFound;

                $imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $ImageReference->imageName;

                if ($IsAnyImageExists)
                {

                    // copy from Display folder
                    if ($ImageReference->IsDisplayImageFound)
                    {
                        //$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $ImageReference->imageName;;
                        $imgSrcPath = JPATH_ROOT . $ImageReference->imagePath;
                        // ToDO: Type may have changed from *.png to *.jpg -> name in db ig in db
                        $isOriginalImageFound = copy($imgSrcPath, $imgDstPath);
                    } // copy from thumbs folder
                    else
                    {
                        if ($ImageReference->IsThumbImageFound)
                        {
                            //$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/' . $ImageReference->imageName;;
                            $imgSrcPath = JPATH_ROOT . $ImageReference->imagePath;
                            $imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $ImageReference->imageName;
                            // ToDO: Type may have changed from *.png to *.jpg -> name in db ig in db
                            $isOriginalImageFound = copy($imgSrcPath, $imgDstPath);
                        } // copy from watermarks folder
                        else
                        {
                            if ($ImageReference->IsWatermarkedImageFound)
                            {
                                //$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_watermarked') . '/' . $ImageReference->imageName;;
                                $imgSrcPath = JPATH_ROOT . $ImageReference->imagePath;
                                $imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $ImageReference->imageName;
                                // ToDO: Type may have changed from *.png to *.jpg -> name in db ig in db
                                $isOriginalImageFound = copy($imgSrcPath, $imgDstPath);
                            }
                        }
                    }

                    // not existing after failed copy
                    if (!$isOriginalImageFound)
                    {
                        $OutTxt = 'Could not copy files  ' . $ImageReference->imageName;
                        $OutTxt .= '<br>$imgSrcPath: ' . $imgSrcPath;
                        $OutTxt .= '<br>$imgDstPath: ' . $imgDstPath;

                        JFactory::getApplication()->enqueueMessage($OutTxt, 'error');
                    }
                }
                else
                {
                    $OutTxt = 'No image file exist for ' . $ImageReference->imageName;
                    JFactory::getApplication()->enqueueMessage($OutTxt, 'warning');
                }
            }

            // When original image exists: Use standard creation of display, thumb
            if ($isOriginalImageFound)
            {
                $IsImageCreated = true;

                // Create display
                if (!$ImageReference->IsDisplayImageFound)
                {
                    $IsImageCreated &= $imageFileModel->createDisplayImageFile($ImageReference->imageName);
                }

                // Create thumb
                if (!$ImageReference->IsThumbImageFound)
                {
                    $IsImageCreated &= $imageFileModel->createThumbImageFile($ImageReference->imageName);
                }

                /** Normally Watermark files are created when visited by user *
                // Create watermark
                if ($rsgConfig->get('imgPath_watermarked')) {
                if(!$ImageReference->IsWatermarkedImageFound) {
                $IsImageCreated &= ! $imageFileModel->createWaterMarkImageFile ($ImageReference->imageName);
                }
                }
                /**/
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing createSelectedMissingImage: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsImageCreated;
    }

    /**
     * Creates one watermark image file
     * The function tries to find the image with the highest redolution
     * Order: original, display then thumb images (?watermarked?)
     *
     * @param ImageReference       $ImageReference
     * @param Rsgallery2ModelImage $imageWatermarkedModel
     *
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function createSelectedWatermarkImage($ImageReference, $imageWatermarkedModel)
	{
		global $rsgConfig;

		$IsImageCreated = false;

		try
		{
			$isOriginalImageFound = $ImageReference->IsOriginalImageFound;
            $isDisplayImageFound = $ImageReference->IsDisplayImageFound;

			// Original does not exist in original folder -> copy from other sources

			if (!$isOriginalImageFound && !$isDisplayImageFound)
			{
					$OutTxt = 'No Original/Display image file exist for ' . $ImageReference->imageName;
					JFactory::getApplication()->enqueueMessage($OutTxt, 'warning');
			}
            else {
                // When original image exists: Use standard creation of display, thumb
                if ($isOriginalImageFound) {
                    /** Normally Watermark files are created when visited by user */
                    // Create watermark
                    $IsImageCreated = $imageWatermarkedModel->createMarkedFromBaseName($ImageReference->imageName, 'Original');
                    /**/
                }
                else {
                    // When original image exists: Use standard creation of display, thumb
                    if ($isDisplayImageFound) {
                        /** Normally Watermark files are created when visited by user */
                        // Create watermark
                        $IsImageCreated = $imageWatermarkedModel->createMarkedFromBaseName($ImageReference->imageName, 'display');
                        /**/
                    }
                }
            }
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing createSelectedMissingImage: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsImageCreated;
	}

	/**
	 * Assignes given gallery to all selected images
     *
     * @since 4.3.0
	 */
	public function assignParentGallery()
	{
		$msg     = "controller.assignGallery: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{
			$input     = JFactory::getApplication()->input;
			$GalleryId = $input->get('ParentGalleryId', 0, 'INT');
			if (empty ($GalleryId))
			{
				$OutTxt = 'Parent gallery not assigned ';
				$app    = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'warning');
			}
			else
			{
				$model = $this->getModel('maintConsolidateDB');

				// $IsAllAssigned = false;

				try
				{
					// Retrieve image list with attributes
					$ImageReferences = $model->SelectedImageReferences();

					if (!empty ($ImageReferences))
					{
						$imageDbModel = $this->getModel('image');

						$IsAssigned = true;
						foreach ($ImageReferences as $ImageReference)
						{
							// Image does not exist in db
							if (!$ImageReference->IsImageInDatabase)
							{
								$OutTxt = 'Gallery not assigned: Database item does not exist for ' . $ImageReference->imageName;
								JFactory::getApplication()->enqueueMessage($OutTxt, 'error');
								continue;
							}

							$ImageId = $imageDbModel->ImageIdFromName($ImageReference->imageName);

							$IsAssigned = $this->assignGallery($ImageId, $imageDbModel, $GalleryId);
							if (!$IsAssigned)
							{
								$OutTxt = 'Parent gallery not assigned for: ' . $ImageReference->name;
								$app    = JFactory::getApplication();
								$app->enqueueMessage($OutTxt, 'warning');

								// $IsAllAssigned = false;
							}
						}

						/**
						 * if (!$IsAllAssigned) {
						 * $OutTxt = 'Image not dreated for: ' . $ImageReference->name;
						 * $app = JFactory::getApplication();
						 * $app->enqueueMessage($OutTxt, 'warning');
						 * }
						 * /**/
					}
				}
				catch (RuntimeException $e)
				{
					$OutTxt = '';
					$OutTxt .= 'Error executing saveOrdering: "' . '<br>';
					$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

					$app = JFactory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');
				}

				/**
				 * if ($IsAllCreated)
				 * {
				 * $msg .= "Successful assignParentGallery";
				 * }
				 * else
				 * {
				 * $msg .= "Error at assignParentGallery";
				 * $msgType = 'warning';
				 * }
				 * /**/
			}
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);
	}


    /**
     * Assigns given gallery to one image
     *
     * @param int                  $ImageId
     * @param Rsgallery2ModelImage $imageDbModel
     * @param int                  $galleryId
     *
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function assignGallery($ImageId, $imageDbModel, $galleryId)
	{
		try
		{
			$IsGalleryAssigned = 0;

			// Does exist in db
			$IsGalleryAssigned = $imageDbModel->assignGalleryId($ImageId, $galleryId);
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing moveTo: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsGalleryAssigned;
	}

	/**
	 * Repairs all missing items (issues) of all images
	 * and assigns gallery if given
     *
     * @since 4.3.0
	 */
	public function repairAllIssuesItems()
	{
		$msg     = "controller.repairItemsAllIssues: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{
			// Model tells if successful
			$model = $this->getModel('maintConsolidateDB');

            $IsAllCreated = false;

			try
			{
				// Retrieve image list with attributes
				$ImageReferences = $model->SelectedImageReferences();

				//--- gallery assignment ? -------------------------------------------
				$input     = JFactory::getApplication()->input;
				$GalleryId = $input->get('ParentGalleryId', 0, 'INT');
				if (empty ($GalleryId))
				{
					// Generate comparable integer value
					$GalleryId = 0;
				}

				if (!empty ($ImageReferences))
				{
					$imageDbModel = $this->getModel('image');
					$imageFileModel = $this->getModel('imageFile');

					$IsAllCreated = true;
					foreach ($ImageReferences as $ImageReference)
					{
						$IsCreated = $this->repairAllIssuesItem($ImageReference, $imageDbModel,
							$imageFileModel, $GalleryId);
						if (!$IsCreated)
						{
							$OutTxt = '"All" issues not repaired for: ' . $ImageReference->name;
							$app    = JFactory::getApplication();
							$app->enqueueMessage($OutTxt, 'warning');

							$IsAllCreated = false;
						}
					}
					/**
					 * if (!$IsAllCreated) {
					 * $OutTxt = 'Image not created for: ' . $ImageReference->name;
					 * $app = JFactory::getApplication();
					 * $app->enqueueMessage($OutTxt, 'warning');
					 * }
					 * /**/
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing repairAllIssuesItems: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

			if ($IsAllCreated)
			{
				$msg .= 'Successful repaired "All issues" in database';
			}
			else
			{
				$msg .= 'Error at repairing "All issues"';
				$msgType = 'warning';
			}

		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);
	}

    /**
     * Repairs all missing items (issues) of one image
     * and assingn gallery if given
     *
     * @param ImageReference       $ImageReference
     * @param Rsgallery2ModelImage $imageDbModel
     * @param Rsgallery2ModelImage $imageFileModel
     * @param int                  $GalleryId
     *
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function repairAllIssuesItem($ImageReference, $imageDbModel, $imageFileModel, $GalleryId)
	{
		try
		{
			$IsItemRepaired = false;

			// Does not exist in db
			$IsImageDbCreated = true;
			if (!$ImageReference->IsImageInDatabase)
			{
				$IsImageDbCreated = $imageDbModel->createImageDbBaseItem($ImageReference->imageName);
				if (!$IsImageDbCreated)
				{
					$msg = "Error at created missing image in db";
					$app = JFactory::getApplication();
					$app->enqueueMessage($msg, 'warning');
				}
			}

			$IsImgageCreated = true;
			$IsOneImgMissing = !$ImageReference->IsOriginalImageFound
				|| !$ImageReference->IsDisplayImageFound
				|| !$ImageReference->IsThumbImageFound;
			if ($IsOneImgMissing)
			{
				$IsImgageCreated = $this->createSelectedMissingImage($ImageReference, $imageFileModel);
				if (!$IsImgageCreated)
				{
					$msg = 'Image not created for: ' . $ImageReference->name;
					$app = JFactory::getApplication();
					$app->enqueueMessage($msg, 'warning');
				}
			}

			// a gallery is selected for assignment
			$IsGalleryAssigned = true;
			if ($GalleryId > 0)
			{
				$ImageId = $imageDbModel->ImageIdFromName($ImageReference->imageName);

				$IsGalleryAssigned = $this->assignGallery($ImageId, $imageDbModel, $GalleryId);
				if (!$IsGalleryAssigned)
				{
					$msg = 'Parent gallery not assigned for: ' . $ImageReference->name;
					$app = JFactory::getApplication();
					$app->enqueueMessage($msg, 'warning');
				}
			}

			$IsItemRepaired = $IsImageDbCreated && $IsImgageCreated && $IsGalleryAssigned;
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing repairAllIssuesItem: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsItemRepaired;
	}

	/**
	 * Deletes all existing items of given images
     *
     * @since 4.3.0
	 */
	public function deleteRowItems()
	{
		$msg     = "controller.deleteRowItems: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintConsolidateDB');

			$IsEveryDeleted = false;

			try
			{
				// Retrieve image list with attributes
				$ImageReferences = $model->SelectedImageReferences();

				if (!empty ($ImageReferences))
				{
					$imageDbModel = $this->getModel('image');
					$imageFileModel = $this->getModel('imageFile');

					$IsEveryDeleted = true;
					foreach ($ImageReferences as $ImageReference)
					{
						$IsDeleted = $this->deleteRowItem($ImageReference, $imageDbModel, $imageFileModel);
						if (!$IsDeleted)
						{
							$OutTxt = 'Image in DB not deleted for: ' . $ImageReference->name;
							$app    = JFactory::getApplication();
							$app->enqueueMessage($OutTxt, 'warning');

							$IsEveryDeleted = false;
						}
					}
					/**
					 * if (!$IsAllCreated) {
					 * $OutTxt = 'Image not created for: ' . $ImageReference->name;
					 * $app = JFactory::getApplication();
					 * $app->enqueueMessage($OutTxt, 'warning');
					 * }
					 * /**/
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing deleteRowItems: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

			if ($IsEveryDeleted)
			{
				$msg .= "Successful deleted row items";
			}
			else
			{
				$msg .= "Error at deleting row items";
				$msgType = 'warning';
			}

		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

	}

    /**
     * Deletes all existing items of given image
     *
     * @param ImageReference       $ImageReference
     * @param Rsgallery2ModelImage $imageDbModel
     * @param Rsgallery2ModelImage $imageFileModel
     *
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function deleteRowItem($ImageReference, $imageDbModel, $imageFileModel)
	{
		try
		{
			$IsRowDeleted = true;

			// Does not exist in db
			if ($ImageReference->IsImageInDatabase)
			{
				$IsImageDbDeleted = $imageDbModel->deleteImageDbItem($ImageReference->imageName);
				if (!$IsImageDbDeleted)
				{
					$msg = "Error at deleting image in db";
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
					$IsRowDeleted = false;
				}
			}

			$IsOneImgExisting = $ImageReference->IsOriginalImageFound
				|| $ImageReference->IsDisplayImageFound
				|| $ImageReference->IsThumbImageFound
				|| $ImageReference->IsWatermarkedImageFound;
			if ($IsOneImgExisting)
			{
				//$IsImgagesDeleted = $this->deleteRowItemImages($ImageReference);
				$IsImgagesDeleted = $imageFileModel->deleteImgItemImages($ImageReference->imageName);

				if (!$IsImgagesDeleted)
				{
					$msg = 'Image not deleted for: "' . $ImageReference->name . '"';
					$app = JFactory::getApplication();
					$app->enqueueMessage($msg, 'warning');
					$IsRowDeleted = false;
				}
			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing deleteRowItem: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsRowDeleted;
	}


}
