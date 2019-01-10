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
        // $msg     = '<strong>' . 'Save2Upload ' . ':</strong><br>';
        $msg     = 'changeSlideshow XXX ';
		$msgType = 'notice';
        $IsSaved = false;

		$link = 'index.php?option=com_rsgallery2&view=maintslideshows';

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
			/* ??? urlencode, rawurlencode() htmlentities() oder htmlspecialchars(). /**/

			// Tell the maintenance which slidshow to use
			$input = JFactory::getApplication()->input;

			$slideshow = $input->get('maintain_slideshow', "", 'STRING');
			if (!empty ($slideshow))
			{
				$link .= '&maintain_slideshow=' . $slideshow;

				$msg = "";
				$msgType = "";
			}
		}

		$this->setRedirect($link, $msg, $msgType);
	}

}


