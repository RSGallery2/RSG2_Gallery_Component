<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
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

class Rsgallery2ControllerDevelop extends JControllerForm
{
	
	 /**
     * 
     *
     *
     * @since version 4.3
     */
	public function orderRsg2Old()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
        $msg = "orderRsg2Old: " . '<br>';
        $msgType = 'notice';

        $link = JRoute::_('index.php?option=com_rsgallery2&amp;view=develop&amp;layout=DebugGalleryOrder');
        $this->setRedirect($link, $msg, $msgType);
		
		return true;
	}

	 /**
     * 
     *
     *
     * @since version 4.3
     */
	public function orderRsg2New()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
        $msg = "orderRsg2New: " . '<br>';
        $msgType = 'notice';

		$link = JRoute::_('index.php?option=com_rsgallery2&amp;view=develop&amp;layout=DebugGalleryOrder');
        $this->setRedirect($link, $msg, $msgType);

		return true;
	}

	 /**
     * 
     *
     *
     * @since version 4.3
     */
	public function unorder()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
        $msg = "unorder: " . '<br>';
        $msgType = 'notice';

        $link = JRoute::_('index.php?option=com_rsgallery2&view=develop&layout=DebugGalleryOrder');
        $this->setRedirect($link, $msg, $msgType);

		return true;
	}

	
	
}