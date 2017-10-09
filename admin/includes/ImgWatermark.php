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
 * Image watermarking class
 *
 * @package RSGallery2
 * @author  Ronald Smit <webmaster@rsdev.nl>
 */
class waterMarker extends GD2
{
    var $imagePath;                    //valid absolute path to image file
    var $waterMarkText;                //the text to draw as watermark
    var $font = "arial.ttf";    //font file to use for drawing text. need absolute path
    var $size = 10;            //font size
    var $angle = 45;            //angle to draw watermark text
    var $imageResource;                //to store the image resource after completion of watermarking
    var $imageType = "jpg";        //this could be either of png, jpg, jpeg, bmp, or gif (if gif then output will be in png)
    var $shadow = false;        //if set to true then a shadow will be drawn under every watermark text
    var $antialiased = true;        //if set to true then watermark text will be drawn anti-aliased. this is recommended
    var $imageTargetPath = '';        //full path to where to store the watermarked image to

    /**
     * this function draws the watermark over the image
     *
     * @param string $imageType
     */
    function mark($imageType = 'display')
    {
        global $rsgConfig;

        // A bit of housekeeping: we want an index.html in the directory storing these images
        if (!JFile::exists(JPATH_WATERMARKED . DS . 'index.html'))
        {
            $buffer = '';    //needed: Cannot pass parameter 2 [of JFile::write()] by reference...
            JFile::write(JPATH_WATERMARKED . DS . 'index.html', $buffer);
        }

        //get basic properties of the image file
        list($width, $height, $type, $attr) = getimagesize($this->imagePath);

        switch ($this->imageType)
        {
            case "png":
                $createProc = "imagecreatefrompng";
                $outputProc = "imagepng";
                break;
            case "gif";
                $createProc = "imagecreatefromgif";
                $outputProc = "imagepng";
                break;
            case "bmp";
                $createProc = "imagecreatefrombmp";
                $outputProc = "imagebmp";
                break;
            case "jpeg":
            case "jpg":
                $createProc = "imagecreatefromjpeg";
                $outputProc = "imagejpeg";
                break;
        }

        //create the image with generalized image create function
// ToDo FIX: $createProc maybe undefined ???
        $im = $createProc($this->imagePath);

        //create copy of image
        $im_copy = ImageCreateTrueColor($width, $height);
        ImageCopy($im_copy, $im, 0, 0, 0, 0, $width, $height);

        $grey        = imagecolorallocate($im, 180, 180, 180); //color for watermark text
        $shadowColor = imagecolorallocate($im, 130, 130, 130); //color for shadow text

        if (!$this->antialiased)
        {
            $grey *= -1; //grey = grey * -1
            $shadowColor *= -1; //shadowColor = shadowColor * -1
        }

        /**
         * Determines the position of the image and returns x and y
         * (1 = Top Left    ; 2 = Top Center    ; 3 = Top Right)
         * (4 = Left        ; 5 = Center        ; 6 = Right)
         * (7 = Bottom Left ; 8 = Bottom Center ; 9 = Bottom Right)
         *
         * @return x and y coordinates
         */
        $position = $rsgConfig->get('watermark_position');
        if ($rsgConfig->get('watermark_type') == 'text')
        {
            $bbox  = imagettfbbox($rsgConfig->get('watermark_font_size'), $rsgConfig->get('watermark_angle'), JPATH_RSGALLERY2_ADMIN . "/fonts/arial.ttf", $rsgConfig->get('watermark_text'));
            $textW = abs($bbox[0] - $bbox[2]) + 20;
            $textH = abs($bbox[7] - $bbox[1]) + 20;
        }
        else
        {
            //Get dimensions for watermark image
            list($w, $h, $t, $a) = getimagesize(JPATH_ROOT . DS . 'images' . DS . 'rsgallery' . DS . $rsgConfig->get('watermark_image'));
            $textW = $w + 20;
            $textH = $h + 20;
        }

        list($width, $height, $type, $attr) = getimagesize($this->imagePath); //get basic properties of the image file
        switch ($position)
        {
            case 1://Top Left
                $newX = 20;
                $newY = 0 + $textH;
                break;
            case 2://Top Center
                $newX = ($width / 2) - ($textW / 2);
                $newY = 0 + $textH;
                break;
            case 3://Top Right
                $newX = $width - $textW;
                $newY = 0 + $textH;
                break;
            case 4://Left
                $newX = 20;
                $newY = ($height / 2) + ($textH / 2);
                break;
            case 5://Center
                $newX = ($width / 2) - ($textW / 2);
                $newY = ($height / 2) + ($textH / 2);
                break;
            case 6://Right
                $newX = $width - $textW;
                $newY = ($height / 2) + ($textH / 2);
                break;
            case 7://Bottom left
                $newX = 20;
                $newY = $height - ($textH / 2);
                break;
            case 8://Bottom Center
                $newX = ($width / 2) - ($textW / 2);
                $newY = $height - ($textH / 2);
                break;
            case 9://Bottom right
                $newX = $width - $textW;
                $newY = $height - ($textH / 2);
                break;
        }

        if ($rsgConfig->get('watermark_type') == 'image')
        {
            //Merge watermark image with image
            $watermark = imagecreatefrompng(JPATH_ROOT . DS . 'images' . DS . 'rsgallery' . DS . $rsgConfig->get('watermark_image'));
            ImageCopyMerge($im, $watermark, $newX + 1, $newY + 1, 0, 0, $w, $h, $rsgConfig->get('watermark_transparency'));
        }
        else
        {
            //draw shadow text over image
            imagettftext($im, $this->size, $this->angle, $newX + 1, $newY + 1, $shadowColor, $this->font, $this->waterMarkText);
            //draw text over image
            imagettftext($im, $this->size, $this->angle, $newX, $newY, $grey, $this->font, $this->waterMarkText);
            //Merge copy and original image
            ImageCopyMerge($im, $im_copy, 0, 0, 0, 0, $width, $height, $rsgConfig->get('watermark_transparency'));
        }

        $fh = fopen($this->imageTargetPath, 'wb');
        fclose($fh);

        //deploy the image with generalized image deploy function
        $this->imageResource = $outputProc($im, $this->imageTargetPath, 100);
        imagedestroy($im);
        imagedestroy($im_copy);
        if (isset($watermark))
        {
            imagedestroy($watermark);
        }

    }

