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

	//protected $rsgConfigData;
	protected $UserIsRoot;

	protected $rsgVersion;
	protected $allowedFileTypes;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 * @return bool
	 */
	public function display ($tpl = null)
	{
		//--- get needed form data ------------------------------------------
		
		// $xmlFile = JPATH_COMPONENT . '/models/forms/config.xml';
		// $form = JForm::getInstance('config', $xmlFile);
		$this->form = $this->get('Form');
		$this->item = $this->get('Item'); 
			
		//--- get needed extra config data ------------------------------------------
		
		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();

		global $rsgConfig;
		//$this->rsgConfigData = $rsgConfig;

		$this->rsgVersion = $rsgConfig->version; // "Version 04.01.00";
		$this->allowedFileTypes = imgUtils::allowedFileTypes ();

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

		// Assign the Data
		// $this->form = $form;

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
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'), 'screwdriver');
				JToolBarHelper::cancel('config.cancel_rawView');
				break;
			case 'RawEdit':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'screwdriver');
				JToolBarHelper::apply('config.apply_rawEdit');
				JToolBarHelper::save('config.save_rawEdit');
				JToolBarHelper::cancel('config.cancel_RawEdit');
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


