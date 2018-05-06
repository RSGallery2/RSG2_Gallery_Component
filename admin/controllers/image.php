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

/**/
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');
	
	// identify active file
	JLog::add('==> ctrl.image.php ');
}

/**
 * @since 4.3.0
 */

class Rsgallery2ControllerImage extends JControllerForm
{


	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function rotate_image_left()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "rotate_left: " . '<br>';

		$direction = 90;
		$this->rotate_image ($direction, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function rotate_image_right()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "rotate_right: " . '<br>';

		$direction = -90;
		$this->rotate_image ($direction, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function rotate_image_180()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "rotate_180: " . '<br>';

		$direction = 180;
		$this->rotate_image ($direction, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function rotate_image($direction = -90, $msg)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msgType = 'notice';

		try
		{
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
				// standard input
				$id = $this->input->get('id', 0, 'int');
				// Input from post
				$input  = JFactory::getApplication()->input;
				// Get the form data
				$formData = new JInput($input->get('jform', '', 'array'));

				$galleryId = $formData->get('gallery_id', -1, 'int');
				$fileName = $formData->get('name', '???', 'string');
				$fileNames = array($fileName);

				$modelFile = $this->getModel('imageFile');
				$ImgCount = $modelFile->rotate_images($fileNames, $galleryId, $direction);

				$msg = ' Successful rotated ' . $ImgCount . ' images';
				// not all images were rotated
				if ($ImgCount < count ($fileNames))
				{
					$msgType = 'warning';
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing rotate_image: ""' . $direction . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		$link = 'index.php?option=com_rsgallery2&view=image&task=image.edit&id=' . $id;
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function flip_image_horizontal()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "flip_image_horizontal: " . '<br>';

		$flipMode = IMG_FLIP_HORIZONTAL; //  IMG_FLIP_VERTICAL,  IMG_FLIP_BOTH
		$this->flip_image($flipMode, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function flip_image_vertical()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "flip_image_vertical: " . '<br>';

		$flipMode = IMG_FLIP_VERTICAL;
		$this->flip_image($flipMode, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function flip_image_both()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "flip_image_both: " . '<br>';

		$flipMode = IMG_FLIP_BOTH;
		$this->flip_image($flipMode, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function flip_image($flipMode, $msg)
	{
		$msgType = 'notice';

		try
		{

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
				// standard input
				$id = $this->input->get('id', 0, 'int');
				// Input from post
				$input  = JFactory::getApplication()->input;
				// Get the form data
				$formData = new JInput($input->get('jform', '', 'array'));

				$galleryId = $formData->get('gallery_id', -1, 'int');
				$fileName = $formData->get('name', '???', 'string');
				$fileNames = array($fileName);

				$modelFile = $this->getModel('imageFile');
				$ImgCount = $modelFile->flip_images($fileNames, $galleryId, $flipMode);

				$msg = ' Successful flipped ' . $ImgCount . ' images';
				// not all images were flipped
				if ($ImgCount < count ($fileNames))
				{
					$msgType = 'warning';
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing flip_image: ""' . $flipMode . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		$link = 'index.php?option=com_rsgallery2&view=image&task=image.edit&id=' . $id;
		$this->setRedirect($link, $msg, $msgType);
	}

}