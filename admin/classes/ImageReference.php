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
    }

    /**
     * ImageReference constructor. Assigns name and path of base image
     *
     * @param string $imageName
     * @param string $imagePath
     */
    public function __construct2($imageName, $imagePath) {

        __construct();

        $this->imageName = $imageName;
        $this->imagePath = $imagePath;
    }

    public function IsAnyLocationExisting () {
        return
               $this->IsDisplayImageFound
            || $this->IsOriginalImageFound
            || $this->IsThumbImageFound
            || $this->IsWatermarkedImageFound;
    }

    public function IsOneLocationMissing () {
        return
            !$this->IsDisplayImageFound
            || !$this->IsOriginalImageFound
            || !$this->IsThumbImageFound;
//            || $this->IsWatermarkedImageFound;
    }

    public function IsWatermarkLocationMissing () {
        return !$this->IsWatermarkedImageFound;
    }

}