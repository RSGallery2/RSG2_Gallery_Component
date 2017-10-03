<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

jimport('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');
jimport('joomla.application.component.model');

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

/**
 *
 *
 * @since 4.3.0
 */
class Rsgallery2ViewUploadFileProperties extends JViewLegacy
{
	// core.admin is the permission used to control access to
	// the global config
	protected $form;
	protected $sidebar;

	//protected $rsgConfigData;
	protected $UserIsRoot;

    protected $galleryId;
    protected $fileSessionId;
    protected $isInOneGallery;
    protected $fileData; // array of files information (fileUrls, fileNames, filePathNames)

	//------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @return mixed bool or void
	 */
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive;

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">Task: watermark (+consolidate...)<br> Image square a) use thumb if possible b) check for gd2:...</span><br><br>';
		}

//		$xmlFile = JPATH_COMPONENT . '/models/forms/maintregenerateimages.xml';
//		$this->form = JForm::getInstance('maintRegenerateImages', $xmlFile);

		//--- get needed data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

        //Retrieve data from submit form
        $input = JFactory::getApplication()->input;
        $this->isInOneGallery = $input->get('isInOneGallery', null, 'INT');
        $this->galleryId = $input->get('galleryId', null, 'INT');
        $this->fileSessionId= $input->get('fileSessionId', '', 'STRING');

        echo 'view: $galleryId = "'      . $this->galleryId      . '"<br>';
        echo 'view: $fileSessionId = "'  . $this->fileSessionId  . '"<br>';
        echo 'view: $isInOneGallery = "' . $this->isInOneGallery . '"<br>';
        echo '<br>';

		// array of files information (fileUrls, fileNames, filePathNames)
		$filePropertiesModel = JModelLegacy::getInstance('UploadFileProperties', 'rsgallery2Model');
		$this->fileData = $filePropertiesModel->RetrieveFileData ($this->galleryId, $this->fileSessionId);

		$xmlFile    = JPATH_COMPONENT . '/models/forms/UploadFileProperties.xml';
		$this->form = JForm::getInstance('UploadFileProperties', $xmlFile);

		$this->form->bind($this->fileData->fileUrls);
		$this->form->bind($this->fileData->fileNames);

		/**
		foreach ($data as $key => $value) {
			$jform->setValue($key, $group, $value); // Setzt die Daten aus das Formular, benötigt aber halt leider diese Gruppe dafür.
		}
		/**/
		echo '$this->fileData->titles: "' . json_encode ($this->fileData->titles) . '<br><br>';
		$this->form->bind($this->fileData->titles);
		$this->form->bind($this->fileData->descriptions);
		$this->form->bind($this->fileData->filePathNames);

		// different toolbar on different layouts
        $Layout = JFactory::getApplication()->input->get('layout');
		$this->addToolbar($this->UserIsRoot); //$Layout);

        $View = JFactory::getApplication()->input->get('view');
        RSG2_SidebarLinks::addItems($View, $Layout);
//        RSGallery2Helper::addSubmenu('rsgallery2');
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);

		return;
	}

	/**
	 * Checks if user has root status (is re.admin')
	 *
	 * @return    bool
	 */
	function CheckUserIsRoot()
	{
		$user     = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');

		return $canAdmin;
	}

	protected function addToolbar($UserIsRoot) //$Layout='default')
	{
		// Title
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_ASSIGN_UPLOADED_FILES'), 'icon-database icon-checkbox-checked');

		//JToolBarHelper::custom('UploadFileProperties.assign2gallery', 'upload', 'upload', 'COM_RSGALLERY2_UPLOAD', false);
		JToolBarHelper::save('UploadFileProperties.assign2gallery');

		//JToolBarHelper::custom ('UploadFileProperties.deleteReferences','delete-2','','COM_RSGALLERY2_ASSIGN_SELECTED_GALLLERIES', true);
		//JToolBarHelper::custom ('UploadFileProperties..','next','','COM_RSGALLERY2_MOVE_TO', true);
		//JToolBarHelper::custom ('UploadFileProperties.','copy','','COM_RSGALLERY2_COPY', true);
	}

}


