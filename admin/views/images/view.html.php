<?php
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

// no direct access
defined( '_JEXEC' ) or die;

JModelLegacy::addIncludePath(JPATH_COMPONENT.'/models');

jimport ('joomla.html.html.bootstrap');

class Rsgallery2ViewImages extends JViewLegacy
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

		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

        $xmlFile = JPATH_COMPONENT . '/models/forms/images.xml';
        $this->form = JForm::getInstance('images', $xmlFile);

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
			case 'images_raw':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_IMAGES_VIEW_RAW_DATA'), 'image');
				JToolBarHelper::editList('image.edit');
				JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'image.delete', 'JTOOLBAR_EMPTY_TRASH');
				break;

			default:
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_IMAGES'), 'image');
				//JToolBarHelper::addNew('image.add');
				JToolBarHelper::editList('image.edit');
				//JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'image.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolbarHelper::deleteList('', 'images.delete', 'JTOOLBAR_DELETE');
				JToolbarHelper::publish('images.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('images.unpublish', 'JTOOLBAR_UNPUBLISH', true);

				JToolBarHelper::spacer();
				JToolBarHelper::spacer();
				JToolBarHelper::spacer();
				JToolBarHelper::spacer();
				JToolBarHelper::spacer();
				JToolBarHelper::spacer();
				JToolbarHelper::divider();
				JToolbarHelper::divider();
				JToolbarHelper::divider();
				// JToolbarHelper::custom('delete', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true);
				// JToolbarHelper::custom('categories.rebuild', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_REBUILD', false);
				JToolBarHelper::custom ('images.moveImagesTo','next','','COM_RSGALLERY2_MOVE_TO', true);
				JToolBarHelper::custom ('images.copyImagesTo','copy','','COM_RSGALLERY2_COPY', true);
//				JToolBarHelper::custom ('','','','', true);
				JToolBarHelper::custom('images.uploadImages','upload','upload.png','COM_RSGALLERY2_UPLOAD', false);
//				JToolBarHelper::custom ('','','','', true);
//				JToolBarHelper::custom ('','','','', true);
//				JToolBarHelper::custom ('','','','', true);
//				JToolBarHelper::custom ('','','','', true);
				JToolBarHelper::custom ('images.resetHits','refresh','','COM_RSGALLERY2_RESET_HITS', true);



//				JToolBarHelper::custom('move_images','forward.png','forward.png','COM_RSGALLERY2_MOVE_TO', true);
//				JToolBarHelper::custom('copy_images','copy.png','copy.png','COM_RSGALLERY2_COPY', true);

				break;
		}

	}
}


