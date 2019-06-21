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

class Rsgallery2ControllerGallery extends JControllerForm
{
	/**
	 * Save edited gallery parameters and goto upload form
     *
     * @since 4.5.0.0 4.3
	 */
	public function save2upload()
	{
        // $msg     = '<strong>' . 'Save2Upload ' . ':</strong><br>';
        $msg     = 'Save and goto upload ';
		$msgType = 'notice';
        $IsSaved = false;

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
			//  tells if successful
			$IsSaved  = $this->save();
		}

		if ($IsSaved)
		{
		    // ToDo: Prepare gallery ID and pre select it in upload form

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
	}

}