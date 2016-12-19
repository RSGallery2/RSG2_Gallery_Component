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
	 * @param   array  $config  An optional associative array of configuration settings.
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
	public function createImageDbItems () {
		$msg = "controller.createImageDbItems: ";
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

            $IsEveryCreated = false;

			try
            {
                // Retrieve image list with attributes
                $ImageReferences = $model->SelectedImageReferences();

                if (!empty ($ImageReferences)) {
                    $imageModel = $this->getModel('image');

                    $IsEveryCreated = true;
                    foreach ($ImageReferences as $ImageReference)
                    {
                        $IsCreated = $this->createImageDbItem ($ImageReference, $imageModel);
                        if (!$IsCreated) {
                            $OutTxt = 'Image not created for: ' . $ImageReference->name;
                            $app = JFactory::getApplication();
                            $app->enqueueMessage($OutTxt, 'warning');

                            $IsEveryCreated = false;
                        }
                    }

                    if (!$IsEveryCreated) {
                        $OutTxt = 'Image not dreated for: ' . $ImageReference->name;
                        $app = JFactory::getApplication();
                        $app->enqueueMessage($OutTxt, 'warning');
                    }
                }
            }
            catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing saveOrdering: "' . '<br>';
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

    public function createImageDbItem ($ImageReference, $imageModel)
    {
        $IsImageDbCreated = 0;

        //       $msgType = 'notice';

        try
        {
            // Does not exist in db
            if(!$ImageReference->IsImageInDatabase)
            {
                $IsImageDbCreated = $imageModel->createImageDbItem($ImageReference->imageName);
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
	public function createMissingImages () {
		$msg = "controller.createMissingImages: ";
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
			$msg .= $model->createSelectedMissingImages();


		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&amp;view=maintConsolidateDB
// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=maintConsolidateDB
	}

	/**
	 * images to ...
	 *
	 */
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
	 */
	public function assignGalleries () {
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


            
          // See createImageDbItems with single calls top model image ...
            // ParentGalleryId

			$model = $this->getModel('maintConsolidateDB');
			$msg .= $model->assignGalleries();  // todo: remove: use images model
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintConsolidateDB', $msg, $msgType);

// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&amp;view=maintConsolidateDB
// http://127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=maintConsolidateDB
	}


	/**
	 * images to ...
	 *
	 */
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






}
