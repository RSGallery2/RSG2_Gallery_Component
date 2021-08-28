<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2021 RSGallery2 Team
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
 * View to edit a gallery
 *
 * @since 4.3.0
 */
class Rsgallery2ViewGallery extends JViewLegacy
{

	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	protected $UserIsRoot;
	protected $sidebar;

	protected $item;
	protected $form;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @since 4.3.0
	*/
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive;

		//--- get needed form data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

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

		// Assign the Data
		// $this->form = $form;

		//--- tool  bar -------------------------

		// different toolbar on different layouts
		$Layout = JFactory::getApplication()->input->get('layout');
		$this->addToolbar($Layout);

		//--- side  bar -------------------------

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
				. '* Activate field parameters again -> Merge Jregistry in site ...<br>'
				. '* Enable modal selection of thumbs ... <br>'
				. '* Show selected gallery thumbnail (different separate control)'
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
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_EDIT_GALLERY', 'images'));

				JToolBarHelper::apply('gallery.apply');
				JToolBarHelper::save('gallery.save');
				JToolbarHelper::save2new('gallery.save2new');
				if (empty($this->item->id))
				{
					JToolBarHelper::cancel('gallery.cancel');
				}
				else
				{
					JToolBarHelper::cancel('gallery.cancel');
				}

				JToolBarHelper::custom ('gallery.save2upload','upload','','COM_RSGALLERY2_SAVE_AND_GOTO_UPLOAD', false);

				break;
		}
	}

}


