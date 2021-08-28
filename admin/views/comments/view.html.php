<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.html.html.bootstrap');

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

/**
 *
 *
 * @since 4.3.0
 */
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

	protected $form;

//	protected $rsgConfigData;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @since 4.3.0
	*/
	public function display($tpl = null)
	{
		global $rsgConfig;

		echo '<span style="color:green">This is only a demo to show what can be in the future</span><br><br>';

		//--- get needed form data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

//		$this->rsgConfigData = $rsgConfig;

		$this->items = $this->get('Items');

		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

//		$xmlFile    = JPATH_COMPONENT . '/models/forms/comments.xml';
//		$this->form = JForm::getInstance('comments', $xmlFile);

		// 2020.10.28 php 7.2 -> 7.4
        //// Check for errors.
        //if (count($errors = $this->get('Errors')))
        //{
        //    throw new RuntimeException(implode('<br />', $errors), 500);
        //}

        // Check for errors.
		if ($errors = $this->get('Errors'))
		{
			if (count($errors))
			{
				throw new RuntimeException(implode('<br />', $errors), 500);
			}
		}

		// different toolbar on different layouts
		$Layout = JFactory::getApplication()->input->get('layout');
		$this->addToolbar($Layout);

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
	 * @since 4.3.0
	 */
	function CheckUserIsRoot()
	{
		$user     = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');

		return $canAdmin;
	}

	/**
	 * @param string $Layout
	 *
	 * @since 4.3.0
	*/
	protected function addToolbar($Layout = 'default')
	{
		global $Rsg2DevelopActive;

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">'
				. 'Tasks: <br>'
				. '* $canChange, $canEdit, and  ...<br>'
				. '* Search tools add image selection<br>'
				. '* ??? Search tools add gallery selection<br>'
				//. '* <br>'
				//. '* <br>'
				//. '* <br>'
				. '</span><br><br>';
		}
		echo '<span style="color:red">Task: </span><br><br>';


		switch ($Layout)
		{
			case 'comments_raw':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_COMMENTS_VIEW_RAW_DATA'), 'comment');
				JToolBarHelper::editList('comment.edit');
//				JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'comment.delete', 'JTOOLBAR_EMPTY_TRASH');

				// on develop show open tasks if existing
				if (!empty ($Rsg2DevelopActive))
				{
					echo '<span style="color:red">Task: Add delete function.</span><br><br>';
				}
				break;

			default:
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MANAGE_COMMENTS'), 'comment');
				// JToolBarHelper::addNew('comment.add');
				JToolBarHelper::editList('comment.edit');
				JToolbarHelper::trash('comment.trash');

				JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'comment.delete', 'JTOOLBAR_EMPTY_TRASH');
				break;
		}

	}
}


