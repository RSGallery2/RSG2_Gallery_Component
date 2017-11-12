<?php
/**
 * @package       RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;


jimport('joomla.html.html.bootstrap');

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

/**
 * View class for a list of images
 *
 * @since 4.3.0
 */
class Rsgallery2ViewImagesProperties extends JViewLegacy
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
	
	protected $HtmlPathThumb;
	protected $HtmlPathDisplay;
	protected $HtmlPathOriginal;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @return bool|void
	 *
	 * @since version
	 */
	public function display($tpl = null)
	{
		global $rsgConfig;

		//--- get needed form data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

//		$this->rsgConfigData = $rsgConfig;
		$this->HtmlPathThumb = JURI_SITE . $rsgConfig->get('imgPath_thumb') . '/';
		$this->HtmlPathDisplay = JURI_SITE . $rsgConfig->get('imgPath_display') . '/';
		$this->HtmlPathOriginal = JURI_SITE . $rsgConfig->get('imgPath_original') . '/';
		////echo 'ThumbPath: ' . JPATH_THUMB . '<br>';
		////echo 'ImagePathThumb: ' . $rsgConfig->imgPath_thumb . '<br>';
		////echo 'ImagePathThumb: ' . JURI_SITE . $rsgConfig->get('imgPath_thumb') . '<br>';
		//echo $this->HtmlPathThumb . '<br>';

//		$input = JFactory::getApplication()->input;
//		$cids = $input->get('cid', 0, 'int');
//		echo 'cids: "' . json_encode($cids) . '"<br>';

		$this->items = $this->get('Items');

		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

//		$this->filterForm    = $this->get('FilterForm');
//		$this->activeFilters = $this->get('ActiveFilters');

		/**
		$xmlFile    = JPATH_COMPONENT . '/models/forms/images.xml';
		/**/		
//		$this->form = JForm::getInstance('imagesProperties', $xmlFile);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
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
	 */
	function CheckUserIsRoot()
	{
		$user     = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');

		return $canAdmin;
	}

	protected function addToolbar($Layout = 'default')
	{
		global $Rsg2DevelopActive;

		switch ($Layout)
		{
			default:
				/**
				// on develop show open tasks if existing
				if (!empty ($Rsg2DevelopActive))
				{
					echo '<span style="color:red">Task: Search controls ...</span><br><br>';
				}

				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MANAGE_IMAGES'), 'image');
				//JToolBarHelper::addNew('image.add');
				JToolbarHelper::publish('images.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('images.unpublish', 'JTOOLBAR_UNPUBLISH', true);
				JToolBarHelper::editList('image.edit');
				//JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'image.delete', 'JTOOLBAR_EMPTY_TRASH');

				// JToolBarHelper::spacer('50%');
				JToolbarHelper::divider();
				JToolbarHelper::divider();
				JToolbarHelper::divider();
				// JToolbarHelper::custom('delete', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true);
				// JToolbarHelper::custom('categories.rebuild', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_REBUILD', false);
				//JToolBarHelper::custom ('images.moveImagesTo','next','','COM_RSGALLERY2_MOVE_TO', true);
				//JToolBarHelper::custom ('images.copyImagesTo','copy','','COM_RSGALLERY2_COPY', true);
//				JToolBarHelper::custom ('','','','', true);
				JToolBarHelper::custom('images.uploadImages', 'upload', 'upload.png', 'COM_RSGALLERY2_UPLOAD', false);
//				JToolBarHelper::custom ('','','','', true);
//				JToolBarHelper::custom ('','','','', true);
//				JToolBarHelper::custom ('','','','', true);
//				JToolBarHelper::custom ('','','','', true);
				JToolBarHelper::custom('images.resetHits', 'refresh', '', 'COM_RSGALLERY2_RESET_HITS', true);

				// Add a batch button
				$user = JFactory::getUser();
				if ($user->authorise('core.create', 'com_rsgallery2')
					&& $user->authorise('core.edit', 'com_rsgallery2')
					&& $user->authorise('core.edit.state', 'com_rsgallery2')
				)
				{
					// Get the toolbar object instance
					$bar = JToolbar::getInstance('toolbar');

					$title = JText::_('JTOOLBAR_BATCH');

					// Instantiate a new JLayoutFile instance and render the batch button
					$layout = new JLayoutFile('joomla.toolbar.batch');

					$dhtml = $layout->render(array('title' => $title));
					$bar->appendButton('Custom', $dhtml, 'batch');
				}

//				JToolBarHelper::custom('move_images','forward.png','forward.png','COM_RSGALLERY2_MOVE_TO', true);
//				JToolBarHelper::custom('copy_images','copy.png','copy.png','COM_RSGALLERY2_COPY', true);
				/**/

				JToolBarHelper::apply('images.apply');
				JToolBarHelper::save('images.save');

				JToolbarHelper::deleteList('', 'images.delete', 'JTOOLBAR_DELETE');
				
				// batch
				
				
				break;
		}

	}
}


