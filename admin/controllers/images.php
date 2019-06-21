<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2019 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

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

	/**
	 * Saves changed manual ordering of galleries
	 *
	 * @throws Exception
	 * @since 4.3.0
     */
	public function saveOrdering()
	{
        $msg     = "";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				// Model tells if successful
				$model = $this->getModel('images');
                $IsSaved = $model->saveOrdering();
                if ($IsSaved) {
                    $msg .= JText::_('COM_RSGALLERY2_NEW_ORDERING_SAVED');
                }
                else
                {
                    $msg .= JText::_('Save new ordering failed');
                    $msgType = 'error';
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
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=images', $msg, $msgType);
	}

	/**
	 * @param string $name
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
	 * @since 4.3.0
     */
	public function getModel($name = 'Image',
		$prefix = 'Rsgallery2Model',
		$config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Moves one or more items (images) to another gallery, ordering each item as the last one.
	 *
	 * @throws Exception
	 * @since 4.3.0
     */
	public function moveImagesTo()
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		$msg     = "Control:moveTo: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				// Model tells if successful
				$model = $this->getModel('image');

				$IsMoved = $model->moveImagesTo();
				if ($IsMoved)
				{
					$msg .= 'Move of images ... sucessfull';
				}
				else
				{
					$msg .= 'Move of images ... failed';
					$msgType = 'error';
				}
			}
			catch (RuntimeException $e)
			{
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
	 * @since 4.3.0
     */
	public function copyImagesTo()
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		$msg     = "Control:copyTo: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				// Model tells if successful
				$model = $this->getModel('image');

				$IsCopied = $model->copyImagesTo();
				if ($IsCopied)
				{
					$msg .= 'Copy of images ... sucessfull';
				}
				else
				{
					$msg .= 'Copy of images ... failed';
					$msgType = 'error';
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing copyTo: "' . '<br>';
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
	 * @since 4.3.0
     */
	public function uploadImages()
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		$msg     = "Control:uploadImages: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$this->setRedirect('index.php?option=com_rsgallery2&view=upload', $msg, $msgType);
	}

	/**
	 * Saves changed manual ordering of galleries
	 *
	 * @throws Exception
	 * @since 4.3.0
     */
	public function resetHits()
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		$msg     = "Control:saveOrdering: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{

			try
			{
				// Model tells if successful
				$model  = $this->getModel('images');
				$result = $model->resetHits();
				if ($result == true)
				{
					$msg = $msg . JText::_('COM_RSGALLERY2_HITS_RESET_TO_ZERO_SUCCESSFUL');
				}

			}
			catch (RuntimeException $e)
			{
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

