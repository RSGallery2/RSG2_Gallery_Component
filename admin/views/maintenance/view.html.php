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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

/**
 * View of maintenance options
 *
 * @since 4.3.0
 */
class Rsgallery2ViewMaintenance extends JViewLegacy
{
	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	protected $UserIsRoot;

	protected $dangerActive;
	protected $rawDbActive;
	protected $upgradeActive;
	protected $testActive;
	protected $developActive;
	protected $debugActive;

	protected $sidebar;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @since 4.3.0
	*/
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive;
		global $rsgConfig;

		//--- get needed data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot  = $this->CheckUserIsRoot();
		$this->debugActive = $rsgConfig->get('debug');

		$this->rawDbActive   = true; // false / true;
		$this->dangerActive  = true; // false / true;
		$this->upgradeActive = true; // false / true;
		if (!empty ($Rsg2DevelopActive))
		{
			$this->testActive    = true; // false / true;
			$this->developActive = true; // false / true;
		}

		//--- begin to display --------------------------------------------

        $Layout = JFactory::getApplication()->input->get('layout');
		$this->addToolbar();

        $View = JFactory::getApplication()->input->get('view');
        RSG2_SidebarLinks::addItems($View, $Layout);

        $this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
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
	
	 * @since 4.3.0
	*/
	protected function addToolbar()
	{
		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">'
				. '* Repair: remove for left over upload files  <br>'
				. '* finish comments list<br>'
				. '* add consolidate gallery database -> -> orphans(is child)  without parents<br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
				. '</span><br><br>';
		}

		JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE'), 'screwdriver'); // 'maintenance');
	}
}


