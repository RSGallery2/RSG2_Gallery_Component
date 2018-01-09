<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2018 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

jimport('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

/**
 * May not be needed ToDo: Delete table when one user has had a problem and we know how to move local acl to standard acl
 *
 *
 *
 * @since 4.3.0
 */
class Rsgallery2ViewAcl_item extends JViewLegacy
{

	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	protected $UserIsRoot;
	protected $sidebar;

	protected $item;
	protected $form;

	protected $rsgConfigData;

	//------------------------------------------------
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive;

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			// echo '<span style="color:red">Task: </span><br><br>';
		}

		//--- get needed data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

		global $rsgConfig;
		$this->rsgConfigData = $rsgConfig;

		$this->item = $this->get('Item');
		$errors     = $this->form = $this->get('Form');

		$this->state = $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new RuntimeException(implode('<br />', $errors), 500);
        }

		// Assign the Data
		// $this->form = $form;

		// different toolbar on different layouts
		$Layout = JFactory::getApplication()->input->get('layout');
		$this->addToolbar($Layout);

        $View = JFactory::getApplication()->input->get('view');
        RSG2_SidebarLinks::addItems('acl_item', $Layout);
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

	protected function addToolbar($Layout = 'default')
	{
		switch ($Layout)
		{
			case 'RawView':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'), 'screwdriver'); // 'maintenance');
				JToolBarHelper::cancel('cancelRawView');
				break;
			case 'RawEdit':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'screwdriver'); // 'maintenance');
				JToolBarHelper::apply('config_rawEdit_apply');
				JToolBarHelper::save('config_rawEdit_save');
				JToolBarHelper::cancel('cancelRawEdit');
				break;
			// case 'default':
			default:
				JToolBarHelper::title(JText::_('yyyy COM_RSGALLERY2_MAINTENANCE')
					. ':' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'), 'screwdriver'); // 'maintenance');
				JToolBarHelper::apply('config_rawEdit_apply');
				JToolBarHelper::save('config_rawEdit_save');
				JToolBarHelper::cancel('cancelConfig');
				break;
		}

	}
}


