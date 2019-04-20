<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
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
	JLog::add('==> ctrl.maintslideshows.php ');
}


/**
 * @package     ${NAMESPACE}
 *
 * @since       4.4.2
 */
class Rsgallery2ControllerMaintSlideshows extends JControllerForm
{
	/**
	 * changeSlideshow
	 * On change of the slideshow selection this function is called
	 * to restart the page with the data of this selection
	 * The name of the selected slideshow is written to the link
	 *
	 * No checks as the result ing URL canbe typed by anyone
	 *
	 * @since 4.4.2 4.4.2
	 */
	public function changeSlideshow()
	{
		$msg     = "";
		$msgType = "";

		$input = JFactory::getApplication()->input;
		$link  = 'index.php?option=com_rsgallery2&view=maintslideshows';

		// Tell the maintenance which slide show to use
		$slideshowName = $input->get('maintain_slideshow', "", 'STRING');
		if (!empty ($slideshowName))
		{
			$link .= '&maintain_slideshow=' . $slideshowName;
		}

		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * saveConfigParameter
	 * User input in 'params' will be written to file.
	 * To clean up and secure the input it is read into
	 * the registry format
	 *
	 * @since 4.4.2
	 * @throws Exception
	 */
	public function saveConfigParameter()
	{
		$msg     = 'Save slideshow config parameter ';
		$msgType = 'notice';
		$IsSaved = false;
		$isErrFound = false;

		$input = JFactory::getApplication()->input;

		//--- Tell the maintenance form the slideshow to use -----------------------------

		// base link
		$link = 'index.php?option=com_rsgallery2&view=maintslideshows';
		// slideshow addition
		$slideshowName = $input->get('maintain_slideshow', "", 'STRING');
		if (!empty ($slideshowName))
		{
			$link .= '&maintain_slideshow=' . $slideshowName;
		}

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//--- Access check ---------------------------------------

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
			//--- form parameter -----------------------------

			$formParams = $input->get('params', [], 'ARRAY');

			// Sanitize input
			$params = new JRegistry;
			$params->loadArray($formParams);

			//--- write to file -----------------------------

			// folder
			$fileBasePath = JPATH_COMPONENT_SITE . '/templates/' . $slideshowName;

			// Does folder exist ?
			if (!is_dir($fileBasePath))
			{
				$isErrFound = true;
				$msg        = $msg . ": folder does not exist: " . $fileBasePath;
				$msgType    = 'error';
			}


			if (!$isErrFound)
			{
				$parameterFileName = 'params.ini';
				$pathFileName = $fileBasePath . '/' . $parameterFileName;

				$configParam = $params->toString('INI');

				// Write parameter to file
				$fileBytes    = file_put_contents($pathFileName, $configParam . PHP_EOL, LOCK_EX);

				$IsSaved = !empty ($fileBytes);
			}
		}

		if ($IsSaved)
		{
			$msg .= ' successful';
		}
		else
		{
			$msg .= ' failed';
			JFactory::getApplication()->enqueueMessage($msg, 'warning');
		}

		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * saveParamsFile
	 * User input in textarea will be written to file.
	 * To clean up and secure the input it is read into
	 * the registry format
	 *
	 * @since 4.4.2
	 * @throws Exception
	 */
	public function saveParamsFile()
	{
		$msg         = 'Save slideshow config file ';
		$msgType     = 'notice';
		$IsSaved     = false;
		$isErrFound = false;
		$configParam = "";

		$input = JFactory::getApplication()->input;

		//--- Tell the maintenance form the slideshow to use -----------------------------

		// base link
		$link = 'index.php?option=com_rsgallery2&view=maintslideshows';
		// slideshow addition
		$slideshowName = $input->get('maintain_slideshow', "", 'STRING');
		if (!empty ($slideshowName))
		{
			$link .= '&maintain_slideshow=' . $slideshowName;
		}

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//--- Access check ---------------------------------------

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
			//--- fetch user data -----------------------------

			$targetSlideshow = $input->get('usedSlideshow', "", 'STRING');
			$paramsIniText   = $input->get('params_ini_' . $targetSlideshow, "", 'STRING');

			// check input
			if (empty ($targetSlideshow))
			{
				$isErrFound = true;
				$msg        = $msg . ': Empty slideshow name';
				$msgType    = 'error';
			}

			//--- sanitize edited text -----------------------------

			if (!$isErrFound)
			{
				// convert to registry
				$params = new JRegistry;
				$params->loadString($paramsIniText, 'INI');
				$configParam = $params->toString('INI');
			}

			//--- Check folder of file -----------------------------

			if (!$isErrFound)
			{
				//--- folder name -----------------------------

				$fileBasePath = JPATH_COMPONENT_SITE . '/templates/' . $targetSlideshow;

				// Does folder exist ?
				if (!is_dir($fileBasePath))
				{
					$isErrFound = true;
					$msg        = $msg . ": folder does not exist: " . $fileBasePath;
					$msgType    = 'error';
				}
			}

			//--- write to file -----------------------------

			if (!$isErrFound)
			{
				//--- file name -----------------------------
				$parameterFileName = 'params.ini';

				$pathFileName = $fileBasePath . '/' . $parameterFileName;
				$fileBytes    = file_put_contents($pathFileName, $configParam . PHP_EOL, LOCK_EX);

				//  tells if successful
				// $IsSaved = $fileBytes != false;
				$IsSaved = !empty ($fileBytes);
			}
		}

		if ($IsSaved)
		{
			$msg .= ' successful';
		}
		else
		{
			$msg .= ' failed';
			JFactory::getApplication()->enqueueMessage($msg, 'warning');
		}

		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * saveUserCssFile
	 * User input in textarea will be written to file.
	 * To clean up and secure the input it is read into
	 * the registry format
	 *
	 * @since 4.4.2
	 * @throws Exception
	 */
	public function saveUserCssFile()
	{
		$msg         = 'Save slideshow user css file ';
		$msgType     = 'notice';
		$IsSaved     = false;
		$isErrFound = false;
		$configParam = "";

		$input = JFactory::getApplication()->input;

		//--- Tell the maintenance form the slideshow to use -----------------------------

		// base link
		$link = 'index.php?option=com_rsgallery2&view=maintslideshows';
		// slideshow addition
		$slideshowName = $input->get('maintain_slideshow', "", 'STRING');
		if (!empty ($slideshowName))
		{
			$link .= '&maintain_slideshow=' . $slideshowName;
		}

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//--- Access check ---------------------------------------

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
			//--- fetch user data -----------------------------

			$targetSlideshow = $input->get('usedSlideshow', "", 'STRING');
			$userCssText   = $input->get('user_css_' . $targetSlideshow, "", 'STRING');

			// check input
			if (empty ($targetSlideshow))
			{
				$isErrFound = true;
				$msg        = $msg . ': Empty slideshow name';
				$msgType    = 'error';
			}

			//--- sanitize edited text -----------------------------

			if (!$isErrFound)
			{
				/**
				// convert to registry
				$params = new JRegistry;
				$params->loadString($userCssText, 'INI');
				$configParam = $params->toString('INI');
				/**/
			}

			//--- Check folder of file -----------------------------

			if (!$isErrFound)
			{
				//--- folder name -----------------------------

				$fileBasePath = JPATH_COMPONENT_SITE . '/templates/' . $targetSlideshow . '/css';

				// Does folder exist ?
				if (!is_dir($fileBasePath))
				{
					$isErrFound = true;
					$msg        = $msg . ": folder does not exist: " . $fileBasePath;
					$msgType    = 'error';
				}
			}

			//--- write to file -----------------------------

			if (!$isErrFound)
			{
				//--- file name -----------------------------
				$parameterFileName = 'user.css';

				$pathFileName = $fileBasePath . '/' . $parameterFileName;
				$fileBytes    = file_put_contents($pathFileName, trim($userCssText) . PHP_EOL, LOCK_EX);

				//  tells if successful
				// $IsSaved = $fileBytes != false;
				$IsSaved = !empty ($fileBytes);
			}
		}

		if ($IsSaved)
		{
			$msg .= ' successful';
		}
		else
		{
			$msg .= ' failed';
			JFactory::getApplication()->enqueueMessage($msg, 'warning');
		}

		$this->setRedirect($link, $msg, $msgType);
	}

} // class


