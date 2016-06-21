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
    
    public function IsAnyLocationExisting () {
        $IsImageExisting =
               $this->IsDisplayImageFound
            || $this->IsOriginalImageFound
            || $this->IsThumbImageFound
            || $this->IsWatermarkedImageFound;

        if($this->UseWatermarked)
        {
            $IsImageExisting |= $this->IsWatermarkedImageFound;
        }
        
        return $IsImageExisting;
    }

    public function IsOneLocationMissing () {
        $IsImageMissing =
               !$this->IsDisplayImageFound
            || !$this->IsOriginalImageFound
            || !$this->IsThumbImageFound;

        // Location of watermarked is only counting when no other
        // image is missing. But then there is an other image already
        // missing
/*        if($this->UseWatermarked)
        {
            $IsImageMissing |= !$this->IsWatermarkedImageFound;
        }
*/
        return $IsImageMissing;
    }

 /*   
    public function IsWatermarkLocationMissing () {
        return !$this->IsWatermarkedImageFound; 
    }
 */

}