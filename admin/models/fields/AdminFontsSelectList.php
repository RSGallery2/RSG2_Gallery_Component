<?php
/*
* @version $Id: GraficLibrarySelectList.php  $
* @package RSGallery2
* @copyright (C) 2005 - 2016 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 *  Select slideshow List Form Field class to create contents of dropdown box for 
 * usable grafic libraries on the system
 */
class JFormFieldAdminFontsSelectList extends JFormFieldList {
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	protected $type = 'AdminFontsSelectList';
	
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  An array of JHtml options.
	 */
	protected function getOptions() 
	{	
		$options = array();
		$admin_fonts = array();

		try
		{
            /**
             * Detect available slideshow 
             * Search source folders 
            */
/*
            $options[] = (object) array(
                'value' => $value,
                'text' => ($value != 0) ? JText::_('J' . $value) : JText::_('JALL'));

            $check = $this->value == $menu->value ? 'edit' : 'create';
/**/

			//Format values for slideshow dropdownbox
			$fonts = JFolder::files(JPATH_RSGALLERY2_ADMIN.DS.'fonts', 'ttf');
            foreach ($fonts as $font) {
                $admin_fonts[] = JHtml::_("select.option", $font, $font);
			}
		
            
//            $lists['$libraries'] = JHtml::_("select.genericlist",$libraries, 'graphicsLib', '', 'value', 'text', $rsgConfig->graphicsLib );
/**/


		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}
		
		
//		// Add select option (no value)
//		$options[] = JHtml::_('select.option', -1, JText::_('COM_RSGALLERY2_SELECT_GALLERY_FROM_LIST'));
//		foreach($galleries as $gallery)
//		{	
//			$options[] = JHtml::_('select.option', $gallery->gid, $gallery->name);
//		}
//		$options = array_merge(parent::getOptions() , $options);
		
		// Merge any additional options in the XML definition.
		// $options[] = JHtml::_('select.option', $key, $value);
		// $options[] = array("value" => 1, "text" => "1");


		$options = $admin_fonts;
		// Put "Select an option" on the top of the list.
		// array_unshift($options, JHtml::_('select.option', '0', JText::_('Select an option')));

		return array_merge(parent::getOptions(), $options);

	}

}

