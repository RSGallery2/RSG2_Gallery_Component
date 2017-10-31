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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/models/imageFile.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermarkNames.php';

/**
 * Image watermarking class
 *
 * @package RSGallery2
 * @author  Ronald Smit <webmaster@rsdev.nl>
 *
 * @since 4.3.2
 */
class rsgallery2ModelImgWaterMark extends JModelList  // extends rsgallery2ModelImageFile // extends ???GD2
{
	var $watermarkText     = ''; // the text to draw as watermark
	var $watermarkPath     = '';
	var $watermarkType     = 'text'; // Or image
	var $watermarkMergeImg = ''; //
	var $watermarkAngle    = 45; //angle to draw watermark text
	var $watermarkPosition = 5;  //Center;

	var $transparency      = 50;
	var $fontName          = 'arial.ttf'; //font file name to use for drawing text. need absolute path
	var $fontSize          = 10;
	var $antialiased       = true;    //if set to true then watermark text will be drawn anti-aliased. this is recommended

	var $originalPath      = ''; // where user original images are kept
	var $displayPath       = ''; // where user display images are kept
	// read once from config
	// var $imagePath;             //valid absolute path to image file
	// var $imageResource;         //to store the image resource after completion of watermarking

	/**
	 * imgWaterMark constructor.
	 *
	 * Collects the class attributes from RSGallery2 configuration
	 */
	public function __construct()
    {
	    global $rsgConfig;

	    parent::__construct();

	    $this->watermarkText     = $rsgConfig->get('watermark_text');
	    $this->watermarkPath     = $rsgConfig->get('imgPath_watermarked');
	    $this->watermarkType     = $rsgConfig->get('watermark_type');
	    $this->watermarkMergeImg = $rsgConfig->get('watermark_image');
	    $this->watermarkAngle    = $rsgConfig->get('watermark_angle');
	    $this->watermarkPosition = $rsgConfig->get('watermark_position');

	    $this->transparency      = $rsgConfig->get('watermark_transparency');
	    //$this->fontName          = JPATH_COMPONENT_ADMINISTRATOR . '/fonts/' . $rsgConfig->get('watermark_font');
	    $this->fontName          = $rsgConfig->get('watermark_font');
	    $this->fontSize          = $rsgConfig->get('watermark_font_size');
	    $this->antialiased       = true;

	    $this->originalPath      = $rsgConfig->get('imgPath_original');
	    $this->displayPath       = $rsgConfig->get('imgPath_display');

		// Write empty index.html file into watermark path if not existing
		self::writeWatermarkPathIndexFile ($this->watermarkPath);
    }

	/**
	 * Check for existing index file in watermark directory
	 *
	 * @param string $watermarkConfigPath given in short form from config it tells
	 *                                    where the watermarked files are kept
	 *
	 * @since 4.3.2
	 */
	private static function writeWatermarkPathIndexFile ($watermarkConfigPath='')
	{
		global $rsgConfig, $Rsg2DebugActive;

		if (empty ($watermarkConfigPath))
		{
			$watermarkConfigPath   = $rsgConfig->get('imgPath_watermarked');
		}

		try
		{
			$watermarkPath = JPATH_ROOT . $watermarkConfigPath;
			// ToDo: check if path exists  ...

			// ToDo: base path inside ...
			$WatermarkedIndexFile = $watermarkPath . '/index.html';
			// A bit of housekeeping: we want an index.html in the directory storing these images
			if (!JFile::exists($WatermarkedIndexFile))
			{
				if ($Rsg2DebugActive)
				{
					JLog::add('==> writeWatermarkPathIndexFile: "' . $watermarkPath . '"');
				}

				$buffer = '';    //needed: Cannot pass parameter 2 [of JFile::write()] by reference...
				JFile::write($WatermarkedIndexFile, $buffer);
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing writeWatermarkPathIndexFile path: "' . $WatermarkedIndexFile . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				JLog::add($OutTxt);
			}
		}
	}

	/**
	 * Checks if file is existing and is an image.
	 * ToDo: This function may be used in other classes: add to common library
	 * @param string $sourceFile Name and path to file
	 *
	 * @return array $imageSize ($srcWidth, $srcHeight, $srcType, ...) from getimagesize of source file
	 *               On empty file does not exist or is no image
	 *
	 * @since 4.3.2
	 */
	public static function isImageExisting($sourceFile=''): array
	{
		$imageSize = null;

		$isFileExisting = is_file($sourceFile);
		if ($isFileExisting)
		{
			// Get image attributes ($srcWidth, $srcHeight, $srcType, $srcAttr)
			$imageSize = getimagesize($sourceFile);
		}

		return $imageSize;
	}

