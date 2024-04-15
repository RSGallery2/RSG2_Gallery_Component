<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2024 RSGallery2 Team
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
	JLog::add('==> ctrl.mainttemplates.php ');
}


/**
 * @package     ${NAMESPACE}
 *
 * @since       4.4.2
 */
class Rsgallery2ControllerMaintTemplates extends JControllerForm
{
	/**
	 * changeTemplate
	 * On change of the template selection this function is called
	 * to restart the page with the data of this selection
	 * The name of the selected template is written to the link
	 *
	 * No checks as the result ing URL canbe typed by anyone
	 *
	 * @since 4.4.2 4.4.2
	 */
	public function changeTemplate()
	{
		$msg     = "";
		$msgType = "";

		$input = JFactory::getApplication()->input;
		$link  = 'index.php?option=com_rsgallery2&view=mainttemplates';

		// Tell the maintenance which slide show to use
		$templateName = $input->get('maintain_template', "", 'STRING');
		if (!empty ($templateName))
		{
			$link .= '&maintain_template=' . $templateName;
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
		$msg     = 'Save template config parameter ';
		$msgType = 'notice';
		$IsSaved = false;
		$isErrFound = false;

		$input = JFactory::getApplication()->input;

		//--- Tell the maintenance form the template to use -----------------------------

		// base link
		$link = 'index.php?option=com_rsgallery2&view=mainttemplates';
		// template addition
		$templateName = $input->get('maintain_template', "", 'STRING');
		if (!empty ($templateName))
		{
			$link .= '&maintain_template=' . $templateName;
		}

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//--- Access check ---------------------------------------

		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
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
			$fileBasePath = JPATH_COMPONENT_SITE . '/templates/' . $templateName;

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
		$msg         = 'Save template config file ';
		$msgType     = 'notice';
		$IsSaved     = false;
		$isErrFound = false;
		$configParam = "";

		$input = JFactory::getApplication()->input;

		//--- Tell the maintenance form the template to use -----------------------------

		// base link
		$link = 'index.php?option=com_rsgallery2&view=mainttemplates';
		// template addition
		$templateName = $input->get('maintain_template', "", 'STRING');
		if (!empty ($templateName))
		{
			$link .= '&maintain_template=' . $templateName;
		}

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//--- Access check ---------------------------------------

		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{
			//--- fetch user data -----------------------------

			$targetTemplate = $input->get('usedTemplate', "", 'STRING');
			$paramsIniText   = $input->get('params_ini_' . $targetTemplate, "", 'STRING');

			// check input
			if (empty ($targetTemplate))
			{
				$isErrFound = true;
				$msg        = $msg . ': Empty template name';
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

				$fileBasePath = JPATH_COMPONENT_SITE . '/templates/' . $targetTemplate;

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
		$msg         = 'Save template user css file ';
		$msgType     = 'notice';
		$IsSaved     = false;
		$isErrFound = false;
		$configParam = "";

		$input = JFactory::getApplication()->input;

		//--- Tell the maintenance form the template to use -----------------------------

		// base link
		$link = 'index.php?option=com_rsgallery2&view=mainttemplates';
		// template addition
		$templateName = $input->get('maintain_template', "", 'STRING');
		if (!empty ($templateName))
		{
			$link .= '&maintain_template=' . $templateName;
		}

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//--- Access check ---------------------------------------

		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{
			//--- fetch user data -----------------------------

			$targetTemplate = $input->get('usedTemplate', "", 'STRING');
			$userCssText   = $input->get('user_css_' . $targetTemplate, "", 'STRING');

			// check input
			if (empty ($targetTemplate))
			{
				$isErrFound = true;
				$msg        = $msg . ': Empty template name';
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

				$fileBasePath = JPATH_COMPONENT_SITE . '/templates/' . $targetTemplate . '/css';

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


