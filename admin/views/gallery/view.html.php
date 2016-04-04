<?php

defined( '_JEXEC' ) or die;

jimport ('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');

class Rsgallery2ViewGallery extends JViewLegacy
{

	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	protected $UserIsRoot;
	protected $sidebar;

	protected $rsgConfigData;

	//------------------------------------------------
	public function display ($tpl = null)
	{	
		//--- get needed data ------------------------------------------
		
		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();

		global $rsgConfig;
		$this->rsgConfigData = $rsgConfig;


//		$form = $this->get('Form');

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
		$Layout = JFactory::getApplication()->input->get('layout');


		$this->addToolbar ($Layout);
		$this->sidebar = JHtmlSidebar::render ();

		parent::display ($tpl);

        return;
	}

	/**
	 * Checks if user has root status (is re.admin')
	 *
	 * @return	bool
	 */		
	function CheckUserIsRoot ()
	{
		$user = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');
		return $canAdmin;
	}

	protected function addToolbar ($Layout='default')
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


