<?php

defined( '_JEXEC' ) or die;
	
jimport('joomla.application.component.view');

class Rsgallery2ViewUpload extends JViewLegacy
{
	protected $form;
	// [Single images], [Zip file], [local server (ftp) path],
	//    ToDo: future image upload sources: folder (PC), ??? FTP,  ??? URL
	protected $ActiveSelection; // ToDo: Activate in html of view 
	
	protected $UploadLimit;
	protected $LastFtpUploadPath;
	protected $LastUploadedZip;

	// ToDo: Config -> update gallery selection preselect latest gallery  (User input ...)
	// ToDo: Config -> update gallery selection preselect last used gallery ? show combo opened for n entries
	
	public function display ($tpl = null)
	{
		$xmlFile = JPATH_COMPONENT . '/models/forms/upload.xml';
		$form = JForm::getInstance('upload', $xmlFile);


/*
		$this->LastFtpUploadPath = "*Last used FTP path ...";  // ToDo: From config last selection ...
*/

/*
		$app = JFactory::getApplication();
		$app->setUserState('com_users.reset.user', $user->id);
		$userId = $app->getUserState('com_users.reset.user');

		$session = JFactory::getSession();
		$session->set('registry',   new JRegistry('session'));
*/
		$this->UploadLimit = round( ini_get('upload_max_filesize') * 1.024 );

		// ToDo: assign from last selection $app->setUserState('com_rsgallery2.last_used_ftp_path');
		$app = JFactory::getApplication();
		$this->LastFtpUploadPath = $app->getUserState('com_rsgallery2.last_used_ftp_path');
		$this->LastUploadedZip = $app->getUserState('com_rsgallery2.last_used_uploaded_zip');

		// upload_zip, upload_folder
		$formParam = array(
			'all_img_in_step1'=>'1',
			'SelectGalleries01' =>'1', // ToDo retrieve newest gallery id in module
			'SelectGalleries02' =>'1'
		);

		$form->bind ($formParam);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors)); // ToDo: replace error handling
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

	}
}
