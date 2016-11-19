<?php

defined( '_JEXEC' ) or die;

jimport ('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');
jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_COMPONENT.'/models');

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
	public function display ($tpl = null)
	{
		//--- get needed form data ------------------------------------------
		
		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();

		$this->form = $this->get('Form');
		$this->item = $this->get('Item'); 
			
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
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
		
		// Assign the Data
		// $this->form = $form;


		// different toolbar on different layouts
		$Layout = JFactory::getApplication()->input->get('layout');
		$this->addToolbar ($Layout);

		$this->sidebar = JHtmlSidebar::render ();

		// echo '<span style="color:red">Task: Toolbar: Link for Upload, (More rows for combo box)</span><br><br>';

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
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'), 'screwdriver');
				JToolBarHelper::cancel('config.cancel_rawView');
				break;
			case 'RawEdit':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'screwdriver');
				JToolBarHelper::apply ('config.apply_rawEdit');
				JToolBarHelper::save  ('config.save_rawEdit');
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


