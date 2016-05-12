<?php

defined( '_JEXEC' ) or die;

jimport ('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');
jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_COMPONENT.'/models');

class Rsgallery2ViewMaintDatabase extends JViewLegacy
{
	// core.admin is the permission used to control access to
	// the global config
//	protected $form;
	protected $sidebar;
	protected $errors;
	protected $errorCount;

	//protected $rsgConfigData;
	protected $UserIsRoot;

//	protected $ImageWidth;
//	protected $thumbWidth;
	
	//------------------------------------------------
	/**
	 * @param null $tpl
	 * @return mixed bool or void
	 */
	public function display ($tpl = null)
	{
		global $rsgConfig;

//		$xmlFile = JPATH_COMPONENT . '/models/forms/maintregenerateimages.xml';
//		$this->form = JForm::getInstance('maintRegenerateImages', $xmlFile);

		//--- get needed data ------------------------------------------
		
		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();

//		// $this->rsgConfigData = $rsgConfig;
//		$this->imageWidth = $rsgConfig->get('image_width');
//		$this->thumbWidth = $rsgConfig->get('thumb_width');

		$DatabaseModel = JModelLegacy::getInstance ('MaintSql', 'rsgallery2Model');
		// .... $DatabaseModel ->

		$this->errorCount = 4;

		$errors [] = "Database schema version (3.3.6-2014-09-30) does not match CMS version (3.5.1-2016-03-29).";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'hits'. (From file 2.5.5.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'header'. (From file 3.4.0-2014-09-16.sql.)";
		$errors [] = "Table 'mmbty_session' does not have column 'session_id' with type varchar(191). (From file 3.5.0-2015-07-01.sql.)";
		$errors [] = "Table 'mmbty_user_keys' does not have column 'series' with type varchar(191). (From file 3.5.0-2015-07-01.sql.)";
		$errors [] = "Table 'mmbty_contentitem_tag_map' should not have index 'idx_tag'. (From file 3.5.0-2015-10-26.sql.)";
		$errors [] = "Table 'mmbty_contentitem_tag_map' should not have index 'idx_type'. (From file 3.5.0-2015-10-26.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' should not have index 'idx_link_old'. (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'old_url' with type VARCHAR(2048). (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'new_url' with type VARCHAR(2048). (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'referer' with type VARCHAR(2048). (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have index 'idx_old_url'. (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_user_keys' does not have column 'user_id' with type varchar(150). (From file 3.5.1-2016-03-25.sql.)";
		$errors [] = "The Joomla! Core database tables have not been converted yet to UTF-8 Multibyte (utf8mb4)";
		$this->errors = $errors;
		
		//--- begin to display --------------------------------------------
		
//		Rsg2Helper::addSubMenu('rsg2'); 
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Assign the Data
		// $this->form = $form;

		// different toolbar on different layouts
		// $Layout = JFactory::getApplication()->input->get('layout');

		// Assign the Data
//		$this->form = $form;

		$this->addToolbar ($this->UserIsRoot); //$Layout);
		$this->sidebar = JHtmlSidebar::render ();

		parent::display ($tpl);

        return;
	}

	/**
	 * Checks if user has root status (is re.admin')
	 *
	 * @return	bool
	 */		
	function CheckUserIsRoot ()
	{
		$user = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');
		return $canAdmin;
	}

	protected function addToolbar ($UserIsRoot) //$Layout='default')
	{
        // Title
        JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE') . ': ' . JText::_('JLIB_FORM_VALUE_SESSION_DATABASE'), 'screwdriver');

        if ($UserIsRoot) {
			JToolbarHelper::custom('database.fix', 'refresh', 'refresh', 'COM_RSGALLERY2_FIX', false);
//			JToolBarHelper::custom('maintRegenerate.RegenerateImagesDisplay','forward.png','forward.png','COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY', false);
//            JToolBarHelper::custom('maintRegenerate.RegenerateImagesThumb','forward.png','forward.png','COM_RSGALLERY2_MAINT_REGEN_THUMBS', false);
            // JToolBarHelper::spacer();
        }

//        JToolBarHelper::cancel('maintRegenerate.cancel');
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
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_CONFIGURATION'), 'cog');
				JToolBarHelper::apply('\'config.apply');
				JToolBarHelper::save('\'config.save');
				JToolBarHelper::cancel('\'config.cancel');
				break;
		}
		*/
	}
}


