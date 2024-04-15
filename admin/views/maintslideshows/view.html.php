<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2024 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

jimport('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');
jimport('joomla.application.component.model');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';
JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

/**
 * View of list of image discrepancies (missing images, Missing DB entries on images ...)
 *
 * @since 4.4.2
 */
class Rsgallery2ViewMaintSlideshows extends JViewLegacy
{

	/**
	 * form field for user slideshow selection with preselected slideshow name
	 * @var
	 * @since 4.4.2
	 */
	protected $formSlideshowSelection;

	/**
	 * Collection of form fields of slideshow and their user defined values.
	 * Supports additionally parameter=value text list
	 * @var
	 * @since 4.4.2
	 */
	protected $slideshowData;

	/**
	 * Contains form fields and parameter values of slideshow
	 *
	 * @var
	 * @since 4.4.2
	 */
	protected $formSlide;

	//------------------------------------------------

	/**
	 * @param null $tpl
	 *
	 * @return mixed bool or void
	 * @since 4.4.2
	 */
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive;
		global $rsgConfig;

		$input = JFactory::getApplication()->input;

		//---------------------------------------------
		// define user slide show name 
		//---------------------------------------------

		// selected in form
		$userSlideshow   = $input->get('maintain_slideshow', "", 'STRING');
		// selected in configuration
		$configSlideshow = $rsgConfig->get('current_slideshow');

		//--- Name list of existing slidehows --------------
		
		// collect slideshow names from existing folder
		$maintSlidesModel = JModelLegacy::getInstance('MaintSlideshows', 'rsgallery2Model');
		$slideshowNames   = $maintSlidesModel->collectSlideshowNames();

		//--- select name of active slideshow --------------

		// use first, user selected or config slideshow name
		$userSlideshowName = $slideshowNames[0]; // May be ...parth
		if (in_array($userSlideshow, $slideshowNames))
		{
			$userSlideshowName = $userSlideshow;
		}
		else
		{
			if (in_array($configSlideshow, $slideshowNames))
			{
				$userSlideshowName = $configSlideshow;
			}
		}

		//--- Create form part with slideshow selection --------------

		// load slideshow selection form field
		$xmlFile = JPATH_COMPONENT . '/models/forms/maintslideshows.xml';

		// Create form
		$this->formSlideshowSelection = JForm::getInstance('slideshowSelection', $xmlFile);

		//--- bind name to form data --------------

		// for slideshow selection control
		$params = new JRegistry;
		$params->loadString("maintain_slideshow=" . $userSlideshowName);
		$this->formSlideshowSelection->bind($params);

		//---------------------------------------------
		// form fields and parameter values   
		//---------------------------------------------

		//--- load data from template.xml and params.ini file --------------

		$slideshowData =
			$maintSlidesModel->collectSlideshowFilesData ($userSlideshowName);
		$this->slideshowData = $slideshowData;

		//--- Create form part with slideshow parameters --------------

		// create root xml form definition
		$xmlForm = new SimpleXMLElement('<form></form>');

		// Add form fields
		if (!empty($slideshowData->formFields))
		{
			$this->SimpleXMLElement_append($xmlForm,
				$slideshowData->formFields->config->fields);
		}

		// load data from template.xml and params.ini file
		$formSlide = new JForm('slideParameter');
		$formSlide->load($xmlForm);

		// add parameter values from xml file
		$params = $slideshowData->parameterValues;

		$formSlide->bind($params);
		$this->formSlide = $formSlide;

		//--- begin of display --------------------------------------------

		// Check rights of user
		$UserIsRoot = $this->CheckUserIsRoot();

		$Layout = JFactory::getApplication()->input->get('layout');
		$this->addToolbar($UserIsRoot); //$Layout);

		$View = JFactory::getApplication()->input->get('view');
		RSG2_SidebarLinks::addItems($View, $Layout);

		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);

		return;
	}

	/**
	 * Checks if user has root status (is re.admin')
	 *
	 * @return    bool
	 * @since 4.4.2
	 */
	function CheckUserIsRoot()
	{
		$user     = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');

		return $canAdmin;
	}

	/**
	 * @param $UserIsRoot
	 *
	 * @since 4.4.2
	 */
	protected function addToolbar($UserIsRoot) //$Layout='default')
	{
		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">'
				. 'Tasks: <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
				. '</span><br><br>';
		}

		// Title
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE') . ': ' . JText::_('COM_RSGALLERY2_MAINT_SLIDESHOW_CONFIG'), 'screwdriver');

		if ($UserIsRoot)
		{
			JToolBarHelper::custom('maintslideshows.saveConfigParameter', 'equalizer', '', 'COM_RSGALLERY2_MAINT_SAVE_PARAMETER', false);
			JToolBarHelper::custom('maintslideshows.saveParamsFile', 'file', 'file', 'COM_RSGALLERY2_MAINT_SAVE_PARAM_FILE', false);
			JToolBarHelper::custom('maintslideshows.saveUserCssFile', 'file', 'file', 'COM_RSGALLERY2_MAINT_SAVE_USER_CSS_FILE', false);
			// JToolBarHelper::spacer();
		}

		// back to maintenance
		JToolBarHelper::cancel('maintRegenerate.cancel');
	}

	/**
	 * SimpleXMLElement_append
	 * Borrowed from the internet
	 * append child inside parent XML element
	 *
	 * @param $parent
	 * @param $child
	 *
	 *
	 * @since 4.4.2
	 */
	function SimpleXMLElement_append($parent, $child)
	{
		// get all namespaces for document
		$namespaces = $child->getNamespaces(true);

		// check if there is a default namespace for the current node
		$currentNs = $child->getNamespaces();
		$defaultNs = count($currentNs) > 0 ? current($currentNs) : null;
		$prefix    = (count($currentNs) > 0) ? current(array_keys($currentNs)) : '';
		$childName = strlen($prefix) > 1
			? $prefix . ':' . $child->getName() : $child->getName();

		// check if the value is string value / data
		if (trim((string) $child) == '')
		{
			$element = $parent->addChild($childName, null, $defaultNs);
		}
		else
		{
			$element = $parent->addChild(
				$childName, htmlspecialchars((string) $child), $defaultNs
			);
		}

		foreach ($child->attributes() as $attKey => $attValue)
		{
			$element->addAttribute($attKey, $attValue);
		}
		foreach ($namespaces as $nskey => $nsurl)
		{
			foreach ($child->attributes($nsurl) as $attKey => $attValue)
			{
				$element->addAttribute($nskey . ':' . $attKey, $attValue, $nsurl);
			}
		}

		// add children -- try with namespaces first, but default to all children
		// if no namespaced children are found.
		$children = 0;
		foreach ($namespaces as $nskey => $nsurl)
		{
			foreach ($child->children($nsurl) as $currChild)
			{
				$this->SimpleXMLElement_append($element, $currChild);
				$children++;
			}
		}
		if ($children == 0)
		{
			foreach ($child->children() as $currChild)
			{
				$this->SimpleXMLElement_append($element, $currChild);
			}
		}
	}


}


