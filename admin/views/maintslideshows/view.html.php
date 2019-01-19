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
jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

/**
 * View of list of image discrepancies (missing images, Missing DB entries on images ...)
 *
 * @since 4.3.0
 */
class Rsgallery2ViewMaintSlideshows extends JViewLegacy
{

	protected $slidesConfig;

	//protected $formsSlides;
	protected $form2Maintain;
	protected $formUserSelectSlideshow;

	protected $slideshow2Maintain;

	/**
	// core.admin is the permission used to control access to
	// the global config
	protected $form;
	protected $sidebar;

	//protected $rsgConfigData;
	protected $UserIsRoot;

	protected $ImageWidth;
	protected $thumbWidth;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @return mixed bool or void
	 * @since 4.3.0
	 */
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive;
		global $rsgConfig;

		//---  ------------------------------------------
		//--- get user slide show name ------------------------------------------
		//---  ------------------------------------------

		$input = JFactory::getApplication()->input;
		$userSlideshow = $input->get('maintain_slideshow', "", 'STRING');

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

		// collect slideshow names from existing folder
		$maintSlidesModel   = JModelLegacy::getInstance('MaintSlideshows', 'rsgallery2Model');
		$slideshowNames = $maintSlidesModel->collectSlideshowsNames();

		// use first or user selected shlideshow
		$this->userSlideshowName = $slideshowNames[1]; // May be ...parth
		if (in_array ($userSlideshow, $slideshowNames))
		{
			$this->userSlideshowName = $userSlideshow;
		}

		$xmlFile    = JPATH_COMPONENT . '/models/forms/maintslideshows.xml';
		$this->formSlideshowSelection = JForm::getInstance('slideshowSelection', $xmlFile);

		// assign previous user selection
		$params = new JRegistry;
		$params->loadString("maintain_slideshow=" . $this->userSlideshowName);
		$this->formSlideshowSelection->bind($params);

		//---  ------------------------------------------
		//--- parameter form  ------------------------------------------
		//---  ------------------------------------------

		// $xmlFileInfo
		$this->slideConfigFile = $maintSlidesModel->collectSlideshowsConfigData(
			$this->userSlideshowName);

		/**
		$xmlFile   = $this->slideConfigFile->cfgFieldsFileName;
		$this->formsSlide = JForm::getInstance($this->slideConfigFile->name, $xmlFile);
		/**/


		//--- add parent form element ------------------------

		//--- add parameter values from xml file ------------------------

		$xmlForm = new SimpleXMLElement('<form></form>');
		$this->SimpleXMLElement_append($xmlForm, $this->slideConfigFile->formFields->config->fields);
		//$xmlFormAsXml = $xmlForm->asXML();


		$formSlide = new JForm('slideParameter');
		//$formSlide = JForm::getInstance('slideParameter');

		$formSlide->load($xmlForm);

		$xmlFormText = $xmlForm->asXML();

		// Define your fieldset here ==> $xpath = '//fieldset[@name="yourfieldset"]';
		//$params = $this->slideConfigFile->parameterValues; // Jregistry ?
		$params = $this->slideConfigFile->parameterValues;

		$formSlide->bind($params);
		$this->formSlide = $formSlide;

		/**/
		$this->addToolbar($this->UserIsRoot); //$Layout);
		$this->sidebar = JHtmlSidebar::render();
		/**/
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
	 * @param $UserIsRoot
	 *
	 * @since 4.3.0
	*/
	protected function addToolbar($UserIsRoot) //$Layout='default')
	{
		// save ??

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">Task: </span><br><br>';
		}

		/**
		// Title
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE') . ': ' . JText::_('COM_RSGALLERY2_MAINT_REGEN'), 'screwdriver');

		if ($UserIsRoot)
		{
			JToolBarHelper::custom('maintRegenerate.RegenerateImagesDisplay', 'forward.png', 'forward.png', 'COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY', false);
			JToolBarHelper::custom('maintRegenerate.RegenerateImagesThumb', 'forward.png', 'forward.png', 'COM_RSGALLERY2_MAINT_REGEN_THUMBS', false);
			// JToolBarHelper::spacer();
		}

		JToolBarHelper::cancel('maintRegenerate.cancel');
		JToolBarHelper::cancel('maintenance.cancel');
//        JToolBarHelper::spacer();
//        JToolBarHelper::help( 'screen.rsgallery2',true);

		/*
		switch ($Layout)
		{
			case 'RawView':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_MAINT_REGEN'), 'screwdriver');
				JToolBarHelper::cancel('config.cancel_rawView');
				break;
			case 'RawEdit':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'screwdriver');
				JToolBarHelper::apply('\'config.apply_rawEdit');
				JToolBarHelper::save('\'config.save_rawEdit');
				JToolBarHelper::cancel('\'config.cancel_RawEdit');
				break;
			// case 'default':
			default:
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_CONFIGURATION'), 'screwdriver');
				JToolBarHelper::apply('\'config.apply');
				JToolBarHelper::save('\'config.save');
				JToolBarHelper::cancel('\'config.cancel');
				break;
		}
		*/
	}

	function SimpleXMLElement_append($parent, $child)
	{
		// get all namespaces for document
		$namespaces = $child->getNamespaces(true);

		// check if there is a default namespace for the current node
		$currentNs = $child->getNamespaces();
		$defaultNs = count($currentNs) > 0 ? current($currentNs) : null;
		$prefix = (count($currentNs) > 0) ? current(array_keys($currentNs)) : '';
		$childName = strlen($prefix) > 1
			? $prefix . ':' . $child->getName() : $child->getName();

		// check if the value is string value / data
		if (trim((string) $child) == '') {
			$element = $parent->addChild($childName, null, $defaultNs);
		} else {
			$element = $parent->addChild(
				$childName, htmlspecialchars((string)$child), $defaultNs
			);
		}

		foreach ($child->attributes() as $attKey => $attValue) {
			$element->addAttribute($attKey, $attValue);
		}
		foreach ($namespaces as $nskey => $nsurl) {
			foreach ($child->attributes($nsurl) as $attKey => $attValue) {
				$element->addAttribute($nskey . ':' . $attKey, $attValue, $nsurl);
			}
		}

		// add children -- try with namespaces first, but default to all children
		// if no namespaced children are found.
		$children = 0;
		foreach ($namespaces as $nskey => $nsurl) {
			foreach ($child->children($nsurl) as $currChild) {
				$this->SimpleXMLElement_append($element, $currChild);
				$children++;
			}
		}
		if ($children == 0) {
			foreach ($child->children() as $currChild) {
				$this->SimpleXMLElement_append($element, $currChild);
			}
		}
	}


}


