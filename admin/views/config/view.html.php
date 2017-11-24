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

JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/includes/version.rsgallery2.php');

/**
 * yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
 * 
 * zek20xw813rj3
 *
 * @since 4.3.0
 */
class Rsgallery2ViewConfig extends JViewLegacy
{
	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to
	// the global config
	protected $form;
	protected $item;
	protected $sidebar;

	protected $rsgConfigData;
	protected $UserIsRoot;

	protected $rsgVersion;
//	protected $allowedFileTypes;

	protected $configVars;

    //------------------------------------------------
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive;

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">Task: Save should not leave programm, rename save in controller as it is used by Raw ...</span><br><br>';
		}

		//--- get needed form data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

		$this->form = $this->get('Form');
//		$this->item = $this->get('Item');

        $rsgVersion = new rsgalleryVersion();
        $this->rsgVersion = $rsgVersion->getLongVersion();

//		global $rsgConfig;
//		$this->rsgConfigData = $rsgConfig;

		//--- get needed extra config data ------------------------------------------

//		$this->rsgVersion = $rsgConfig->version; // "Version 04.01.00";
//		 ToDo: Check for using List in XML ???
//		$this->allowedFileTypes = imgUtils::allowedFileTypes ();

//		$this->configVars = get_object_vars($this->rsgConfigData);
//		$this->form->bind ($this->configVars);

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
		$Layout = JFactory::getApplication()->input->get('layout');
		$this->addToolbar($Layout);

        $View = JFactory::getApplication()->input->get('view');
        RSG2_SidebarLinks::addItems($View, $Layout);
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
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'), 'screwdriver');
				JToolBarHelper::cancel('config.cancel_rawView');
				break;
			case 'RawEdit':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'screwdriver');
				JToolBarHelper::apply('config.apply_rawEdit');
				JToolBarHelper::save('config.save_rawEdit');
				JToolBarHelper::cancel('config.cancel_rawEdit');
				break;
			// case 'default':
			default:
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_CONFIGURATION'), 'cog');

				JToolBarHelper::apply('config.apply');
				JToolBarHelper::save('config.save');
				JToolBarHelper::cancel('config.cancel');

				break;
		}
	}

}


