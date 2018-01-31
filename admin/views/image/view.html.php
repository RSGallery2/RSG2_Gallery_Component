<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

jimport('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

/**
 *
 *
 * @since 4.3.0
 */
class Rsgallery2ViewImage extends JViewLegacy
{

	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	protected $UserIsRoot;
	protected $sidebar;

	protected $item;
	protected $form;

	protected $HtmlPathThumb;
	protected $HtmlPathDisplay;
	protected $HtmlPathOriginal;

	protected $HtmlImageSrc;

	//------------------------------------------------
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive;
		global $rsgConfig;

		//--- get needed data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();
//		$this->rsgConfigData = $rsgConfig;
		$this->HtmlPathThumb    = JURI_SITE . $rsgConfig->get('imgPath_thumb') . '/';
		$this->HtmlPathDisplay  = JURI_SITE . $rsgConfig->get('imgPath_display') . '/';
		$this->HtmlPathOriginal = JURI_SITE . $rsgConfig->get('imgPath_original') . '/';

		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new RuntimeException(implode('<br />', $errors), 500);
        }

		// Assign the Data
		// $this->form = $form;
		$this->HtmlImageSrc = $this->HtmlPathDisplay . $this->item->name . '.jpg';

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
		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">'
				. 'Tasks: <br>'
//				. '* show image big as modal on  click<br>'
//				. '*  <br>'
//				. '*  <br>'
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
			case 'edit':
			default:
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_EDIT_IMAGE', 'image'));

				JToolBarHelper::apply('image.apply');
				JToolBarHelper::save('image.save');
				JToolbarHelper::save2new('image.save2new');
				if (empty($this->item->id))
				{
					JToolBarHelper::cancel('image.cancel');
				}
				else
				{
					JToolBarHelper::cancel('image.cancel');
				}
				break;
		}
	}

}


