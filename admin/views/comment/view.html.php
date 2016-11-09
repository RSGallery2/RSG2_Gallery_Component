<?php

defined( '_JEXEC' ) or die;

jimport ('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');

class Rsgallery2ViewComment extends JViewLegacy
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
	public function display ($tpl = null)
	{	
		//--- get needed form data ------------------------------------------
		
		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();

		global $rsgConfig;
		$this->rsgConfigData = $rsgConfig;

		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');

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
			case 'edit':
			default:
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_EDIT_COMMENT', 'comment')); 

				JToolBarHelper::apply('comment.apply');
				JToolBarHelper::save('comment.save');
				if(empty($this->item->id))
				{
					JToolBarHelper::cancel('comment.cancel');
				}
				else
				{
					JToolBarHelper::cancel('comment.cancel');
				}
				break;
		}

	}
}


