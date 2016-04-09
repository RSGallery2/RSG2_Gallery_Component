<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
/**
 * 
 */
class Rsgallery2ModelMaintImageFiles extends  JModelList
{
//    protected $text_prefix = 'COM_RSG2';
	
	protected $imagePath_thumb;
	protected $imagePath_display;
	protected $imagePath_original;
	protected $imagePath_watermarked;

    protected function removeAllImageFiles ()
    {
        $msg = "Remove image files: \n";

		// assign class variables
		$this->getImagePaths (); 		
		
		$msg .=  $this->RemoveImagesInFolder ($this->imagePath_thumb);
		$msg .=  $this->RemoveImagesInFolder ($this->imagePath_display);
		$msg .=  $this->RemoveImagesInFolder ($this->imagePath_original);
		$msg .=  $this->RemoveImagesInFolder ($this->imagePath_watermarked);

        return $msg;
    }

	private function RemoveImagesInFolder ($fullPath='')
	{
        $msg = 'Remove images in folder: "' . $fullPath . '"';
		
		//--- valid path ? ----------------------------------
		
		if(empty ($fullPath))
		{
			$errMsg = JText::_('COM_RSGALLERY2_FOLDER_DOES_NOT_EXIST');
			$msg .= "\n" . $errMsg;  
			
			JFactory::getApplication()->enqueueMessage($msg, 'error');
		}
		
		// ToDo: check that path is valid and not a base path to "anywhere"
		
		
		//--- remove display images ------------------------

		try
		{
			foreach ( glob( $fullPath.'*' ) as $filename ) {
				if( is_file( $filename )) unlink( $filename );
			}
			
			$msg .= ' successful';
		}
		catch ( Exception $e) {
			$msg .= '. error found: '. $e->getMessage();
		}

		return $msg;
	}

	// ToDo: Move to model image pathe and inherit from there or rename this class ...
    private function getImagePaths ()
    {
        // ToDo: Throws .... \Jdatabaseexception ....

		//--- thumb -------------------------------------
        $db = JFactory::getDbo();
		$query = $db->getQuery (true);

		$query->select ($db->quoteName('value'))
			->from($db->quoteName('#__rsgallery2_config'))
			->where($db->quoteName('name')." = ".$db->quote('imgPath_thumb'));
		
		$db->setQuery($query);
		$this->imagePath_thumb  = $db->loadResult();
		
		//--- display -------------------------------------
        $db = JFactory::getDbo();
		$query = $db->getQuery (true);

		$query->select ($db->quoteName('value'))
			->from($db->quoteName('#__rsgallery2_config'))
			->where($db->quoteName('name')." = ".$db->quote('imgPath_display'));
		
		$db->setQuery($query);
		$this->imagePath_thumb  = $db->loadResult();
		
		//--- original -------------------------------------
        $db = JFactory::getDbo();
		$query = $db->getQuery (true);

		$query->select ($db->quoteName('value'))
			->from($db->quoteName('#__rsgallery2_config'))
			->where($db->quoteName('name')." = ".$db->quote('imgPath_original'));
		
		$db->setQuery($query);
		$this->imagePath_thumb  = $db->loadResult();
		
		//--- water marked -------------------------------------
        $db = JFactory::getDbo();
		$query = $db->getQuery (true);

		
		$query->select ($db->quoteName('value'))
			->from($db->quoteName('#__rsgallery2_config'))
			->where($db->quoteName('name')." = ".$db->quote('imgPath_watermarked'));
		
		$db->setQuery($query);
		$this->imagePath_thumb  = $db->loadResult();


		/* ToDo ?
        if($db->getErrorMsg()){
            $msf = $msg . $db->getErrorMsg();
        }
        else{
            $msg = $successMsg;
        }
		
        return $msg;
		/**/
    }

	
	
	
}