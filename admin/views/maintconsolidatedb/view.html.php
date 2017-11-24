<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
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
class Rsgallery2ViewMaintConsolidateDB extends JViewLegacy
{
	// core.admin is the permission used to control access to
	// the global config
	protected $form;
	protected $sidebar;

	//protected $rsgConfigData;
	protected $UserIsRoot;

	/**
	 * @var ImageReferences
	 */
	protected $ImageReferences;

	/**
	 * protected $IsHeaderActive4DB;
	 * protected $IsHeaderActive4Display;
	 * protected $IsHeaderActive4Original;
	 * protected $IsHeaderActive4Thumb;
	 * protected $IsHeaderActive4Parent;
	 * /**/

	protected $IsAnyDbRefMissing; // header

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
			echo '<span style="color:red">Tasks:<br>* Delete also watermarked images<br>* Image square a) use thumb if possible b) check for gd2:...<br> * Select for parent galleries -> only when errors exist</span><br><br>';
		}

//		$xmlFile = JPATH_COMPONENT . '/models/forms/maintregenerateimages.xml';
//		$this->form = JForm::getInstance('maintRegenerateImages', $xmlFile);

		//--- get needed data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

		$ConsolidateModel      = JModelLegacy::getInstance('MaintConsolidateDB', 'rsgallery2Model');
		$this->ImageReferences = $ConsolidateModel->GetImageReferences();

		// echo json_encode($this->DisplayImageData);

		/*
                global $rsgConfig;
                // $this->rsgConfigData = $rsgConfig;
                $this->imageWidth = $rsgConfig->get('image_width');
                $this->thumbWidth = $rsgConfig->get('thumb_width');


                //--- begin to display --------------------------------------------

				//		Rsg2Helper::addSubMenu('rsg2');

				// Check for errors.
				if (count($errors = $this->get('Errors')))
				{
					throw new RuntimeException(implode('<br />', $errors), 500);
				}

                // Assign the Data
                // $this->form = $form;

                // different toolbar on different layouts
                // $Layout = JFactory::getApplication()->input->get('layout');

                // Assign the Data
        //		$this->form = $form;
        */

		$xmlFile    = JPATH_COMPONENT . '/models/forms/maintConsolidateDB.xml';
		$this->form = JForm::getInstance('maintConsolidateDB', $xmlFile);

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
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE'), 'icon-database icon-checkbox-checked');

		JToolBarHelper::custom('MaintConsolidateDb.createImageDbItems', 'database', '', 'COM_RSGALLERY2_CREATE_DATABASE_ENTRIES', true);
		JToolBarHelper::custom('MaintConsolidateDb.createMissingImages', 'image', '', 'COM_RSGALLERY2_CREATE_MISSING_IMAGES', true);
		JToolBarHelper::custom('MaintConsolidateDb.assignParentGallery', 'images', '', 'COM_RSGALLERY2_ASSIGN_SELECTED_GALLERY', true);
		JToolBarHelper::custom('MaintConsolidateDb.deleteRowItems', 'delete', '', 'COM_RSGALLERY2_DELETE_SUPERFLOUS_ITEMS', true);
		JToolBarHelper::custom('MaintConsolidateDb.repairAllIssuesItems', 'refresh', '', 'COM_RSGALLERY2_REPAIR_ALL_ISSUES', true);
		//JToolBarHelper::custom ('MaintConsolidateDb.deleteReferences','delete-2','','COM_RSGALLERY2_ASSIGN_SELECTED_GALLLERIES', true);
		//JToolBarHelper::custom ('MaintConsolidateDb..','next','','COM_RSGALLERY2_MOVE_TO', true);
		//JToolBarHelper::custom ('MaintConsolidateDb.','copy','','COM_RSGALLERY2_COPY', true);

	}

}


