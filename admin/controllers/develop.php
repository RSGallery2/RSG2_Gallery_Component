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

        try
        {
            // Model tells if successful
            $model = $this->getModel('GalleriesOrder');
            $IsSaved = $model->orderRsg2ByOld15Method();
            if ($IsSaved) {
                $msg .= JText::_('Ordering by Old 1.5 method saved');
            }
            else
            {
                $msg .= JText::_('Save ordering by Old 1.5 method failed');
                $msgType = 'error';
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing orderRsg2ByOld15Method: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=DebugGalleryOrder';
        $this->setRedirect($link, $msg, $msgType);
		
		return true;
	}

	 /**
     * 
     *
     *
     * @since version 4.3.1
     */
	public function orderRsg2New()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
        $msg = "orderRsg2New: ";
        $msgType = 'notice';

        try
        {
            // Model tells if successful
            $model = $this->getModel('GalleriesOrder');
            $IsSaved = $model->orderRsg2ByNewMethod();
            if ($IsSaved) {
                $msg .= JText::_('Ordering by NewMethod saved');
            }
            else
            {
                $msg .= JText::_('Save ordering by NewMethod failed');
                $msgType = 'error';
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing orderRsg2ByOld15Method: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=DebugGalleryOrder';
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

        try
        {
            // Model tells if successful
            $model = $this->getModel('GalleriesOrder');
            $IsSaved = $model->orderRsg2ByUnorderMethod();
            if ($IsSaved) {
                $msg .= JText::_('Unordering saved');
            }
            else
            {
                $msg .= JText::_('Save unordering failed');
                $msgType = 'error';
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing orderRsg2ByOld15Method: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }


        $link = 'index.php?option=com_rsgallery2&view=develop&layout=DebugGalleryOrder';
        $this->setRedirect($link, $msg, $msgType);

		return true;
	}

    /**
     *
     *
     *
     * @since version 4.3
     */
    public function updateOrder()
    {
        $msg = "updateOrder: " . '<br>';
        $msgType = 'notice';

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=DebugGalleryOrder';
        $this->setRedirect($link, $msg, $msgType);

        return true;
    }


}