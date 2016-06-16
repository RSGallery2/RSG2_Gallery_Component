<?php

defined( '_JEXEC' ) or die;

jimport ('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');
jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_COMPONENT.'/models');

class Rsgallery2ViewMaintConsolidateDB extends JViewLegacy
{
	// core.admin is the permission used to control access to
	// the global config
	protected $form;
	protected $sidebar;

	//protected $rsgConfigData;
	protected $UserIsRoot;

	protected $DisplayImageData;

	protected $IsHeaderActive4DB;
	protected $IsHeaderActive4Display;
	protected $IsHeaderActive4Original;
	protected $IsHeaderActive4Thumb;
	protected $IsHeaderActive4Parent;

	//------------------------------------------------
	/**
	 * @param null $tpl
	 * @return mixed bool or void
	 */
	public function display ($tpl = null)
	{
//		$xmlFile = JPATH_COMPONENT . '/models/forms/maintregenerateimages.xml';
//		$this->form = JForm::getInstance('maintRegenerateImages', $xmlFile);

		//--- get needed data ------------------------------------------
		
		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();

		$ConsolidateModel = JModelLegacy::getInstance ('MaintConsolidateDB', 'rsgallery2Model');

		$this->DisplayImageData = $ConsolidateModel->GetDisplayImageData ();

		// debug settings only
		$this->IsHeaderActive4DB = true;
		$this->IsHeaderActive4Display = true;
		$this->IsHeaderActive4Original = true;
		$this->IsHeaderActive4Thumb = true;
		$this->IsHeaderActive4Parent = true;

		// echo json_encode($this->DisplayImageData);

		/*
                global $rsgConfig;
                // $this->rsgConfigData = $rsgConfig;
                $this->imageWidth = $rsgConfig->get('image_width');
                $this->thumbWidth = $rsgConfig->get('thumb_width');


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
        */

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
        JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE'), 'icon-database icon-checkbox-checked');
/*
        if ($UserIsRoot) {
            JToolBarHelper::custom('maintRegenerate.RegenerateImagesDisplay','forward.png','forward.png','COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY', false);
            JToolBarHelper::custom('maintRegenerate.RegenerateImagesThumb','forward.png','forward.png','COM_RSGALLERY2_MAINT_REGEN_THUMBS', false);
            // JToolBarHelper::spacer();
        }

        JToolBarHelper::cancel('maintRegenerate.cancel');
        JToolBarHelper::cancel('maintenance.cancel');
//        JToolBarHelper::spacer();
//        JToolBarHelper::help( 'screen.rsgallery2',true);
*/

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


