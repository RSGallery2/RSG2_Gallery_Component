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
class Rsgallery2ViewMaintDeleteLeftOverImages extends JViewLegacy
{
	// core.admin is the permission used to control access to
	// the global config
	protected $form;
	protected $sidebar;

	//protected $rsgConfigData;
	protected $UserIsRoot;

	/**
	 * @var FolderReferences
	 */
	protected $FolderReferences;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @return mixed bool or void
	 * @since 4.4.1
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
//		$xmlFile = JPATH_COMPONENT . '/models/forms/maintregenerateimages.xml';
//		$this->form = JForm::getInstance('maintRegenerateImages', $xmlFile);

		//--- get needed data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

		$LeftOverModel = JModelLegacy::getInstance('MaintDeleteLeftOverImages', 'rsgallery2Model');
		$this->FolderReferences = $LeftOverModel->GetLeftOverFolderReferences();

		// echo json_encode($this->DisplayImageData);

		/**
		$xmlFile    = JPATH_COMPONENT . '/models/forms/maintConsolidateDB.xml';
		$this->form = JForm::getInstance('maintConsolidateDB', $xmlFile);
		/**/
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
	 * @since 4.3.0
	 */
	function CheckUserIsRoot()
	{
		$user     = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');

		return $canAdmin;
	}

	/**
	 * @param $UserIsRoot Not used

	 * @since 4.3.0
	*/
	protected function addToolbar($UserIsRoot) //$Layout='default')
	{
		global $Rsg2DevelopActive;

		// on develop show open tasks if existing
		//if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">'
				. 'Tasks: <br>'
				. '*  !!! Do real delete of folders and images within !!!<br>'
				. '*  Display images a) as one row name list<br>'
				. '*  Display images b) as one row image list<br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
				. '</span><br><br>';
		}
		
		// Title
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINT_DELETE_LEFT_OVER_IMAGES'), 'icon-database icon-checkbox-checked');

		JToolBarHelper::custom('MaintDeleteLeftOverImages.DeleteFromFolderList', 'database', '', 'COM_RSGALLERY2_DELETE_SUPERFLOUS_ITEMS', true);

		/**
		JToolBarHelper::custom('MaintConsolidateDb.createImageDbItems', 'database', '', 'COM_RSGALLERY2_CREATE_DATABASE_ENTRIES', true);
        JToolBarHelper::custom('MaintConsolidateDb.createMissingImages', 'image', '', 'COM_RSGALLERY2_CREATE_MISSING_IMAGES', true);
        JToolBarHelper::custom('MaintConsolidateDb.createWatermarkImages', 'scissors', '', 'COM_RSGALLERY2_CREATE_MISSING_WATERMARKS', true);
		JToolBarHelper::custom('MaintConsolidateDb.assignParentGallery', 'images', '', 'COM_RSGALLERY2_ASSIGN_SELECTED_GALLERY', true);
		JToolBarHelper::custom('MaintConsolidateDb.deleteRowItems', 'delete', '', 'COM_RSGALLERY2_DELETE_SUPERFLOUS_ITEMS', true);
		JToolBarHelper::custom('MaintConsolidateDb.repairAllIssuesItems', 'refresh', '', 'COM_RSGALLERY2_REPAIR_ALL_ISSUES', true);
		//JToolBarHelper::custom ('MaintConsolidateDb.deleteReferences','delete-2','','COM_RSGALLERY2_ASSIGN_SELECTED_GALLLERIES', true);
		//JToolBarHelper::custom ('MaintConsolidateDb..','next','','COM_RSGALLERY2_MOVE_TO', true);
		//JToolBarHelper::custom ('MaintConsolidateDb.','copy','','COM_RSGALLERY2_COPY', true);
		/**/
	}

}


