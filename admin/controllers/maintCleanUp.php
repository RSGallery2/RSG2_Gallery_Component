<?php
defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.maintenanc.php ');
}


jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerMaintCleanUp extends JControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

    public function getModel($name = 'MaintCleanUp',
 							 $prefix = 'rsgallery2Model', 
  							 $config = array())
	{
		$config ['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	
	
	
	
	function purgeImagesAndData()
	{
        $msg = "removeImagesAndData: ";
        $msgType = 'notice';

//		$msg .= '!!! Not implemented yet !!!';

		// Access check
        $canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
		if (!$canAdmin) {
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

			$msg .= '!!! Not implemented yet !!!';

			//--- Delete all images -------------------------------
			
			$imageModel = $this->getModel ('MaintImageFiles');
			$msg .= $imageModel->RemoveImagesInFolder ();
			
			//--- delete images reference in database ---------------
			
			$imageModel = $this->getModel ('MaintDatabaseTables');
			$msg .= $imageModel->removeDataInTables ();
			
			//--- purge message -------------------------------------
            $msg .= '\n' . JText::_('COM_RSGALLERY2_PURGED', true );
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function removeImagesAndData()
	{
		$msg = "removeImagesAndData: ";
		$msgType = 'notice';

//		$msg .= '!!! Not implemented yet !!!';

//		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);

		//Access check
		$canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
		if (!$canAdmin) {
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {

			$msg .= '!!! Not implemented yet !!!';


			$msg .= '!!! Not implemented yet !!!';

			//--- Delete all images -------------------------------
			
			$imageModel = $this->getModel ('MaintImageFiles');
			$msg .= $imageModel->RemoveImagesInFolder ();
			
			//--- delete images reference in database ---------------
			
			$imageModel = $this->getModel ('MaintDatabaseTables');
			$msg .= $imageModel->removeAllTables ();
			
			//--- purge message -------------------------------------
            $msg .= '\n' . JText::_('COM_RSGALLERY2_REAL_UNINST_DONE', true );

			
			
/**			
			
            //--- delete all data ----------------------------------------
			
			// HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_USED_RM_MINUS_R_TO_ATTEMPT_TO_REMOVE_JPATH_SITE_IMAGES_RSGALLERY') );
			$msg = $msg . JText::_('COM_RSGALLERY2_USED_RM_MINUS_R_TO_ATTEMPT_TO_REMOVE_JPATH_SITE_IMAGES_RSGALLERY');

            // ToDO: use model to delete data
            // load model -> drop data


            // call remove
			$msg = $msg . $this->removeImageReferences ();

			//			HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_REAL_UNINST_DONE') );
			$msg = $msg . JText::_('COM_RSGALLERY2_REAL_UNINST_DONE');
			
			// ToDo: Message you may now deinstall and reinstall ... as all data and tables are gone
			
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);			
*/

		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	
}
