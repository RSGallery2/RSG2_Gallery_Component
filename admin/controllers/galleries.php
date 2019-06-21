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
	JLog::add('==> ctrl.galleries.php ');
}
/**/

// ToDo: // Sanitize the input

jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerGalleries extends JControllerAdmin
{
    /**
     * Proxy for getModel.
	 * @param string $name
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
     * @since 4.3.0
     */
    public function getModel($name = 'Gallery', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return  parent::getModel($name, $prefix, $config);
    }

    /**
	 * Saves changed manual ordering of galleries
	 * !!! Not implemented yet !!!
     *
	 * @throws Exception
     *
     * @since 4.5.0.0 4.3
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
				$model = $this->getModel('GalleriesOrder');
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

		$this->setRedirect('index.php?option=com_rsgallery2&view=galleries', $msg, $msgType);
	}

}

