<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

/**
 * Handles the file name and url of watermarked files
 * The file names are hidden with renaming using MD5 with additional text
 * The image name depends on image display type which is either 'display' or 'original'
 * 
 * @package     ${NAMESPACE}
 *
 * @since       4.3.2
 */
class ImgWatermarkNames
{
    /**
     * Function that takes an image name and returns the url to watermarked image
     * The image will be created if it does not exist
     * 
     * @param string  $imageName Name of the image in question
     * @param string  $imageOrigin is either 'display' or 'original' and will precide the output filename
     *
     * @return string url to watermarked image
     *
     * @since 4.3.2
     */
    static function watermarkUrl4Display($imageName, $imageOrigin = 'display')
    {
        global $rsgConfig;

	    //--- Create URL to watermarked file ------------------

	    $watermarkFilename     = self::createWatermarkedFileName($imageName, $imageOrigin);
        $watermarkUrl = trim(JURI_SITE, '/') . $rsgConfig->get('imgPath_watermarked') . '/' . $watermarkFilename;

        //--- Create watermarked file if not exist ------------------

	    $watermarkPathFilename = self::PathFileName($watermarkFilename);
        if (!JFile::exists($watermarkPathFilename))
        {
        	// waterMarker object
	        require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermark.php';
	        $ImgWatermark = new imgWatermark();

	        $isCreated = $ImgWatermark->createMarkedFromBaseName ($imageName, $imageOrigin);
	        if(!$isCreated)
	        {
		        $OutTxt = '';
		        $OutTxt .= 'Error calling createMarkedFromBaseName in watermarkUrl4Display: "' . '<br>';
		        $OutTxt .= '$imageName: "' . $imageName . '"' . '<br>';
		        $OutTxt .= '$imageOrigin: "' . $imageOrigin . '"' . '<br>';

		        $app = JFactory::getApplication();
		        $app->enqueueMessage($OutTxt, 'error');
	        }
	        /**/
        }

        return $watermarkUrl;
    }

    /**
     * Function creates file name of watermarked image using MD5 on name
     * Three functions exists for the access of the filename to do the MD5 just once
     *
     * @param string $imageName Name of the image in question
     * @param string $imageOrigin is either 'display' or 'original' and will precide the resulting filename
     *
     * @return string MD5 name of watermarked image (example "displayc4cef3bababbff9e68015992ff6b8cbb.jpg")
     * @throws Exception
     *
     * @since 4.3.2
     */
    static function createWatermarkedFileName($imageName, $imageOrigin)
    {

        $pepper = 'RSG2Watermarked';
        $app    = JFactory::getApplication();
        $salt     = $app->get('secret');

        $filename = $imageOrigin . md5($pepper . $imageName . $salt) . '.jpg';

        return $filename;
    }

    /**
     * Function adds the path to the given watermarked Md5 file name
     *
     * @param $watermarkFilename
     *
     * @return string url to watermarked image
     *
     * @since 4.3.2
     */
    static function PathFileName($watermarkFilename)
    {
        $watermarkPathFilename = JPATH_WATERMARKED . DS . $watermarkFilename;

        return $watermarkPathFilename;
    }

    /**
     * Function creates path and file name of watermarked image
     *
     * @param string $imageName Name of the image in question
     * @param        $imageOrigin
     *
     * @return string url to watermarked image
     *
     * @since 4.3.2
     */
    static function createWatermarkedPathFileName($imageName, $imageOrigin)
    {
        $watermarkPathFilename = self::PathFileName(waterMarker::createWatermarkedFileName($imageName, $imageOrigin));

        return $watermarkPathFilename;
    }

}
