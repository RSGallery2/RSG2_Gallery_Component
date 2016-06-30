<?php

defined( '_JEXEC' ) or die;

jimport ('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');
jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_COMPONENT.'/models');

class Rsgallery2ViewComments extends JViewLegacy
{

	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	
/*	
	protected $form;
	protected $item;
	protected $sidebar;

	protected $rsgConfigData;
	protected $UserIsRoot;

	protected $rsgVersion;
	protected $allowedFileTypes;
/**/

	protected $items;


	//------------------------------------------------
	/**
	 * @param null $tpl
	 * @return bool
	 */
	public function display ($tpl = null)
	{
		//--- get needed form data ------------------------------------------

		// $CommentsModel = JModelLegacy::getInstance ('Comments', 'rsgallery2Model');
		// $this->items = $CommentsModel-> ();

		$this->items         = $this->get('Items');




		/**
		// $xmlFile = JPATH_COMPONENT . '/models/forms/config.xml';
		// $form = JForm::getInstance('config', $xmlFile);
		$this->form = $this->get('Form');
		$this->item = $this->get('Item'); 
			
		//--- get needed extra config data ------------------------------------------
		
		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();

		global $rsgConfig;
		$this->rsgConfigData = $rsgConfig;

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
/**/

		//$this->addToolbar ($Layout);
		$this->addToolbar ();
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
			default:
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_COMMENTS'), 'comment');
//				JToolBarHelper::apply('config.apply');
//				JToolBarHelper::save('config.save');
//				JToolBarHelper::cancel('config.cancel');
				break;
		}

	}
}


