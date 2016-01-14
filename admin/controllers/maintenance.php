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

class Rsgallery2ControllerMaintenance extends JControllerAdmin
{
	function consolidateDB()
	{
        $msg = "consolidateDB: ";
        $msgType = 'notice';

        echo 'consolidateDB';

        $this->setRedirect('index.php?option=com_rsg2&view=maintenance', $msg, $msgType);
    }

	function regenerateThumbs()
	{
        $msg = "regenerateThumbs: ";
        $msgType = 'notice';

        echo 'regenerateThumbs';

        $this->setRedirect('index.php?option=com_rsg2&view=maintenance', $msg, $msgType);
	}

	function optimizeDB()
	{
        $msg = "optimizeDB: ";
        $msgType = 'notice';

        echo 'optimizeDB';

        $this->setRedirect('index.php?option=com_rsg2&view=maintenance', $msg, $msgType);
	}

	function viewConfigPlain()
	{
        $msg = "viewConfigPlain: ";
        $msgType = 'notice';

        echo 'config_dumpVars';

        $this->setRedirect('index.php?option=com_rsg2&view=maintenance', $msg, $msgType);
	}

	function editConfigRaw()
	{
        $msg = "editConfigRaw: ";
        $msgType = 'notice';

        echo 'config_rawEdit';

        $this->setRedirect('index.php?option=com_rsg2&view=maintenance', $msg, $msgType);
	}

	function purgeImagesAndData()
	{
        $msg = "removeImagesAndData: ";
        $msgType = 'notice';

        //Access check
        $canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
        if (!$canAdmin) {
            // return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
//			JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
//			return;	// 150518 Does not return JError::raiseWarning object $error

            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);

//            $this->setRedirect('index.php?option=com_rsg2&view=maintenance', $msg, $msgType);
        } else {

            //--- delete all images ----------------------------------------
/*
            $fullPath_thumb = JPATH_ROOT.$rsgConfig->get('imgPath_thumb') . '/';
            $fullPath_display = JPATH_ROOT.$rsgConfig->get('imgPath_display') . '/';
            $fullPath_original = JPATH_ROOT.$rsgConfig->get('imgPath_original') . '/';

            //--- delete all images ----------------------------------------
            // remove thumbnails
            $msg .=  JText::_('COM_RSGALLERY2_REMOVING_THUMB_IMAGES');
            foreach ( glob( $fullPath_thumb.'*' ) as $filename ) {
                if( is_file( $filename )) unlink( $filename );
            }

            // remove display images
            $msg .=  JText::_('COM_RSGALLERY2_REMOVING_ORIGINAL_IMAGES');
            foreach ( glob( $fullPath_display.'*' ) as $filename ) {
                if( is_file( $filename )) unlink( $filename );
            }

            // remove display images
            $msg .=  JText::_('COM_RSGALLERY2_REMOVING_ORIGINAL_IMAGES');
            foreach ( glob( $fullPath_original.'*' ) as $filename ) {
                if( is_file( $filename )) unlink( $filename );
            }

            //--- delete images reference in db ---------------------------------
            $msg .= this->removeImageReferences();


            $msg .= ( JText::_('COM_RSGALLERY2_PURGED'), true );
*/

        }

        $this->setRedirect('index.php?option=com_rsg2&view=maintenance', $msg, $msgType);
	}

	function removeImagesAndData()
	{
		$msg = "removeImagesAndData: ";
		$msgType = 'notice';
		
		//Access check
		$canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
		if (!$canAdmin) {
			// return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
//			JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
//			return;	// 150518 Does not return JError::raiseWarning object $error 
			
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);			

			$this->setRedirect('index.php?option=com_rsg2&view=maintenance', $msg, $msgType);
		} else {

            //--- delete all images ----------------------------------------
/*
            $fullPath_thumb = JPATH_ROOT.$rsgConfig->get('imgPath_thumb') . '/';
//ToDo:            Check 4 valid path !
//            passthru( "rm -r ".$fullPath_thumb);

            $fullPath_display = JPATH_ROOT.$rsgConfig->get('imgPath_display') . '/';
//ToDo:            Check 4 valid path !
//            passthru( "rm -r ".$fullPath_display);

            $fullPath_original = JPATH_ROOT.$rsgConfig->get('imgPath_original') . '/';
//ToDo:            Check 4 valid path !
//            passthru( "rm -r ".$fullPath_original);

            passthru( "rm -r ".JPATH_SITE."/images/rsgallery");

            //--- delete all data ----------------------------------------
			
			// HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_USED_RM_MINUS_R_TO_ATTEMPT_TO_REMOVE_JPATH_SITE_IMAGES_RSGALLERY') );
			$msg = $msg . JText::_('COM_RSGALLERY2_USED_RM_MINUS_R_TO_ATTEMPT_TO_REMOVE_JPATH_SITE_IMAGES_RSGALLERY');

            // ToDO: use model to delete data
            // load model -> drop data


            // call remove
			$msg = $msg . this->removeImageReferences ();

			//			HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_REAL_UNINST_DONE') );
			$msg = $msg . JText::_('COM_RSGALLERY2_REAL_UNINST_DONE');
			
			// ToDo: Message you may now deinstall and reinstall ... as all data and tables are gone
			
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);			
*/

			$this->setRedirect('index.php?option=com_rsg2&view=maintenance', $msg, $msgType);
		}

	}
	
	
}


