<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2017 - 2017 RSGallery2
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

require_once JPATH_COMPONENT_ADMINISTRATOR . '/modelsimageFile.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermarkNames.php';

// ToDo: create class .....

class ImgWatermark
{
	/**
	 * Image watermarking class
	 *
	 * @package RSGallery2
	 * @author  Ronald Smit <webmaster@rsdev.nl>
	 */

	public function createWaterMarkImageFile($originalFileName)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$isCreated = false;

		// if (JFile::exists(JPATH_DISPLAY . '/' . $basename) || JFile::exists(JPATH_ORIGINAL . '/' . $basename)) {
		try
		{
			$ImageLib = $this->ImageLib;

			// ToDo: make separate functions in each grafics lib
			// Actual short cut : use GD
			// Use rsgConfig to determine which image library is loaded
			$graphicsLib = $rsgConfig->get('graphicsLib');
			// Use GD even if $graphicsLib is different
			if ($graphicsLib != 'gd2')
			{
				require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_GD.php';
				$ImageLib = new external_GD2;
			}


//			$IsImageCreated = $ImageLib->resizeImage($imgSrcPath, $imgDstPath, $maxSideImage);


			$baseName    = basename($originalFileName);
			$srcFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $baseName;
			$dstFileName = JPATH_ROOT . $rsgConfig->get('imgPath_watermarked') . '/' . $baseName;

			if ($Rsg2DebugActive)
			{
				JLog::add('==> createWatermarkFile: "' . $srcFileName . '" -> "' . $dstFileName . '"');
			}


			// seed is used ...
			// todo: copy and resize ...

			$isCreated = copy($srcFileName, $dstFileName);
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'createThumbFile: "' . $srcFileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isCreated;
	}

}

/**/

/**
 * Image watermarking class
 *
 * @package RSGallery2
 * @author  Ronald Smit <webmaster@rsdev.nl>
 */
class waterMarker extends Rsgallery2ImageFile // extends ???GD2
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
	function createMarker($imageType = 'display')
	{
		global $rsgConfig;

		$IsMarked   = false;
		$ErrorFound = false;

		try
		{

			$WatermarkedPath      = $rsgConfig->get('imgPath_watermarked');
			$WatermarkedIndexFile = $WatermarkedPath . '/index.html';
			// A bit of housekeeping: we want an index.html in the directory storing these images
			if (!JFile::exists($WatermarkedIndexFile))
			{
				$buffer = '';    //needed: Cannot pass parameter 2 [of JFile::write()] by reference...
				JFile::write($WatermarkedIndexFile, $buffer);
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
				default:
					$ErrorFound = true;

					$OutTxt = '';
					$OutTxt .= 'Error imageType for waterMarker: "' . '<br>';
					$OutTxt .= 'ImageType: "' . $this->imageType . '"' . '<br>';

					$app = JFactory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');
			}

			// Processes are defined
			if (!$ErrorFound)
			{
				//create the image with generalized image create function
				$im = $createProc($this->imagePath);

				//create copy of image
				$im_copy = ImageCreateTrueColor($width, $height);
				ImageCopy($im_copy, $im, 0, 0, 0, 0, $width, $height);

				$grey        = imagecolorallocate($im, 180, 180, 180); //color for watermark text
				$shadowColor = imagecolorallocate($im, 130, 130, 130); //color for shadow text

				if (!$this->antialiased)
				{
					$grey        *= -1; //grey = grey * -1
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
					$oWatermark = imagecreatefrompng(JPATH_ROOT . DS . 'images' . DS . 'rsgallery' . DS . $rsgConfig->get('watermark_image'));
					//ImageCopyMerge($im, $watermark, $newX + 1, $newY + 1, 0, 0, $w, $h, $rsgConfig->get('watermark_transparency'));
					imagecopymerge($im, $oWatermark, $newX + 1, $newY + 1, 0, 0, $w, $h, $rsgConfig->get('watermark_transparency'));
				}
				else
				{
					// 'watermark_type') == 'display

					//draw shadow text over image
					imagettftext($im, $this->size, $this->angle, $newX + 1, $newY + 1, $shadowColor, $this->font, $this->waterMarkText);
					//draw text over image
					imagettftext($im, $this->size, $this->angle, $newX, $newY, $grey, $this->font, $this->waterMarkText);
					//Merge copy and original image
					imagecopymerge($im, $im_copy, 0, 0, 0, 0, $width, $height, $rsgConfig->get('watermark_transparency'));
				}

				$fh = fopen($this->imageTargetPath, 'wb');
				fclose($fh);

				//deploy the image with generalized image deploy function
				$this->imageResource = $outputProc($im, $this->imageTargetPath, 100);
				imagedestroy($im);
				imagedestroy($im_copy);
// yyy
				if (isset($watermark))
				{
					imagedestroy($watermark);
				}


				$IsMarked = true;
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing waterMarker: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsMarked;
	}


}//END CLASS WATERMARKER
