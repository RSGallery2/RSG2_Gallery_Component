<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldRsgGalleryOrderingList extends JFormFieldList {

    protected $type = 'RsgGalleryOrderingList';

	/**
	 * Method to get the field options. -> List of galleries
	 *
	 * @return  array  The field option objects
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
		$options = array();

		// $GalleryId = $this->form->getValue('gallery_id');
		$DbVarName = (string) $this->element['name'];

		// List of row number to gallery names
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true)
			->select($DbVarName.' As idx, name as text')
			->from('#__rsgallery2_galleries AS a')
			->order('a.ordering');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$galleries = $db->loadObjectList();

			// Create row number to Text = "Row number -> gallery name" assignment
			foreach($galleries as $gallery)
			{	
		        $options[] = array("value" => $gallery->idx, "text" => str_pad($gallery->idx, 3, " ", STR_PAD_LEFT ) . ' ->'.$gallery->text);
			}				
			
			// Add first JOPTION_ORDER_FIRST
			if (count($options) > 0)
			{
				// Merge any additional options in the XML definition.
				$options = array_merge(parent::getOptions(), $options);
			}
			
			// Append JText::_('JOPTION_ORDER_LAST')
			$options[] = array("value" => count($options),  
				"text" =>  JText::_('JOPTION_ORDER_LAST')); 
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}
		
		return $options;
    }
}
