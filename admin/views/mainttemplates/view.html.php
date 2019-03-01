<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
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
class Rsgallery2ViewMaintTemplates extends JViewLegacy
{

	/**
	 * form field for user template selection with preselected template name
	 * @var
	 * @since 4.4.2
	 */
	protected $formTemplateSelection;

	/**
	 * Collection of form fields of template and their user defined values.
	 * Supports additionally parameter=value text list
	 * @var
	 * @since 4.4.2
	 */
	protected $templateData;

	/**
	 * Contains form fields and parameter values of template
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
		$userTemplate   = $input->get('maintain_template', "", 'STRING');
		// selected in configuration
		$configTemplate = $rsgConfig->get('current_template');

		//--- Name list of existing slidehows --------------
		
		// collect template names from existing folder
		$maintSlidesModel = JModelLegacy::getInstance('MaintTemplates', 'rsgallery2Model');
		$templateNames   = $maintSlidesModel->collectTemplateNames();

		//--- select name of active template --------------

		// use first, user selected or config template name
		$userTemplateName = $templateNames[0]; // May be ...parth
		if (in_array($userTemplate, $templateNames))
		{
			$userTemplateName = $userTemplate;
		}
		else
		{
			if (in_array($configTemplate, $templateNames))
			{
				$userTemplateName = $configTemplate;
			}
		}

		//--- Create form part with template selection --------------

		// load template selection form field
		$xmlFile = JPATH_COMPONENT . '/models/forms/mainttemplates.xml';

		// Create form
		$this->formTemplateSelection = JForm::getInstance('templateSelection', $xmlFile);

		//--- bind name to form data --------------

		// for template selection control
		$params = new JRegistry;
		$params->loadString("maintain_template=" . $userTemplateName);
		$this->formTemplateSelection->bind($params);

		//---------------------------------------------
		// form fields and parameter values   
		//---------------------------------------------

		//--- load data from template.xml and params.ini file --------------

		$templateData =
			$maintSlidesModel->collectTemplateFilesData ($userTemplateName);
		$this->templateData = $templateData;

		//--- Create form part with template parameters --------------

		// create root xml form definition
		$xmlForm = new SimpleXMLElement('<form></form>');

		// Add form fields
		if (!empty($templateData->formFields))
		{
			$this->SimpleXMLElement_append($xmlForm,
				$templateData->formFields->config->fields);
		}

		// load data from template.xml and params.ini file
		$formSlide = new JForm('slideParameter');
		$formSlide->load($xmlForm);

		// add parameter values from xml file
		$params = $templateData->parameterValues;

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
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE') . ': ' . JText::_('COM_RSGALLERY2_MAINT_TEMPLATE_CONFIG'), 'screwdriver');

		if ($UserIsRoot)
		{
			JToolBarHelper::custom('mainttemplates.saveConfigParameter', 'equalizer', '', 'COM_RSGALLERY2_MAINT_SAVE_PARAMETER', false);
			JToolBarHelper::custom('mainttemplates.saveParamsFile', 'file', 'file', 'COM_RSGALLERY2_MAINT_SAVE_PARAM_FILE', false);
			JToolBarHelper::custom('mainttemplates.saveUserCssFile', 'file', 'file', 'COM_RSGALLERY2_MAINT_SAVE_CSS_FILE', false);
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


