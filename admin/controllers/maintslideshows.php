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
/**/

class Rsgallery2ControllerMaintSlideshows extends JControllerForm
{
	/**
	 * On change of the slideshow selection this function is called to restart
	 * the page with the data of this selection
	 *
     * @since version
	 */
	public function changeSlideshow()
	{
        $msg     = 'changeSlideshow';
		$msgType = 'notice';
        $IsSaved = false;

		$input = JFactory::getApplication()->input;
		$link = 'index.php?option=com_rsgallery2&view=maintslideshows';
		// Tell the maintenance which slidshow to use
		$slideshowName = $input->get('maintain_slideshow', "", 'STRING');
		/* ??? urlencode, rawurlencode() htmlentities() oder htmlspecialchars(). /**/
		if (!empty ($slideshowName))
		{
			$link .= '&maintain_slideshow=' . $slideshowName;
		}

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
			$msg = "";
			$msgType = "";
		}

		$this->setRedirect($link, $msg, $msgType);
	}




	public function saveConfigParameter ()
	{
		$msg     = 'Save slideshow config parameter ';
		$msgType = 'notice';
		$IsSaved = false;

		$input = JFactory::getApplication()->input;

		//--- prepare link with slideshow name -----------------------------

		$link = 'index.php?option=com_rsgallery2&view=maintslideshows';
		// Tell the maintenance which slideshow to use
		$slideshowName = $input->get('maintain_slideshow', "", 'STRING');
		/* ??? urlencode, rawurlencode() htmlentities() oder htmlspecialchars(). /**/
		if (!empty ($slideshowName))
		{
			$link .= '&maintain_slideshow=' . $slideshowName;
		}

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
				$configParam = $params->toString('INI');
				// $row->params = $registry->toString();

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

			// ToDo:
			/**
			$link = 'index.php?option=com_rsgallery2&view=upload';
			// Tell the upload the id (not used there)
			$input = JFactory::getApplication()->input;

			$Id = $input->get('id', 0, 'INT');
			if (!empty ($Id))
			{
				$link .= '&id=' . $Id;
			}

			$msg .= ' successful';
			$this->setRedirect($link, $msg, $msgType);
			/**/
		}
		else
		{
			$msg .= ' failed';
			JFactory::getApplication()->enqueueMessage($msg, 'warning');
		}

		$this->setRedirect($link, $msg, $msgType);
	}



	public function saveConfigFile ()
	{
		// $msg     = '<strong>' . 'Save2Upload ' . ':</strong><br>';
		$msg     = 'Save slideshow config file ';
		$msgType = 'notice';
		$IsSaved = false;
		$configParam = "";

		$input = JFactory::getApplication()->input;

		//--- prepare link with slideshow name -----------------------------

		$link = 'index.php?option=com_rsgallery2&view=maintslideshows';

		// Tell the maintenance which slideshow to use
		$slideshowName = $input->get('maintain_slideshow', "", 'STRING');

		/* ??? urlencode, rawurlencode() htmlentities() oder htmlspecialchars(). /**/
		if (!empty ($slideshowName))
		{
			$link .= '&maintain_slideshow=' . $slideshowName;
		}

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
			//--- fetch file data -----------------------------

			$targetSlideshow = $input->get('usedSlideshow', "", 'STRING');
			$paramsIniText    = $input->get('params_ini_' . $targetSlideshow, "", 'STRING');
			// check input
			$isErrFound = false;
			// error ?
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

			//--- write to file -----------------------------

			$isSaved = false;

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
		/**
		else
		{
			$msg .= ' failed';
			JFactory::getApplication()->enqueueMessage($msg, 'warning');
		}
		/**/

		$this->setRedirect($link, $msg, $msgType);
	}


}