	/**
	 * Depending on image type the creation functions are returned
	 * The first function is used to create the image in memory
	 * The second function  is used to create the resulting file
	 *
	 * Reason: In type Png matches out type of png best
	 *
	 * @param $imageType
	 *
	 * @return string[]|null  ($fncCreateImgMemory, $fncCreateImgFile)
	 *
	 * @since 4.3.2
	 */
	public static function imgCreateFunctions($imageType): array
	{
		// @var $imgFunctions Type[] */
		$imgFunctions = null;

		// define image handling functions (php procedures)
		switch ($imageType)
		{
			case IMAGETYPE_PNG: // "png":
				$fncCreateImgMemory = "imagecreatefrompng";
				$fncCreateImgFile   = "imagepng";
				$imgFunctions = array($fncCreateImgMemory, $fncCreateImgFile);
				break;
			case IMAGETYPE_GIF: //"gif";
				$fncCreateImgMemory = "imagecreatefromgif";
				$fncCreateImgFile   = "imagepng";
				$imgFunctions = array($fncCreateImgMemory, $fncCreateImgFile);
				break;
			case IMAGETYPE_BMP: //"bmp";
				$fncCreateImgMemory = "imagecreatefrombmp";
				$fncCreateImgFile   = "imagebmp";
				$imgFunctions = array($fncCreateImgMemory, $fncCreateImgFile);
				break;
			case IMAGETYPE_JPEG: // "jpeg" / "jpg":
				$fncCreateImgMemory = "imagecreatefromjpeg";
				$fncCreateImgFile   = "imagejpeg";
				$imgFunctions = array($fncCreateImgMemory, $fncCreateImgFile);
				break;
			// default:
		}

		return $imgFunctions;
	}

	/**
	 * @param $watermarkPosition
	 * @param $watermarkHeight
	 * @param $srcWidth
	 * @param $watermarkWidth
	 * @param $srcHeight
	 *
	 * @return array int[2] start of watermark x and y coordinates
	 *
	 *  @since 4.3.2
	 */
	public static function watermarkXY($watermarkPosition, $srcWidth, $srcHeight, $watermarkHeight, $watermarkWidth): array
	{
		/**
		 * Determines the position of the image and returns x and y
		 * (1 = Top Left    ; 2 = Top Center    ; 3 = Top Right)
		 * (4 = Left        ; 5 = Center        ; 6 = Right)
		 * (7 = Bottom Left ; 8 = Bottom Center ; 9 = Bottom Right)
		 *
		 */
		switch ($watermarkPosition)
		{
			case 1://Top Left
				$watermarkX = 20;
				$watermarkY = 0 + $watermarkHeight;
				break;
			case 2://Top Center
				$watermarkX = ($srcWidth / 2) - ($watermarkWidth / 2);
				$watermarkY = 0 + $watermarkHeight;
				break;
			case 3://Top Right
				$watermarkX = $srcWidth - $watermarkWidth;
				$watermarkY = 0 + $watermarkHeight;
				break;
			case 4://Left
				$watermarkX = 20;
				$watermarkY = ($srcHeight / 2) + ($watermarkHeight / 2);
				break;
			case 5://Center
			default:
				$watermarkX = ($srcWidth / 2) - ($watermarkWidth / 2);
				$watermarkY = ($srcHeight / 2) + ($watermarkHeight / 2);
				break;
			case 6://Right
				$watermarkX = $srcWidth - $watermarkWidth;
				$watermarkY = ($srcHeight / 2) + ($watermarkHeight / 2);
				break;
			case 7://Bottom left
				$watermarkX = 20;
				$watermarkY = $srcHeight - ($watermarkHeight / 2);
				break;
			case 8://Bottom Center
				$watermarkX = ($srcWidth / 2) - ($watermarkWidth / 2);
				$watermarkY = $srcHeight - ($watermarkHeight / 2);
				break;
			case 9://Bottom right
				$watermarkX = $srcWidth - $watermarkWidth;
				$watermarkY = $srcHeight - ($watermarkHeight / 2);
				break;
		}

		return array($watermarkX, $watermarkY);
	}

