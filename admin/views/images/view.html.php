<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2019 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

// no direct access
defined('_JEXEC') or die;

JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

jimport('joomla.html.html.bootstrap');

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

/**
 * View class for a list of images
 *
 * @since 4.3.0
 */
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

	protected $HtmlPathThumb;
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

		//--- get needed form data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

//		$this->rsgConfigData = $rsgConfig;
		$this->HtmlPathThumb = JURI_SITE . $rsgConfig->get('imgPath_thumb') . '/';
		////echo 'ThumbPath: ' . JPATH_THUMB . '<br>';
		////echo 'ImagePathThumb: ' . $rsgConfig->imgPath_thumb . '<br>';
		////echo 'ImagePathThumb: ' . JURI_SITE . $rsgConfig->get('imgPath_thumb') . '<br>';
		//echo $this->HtmlPathThumb . '<br>';

		$this->items = $this->get('Items');

		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		$xmlFile    = JPATH_COMPONENT . '/models/forms/images.xml';
		$this->form = JForm::getInstance('images', $xmlFile);

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new RuntimeException(implode('<br />', $errors), 500);
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

		switch ($Layout)
		{
			case 'images_raw':
				// on develop show open tasks if existing
				if (!empty ($Rsg2DevelopActive))
				{
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
                        . '* Add pagination<br>'
	                    . '* Add delete function<br>'
	                    . '* Fix: Number of comments<br>'
	                    . '* Fix: Number of votings/rating<br>'
	                    . '* Enable click on votes/comment to edit these<br>'
	                    //. '* <br>'
	                    //. '* <br>'
	                    //. '* <br>'
	                    //. '* <br>'
	                    //. '* <br>'
                        . '</span><br><br>';
                }

				JToolBarHelper::title(JText::_('COM_RSGALLERY2_IMAGES_VIEW_RAW_DATA'), 'image');
				JToolBarHelper::editList('image.edit');
				// JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'image.delete', 'JTOOLBAR_EMPTY_TRASH');
				break;

			default:
				// on develop show open tasks if existing
				if (!empty ($Rsg2DevelopActive))
				{
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
	                    . '* Add trash state of image, delete only if trashed<br>'
                        . '* Delete function needs to delete watermarked too !<br>'
	                    . '* Search selection has on option too many<br>'
	                    . '* Search controls ...<br>'
                        . '* Sort by ???image count is wrong<br>'
	                    . '* Image not shown above title (data-original-title?)<br>'
	                    . '* Add click on comments to go to comments view with image preselected<br>'
	                    //. '* <br>'
	                    //. '* <br>'
	                    //. '* <br>'
                        . '</span><br><br>';
				}

				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MANAGE_IMAGES'), 'image');
				//JToolBarHelper::addNew('image.add');
				JToolbarHelper::publish('images.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('images.unpublish', 'JTOOLBAR_UNPUBLISH', true);
				JToolBarHelper::editList('image.edit');
				//JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'image.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolbarHelper::deleteList('', 'images.delete', 'JTOOLBAR_DELETE');

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

				break;
		}

	}
}


