<?php

defined( '_JEXEC' ) or die;
	
jimport('joomla.application.component.view');

class Rsgallery2ViewUpload extends JViewLegacy
{
	protected $form;
	
	// [Single images], [Zip file], [local server (ftp) path], 
	//    ToDo: future image upload sources: folder (PC), ??? FTP,  ??? URL
	protected $ActiveSelection; // ToDo: Activate in html of view 
	
	protected $bYesAllImgInStep1;
	protected $UploadLimit;
	protected $LastFtpUploadPath;
	
	// ToDo: Config -> update gallery selection preselect latest gallery
	// ToDo: Config -> update gallery selection preselect last used gallery ? show combo opened for n entries
	
	public function display ($tpl = null)
	{
		$form = $this->get('Form');
		var_dump($form);

		// $item = $this->get('Item');
//		Rsg2Helper::addSubMenu('uploadSingle'); 
		
	//	$Config = array ('upload_maxsize' => '21M'); // ToDo: Replace with value
		$this->bYesAllImgInStep1 = true; // ToDo: From config last selection ...
		$this->UploadLimit = "*21M"; // ToDo: collect info 
		$this->LastFtpUploadPath = "*Last used FTP path ";  // ToDo: From config last selection ...
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Assign the Data
		$this->form = $form;
		// $this->item = $item;
	
		$this->addToolbar ();
		$this->sidebar = JHtmlSidebar::render ();

		parent::display ($tpl);
		
	}

	
	protected function addToolbar ()
	{
		// COM_RSGALLERY2_SPECIFY_UPLOAD_METHOD
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_SUBMENU_UPLOAD'), 'generic.png');

		//JToolBarHelper::TitleText ("Test01");
//		JToolBarHelper::title(JText::_('COM_RSG2_MENU_UPLOAD'), 'generic.png');
		//$input = JFactory::getApplication()->input;
		
		//$link = 'index.php?option=COM_RSG2&rsgOption=images&task=batchupload';


		//JToolBarHelper::custom('com_rsg2.Redirect2ControlCenter', 'config.png', 'config.png', 'COM_RSG2_MENU_CONTROL_PANEL', false, false);
		
		//JToolBarHelper::custom('com_rsg2.Redirect2Upload', 'rsg2', 'rsg2', JText::_('COM_RSG2_MENU_BATCH_UPLOAD'), false, false);
		
		//JToolBarHelper::custom('com_rsg2.Redirect2Galleries', 'rsg2', 'rsg2', 'COM_RSG2_MENU_GALLERIES', false, false);
		
		//JToolBarHelper::custom('com_rsg2.Redirect2Images', 'mediamanager.png', 'mediamanager.png', 'COM_RSG2_MENU_IMAGES', false, false);
	}
	
    /**
     * Method to set up the document properties
     *
     * @return void
     */
/*    protected function setDocument() 
    {
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('COM_RSGALLERY2_MENU_UPLOAD'));
    }
*/
}