    /**
     * Function that takes an image and returns the url to watermarked image
     *
     * @param string  $imageName Name of the image in question
     * @param string  $imageType ImageType is either 'display' or 'original' and will precide the output filename
     * @param string  $font      Font used for watermark
     * @param boolean $shadow    Shadow text yes or no
     *
     * @return string url to watermarked image
     */
    static function showMarkedImage($imageName, $imageType = 'display', $font = "arial.ttf", $shadow = true)
    {
        global $rsgConfig, $mainframe;

        // ToDo: Don't know why image type can't be 'display' for creating watermarked file ? Just display on screen ??

        $watermarkFilename     = waterMarker::createWatermarkedFileName($imageName, $imageType);
        $watermarkPathFilename = waterMarker::PathFileName($watermarkFilename);

        if (!JFile::exists($watermarkPathFilename))
        {
            if ($imageType == 'display')
            {
                $imagePath = JPATH_DISPLAY . DS . $imageName . ".jpg";
            }
            else
            {
                $imagePath = JPATH_ORIGINAL . DS . $imageName;
            }

            $imark                  = new waterMarker();
            $imark->waterMarkText   = $rsgConfig->get('watermark_text');
            $imark->imagePath       = $imagePath;
            $imark->font            = JPATH_RSGALLERY2_ADMIN . DS . "fonts" . DS . $rsgConfig->get('watermark_font');
            $imark->size            = $rsgConfig->get('watermark_font_size');
            $imark->shadow          = $shadow;
            $imark->angle           = $rsgConfig->get('watermark_angle');
            $imark->imageTargetPath = $watermarkPathFilename;

            $imark->mark($imageType); //draw watermark
        }

        return trim(JURI_SITE, '/') . $rsgConfig->get('imgPath_watermarked') . '/' . $watermarkFilename;
    }

    /**
     * Function creates file name of watermarked image using MD5 on name
     * Three functions exists for the access of the filename to do the MD5 just once
     *
     * @param string $imageName Name of the image in question
     * @param string $imageType Image type is either 'display' or 'original' and will precide the output filename
     *
     * @return string MD5 name of watermarked image (example "displayc4cef3bababbff9e68015992ff6b8cbb.jpg")
     * @throws Exception
     */
    static function createWatermarkedFileName($imageName, $imageType)
    {

        $pepper = 'RSG2Watermarked';
        $app    = JFactory::getApplication();

        $salt     = $app->get('secret');
        $filename = $imageType . md5($pepper . $imageName . $salt) . '.jpg';

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
     * @param        $imageType
     *
     * @return string url to watermarked image
     */
    static function createWatermarkedPathFileName($imageName, $imageType)
    {
        $watermarkPathFilename = waterMarker::PathFileName(waterMarker::createWatermarkedFileName($imageName, $imageType));

        return $watermarkPathFilename;
    }

}//END CLASS WATERMARKER
