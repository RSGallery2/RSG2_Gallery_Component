<?php

defined( '_JEXEC' ) or die;
	
jimport('joomla.application.component.view');

class Rsgallery2ViewUpload extends JViewLegacy
{
	protected $form;
/*
	// [Single images], [Zip file], [local server (ftp) path], 
	//    ToDo: future image upload sources: folder (PC), ??? FTP,  ??? URL
	protected $ActiveSelection; // ToDo: Activate in html of view 
	
	protected $bYesAllImgInStep1;
	protected $UploadLimit;
	protected $LastFtpUploadPath;
*/

	protected $UploadLimit;

	// ToDo: Config -> update gallery selection preselect latest gallery
	// ToDo: Config -> update gallery selection preselect last used gallery ? show combo opened for n entries
	
	public function display ($tpl = null)
	{
		$xmlFile = JPATH_COMPONENT . '/models/forms/upload.xml';
		$form = JForm::getInstance('upload', $xmlFile);

//		$Config = array ('upload_maxsize' => '21M'); // ToDo: Replace with value

/*
		$this->bYesAllImgInStep1 = true; // ToDo: From config last selection ...
		$this->UploadLimit = "*21M"; // ToDo: collect info 
		$this->LastFtpUploadPath = "*Last used FTP path ...";  // ToDo: From config last selection ...
*/
/*
		$var_str = var_export($form, true);
		$var = "<?php\n\n\$$$form = $var_str;\n\n?>";
		file_put_contents('form01Before.php', $var);
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


/*
		// upload_zip, upload_folder
		$formParam = array(
			'upload_zip' => array (
				'all_img_in_step1_01'=>'true',
				'SelectGalleries_01' =>'1'
			),
			'upload_folder' => array (
				'all_img_in_step1_02'=>'true',
				'SelectGalleries_02' =>'1'
			),
		);
*/
		// upload_zip, upload_folder
		$formParam = array(
			'all_img_in_step1'=>'1',
			'SelectGalleries01' =>'1',
			'SelectGalleries02' =>'1'
		);

		var_dump ($formParam);


		/*
		$jform = JForm::getInstance('com_xyz.user_profile', JPATH_COMPONENT."/models/user_profile_fields.xml"); // In dieser XML-Datei liegen die Feld-Definitionen.
		$data = daten_aus_db_laden_oder_aus_formular(); // Ergibt ein Assoc. Array, zB: array("username" => "klaus", "age" => "30")

		$group = "userprofile"; // Das hier ist der Wert des FIELD-Tags aus deiner XML Datei. Bei mir steht da drin: <?xml version="1.0" encoding="UTF-8"?> <form>  <fields name="userprofile"> .......

		foreach ($data as $key => $value) {
			$jform->setValue($key, $group, $value); // Setzt die Daten aus das Formular, benötigt aber halt leider diese Gruppe dafür.
		}
		*/

//		var_export ($form);

//		$var_str = var_export($form, true);

		$content = json_encode($form);
		$content = var_export($form, true);
		echo $content;
		file_put_contents(__DIR__ . '/form01Before.php', $content);

		$form->bind ($formParam);
/*
		$formParam=new stdClass;
		$formParam->name='Hungry Hamster';
		$formParam->street='Grain Street 14';
		$formParam->city='Corn field'
*/
		var_dump ($formParam);

//		$form->bind ($formParam);

		$content = json_encode($form);
		$content = var_export($form, true);
		echo $content;
		file_put_contents(__DIR__ . '/form02After.php', $content);

/*
		$var_str = var_export($form, true);
		$var = "<?php\n\n\$$$form = $var_str;\n\n?>";
		file_put_contents('form02After.php', $var);
/**/
//		var_export ($form);

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
