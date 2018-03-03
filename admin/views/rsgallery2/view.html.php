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

/**
 * @param bool
 */
global $Rsg2DebugActive;

if ($Rsg2DebugActive) {
    // Include the JLog class.
    jimport('joomla.log.log');

    // identify active file
//    JLog::add('==> rsgallery2 view.php');
}

jimport('joomla.html.html.bootstrap');
// jimport('joomla.application.component.view');

//require (JUri::root(true).'/administrator/components/com_rsgallery2/helpers/CreditsEnumaration.php');

//require_once JPATH_ADMINISTRATOR . '/components/com_rsgallery2/includes/version.rsgallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/version.rsgallery2.php';

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/CreditsEnumaration.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/models/images.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/models/galleries.php';

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

/**
 * Base view of control panel
 *
 * @since 4.3.0
 */
class Rsgallery2ViewRsgallery2 extends JViewLegacy
{
	protected $Credits;
	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	protected $UserIsAdmin;
	protected $LastImages;
	protected $LastGalleries;
	protected $Rsg2Version;

	protected $FooterText;

	protected $form;
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
        global $Rsg2DebugActive;

        if ($Rsg2DebugActive) {
            JLog::add('==> rsgallery2 view display');
        }

        // on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			// echo '<span style="color:red">Task: Homepage link</span><br><br>';
		}

		//--- get needed data ------------------------------------------

        // List of credits for rsgallery2 developers / translators
		$this->Credits = CreditsEnumaration::CreditsEnumarationText;

        // Check rights of user
		$this->UserIsAdmin = $this->CheckUserIsAdmin();

        // fetch data of last galleries (within one week ?)
		//$this->LastImages = rsg2ModelImages::lastWeekImages(5);
		$this->LastImages = rsgallery2ModelImages::latestImages(5);

        //$this->LastGalleries = rsg2ModelGalleries::lastWeekGalleries(5);
		$this->LastGalleries = rsgallery2ModelGalleries::latestGalleries(5);

        // Get rsgallery2 component version
		// $this->Rsg2Version = rsg2Common::getRsg2ComponentVersion();
		//$this->Rsg2Version = rsg2Common::getRsg2ComponentVersion();
		$Rsg2Version = new rsgalleryVersion();

        $this->Rsg2Version = $Rsg2Version->getLongVersion(); // getShortVersion, getVersionOnly

        $this->FooterText  = $this->RSGallery2Footer($Rsg2Version->getCopyrightVersion());

        $form = $this->get('Form');


        //--- begin to display --------------------------------------------

		/**
		// Options button.
        if ($this->UserIsAdmin) {
            JToolBarHelper::preferences('com_rsgallery2');
        }
		*/

        if ($Rsg2DebugActive) {
            JLog::add('    (V10) ');
        }

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new RuntimeException(implode('<br />', $errors), 500);
        }

        if ($Rsg2DebugActive) {
            JLog::add('    (V11) ');
        }

        // Assign the Data
		$this->form = $form;

        if ($Rsg2DebugActive) {
            JLog::add('    (V12) ');
        }

        //$this->addToolbar ();
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_MENU_CONTROL_PANEL'), 'home-2');

        if ($Rsg2DebugActive) {
            JLog::add('    (V13) ');
        }

        $Layout = JFactory::getApplication()->input->get('layout');
        $View = JFactory::getApplication()->input->get('view');
        RSG2_SidebarLinks::addItems($View, $Layout);
//        RSGallery2Helper::addSubmenu('rsgallery2');

/** wrong
        $RSGallery2Helper=new RSGallery2Helper();
        $RSGallery2Helper->addSubmenu('rsgallery2'); //pass the view name
/**/
        $this->sidebar = JHtmlSidebar::render();

        if ($Rsg2DebugActive) {
            JLog::add('    (V14) ');
        }

        parent::display($tpl);

        if ($Rsg2DebugActive) {
            JLog::add('    (V15) ');
        }


        if ($Rsg2DebugActive) {
            JLog::add('<== rsgallery2 view display');
        }


        return;
	}

	/**
	 * Checks if user has root status (is re.admin')
	 *
	 * @return    bool
	 * @since 4.3.0
	 */
	function CheckUserIsAdmin()
	{
		$user     = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');

		return $canAdmin;
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 *
	 * protected function setDocument()
	 * {
	 * $document = JFactory::getDocument();
	 * $document->setTitle(JText::_('COM_RSGALLERY2_MENU_CONTROL_PANEL'));
	 * }
	  * @since 4.3.0
	*/

	/**
	 * Inserts the HTML placed at the bottom of (all) RSGallery Admin pages.
	 * @param $rsg2ShortVersion
	 *
	 * @return string
	 *
	 * @since 4.3.0
	 */
	function RSGallery2Footer($rsg2ShortVersion)
	{

		$Footer = <<<EOD
        <div class= "rsg2-footer" align="center"><br /><br />$rsg2ShortVersion</div>
        <div class='rsg2-clr'>&nbsp;</div>
EOD;

		return $Footer;
	}

}	

