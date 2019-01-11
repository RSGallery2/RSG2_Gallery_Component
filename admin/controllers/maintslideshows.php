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
		$slideshow = $input->get('maintain_slideshow', "", 'STRING');
		/* ??? urlencode, rawurlencode() htmlentities() oder htmlspecialchars(). /**/
		if (!empty ($slideshow))
		{
			$link .= '&maintain_slideshow=' . $slideshow;
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
		$link = 'index.php?option=com_rsgallery2&view=maintslideshows';
		// Tell the maintenance which slidshow to use
		$slideshow = $input->get('maintain_slideshow', "", 'STRING');
		/* ??? urlencode, rawurlencode() htmlentities() oder htmlspecialchars(). /**/
		if (!empty ($slideshow))
		{
			$link .= '&maintain_slideshow=' . $slideshow;
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
			//  tells if successful
			//$IsSaved  = $this->save();
			$IsSaved  = True;
			$IsSaved  = false;
		}

		if ($IsSaved)
		{
			// ToDo:

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

		$input = JFactory::getApplication()->input;
		$link = 'index.php?option=com_rsgallery2&view=maintslideshows';
		// Tell the maintenance which slidshow to use
		$slideshow = $input->get('maintain_slideshow', "", 'STRING');
		/* ??? urlencode, rawurlencode() htmlentities() oder htmlspecialchars(). /**/
		if (!empty ($slideshow))
		{
			$link .= '&maintain_slideshow=' . $slideshow;
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
			//  tells if successful
			//$IsSaved  = $this->save();
			$IsSaved  = True;
			$IsSaved  = false;
		}

		if ($IsSaved)
		{
			// ToDo:

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
		}
		else
		{
			$msg .= ' failed';
			JFactory::getApplication()->enqueueMessage($msg, 'warning');
		}

		$this->setRedirect($link, $msg, $msgType);
	}


}


