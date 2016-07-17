<?php

/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

// no direct access
defined( '_JEXEC' ) or die;

jimport('joomla.html.html.bootstrap');
// jimport('joomla.application.component.view');
jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_COMPONENT.'/models');

class Rsgallery2ViewComments extends JViewLegacy
{

	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	
	protected $UserIsRoot;
	protected $sidebar;

	protected $items;
	protected $pagination;
	protected $state;

//	protected $rsgConfigData;

	//------------------------------------------------
	public function display ($tpl = null)
	{
		//--- get needed form data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();

//		global $rsgConfig;
//		$this->rsgConfigData = $rsgConfig;

		$this->items = $this->get('Items');
		
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
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
			case 'comments_raw':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_RAW_COMMENTS_TXT'), 'comment');
				JToolBarHelper::editList('comment.edit');
				JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'comment.delete', 'JTOOLBAR_EMPTY_TRASH'); 				
				break;

			default:
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_COMMENTS'), 'comment');
				JToolBarHelper::addNew('comment.add');
				JToolBarHelper::editList('comment.edit');
				JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'comment.delete', 'JTOOLBAR_EMPTY_TRASH'); 				
				break;
		}

	}
}


