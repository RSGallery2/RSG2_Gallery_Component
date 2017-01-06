<?php
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

class Rsgallery2ControllerMaintConsolidateDb extends JControllerAdmin
{

    /**
     * Constructor.
     *
     * @param   array $config An optional associative array of configuration settings.
     *
     * @see     JController
     * @since
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getModel($name = 'maintConsolidateDB',
                             $prefix = 'rsgallery2Model',
                             $config = array())
    {
        $config ['ignore_request'] = true;
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    /**
     * images to ...
     *
     */
    public function createImageDbItems()
    {
        $msg = "controller.createImageDbItems: ";
        $msgType = 'notice';

        $canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            // Model tells if successful
            $model = $this->getModel('maintConsolidateDB');

            // $IsEveryCreated = false;

            try {
                // Retrieve image list with attributes
                $ImageReferences = $model->SelectedImageReferences();

                if (!empty ($ImageReferences)) {
                    $imageModel = $this->getModel('image');

                    $IsEveryCreated = true;
                    foreach ($ImageReferences as $ImageReference) {
                        $IsCreated = $this->createImageDbItem($ImageReference, $imageModel);
                        if (!$IsCreated) {
                            $OutTxt = 'Image in DB not created for: ' . $ImageReference->name;
                            $app = JFactory::getApplication();
                            $app->enqueueMessage($OutTxt, 'warning');

                            $IsEveryCreated = false;
                        }
                    }
                    /**
                     * if (!$IsEveryCreated) {
                     * $OutTxt = 'Image not created for: ' . $ImageReference->name;
                     * $app = JFactory::getApplication();
                     * $app->enqueueMessage($OutTxt, 'warning');
                     * }
                     * /**/
                }
            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createImageDbItems: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

            if ($IsEveryCreated) {
                $msg .= "Successful created image references in database";
            } else {
                $msg .= "Error at created image referenes in database";
                $msgType = 'warning';
            }

        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&amp;view=maintConsolidateDB
// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=maintConsolidateDB
    }

    public function createImageDbItem($ImageReference, $imageModel)
    {
        $IsImageDbCreated = 0;

        try {
            // Does not exist in db
            if (!$ImageReference->IsImageInDatabase) {
                $IsImageDbCreated = $imageModel->createImageDbItem($ImageReference->imageName);
            } else {
                $OutTxt = 'Database item does already exist for ' . $ImageReference->imageName;
                JFactory::getApplication()->enqueueMessage($OutTxt, 'warning');
            }

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing createImageDbItem: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsImageDbCreated;
    }

    /**
     * images to ...
     *
     */
    public function createMissingImages()
    {
        $msg = "controller.createMissingImages: ";
        $msgType = 'notice';

        $canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {


            // Model tells if successful
            $model = $this->getModel('maintConsolidateDB');

            // $IsEveryCreated = false;

            try {
                // Retrieve image list with attributes
                $ImageReferences = $model->SelectedImageReferences();

                if (!empty ($ImageReferences)) {
                    $imageModel = $this->getModel('image');

                    $IsEveryCreated = true;
                    foreach ($ImageReferences as $ImageReference) {
                        $IsCreated = $this->createSelectedMissingImage($ImageReference, $imageModel);
                        if (!$IsCreated) {
                            $OutTxt = 'Image not created for: ' . $ImageReference->name;
                            $app = JFactory::getApplication();
                            $app->enqueueMessage($OutTxt, 'warning');

                            $IsEveryCreated = false;
                        }
                    }
                    /**
                     * if (!$IsEveryCreated) {
                     * $OutTxt = 'Image not created for: ' . $ImageReference->name;
                     * $app = JFactory::getApplication();
                     * $app->enqueueMessage($OutTxt, 'warning');
                     * }
                     * /**/
                }
            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createMissingImages: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

            if ($IsEveryCreated) {
                $msg .= "Successful created missing image files";
            } else {
                $msg .= "Error at created missing image files";
                $msgType = 'warning';
            }
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

    }


    public function createSelectedMissingImage($ImageReference, $imageModel)
    {
        global $rsgConfig;

        $IsImageCreated = false;

        /*
        $this->IsOriginalImageFound = false;
        $this->IsDisplayImageFound = false;
        $this->IsThumbImageFound = false;
        $this->IsWatermarkedImageFound = false;

        $files_display  = $this->getFilenameArray($rsgConfig->get('imgPath_display'));
        $files_original = $this->getFilenameArray($rsgConfig->get('imgPath_original'));
	    $files_thumb    = $this->getFilenameArray($rsgConfig->get('imgPath_thumb'));

	    // Watermarked: Start with empty array
	    $files_watermarked = array ();
	    if($this->UseWatermarked)
        /**/

        try {
            $isOriginalImageFound = $ImageReference->IsOriginalImageFound;

            // Original does not exist in original folder -> copy from other sources

            if (!isOriginalImageFound) {
                $imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . $ImageReference->imageName;

                // copy from Display folder
                if ($ImageReference->IsDisplayImageFound) {
                    $imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . $ImageReference->imageName;;

                    $isOriginalImageFound = copy($imgSrcPath, $imgDstPath);
                } // copy from thumbs folder
                else if ($ImageReference->IsThumbImageFound) {
                    $imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . $ImageReference->imageName;;

                    $isOriginalImageFound = copy($imgSrcPath, $imgDstPath);
                } // copy from watermarks folder
                else if ($ImageReference->IsWatermarkedImageFound) {
                    $imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_watermarked') . $ImageReference->imageName;;

                    $isOriginalImageFound = copy($imgSrcPath, $imgDstPath);
                } else {
                    $OutTxt = 'No image file exist for ' . $ImageReference->imageName;
                    JFactory::getApplication()->enqueueMessage($OutTxt, 'warning');
                }
            }

            // When original image exists: Use standard creation of display, thumb
            if ($isOriginalImageFound) {
                $IsImageCreated = true;

                // Create display
                if (!$ImageReference->IsDisplayImageFound) {
                    $IsImageCreated &= !$imageModel->createDisplayImageFile($ImageReference->imageName);
                }

                // Create thumb
                if (!$ImageReference->IsThumbImageFound) {
                    $IsImageCreated &= !$imageModel->createThumbImageFile($ImageReference->imageName);
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
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing createSelectedMissingImage: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsImageCreated;
    }


    /**
     * images to ...
     *
     */
    public function assignParentGallery()
    {
        $msg = "controller.assignGallery: ";
        $msgType = 'notice';

        $canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            $input = JFactory::getApplication()->input;
            $GalleryId = $input->get('ParentGalleryId', 0, 'INT');
            if (empty ($GalleryId)) {
                $OutTxt = 'Parent gallery not assigned ';
                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'warning');
            } else {
                $model = $this->getModel('maintConsolidateDB');

                // $IsAllAssigned = false;

                try {
                    // Retrieve image list with attributes
                    $ImageReferences = $model->SelectedImageReferences();

                    if (!empty ($ImageReferences)) {
                        $imageModel = $this->getModel('image');

                        $IsAssigned = true;
                        foreach ($ImageReferences as $ImageReference) {
                            // Does not exist in db
                            if (!$ImageReference->IsImageInDatabase) {
                                $OutTxt = 'Database item does not exist for ' . $ImageReference->imageName;
                                JFactory::getApplication()->enqueueMessage($OutTxt, 'warning');
                            }

                            $ImageId = $imageModel->ImageIdFromName($ImageReference->imageName);

                            $IsAssigned = $this->assignGallery($ImageId, $imageModel, $GalleryId);
                            if (!$IsAssigned) {
                                $OutTxt = 'Parent gallery not assigned for: ' . $ImageReference->name;
                                $app = JFactory::getApplication();
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
                } catch (RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error executing saveOrdering: "' . '<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = JFactory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                /**
                 * if ($IsEveryCreated)
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

// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&amp;view=maintConsolidateDB
// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=maintConsolidateDB
    }

//-----------------------------------------------------------------------------------------

    public function assignGallery($ImageId, $imageModel, $galleryId)
    {
        try {
            $IsGalleryAssigned = 0;

            // Does exist in db
            $IsGalleryAssigned = $imageModel->assignGalleryId($ImageId, $galleryId);
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing moveTo: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsGalleryAssigned;
    }


    /**
     * images to ...
     *
     */
    public function repairAllIssuesItems()
    {
        $msg = "controller.repairItemsAllIssues: ";
        $msgType = 'notice';

        $canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            // Model tells if successful
            $model = $this->getModel('maintConsolidateDB');

            // $IsEveryCreated = false;

            try {
                // Retrieve image list with attributes
                $ImageReferences = $model->SelectedImageReferences();

                //--- gallery assignment ? -------------------------------------------
                $input = JFactory::getApplication()->input;
                $GalleryId = $input->get('ParentGalleryId', 0, 'INT');
                if (empty ($GalleryId)) {
                    // Generate comparable integer value
                    $GalleryId = 0;
                }

                if (!empty ($ImageReferences)) {
                    $imageModel = $this->getModel('image');

                    $IsEveryCreated = true;
                    foreach ($ImageReferences as $ImageReference) {
                        $IsCreated = $this->repairAllIssuesItem($ImageReference, $imageModel, $GalleryId);
                        if (!$IsCreated) {
                            $OutTxt = '"All" issues not repaired for: ' . $ImageReference->name;
                            $app = JFactory::getApplication();
                            $app->enqueueMessage($OutTxt, 'warning');

                            $IsEveryCreated = false;
                        }
                    }
                    /**
                     * if (!$IsEveryCreated) {
                     * $OutTxt = 'Image not created for: ' . $ImageReference->name;
                     * $app = JFactory::getApplication();
                     * $app->enqueueMessage($OutTxt, 'warning');
                     * }
                     * /**/
                }
            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing repairAllIssuesItems: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

            if ($IsEveryCreated) {
                $msg .= 'Successful repaired "All issues" in database';
            } else {
                $msg .= 'Error at repairing "All issues"';
                $msgType = 'warning';
            }

        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&amp;view=maintConsolidateDB
// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=maintConsolidateDB
    }

    public function repairAllIssuesItem($ImageReference, $imageModel, $GalleryId)
    {
        try {
            $IsItemRepaired = 0;

            // Does not exist in db
            $IsImageDbCreated = true;
            if (!$ImageReference->IsImageInDatabase) {
                $IsImageDbCreated = $imageModel->createImageDbItem($ImageReference->imageName);
                if (!$IsImageDbCreated) {
                    $msg = "Error at created missing image in db";
                    $app = JFactory::getApplication();
                    $app->enqueueMessage($msg, 'warning');
                }
            }

            $IsImgageCreated = true;
            $IsOneImgMissing = !$ImageReference->IsOriginalImageFound
                || !$ImageReference->IsDisplayImageFound
                || !$ImageReference->IsThumbImageFound;
            if ($IsOneImgMissing) {
                $IsImgageCreated = $this->createSelectedMissingImage($ImageReference, $imageModel);
                if (!$IsImgageCreated) {
                    $msg = 'Image not created for: ' . $ImageReference->name;
                    $app = JFactory::getApplication();
                    $app->enqueueMessage($msg, 'warning');
                }
            }

            // a gallery is selected for assignment
            if ($GalleryId > 0) {
                $ImageId = $imageModel->ImageIdFromName($ImageReference->imageName);

                $IsAssigned = $this->assignGallery($ImageId, $imageModel, $GalleryId);
                if (!$IsAssigned) {
                    $msg = 'Parent gallery not assigned for: ' . $ImageReference->name;
                    $app = JFactory::getApplication();
                    $app->enqueueMessage($msg, 'warning');
                }
            }

            $IsItemRepaired = !$IsImageDbCreated || !$IsImgageCreated;
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing repairAllIssuesItem: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsItemRepaired;
    }


    /**
     * images to ...
     *
     */
    public function deleteRowItems()
    {
        $msg = "controller.deleteRowItems: ";
        $msgType = 'notice';

        $canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            // Model tells if successful
            $model = $this->getModel('maintConsolidateDB');

            $IsEveryCreated = false;

            try {
                // Retrieve image list with attributes
                $ImageReferences = $model->SelectedImageReferences();

                if (!empty ($ImageReferences)) {
                    $imageModel = $this->getModel('image');

                    $IsEveryCreated = true;
                    foreach ($ImageReferences as $ImageReference) {
                        $IsCreated = $this->deleteRowItem($ImageReference, $imageModel);
                        if (!$IsCreated) {
                            $OutTxt = 'Image in DB not created for: ' . $ImageReference->name;
                            $app = JFactory::getApplication();
                            $app->enqueueMessage($OutTxt, 'warning');

                            $IsEveryCreated = false;
                        }
                    }
                    /**
                     * if (!$IsEveryCreated) {
                     * $OutTxt = 'Image not created for: ' . $ImageReference->name;
                     * $app = JFactory::getApplication();
                     * $app->enqueueMessage($OutTxt, 'warning');
                     * }
                     * /**/
                }
            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing deleteRowItems: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

            if ($IsEveryCreated) {
                $msg .= "Successful row items";
            } else {
                $msg .= "Error at deleting row items";
                $msgType = 'warning';
            }

        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&amp;view=maintConsolidateDB
// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=maintConsolidateDB
    }


    public function deleteRowItem($ImageReference, $imageModel)
    {
        try {
            $IsRowDeleted = 0;

            // Does not exist in db
            $IsImageDbDeleted = true;
            if (!$ImageReference->IsImageInDatabase) {
                $IsImageDbDeleted = $imageModel->deleteImageDbItem($ImageReference->imageName);
                if (!$IsImageDbDeleted) {
                    $msg = "Error at deleting image in db";
                    JFactory::getApplication()->enqueueMessage($msg, 'warning');
                }
            }

            $IsImgagesDeleted = true;
            $IsOneImgExisting = $ImageReference->IsOriginalImageFound
                || $ImageReference->IsDisplayImageFound
                || $ImageReference->IsThumbImageFound;
            if ($IsOneImgExisting) {
                $IsImgagesDeleted = $this->deleteRowItemImages($ImageReference);
                if (!$IsImgagesDeleted) {
                    $msg = 'Image not deleted for: "' . $ImageReference->name . '"';
                    $app = JFactory::getApplication();
                    $app->enqueueMessage($msg, 'warning');
                }
            }

            $IsRowDeleted = !$IsImageDbDeleted || !$IsImgagesDeleted;
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing deleteRowItem: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsRowDeleted;
    }


    public function deleteRowItemImages($ImageReference)
    {
        global $rsgConfig;

        $IsImagesDeleted = false;

        try {
            $IsImagesDeleted = true;

            // Delete existing images
            if ($ImageReference->IsOriginalImageFound) {
                $imgPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . $ImageReference->imageName;
                $IsImageDeleted = DeleteImage($imgPath);
                if (!$IsImageDeleted) {
                    $IsImagesDeleted = false;
                }
            }

            if ($ImageReference->IsDisplayImageFound) {
                $imgPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . $ImageReference->imageName;
                $IsImageDeleted = DeleteImage($imgPath);
                if (!$IsImageDeleted) {
                    $IsImagesDeleted = false;
                }
            }

            if ($ImageReference->IsThumbImageFound) {
                $imgPath = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . $ImageReference->imageName;
                $IsImageDeleted = DeleteImage($imgPath);
                if (!$IsImageDeleted) {
                    $IsImagesDeleted = false;
                }
            }

            if ($ImageReference->IsThumbImageFound) {
                $imgPath = JPATH_ROOT . $rsgConfig->get('imgPath_watermarked') . $ImageReference->imageName;
                $IsImageDeleted = DeleteImage($imgPath);
                if (!$IsImageDeleted) {
                    $IsImagesDeleted = false;
                }
            }

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing deleteRowItemImages: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsImagesDeleted;
    }


    private function DeleteImage($filename)
    {
        $IsImageDeleted = true;

        if (file_exists($filename)) {
            $IsImageDeleted = unlink($filename);
        } else {
            $IsImageDeleted = false;
        }

        return $IsImageDeleted;
    }







    /**
     * images to ...
     *
     *
    public function deleteAllImages () {
        $msg = "controller.deleteAllImages: ";
        $msgType = 'notice';

        $canAdmin	= JFactory::getUser()->authorise('core.manage',	'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            // Model tells if successful
            $model = $this->getModel('maintConsolidateDB');
            $msg .= $model->deleteAllImages();
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&amp;view=maintConsolidateDB
// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=maintConsolidateDB
    }

    /**
     * images to ...
     *
     *
    public function deleteReferences () {
    $msg = "controller.assignGallery: ";
    $msgType = 'notice';

    $canAdmin	= JFactory::getUser()->authorise('core.manage',	'com_rsgallery2');
    if (!$canAdmin) {
    //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
    $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
    $msgType = 'warning';
    // replace newlines with html line breaks.
    str_replace('\n', '<br>', $msg);
    } else {
    // Model tells if successful
    $model = $this->getModel('maintConsolidateDB');
    $msg .= $model->deleteReferences();
    }

    $this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

    // http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&amp;view=maintConsolidateDB
    // http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=maintConsolidateDB
    }
    /**/



}
