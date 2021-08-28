<?php
/**
 * @package       RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;


jimport('joomla.html.html.bootstrap');
//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';
// IMPORT EDITOR CLASS
jimport( 'joomla.html.editor' );

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

	protected $editor;
    protected $editorParams;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @return bool|void
	 *
	 * @since 4.3.0
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

        // select user editor
        $this->determineEditor ();

		/**
		$xmlFile    = JPATH_COMPONENT . '/models/forms/images.xml';
		/**/		
//		$this->form = JForm::getInstance('imagesProperties', $xmlFile);

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
				. '* Leave out some editor buttons : use config ...<br>'
				. '* Config: enable use of textframe for input<br>'
				. '* ON CIDS -1 dont show any image of this gallery <br>'
				. '* Light box modal image also in edit image ... <br>'
				. '* ? selection of gallery <br>'
				. '* Test: The gallery name may be changed to move the image to a different folder <br>'
				. '* Rotate left, right, flip<br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
				. '</span><br><br>';
		}

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

				JToolBarHelper::title(JText::_('COM_RSGALLERY2_ADD_IMAGES_PROPERTIES', 'image'));

				JToolBarHelper::apply('imagesProperties.apply_imagesProperties');
				JToolBarHelper::save('imagesProperties.save_imagesProperties');
                JToolBarHelper::cancel('imagesProperties.cancel_imagesProperties');

				JToolbarHelper::deleteList('', 'ImagesProperties.delete_imagesProperties', 'JTOOLBAR_DELETE');


				//--- turn image -> flip / rotate -------------------------------

				// JToolBarHelper::spacer('50px');
				JToolBarHelper::custom('', '', '', '   ', false);

				JToolBarHelper::custom('imagesProperties.rotate_images_left', 'undo-2', '', 'COM_RSGALLERY2_ROTATE_LEFT', true);
				JToolBarHelper::custom('imagesProperties.rotate_images_right', 'redo-2', '', 'COM_RSGALLERY2_ROTATE_RIGHT', true);
				JToolBarHelper::custom('imagesProperties.rotate_images_180', 'backward-2', '', 'COM_RSGALLERY2_ROTATE_180', true);
				JToolBarHelper::custom('imagesProperties.flip_images_horizontal', 'arrow-right-4', '', 'COM_RSGALLERY2_FLIP_HORIZONTAL', true);
				JToolBarHelper::custom('imagesProperties.flip_images_vertical', 'arrow-down-4', '', 'COM_RSGALLERY2_FLIP_VERTICAL', true);

				break;
		 }
	}

	/**
	
	 * @since 4.3.0
	*/
    protected function determineEditor ()
    {
        // ToDo: try and catch
        // GET EDITOR SELECTED IN GLOBAL SETTINGS
        $config = JFactory::getConfig();
        $global_editor = $config->get( 'editor' );

        // GET USER'S DEFAULT EDITOR
        $user_editor = JFactory::getUser()->getParam("editor");

        if($user_editor && $user_editor !== 'JEditor') {
            $selected_editor = $user_editor;
        } else {
            $selected_editor = $global_editor;
        }

        // INSTANTIATE THE EDITOR
        $this->editor = JEditor::getInstance($selected_editor);

	    // SET EDITOR PARAMS
        $this->editorParams = array(
        	'smilies'=> '1' ,
	        'style'  => '1' ,
            'layer'  => '0' ,
            'table'  => '0' ,
            'clear_entities'=>'0');
    }
}

