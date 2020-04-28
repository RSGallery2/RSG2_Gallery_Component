<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2020 RSGallery2 Team
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
 * View of maintenance options
 *
 * @since 4.3.0
 */
class Rsgallery2ViewDevelop extends JViewLegacy
{
	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	protected $UserIsRoot;
    protected $debugActive;

    protected $GalleriesOrderModel;
    protected $OrderedGalleries;
    protected $LeftJoinGalleries;

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

		//--- begin to display --------------------------------------------

        // different toolbar on different layouts
        $Layout = JFactory::getApplication()->input->get('layout');

        // collect data dependend on layout
        switch ($Layout) {
            case 'DebugGalleryOrder':

                $this->GalleriesOrderModel = JModelLegacy::getInstance('GalleriesOrder', 'rsgallery2Model');
                $this->OrderedGalleries = $this->GalleriesOrderModel->OrderedGalleries();
                $this->LeftJoinGalleries = $this->GalleriesOrderModel->LeftJoinGalleries();
                break;
        }
        $this->addToolbar($Layout);

        $View = JFactory::getApplication()->input->get('view');
        RSG2_SidebarLinks::addItems($View, $Layout);
//        RSGallery2Helper::addSubmenu('rsgallery2');
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
	 * @param string $Layout
	 *
	 * @since 4.3.0
	*/
	protected function addToolbar($Layout = 'default')
	{
        global $Rsg2DevelopActive;

        switch ($Layout)
        {
            case 'DebugGalleryOrder':
                JToolBarHelper::title(JText::_('COM_RSGALLERY2_DEBUG_GALLERY_ORDER'), 'expand-2');

                // on develop show open tasks if existing
                if (!empty ($Rsg2DevelopActive))
                {
                    echo '<span style="color:red">Task: Check and change gallery order (set to old order, new order or unordered).</span><br><br>';
                }

                JToolbarHelper::custom('develop.orderRsg2Old', 'previous', 'previous', 'Old RSG2 1.5 order', false);
                JToolbarHelper::custom('develop.orderRsg2New', 'next', 'next', 'New RSG2 3.x order', false);
                JToolbarHelper::custom('develop.unorder', 'expand-2', 'expand-2', 'Unorder', false);
                JToolbarHelper::custom('develop.updateOrder', 'expand-2', 'expand-2', 'Update', false);
                // JToolbarHelper::custom('develop.', '', '', '', false);

            break;

            case 'InitUpgradeMessage':
            default:
                JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE'), 'screwdriver'); // 'maintenance');
                if (!empty ($Rsg2DevelopActive))
                {
                    // echo '<span style="color:red">Task: .</span><br><br>';
                }
            break;
        }

	}
}


