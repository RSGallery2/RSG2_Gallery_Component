<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldParentGalleryList extends JFormFieldList {

    protected $type = 'ParentGalleryList';

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

		$ActGalleryId = (string) $this->element['id'];
		
		// $user = JFactory::getUser();
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true)
			->select('id As value, name As text')
			->from('#__rsgallery2_galleries AS a')
			->where('id !=' . (int) $ActGalleryId)
			->order('a.name');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());

            // toDo: Check out catch with enqueueMessage($e->getMessage());
            JFactory::getApplication()->enqueueMessage($e->getMessage());

            return false;

		}
		
		// Merge any additional options in the XML definition.
		// $options[] = JHtml::_('select.option', $key, $value);
		$options = array_merge(parent::getOptions(), $options);
		
		return $options;
    }
}
