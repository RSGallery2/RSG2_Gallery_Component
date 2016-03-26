<?php

defined( '_JEXEC' ) or die;
	
jimport('joomla.application.component.view');
jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_COMPONENT.'/models');

class Rsgallery2ViewUpload extends JViewLegacy
{
	protected $form;
	protected $sidebar;

	// [Single images], [Zip file], [local server (ftp) path],
	protected $ActiveSelection; // ToDo: Activate in html of view
	
	protected $UploadLimit;
	protected $PostMaxSize;
	protected $FtpUploadPath;
	// protected $LastUsedUploadZip;

	// ToDo: Config -> update gallery selection preselect latest gallery  (User input ...)
	// ToDo: Config -> update gallery selection preselect last used gallery ? show combo opened for n entries

	//------------------------------------------------
	/**
	 * @param null $tpl
	 * @return bool
	 */
	public function display ($tpl = null)
	{
		$xmlFile = JPATH_COMPONENT . '/models/forms/upload.xml';
		$form = JForm::getInstance('upload', $xmlFile);

		$this->UploadLimit = round( ini_get('upload_max_filesize') * 1.024 );
		$this->PostMaxSize = round( ini_get('post_max_size') * 1.024 );

		//--- FtpUploadPath ------------------------

		$UploadModel = JModelLegacy::getInstance ('upload', 'rsgallery2Model');

		// Retrieve path from config
		$FtpUploadPath = $UploadModel->getFtpPath ();
		// On empty use last successful
		if (empty ($FtpUploadPath)) {
			$FtpUploadPath = $UploadModel->getLastUsedFtpPath();
		}
		$this->FtpUploadPath = $FtpUploadPath;

		//--- LastUsedUploadZip ------------------------

		// Not possible to set input variable in HTML so it is not collected
		// $this->LastUploadedZip = $app->getUserState('com_rsgallery2.last_used_uploaded_zip');
		// $LastUsedUploadZip->getLastUsedUploadZip();

		//--- Config requests ------------------------

		// register 'upload_single', 'upload_zip_pc', 'upload_folder_server'
		$this->ActiveSelection = $UploadModel->getLastUpdateType ();
		if (empty ($this->ActiveSelection)) {
			$this->ActiveSelection = 'upload_zip_pc';
		}

		// 0: default, 1: enable, 2: disable
		$IsUseOneGalleryNameForAllImages = $UploadModel->getIsUseOneGalleryNameForAllImages ();
		if (empty ($IsUseOneGalleryNameForAllImages)) {
			$IsUseOneGalleryNameForAllImages = '1';
		}
		if ($IsUseOneGalleryNameForAllImages == '2') {
			$IsUseOneGalleryNameForAllImages = '0';
		}

		//--- Pre select latest gallery ?  ------------------------

		$IdGallerySelect = -1; //No selection

		$IsPreSelectLatestGallery = $UploadModel->getIsPreSelectLatestGallery ();
		if ($IsPreSelectLatestGallery) {
			$IdGallerySelect = $UploadModel->getIdLatestGallery();
		}

		// upload_zip, upload_folder
		$formParam = array(
			'all_img_in_step1_01' => $IsUseOneGalleryNameForAllImages,
			'all_img_in_step1_02' => $IsUseOneGalleryNameForAllImages,
			'SelectGalleries01_01' => $IdGallerySelect,
			'SelectGalleries02_02' => $IdGallerySelect
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

		return parent::display ($tpl);
	}

	
	protected function addToolbar ()
	{
		// COM_RSGALLERY2_SPECIFY_UPLOAD_METHOD
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_SUBMENU_UPLOAD'), 'generic.png');
	}
}