	/**
	 * Creates a watermarked file using the base file name instead of a full path.
	 * The full path name will be created in the standard way using $imageOrigin as source path
	 *
	 * @param string $imageName Just the name no path added
	 * @param string  $imageOrigin is either 'display' or 'original' and will precide the output filename
	 *
	 * @return bool
	 *
	 * @since 4.3.2
	 */
	public function createMarkedFromBaseName ($imageName='', $imageOrigin = 'display')
	{
		$isCreated = false;
		global $Rsg2DebugActive;

		// Not an image file name
		if (empty ($imageName))
		{
			$OutTxt = '';
			$OutTxt .= 'Error source file is not given for createMarkedFromBaseName: "' . '<br>';
			//$OutTxt .= 'sourceFile: "' . $sourceFile . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				JLog::add($OutTxt);
			}

			return $isCreated;
		}

		// source path file name
		if ($imageOrigin == 'display')
		{
			$srcImagePath = JPATH_ROOT . $this->displayPath . '/' . $imageName . ".jpg";
		}
		else
		{
			$srcImagePath = JPATH_ROOT . $this->originalPath . '/' . $imageName;
		}

		// destination  path file name
		$watermarkFilename = ImgWatermarkNames::createWatermarkedPathFileName($imageName, $imageOrigin);

		//--- create water marked file ------------------------
		$isCreated = $this->createMarkedFromFileNames ($srcImagePath, $watermarkFilename);

