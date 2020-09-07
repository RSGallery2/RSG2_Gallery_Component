<?php
/*
* @version $Id: gallery.php 1073 2012-05-14 12:35:41Z mirjam $
* @package RSGallery2
* @copyright (C) 2005 - 2016 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// import the list field type
jimport('joomla.html.html.list');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Gallery Form Field class to display an image related to
 * the actual edit view of the image
 */
class JFormFieldRsgImageDisplay extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	protected $type = 'RsgImageDisplay';

	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 *
	 * @return  string  The field HTML
	 */
	public function getInput()
	{

		// Determine image name. Try to get Thumb, display, or´riginal in this order

		// Create HTML layout ...

/**
		$db = JFactory::getDBO();
		//Get galleries for optionlist from database
		$query = 'SELECT id as gid, name'
		. ' FROM #__rsgallery2_galleries'
		. ' WHERE published = 1'
		. ' ORDER BY name'
		;
		$db->setQuery( $query );
		$galleries = $db->loadObjectList();
		
		//Add default option (no value)
		$options[] = JHtml::_('select.option', 0, JText::_('COM_RSGALLERY2_ROOT_GALLERY'));
		foreach($galleries as $gallery)
		{	
			$options[] = JHtml::_('select.option', $gallery->gid, $gallery->name);
		}
		$options = array_merge(parent::getOptions() , $options);

		return '<' . $type . ' id="' . $this->id . '" class="btn ' . $class . '" ' .
		$onclick . $url . $title . '>' .
		$icon .
		htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') .
		'</' . $type . '>';
/**/

		return '<h2>RsgImageDisplay is not ready yet</h2>';
	}
}