<?php

defined( '_JEXEC' ) or die;

jimport('joomla.application.component.view');

class Rsg2ViewUploadSingle extends JViewLegacy
{

	public function display ($tpl = null)
	{
	
		$form = $this->get('Form');
		// $item = $this->get('Item');
		Rsg2Helper::addSubMenu('uploadSingle'); 
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Assign the Data
		$this->form = $form;
		// $this->item = $item;
	
		$this->addToolbar ();
		$this->sidebar = JHtmlSidebar::render ();

		parent::display ($tpl);
		
		// Set the document
		$this->setDocument();		
	}

	
	protected function addToolbar ()
	{
		//JToolBarHelper::TitleText ("Test01");
		JToolBarHelper::title(JText::_('COM_RSG2_MENU_UPLOAD'), 'generic.png');
		//$input = JFactory::getApplication()->input;
		
		//$link = 'index.php?option=COM_RSG2&rsgOption=images&task=batchupload';


		//JToolBarHelper::custom('com_rsg2.Redirect2ControlCenter', 'config.png', 'config.png', 'COM_RSG2_MENU_CONTROL_PANEL', false, false);
		
		//JToolBarHelper::custom('com_rsg2.Redirect2Upload', 'rsg2', 'rsg2', JText::_('COM_RSG2_MENU_BATCH_UPLOAD'), false, false);
		
		//JToolBarHelper::custom('com_rsg2.Redirect2Galleries', 'rsg2', 'rsg2', 'COM_RSG2_MENU_GALLERIES', false, false);
		
		//JToolBarHelper::custom('com_rsg2.Redirect2Images', 'mediamanager.png', 'mediamanager.png', 'COM_RSG2_MENU_IMAGES', false, false);
	}
	
    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument() 
    {
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('COM_RSG2_MENU_UPLOAD'));
    }
}