		return $isCreated;
	}

	/**
	 * Creates a watermarked file using the full path names
	 * for source and destination file. It uses the class
	 * variables as attributes (from config) to call the
	 * standard marking function
	 *
	 * @param string $sourceFile
	 * @param string $targetFile
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public function createMarkedFromFileNames ($sourceFile='', $targetFile='')
	{
		//$isCreated = false;

		$isCreated = $this->createMarkedFile (
			$sourceFile,
			$targetFile,  //	contains $imageOrigin

			//--- Class properties ---
			$this->watermarkText,
			$this->watermarkType,
			$this->watermarkAngle,
			$this->watermarkPosition,
			$this->watermarkMergeImg,

			$this->transparency,
			$this->fontName,
			$this->fontSize,
			$this->antialiased
		);

		return $isCreated;
	}


	/**
	 * Creates a watermarked image in $target path file.
	 * All used attributes can be set
	 * For direct call it is kept static with no direct
	 * object from class used
	 *
	 * @param string $sourceFile Name includes path
	 * @param string $targetFile
	 * @param string $watermarkText
	 * @param string $watermarkType
	 * @param int    $watermarkAngle
	 * @param int    $watermarkPosition
	 * @param string $watermarkMergeImg
	 * @param int    $transparency
	 * @param string $fontName
	 * @param int    $fontSize
	 * @param bool   $antialiased
	 *
	 * @return bool
	 *
	 * @since 4.3.2
	 */
	public static function createMarkedFile(
		$sourceFile='',
		$targetFile='',  //	contains $imageOrigin

		//--- Class properties ---
		$watermarkText = '--- Watermarked ---',
		$watermarkType = 'text', // or image
		$watermarkAngle = 45,            //angle to draw watermark text
		$watermarkPosition = 5, //Center
		$watermarkMergeImg = '',

		$transparency = 50,
		$fontName = "arial.ttf",    //font file to use for drawing text. need absolute path
		$fontSize = 10,             //font size
		$antialiased = true    //if set to true then watermark text will be drawn anti-aliased. this is recommended
	)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$IsMarked   = false;
		$ErrorFound = false;

		try
		{
			//---------------------------------------------------------
			// Does source exist ?
			//---------------------------------------------------------

			$imgSize = self::isImageExisting($sourceFile);
			// Is an image file
			if (!empty ($imgSize))
			{
				// Use returned attributes
				// ??? $size['mime'] ....
				list($srcWidth, $srcHeight, $srcType, $srcAttr) = $imgSize;
			}
			else
			{
				// Not an image file
				$ErrorFound = true;

				$OutTxt = '';
				$OutTxt .= 'Error source file is not an image for waterMarker: "' . '<br>';
				$OutTxt .= 'sourceFile: "' . $sourceFile . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');

				if ($Rsg2DebugActive)
				{
					JLog::add($OutTxt);
				}

				// for ide reasons :-)
				list($srcWidth, $srcHeight, $srcType, $srcAttr) = array('','','','');
			}

			//---------------------------------------------------------
			// Define image processing functions from type
			//---------------------------------------------------------

			// for ide reasons :-)
			list($fncCreateImgMemory, $fncCreateImgFile) = array('','');

			if (!$ErrorFound)
			{
				// list($fncCreateImgMemory, $fncCreateImgFile, $ErrorFound, $OutTxt, $app) = self::imgCreateFunctions($srcType);
				$imgFunctions = self::imgCreateFunctions($srcType);
				if (!empty ($imgFunctions))
				{
					list($fncCreateImgMemory, $fncCreateImgFile) = $imgFunctions;
				}
				else
				{
					$ErrorFound = true;

					$OutTxt = '';
					$OutTxt .= 'Error determining processing function for waterMarker: "' . '<br>';
					$OutTxt .= 'ImageType: "' . $srcType . '"' . '<br>';

					$app = JFactory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');

					if ($Rsg2DebugActive)
					{
						JLog::add($OutTxt);
					}
				}
			}

			//---------------------------------------------------------
			// Create memory image
			//---------------------------------------------------------

			// Processes are defined
			if (!$ErrorFound)
			{
				// Create memory image with generalized image create function
				$oImg = $fncCreateImgMemory($sourceFile);
				// No image created
				if (empty ($oImg))
				{
					$ErrorFound = true;

					$OutTxt = '';
					$OutTxt .= 'Error calling fncCreateImgMemory for waterMarker: "' . '<br>';
					$OutTxt .= 'fncCreateImgMemory: "' . $fncCreateImgMemory . '"' . '<br>';

					$app = JFactory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');

					if ($Rsg2DebugActive)
					{
						JLog::add($OutTxt);
					}
				}

				//---------------------------------------------------------
				// Create copy of image
				//---------------------------------------------------------

				// Image is created
				if (!$ErrorFound)
				{
					//create copy of image
					$oImgCopy = ImageCreateTrueColor($srcWidth, $srcHeight);
					ImageCopy($oImgCopy, $oImg, 0, 0, 0, 0, $srcWidth, $srcHeight);

					if ($watermarkType == 'text')
					{
						//---------------------------------------------------------
						// Dimensions of watermark
						//---------------------------------------------------------

						$fontFile = JPATH_RSGALLERY2_ADMIN . '/fonts/' . $fontName;

						$isFileExisting = is_file($fontFile);
						if (!$isFileExisting)
						{
							// file not found use standard
							$fontFile = JPATH_RSGALLERY2_ADMIN . '/fonts/arial.ttf';

							$OutTxt = '';
							$OutTxt .= 'Error calling imagecopymerge for waterMarker: "' . '<br>';
							$OutTxt .= '$watermarkText: "' . $watermarkText . '"' . '<br>';

							//$app = JFactory::getApplication();
							//$app->enqueueMessage($OutTxt, 'error');

							if ($Rsg2DebugActive)
							{
								JLog::add($OutTxt);
							}
						}

						$textBox         = imageTTFBbox($fontSize, $watermarkAngle, $fontFile, $watermarkText);
						$watermarkWidth  = abs($textBox[0] - $textBox[2]) + 20;
						$watermarkHeight = abs($textBox[7] - $textBox[1]) + 20;

						//---------------------------------------------------------
						// Watermark Position
						//---------------------------------------------------------

						list($watermarkX, $watermarkY) =
							self::watermarkXY($watermarkPosition, $srcWidth, $srcHeight, $watermarkHeight, $watermarkWidth);

						//---------------------------------------------------------
						// Merge Watermark
						//---------------------------------------------------------

						$grey        = imagecolorallocate($oImg, 180, 180, 180); //color for watermark text
						$shadowColor = imagecolorallocate($oImg, 130, 130, 130); //color for shadow text

						if (!$antialiased)
						{
							$grey        *= -1; //grey = grey * -1
							$shadowColor *= -1; //shadowColor = shadowColor * -1
						}

						//draw shadow text over image
						imagettftext($oImg, $fontSize, $watermarkAngle, $watermarkX + 1, $watermarkY + 1, $shadowColor, $fontName, $watermarkText);
						//draw text over image
						imagettftext($oImg, $fontSize, $watermarkAngle, $watermarkX, $watermarkY, $grey, $fontName, $watermarkText);
						//Merge copy and original image
						$ErrorFound = !imagecopymerge($oImg, $oImgCopy, 0, 0, 0, 0, $srcWidth, $srcHeight, $transparency);

						// Not merged
						if ($ErrorFound)
						{
							$OutTxt = '';
							$OutTxt .= 'Error calling imagecopymerge for waterMarker: "' . '<br>';
							$OutTxt .= '$watermarkText: "' . $watermarkText . '"' . '<br>';

							$app = JFactory::getApplication();
							$app->enqueueMessage($OutTxt, 'error');

							if ($Rsg2DebugActive)
							{
								JLog::add($OutTxt);
							}
						}
					}
					else
					{
						//---------------------------------------------------------
						// Dimensions of watermark
						//---------------------------------------------------------

						// ToDo: a) show selection of possible images (? smaller then ...) in config
						$mergeFile = JPATH_ROOT . '/images/rsgallery/' . $watermarkMergeImg;

						$isFileExisting = is_file($sourceFile);
						// file not found
						if (!$isFileExisting)
						{
							$ErrorFound = true;

							$OutTxt = '';
							$OutTxt .= 'Error calling imagecopymerge for waterMarker: "' . '<br>';
							$OutTxt .= '$mergeFile: "' . $mergeFile . '"' . ' does not exist<br>';

							$app = JFactory::getApplication();
							$app->enqueueMessage($OutTxt, 'error');

							if ($Rsg2DebugActive)
							{
								JLog::add($OutTxt);
							}
						}

						if (!$ErrorFound)
						{
							// Get dimensions for watermark image
							list($mergeWidth, $mergeHeight, $t, $a) = getimagesize($mergeFile);
							$watermarkWidth  = $mergeWidth + 20;
							$watermarkHeight = $mergeHeight + 20;

							//---------------------------------------------------------
							// Watermark Position
							//---------------------------------------------------------

							list($watermarkX, $watermarkY) =
								self::watermarkXY($watermarkPosition, $srcWidth, $srcHeight, $watermarkHeight, $watermarkWidth);

							//---------------------------------------------------------
							// Merge Watermark
							//---------------------------------------------------------

							//Merge watermark image with image
							$oWatermarkMerge = imagecreatefrompng($mergeFile);
							//ImageCopyMerge($oImg, $watermark, $watermarkX + 1, $watermarkY + 1, 0, 0, $mergeWidth, $mergeHeight, $rsgConfig->get('watermark_transparency'));
							$ErrorFound = !imagecopymerge($oImg, $oWatermarkMerge, $watermarkX + 1, $watermarkY + 1, 0, 0, $mergeWidth, $mergeHeight, $rsgConfig->get('watermark_transparency'));

							// Not merged
							if ($ErrorFound)
							{
								$OutTxt = '';
								$OutTxt .= 'Error calling imagecopymerge for waterMarker: "' . '<br>';
								$OutTxt .= '$mergeFile: "' . $mergeFile . '"' . '<br>';

								$app = JFactory::getApplication();
								$app->enqueueMessage($OutTxt, 'error');

								if ($Rsg2DebugActive)
								{
									JLog::add($OutTxt);
								}
							}
						}
					}

					// Merge successful
					if (!$ErrorFound)
					{
						//---------------------------------------------------------
						// Create watermarked file
						//---------------------------------------------------------

						// Create file
						$fh = fopen($targetFile, 'wb');
						fclose($fh);

						if ($fh === false)
						{
							$ErrorFound = true;

							$OutTxt = '';
							$OutTxt .= 'Error creating target file for waterMarker: "' . '<br>';
							$OutTxt .= '$targetFile: "' . $targetFile . '"' . '<br>';

							$app = JFactory::getApplication();
							$app->enqueueMessage($OutTxt, 'error');

							if ($Rsg2DebugActive)
							{
								JLog::add($OutTxt);
							}
						}

						// Open file successful
						if (!$ErrorFound)
						{
							//deploy the image with generalized image deploy function
							$IsMarked = $fncCreateImgFile($oImg, $targetFile, 100);

							// Not merged
							if (!$IsMarked)
							{
								$OutTxt = '';
								$OutTxt .= 'Error calling function "create.." file after merge for waterMarker: "' . '<br>';
								$OutTxt .= '$targetFile: "' . $targetFile . '"' . '<br>';

								$app = JFactory::getApplication();
								$app->enqueueMessage($OutTxt, 'error');

								if ($Rsg2DebugActive)
								{
									JLog::add($OutTxt);
								}
							}

							imagedestroy($oImg);
							imagedestroy($oImgCopy);
							if (isset($oWatermarkMerge))
							{
								imagedestroy($oWatermarkMerge);
							}
						}
					}
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing waterMarker: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				JLog::add($OutTxt);
			}
		}

		return $IsMarked;
	}


}//END CLASS WATERMARKER
