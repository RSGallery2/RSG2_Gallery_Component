<?php
/**
* 
* @package Rsgallery2
* @copyright (C) 2016 - 2016 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author finnern
* RSGallery2 is Free Software
*/
// no direct access
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');

/*------------------------------------------------------------------------------------
ImageReference
--------------------------------------------------------------------------------------
 

------------------------------------------------------------------------------------*/

/**
 * Class ImageReferences
 */
class ImageReference
{
    /**
     * @var string
     */
    public $imageName;
    /**
     * @var string the path to the base file including image name. If exist first original, then display, thumb (? watermarked)
     *
     */
    public $imagePath;
    /**
     * @var bool
     */
    public $IsImageInDatabase;
    /**
     * @var bool
     */
    public $IsDisplayImageFound;
    /**
     * @var bool
     */
    public $IsOriginalImageFound;
    /**
     * @var bool
     */
    public $IsThumbImageFound;
    /**
     * @var bool
     */
    public $IsWatermarkedImageFound;

    /**
     * @var int
     */
    public $ParentGalleryId;
    
    /**
     * @var bool
     */
    public $UseWatermarked;

    //--- constants -----------------------------------------
    const dontCareForWatermarked = 0;
    const careForWatermarked = 0;

    /*------------------------------------------------------------------------------------
	__construct()
	------------------------------------------------------------------------------------*/
    /**
     * ImageReference constructor. init all variables
     */
    public function __construct()
    {
        $this->imageName = '';
        $this->imagePath = '';

        $this->IsImageInDatabase = false;
        $this->IsDisplayImageFound = false;
        $this->IsOriginalImageFound = false;
        $this->IsThumbImageFound = false;
        $this->IsWatermarkedImageFound = false;

        $this->ParentGalleryId = -1;

        $this->UseWatermarked = false;
    }

    /**
     * ImageReference constructor. Tells if watermarked images shall be checked too
     *
     * @param bool $watermarked
     */
    public function __construct1($watermarked) {

        __construct();
    
        $this->UseWatermarked = $watermarked;
    }

    /**
     * @return bool
     */
    public function IsAnyImageExisting ($careForWatermarked = ImageReference::dontCareForWatermarked) {
        $IsImageExisting =
               $this->IsDisplayImageFound
            || $this->IsOriginalImageFound
            || $this->IsThumbImageFound
            || $this->IsWatermarkedImageFound;

        // Image of watermarked is only counting when no other
        // image is missing.
        if ($careForWatermarked)
        {
            if ($this->UseWatermarked)
            {
                $IsImageExisting |= $this->IsWatermarkedImageFound;
            }
        }
        
        return $IsImageExisting;
    }

    /*
     *
     * watermarked images are not missing as such. watermarked images will be created when displaying image
     * @param bool $careForWatermarked
     * @return bool
     */
    public function IsMainImageMissing ($careForWatermarked = ImageReference::dontCareForWatermarked)
    {
        $IsImageMissing =
            !$this->IsDisplayImageFound
            || !$this->IsOriginalImageFound
            || !$this->IsThumbImageFound;

        // Image of watermarked is only counting when no other
        // image is missing.
        if ($careForWatermarked)
        {
            if ($this->UseWatermarked)
            {
                $IsImageMissing |= !$this->IsWatermarkedImageFound;
            }
        }

        return $IsImageMissing;
    }

 /*   
    public function IsWatermarkImageMissing () {
        return !$this->IsWatermarkedImageFound; 
    }
 */

}