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
class JFormFieldExifTagSelectList extends JFormFieldList {
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	protected $type = 'ExifTagSelectList';
	
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  An array of JHtml options.
	 */
	protected function getOptions() 
	{
        global $rsgConfig;

        $options = array();
		$exif_selection = array();

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

            //Exif tags
            $exifTagsArray = array(
                "resolutionUnit" 		=> "Resolution unit",
                "FileName" 				=> "Filename",
                "FileSize" 				=> "Filesize",
                "FileDateTime" 			=> "File Date",
                "FlashUsed" 			=> "Flash used",
                "imageDesc" 			=> "Image description",
                "make" 					=> "Camera make",
                "model" 				=> "Camera model",
                "xResolution" 			=> "X Resolution",
                "yResolution" 			=> "Y Resolution",
                "software" 				=> "Software used",
                "fileModifiedDate" 		=> "File modified date",
                "YCbCrPositioning" 		=> "YCbCrPositioning",
                "exposureTime" 			=> "Exposure time",
                "fnumber" 				=> "f-Number",
                "exposure" 				=> "Exposure",
                "isoEquiv" 				=> "ISO equivalent",
                "exifVersion" 			=> "EXIF version",
                "DateTime" 				=> "Date & time",
                "dateTimeDigitized" 	=> "Original date",
                "componentConfig" 		=> "Component config",
                "jpegQuality" 			=> "Jpeg quality",
                "exposureBias" 			=> "Exposure bias",
                "aperture" 				=> "Aperture",
                "meteringMode" 			=> "Metering Mode",
                "whiteBalance" 			=> "White balance",
                "flashUsed" 			=> "Flash used",
                "focalLength" 			=> "Focal lenght",
                "makerNote" 			=> "Maker note",
                "subSectionTime" 		=> "Subsection time",
                "flashpixVersion" 		=> "Flashpix version",
                "colorSpace" 			=> "Color Space",
                "Width" 				=> "Width",
                "Height" 				=> "Height",
                "GPSLatitudeRef" 		=> "GPS Latitude reference",
                "Thumbnail" 			=> "Thumbnail",
                "ThumbnailSize" 		=> "Thumbnail size",
                "sourceType" 			=> "Source type",
                "sceneType" 			=> "Scene type",
                "compressScheme" 		=> "Compress scheme",
                "IsColor" 				=> "Color or B&W",
                "Process" 				=> "Process",
                "resolution" 			=> "Resolution",
                "color" 				=> "Color",
                "jpegProcess" 			=> "Jpeg process"
            );

            // Format selected items
            $exifSelected = explode("|", $rsgConfig->exifTags);
            foreach ($exifSelected as $select) {
                $exifSelect[] = JHtml::_("select.option",$select,$select);
            }

            //Format values for dropdownbox
            foreach ($exifTagsArray as $key=>$value) {
                $exif[] = JHtml::_("select.option",$key,$key);
            }

            $exif_selection = JHtml::_("select.genericlist", $exif, 'exifTags[]', 'MULTIPLE size="15"', 'value', 'text', $exifSelect );

//            $current_slideshows[] = JHtml::_("select.option", $folder, $folder);


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


		$options = $exif_selection;
		// Put "Select an option" on the top of the list.
		// array_unshift($options, JHtml::_('select.option', '0', JText::_('Select an option')));

		return array_merge(parent::getOptions(), $options);

	}


}

