<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldRsgGalleryThumbList extends JFormFieldList {

    protected $type = 'RsgGalleryThumbList';

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

		$ActGalleryId = $this->form->getValue('id');
		$ActGalleryName = $this->form->getValue('name');

		// List of row number to gallery names
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true)
			->select($db->quoteName('a.id').' As idx, a.name As text')
			->from('#__rsgallery2_files AS a')
			->where($db->quoteName('gallery_id').'=' . (int) $ActGalleryId)
			->order($db->quoteName('a.id'));

		// Get the options.
		$db->setQuery($query);

		try
		{
			$images = $db->loadObjectList();

			// Create row number to Text = "Row number -> image name" assignment
			foreach($images as $image)
			{	
		        $options[] = array("value" => $image->idx, 
					"text" => $image->text.' ('.$ActGalleryName.')');				
		        //$options[] = array("value" => $image->idx, 
				//	"text" => $image->text);	
		        //$options[] = array("value" => $image->idx, "text" => str_pad($image->idx, 3, " ", STR_PAD_LEFT ) . ' ->'.$image->text);			
			}				
			
			// Add first '- Random thumbnail -'
			//if (count($options) > 0)
			{
				// Merge any additional options in the XML definition.
				$options = array_merge(parent::getOptions(), $options);
			}
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}
		
		return $options;
    }
}
