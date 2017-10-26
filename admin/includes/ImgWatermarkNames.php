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



// ToDo: create class .....


/**
 * Handles the file name and url of watermarked files
 * The file names are hidden with renaming using MD5 with additional text
 * Therefore access functions are needed
 * 
 * @package     ${NAMESPACE}
 *
 * @since       4,3,2
 */
class ImgWatermarkNames
{


    /**
     * Function that takes an image name and returns the url to watermarked image
     * The image will be created if it does not exist
     * 
     * @param string  $imageName Name of the image in question
     * @param string  $imageOrigin ImageType is either 'display' or 'original' and will precide the output filename
     * @param string  $font      Font used for watermark
     * @param boolean $shadow    Shadow text yes or no
     *
     * @return string url to watermarked image
     */
    // ToDo ??? rename to get WaltermarkedUrlAndCreate
    static function watermarkedUrl4Display($imageName, $imageOrigin = 'display', $font = "arial.ttf", $shadow = true)
    {
        global $rsgConfig;

        // ToDo: Don't know why image type can't be 'display' for creating watermarked file ? Just display on screen ??

        $watermarkFilename     = ImgWatermarkNames::createWatermarkedFileName($imageName, $imageOrigin);
        $watermarkPathFilename = ImgWatermarkNames::PathFileName($watermarkFilename);
        
        $watermerkUrl = trim(JURI_SITE, '/') . $rsgConfig->get('imgPath_watermarked') . '/' . $watermarkFilename;

        if (!JFile::exists($watermarkPathFilename))
        {
        	// ToDo: load model, create watermarked file
	        /**
	        if ($imageOrigin == 'display')
	        {
	        $imagePath = JPATH_DISPLAY . DS . $imageName . ".jpg";
	        }
	        else
	        {
	        $imagePath = JPATH_ORIGINAL . DS . $imageName;
	        }

	        require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermark.php';
	        $imark                  = new ImgWatermark();

            $imark->waterMarkText   = $rsgConfig->get('watermark_text');
            $imark->imagePath       = $imagePath;
            $imark->font            = JPATH_RSGALLERY2_ADMIN . DS . "fonts" . DS . $rsgConfig->get('watermark_font');
            $imark->size            = $rsgConfig->get('watermark_font_size');
            $imark->shadow          = $shadow;
            $imark->angle           = $rsgConfig->get('watermark_angle');
            $imark->imageTargetPath = $watermarkPathFilename;

            $imark->createrWatermark($imageOrigin); //draw watermark
	        /**/
        }

        return $watermerkUrl;
    }

    /**
     * Function creates file name of watermarked image using MD5 on name
     * Three functions exists for the access of the filename to do the MD5 just once
     *
     * @param string $imageName Name of the image in question
     * @param string $imageOrigin Image type is either 'display' or 'original' and will precide the output filename
     *
     * @return string MD5 name of watermarked image (example "displayc4cef3bababbff9e68015992ff6b8cbb.jpg")
     * @throws Exception
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
     */
    static function createWatermarkedPathFileName($imageName, $imageOrigin)
    {
        $watermarkPathFilename = ImgWatermarkNames::PathFileName(waterMarker::createWatermarkedFileName($imageName, $imageOrigin));

        return $watermarkPathFilename;
    }

}
