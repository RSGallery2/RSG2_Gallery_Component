<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
/**
 * 
 */
class rsg2ModelMaintDatabaseTables extends  JModelList
{
//    protected $text_prefix = 'COM_RSG2';


//    protected function removeImageReferences ()
    protected function removeDataInTables ()
    {
        $msg = "removeDataInTables: ";
		
//COM_RSGALLERY2_DELETE_FROM_FILESYSTEM COM_RSGALLERY2_DELETE_IMAGES
        $msg = $msg . $this->PurgeTable ('#__rsgallery2_acl', JText::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_ACL')) . "\n";
        $msg = $msg . $this->PurgeTable ('#__rsgallery2_files', JText::_('COM_RSGALLERY2_PURGED_IMAGE_ENTRIES_FROM_DATABASE')) . "\n";
        $msg = $msg . $this->PurgeTable ('#__rsgallery2_cats', JText::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_CATS')) . "\n";
        $msg = $msg . $this->PurgeTable ('#__rsgallery2_galleries', JText::_('COM_RSGALLERY2_PURGED_GALLERIES_FROM_DATABASE')) . "\n";
        $msg = $msg . $this->PurgeTable ('#__rsgallery2_config', JText::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_CONFIG')) . "\n";
        $msg = $msg . $this->PurgeTable ('#__rsgallery2_comments', JText::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_COMMENTS')) . "\n";

        return $msg;
    }

    /**
     * Deletes all Tables of RSG2 in preparation of of deinstall/reinstall
     * @return string $msg
     */
    protected function removeAllTables ()
    { 
		$msg = "RemoveAllTables: ";
		
		$msg = $msg . $this->DropTable ('#__rsgallery2_acl', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_ACL')) . "\n";
		$msg = $msg . $this->DropTable ('#__rsgallery2_files', JText::_('COM_RSG2DROPED_TABLE___RSGALLERY2_FILES')) . "\n";
		$msg = $msg . $this->DropTable ('#__rsgallery2_cats', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_CATS')) . "\n";
		$msg = $msg . $this->DropTable ('#__rsgallery2_galleries', JText::_('COM_RSG2DROPED_TABLE___RSGALLERY2_GALLERIES')) . "\n";
		$msg = $msg . $this->DropTable ('#__rsgallery2_config', JText::_('COM_RSG2DROPED_TABLE___RSGALLERY2_CONFIG')) . "\n";
		$msg = $msg . $this->DropTable ('#__rsgallery2_comments', JText::_('COM_RSG2DROPED_TABLE___RSGALLERY2_COMMENTS')) . "\n";
				
		return $msg;
	}


    /**
     * Removes one table from RSG2
     * @param string $TableId
     * @param string $successMsg
     * @return string bool success or error message
     */
    private function PurgeTable ($TableId, $successMsg)
    {
        // ToDo: Throws .... \Jdatabaseexception ....

        $msg = "Purge table failure: ";

        $db = JFactory::getDbo();
        $db->truncateTable($TableId);
        $db->execute();

        if($db->getErrorMsg()){
            $msg = $msg . $db->getErrorMsg();
        }
        else{
            $msg = $successMsg;
        }

        return $msg;
    }

    /**
     * Removes one table from RSG2
     * @param string $TableId
     * @param string $successMsg
     * @return string bool success or error message
     */
    private function DropTable ($TableId, $successMsg)
    {
        // ToDo: Throws .... \Jdatabaseexception ....

        $msg = "Drop table failure: ";

        $db = JFactory::getDbo();
        $db->dropTable($TableId, true);
        $db->execute();

        if($db->getErrorMsg()){
            $msg = $msg . $db->getErrorMsg();
        }
        else{
            $msg = $successMsg;
        }

        return $msg;
    }

}