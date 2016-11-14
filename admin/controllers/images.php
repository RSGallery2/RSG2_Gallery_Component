<?php
defined('_JEXEC') or die;

/*
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.image.php ');
}
/**/


// ToDo: // Sanitize the input

jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerImages extends JControllerAdmin
{

	public function getModel($name = 'Image', 
 							 $prefix = 'Rsgallery2Model', 
  							 $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Saves changed manual ordering of galleries
	 *
	 * @throws Exception
	 */
	public function saveOrdering()
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		$msg = "Control:saveOrdering: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {

			try {
				// Model tells if successful
				$model = $this->getModel('images');
				$msg .= $model->saveOrdering();
			}
			catch (RuntimeException $e) {
				$OutTxt = '';
				$OutTxt .= 'Error executing saveOrdering: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}
		}

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=images', $msg, $msgType);
	}

    /**
     * Moves one or more items (images) to another gallery, ordering each item as the last one.
     *
     * @throws Exception
     */
    public function moveImagesTo()
    {
        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg = "Control:moveTo: ";
        $msgType = 'notice';

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {
                // Model tells if successful
                $model = $this->getModel('images');

                $IsMoved = $model->moveImagesTo();
                if ($IsMoved) {
                    $msg .= 'Move of images ... sucessfull';
                }
                else
                {
                    $msg .= 'Move of images ... failed';
                    $msgType = 'error';
                }
            }
            catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing moveTo: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=images', $msg, $msgType);
    }

    /**
     * Saves changed manual ordering of galleries
     *
     * @throws Exception
     */
    public function copyImagesTo()
    {
        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg = "Control:copyTo: ";
        $msgType = 'notice';

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {
                // Model tells if successful
                $model = $this->getModel('images');

                $IsCopied = $model->copyTo();
                if ($IsCopied) {
                    $msg .= 'Copy of images ... sucessfull';
                }
                else
                {
                    $msg .= 'Copy of images ... failed';
                    $msgType = 'error';
                }
            }
            catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing copyTo: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $msg .= '!!! Not implemented yet !!!';

        $this->setRedirect('index.php?option=com_rsgallery2&view=images', $msg, $msgType);
    }

    /**
     * Saves changed manual ordering of galleries
     *
     * @throws Exception
     */
    public function uploadImages()
    {
        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg = "Control:uploadImages: ";
        $msgType = 'notice';

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $this->setRedirect('index.php?option=com_rsgallery2&view=upload', $msg, $msgType);
    }

    /**
     * Saves changed manual ordering of galleries
     *
     * @throws Exception
     */
    public function resetHits()
    {
        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg = "Control:saveOrdering: ";
        $msgType = 'notice';

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {
                // Model tells if successful
                $model = $this->getModel('images');
                $result = $model->resetHits();
                if($result == true)
                {
        			$msg = $msg . JText::_('COM_RSGALLERY2_HITS_RESET_TO_ZERO_SUCCESSFUL');
                }

            }
            catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing resetHits: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=images', $msg, $msgType);
    }



}

