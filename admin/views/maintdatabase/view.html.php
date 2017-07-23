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
 * View of Repair missing database tables, table columns and ...
 *
 * @since 4.3.0
 */
class Rsgallery2ViewMaintDatabase extends JViewLegacy
{
	// core.admin is the permission used to control access to
	// the global config
//	protected $form;
	protected $sidebar;
	protected $errors;
	protected $errorCount;

	//protected $rsgConfigData;
	protected $UserIsRoot;

//	protected $ImageWidth;
//	protected $thumbWidth;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @return mixed bool or void
	 */
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive;
		global $rsgConfig;

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			// echo '<span style="color:red">Task: </span><br><br>';
		}

//		$xmlFile = JPATH_COMPONENT . '/models/forms/maintregenerateimages.xml';
//		$this->form = JForm::getInstance('maintRegenerateImages', $xmlFile);

		//--- get needed data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

//		// $this->rsgConfigData = $rsgConfig;
//		$this->imageWidth = $rsgConfig->get('image_width');
//		$this->thumbWidth = $rsgConfig->get('thumb_width');

		$DatabaseModel = JModelLegacy::getInstance('MaintSql', 'rsgallery2Model');
		$this->errors  = $DatabaseModel->check4Errors();

		// .... $DatabaseModel ->

		$this->errorCount = 0;
		if (empty($this->errors))
		{
			$this->errorCount = 0;
		}
		else
		{
			$this->errorCount = count($this->errors);
		}
		//--- begin to display --------------------------------------------

//		Rsg2Helper::addSubMenu('rsg2'); 

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		// Assign the Data
		// $this->form = $form;

		// different toolbar on different layouts
		// $Layout = JFactory::getApplication()->input->get('layout');

		// Assign the Data
//		$this->form = $form;

        // different toolbar on different layouts
        $Layout = JFactory::getApplication()->input->get('layout');
		$this->addToolbar($this->UserIsRoot, $this->errorCount); //$Layout);

        $View = JFactory::getApplication()->input->get('view');
        RSG2_SidebarLinks::addItems($View, $Layout);
//        RSGallery2Helper::addSubmenu('rsgallery2');
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);

		return;
	}

	/**
	 * Checks if user has root status (is re.admin')
	 * If errors are detected repair is enabled
	 *
	 * @return    bool
	 */
	function CheckUserIsRoot()
	{
		$user     = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');

		return $canAdmin;
	}

	protected function addToolbar($UserIsRoot, $errorCount) //$Layout='default')
	{
		// Title
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE') . ': ' . JText::_('JLIB_FORM_VALUE_SESSION_DATABASE'), 'screwdriver');

		if ($UserIsRoot)
		{
			if ($errorCount > 0)
			{
				JToolbarHelper::custom('maintSql.repairSqlTables', 'refresh', 'refresh', 'COM_RSGALLERY2_FIX', false);
				// JToolBarHelper::spacer();
			}
		}

		JToolBarHelper::cancel('maintenance.cancel');
	}
}


