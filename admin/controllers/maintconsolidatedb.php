<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
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
     * Creates a database entry (row) for all mismatched items
     *
     * @since 4.3.0
	 */
	public function createImageDbItems()
	{
		$msg     = "controller.createImageDbItems: ";
		$msgType = 'notice';

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
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
					$imageModel = $this->getModel('image');

					$IsAllCreated = true;
					foreach ($ImageReferences as $ImageReference)
					{
						$IsCreated = $this->createImageDbBaseItem($ImageReference, $imageModel);
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
     *
     * @param string $name
     * @param string $prefix
     * @param array $config
     * @return bool|rsgallery2ModelMaintConsolidateDB
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
     * Creates a database entry (row) for given item
     *
     * @param ImageReference $ImageReference
     * @param Rsgallery2ModelImage $imageModel
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function createImageDbBaseItem($ImageReference, $imageModel)
	{
		$IsImageDbCreated = false;

		try
		{
			// Does not exist in db
			if (!$ImageReference->IsImageInDatabase)
			{
				$IsImageDbCreated = $imageModel->createImageDbBaseItem($ImageReference->imageName);
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

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
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
					$imageModel = $this->getModel('image');

					$IsAllCreated = true;
					foreach ($ImageReferences as $ImageReference)
					{
						$IsCreated = $this->createSelectedMissingImage($ImageReference, $imageModel);
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
     * Creates one missing image file
     * The function tries to find the image with the highest redolution
     * Order: original, display then thumb images (?watermarked?)
     *
     * @param ImageReference $ImageReference
     * @param Rsgallery2ModelImage $imageModel
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function createSelectedMissingImage($ImageReference, $imageModel)
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
					$IsImageCreated &= $imageModel->createDisplayImageFile($ImageReference->imageName);
				}

				// Create thumb
				if (!$ImageReference->IsThumbImageFound)
				{
					$IsImageCreated &= $imageModel->createThumbImageFile($ImageReference->imageName);
				}

				/** Watermark files are created when visited by user
				 * // Create watermark
				 * if(!$ImageReference->IsWatermarkedImageFound) {
				 * if ($rsgConfig->watermark) {
				 * $IsImageCreated &= ! $imageModel->createWaterMarkImageFile ($ImageReference->imageName);
				 * }
				 * }
				 * /**/
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

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
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
						$imageModel = $this->getModel('image');

						$IsAssigned = true;
						foreach ($ImageReferences as $ImageReference)
						{
							// Does not exist in db
							if (!$ImageReference->IsImageInDatabase)
							{
								$OutTxt = 'Database item does not exist for ' . $ImageReference->imageName;
								JFactory::getApplication()->enqueueMessage($OutTxt, 'warning');
							}

							$ImageId = $imageModel->ImageIdFromName($ImageReference->imageName);

							$IsAssigned = $this->assignGallery($ImageId, $imageModel, $GalleryId);
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
     * Assignes given gallery to one image
     *
     * @param int $ImageId
     * @param Rsgallery2ModelImage $imageModel
     * @param int $galleryId
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function assignGallery($ImageId, $imageModel, $galleryId)
	{
		try
		{
			$IsGalleryAssigned = 0;

			// Does exist in db
			$IsGalleryAssigned = $imageModel->assignGalleryId($ImageId, $galleryId);
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

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
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
					$imageModel = $this->getModel('image');

					$IsAllCreated = true;
					foreach ($ImageReferences as $ImageReference)
					{
						$IsCreated = $this->repairAllIssuesItem($ImageReference, $imageModel, $GalleryId);
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
     * @param ImageReference $ImageReference
     * @param Rsgallery2ModelImage $imageModel
     * @param int $galleryId
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function repairAllIssuesItem($ImageReference, $imageModel, $GalleryId)
	{
		try
		{
			$IsItemRepaired = false;

			// Does not exist in db
			$IsImageDbCreated = true;
			if (!$ImageReference->IsImageInDatabase)
			{
				$IsImageDbCreated = $imageModel->createImageDbBaseItem($ImageReference->imageName);
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
				$IsImgageCreated = $this->createSelectedMissingImage($ImageReference, $imageModel);
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
				$ImageId = $imageModel->ImageIdFromName($ImageReference->imageName);

				$IsGalleryAssigned = $this->assignGallery($ImageId, $imageModel, $GalleryId);
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

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
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
					$imageModel = $this->getModel('image');

					$IsEveryDeleted = true;
					foreach ($ImageReferences as $ImageReference)
					{
						$IsDeleted = $this->deleteRowItem($ImageReference, $imageModel);
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
     * @param ImageReference $ImageReference
     * @param Rsgallery2ModelImage $imageModel
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function deleteRowItem($ImageReference, $imageModel)
	{
		try
		{
			$IsRowDeleted = true;

			// Does not exist in db
			if ($ImageReference->IsImageInDatabase)
			{
				$IsImageDbDeleted = $imageModel->deleteImageDbItem($ImageReference->imageName);
				if (!$IsImageDbDeleted)
				{
					$msg = "Error at deleting image in db";
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
					$IsRowDeleted = false;
				}
			}

			$IsOneImgExisting = $ImageReference->IsOriginalImageFound
				|| $ImageReference->IsDisplayImageFound
				|| $ImageReference->IsThumbImageFound;
			if ($IsOneImgExisting)
			{
				$IsImgagesDeleted = $this->deleteRowItemImages($ImageReference);
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

    /**
     * Delete all existing image files on one image
     *
     * @param ImageReference $ImageReference
     * @return bool True on success
     *
     * @since 4.3.0
     */
	public function deleteRowItemImages($ImageReference)
	{
		global $rsgConfig;

		$IsImagesDeleted = false;

		try
		{
			$IsImagesDeleted = true;

			// Delete existing images
			if ($ImageReference->IsOriginalImageFound)
			{
				$imgPath        = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $ImageReference->imageName;
				$IsImageDeleted = $this->DeleteImage($imgPath);
				if (!$IsImageDeleted)
				{
					$IsImagesDeleted = false;
				}
			}

			if ($ImageReference->IsDisplayImageFound)
			{
				$imgPath        = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $ImageReference->imageName . '.jpg';
				$IsImageDeleted = $this->DeleteImage($imgPath);
				if (!$IsImageDeleted)
				{
					$IsImagesDeleted = false;
				}
			}

			if ($ImageReference->IsThumbImageFound)
			{
				$imgPath = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/' . $ImageReference->imageName . '.jpg';;
				$IsImageDeleted = $this->DeleteImage($imgPath);
				if (!$IsImageDeleted)
				{
					$IsImagesDeleted = false;
				}
			}

			if ($ImageReference->IsWatermarkedImageFound)
			{
				$imgPath        = JPATH_ROOT . $rsgConfig->get('imgPath_watermarked') . $ImageReference->imageName;
				$IsImageDeleted = $this->DeleteImage($imgPath);
				if (!$IsImageDeleted)
				{
					$IsImagesDeleted = false;
				}
			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing deleteRowItemImages: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsImagesDeleted;
	}

    /**
     * Deletes given  file
     * @param string $filename
     * @return bool True on success
     *
     * @since 4.3.0
     */
	private function DeleteImage($filename)
	{
		$IsImageDeleted = true;

		if (file_exists($filename))
		{
			$IsImageDeleted = unlink($filename);
		}
		else
		{
			$IsImageDeleted = false;
		}

		return $IsImageDeleted;
	}

}
