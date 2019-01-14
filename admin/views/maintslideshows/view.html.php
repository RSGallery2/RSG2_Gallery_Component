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

		//--- get user data ------------------------------------------

		$input = JFactory::getApplication()->input;
		$userSlideshow = $input->get('maintain_slideshow', "", 'STRING');

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

		// collect slideshow names from existing folder
		$maintSlidesModel   = JModelLegacy::getInstance('MaintSlideshows', 'rsgallery2Model');
		$slideshowNames = $maintSlidesModel->collectSlideshowsNames();

		// use first or user selected shlideshow
		$this->slideshow2Maintain = $slideshowNames[1]; // May be ...parth
		if (in_array ($userSlideshow, $slideshowNames))
		{
			$this->slideshow2Maintain = $userSlideshow;
		}

		$this->slideConfigFile = $maintSlidesModel->collectSlideshowsConfigData(
			$this->slideshow2Maintain);

		/**
		// ToDo: rename to slidesConfigData
		$this->slidesConfigFiles = $maintSlidesModel->collectSlideshowsConfigFilesAll();
		//$this->slidesParameter = $maintSlidesModel->parameterFromConfigFiles($this->slidesConfigFiles);

		$formsSlides = [];

		foreach ($this->slidesConfigFiles as $xmlFileInfo)
		{
			$xmlFile    = $xmlFileInfo->cfgFieldsFileName;
			$formsSlides [$xmlFileInfo->name] = JForm::getInstance($xmlFileInfo->name, $xmlFile);
		}
		$this->formsSlides = $formsSlides;
		/**/

		$xmlFile    = JPATH_COMPONENT . '/models/forms/maintslideshows.xml';
		$this->formUserSelectSlideshow = JForm::getInstance('maintslideshows', $xmlFile);

		$xmlFile    = $this->slideConfigFile->;
		$this->formUserSelectSlideshow = JForm::getInstance('', $xmlFile);


		/**
		// $this->rsgConfigData = $rsgConfig;
		$this->imageWidth = $rsgConfig->get('image_width');
		$this->thumbWidth = $rsgConfig->get('thumb_width');

		//--- begin to display --------------------------------------------

//		Rsg2Helper::addSubMenu('rsg2'); 

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new RuntimeException(implode('<br />', $errors), 500);
        }

		// Assign the Data
		// $this->form = $form;

		// different toolbar on different layouts
		// $Layout = JFactory::getApplication()->input->get('layout');

		// Assign the Data
//		$this->form = $form;
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
}


