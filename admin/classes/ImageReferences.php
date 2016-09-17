<?php
/**
* access to the content of the 'install.mysql.utf8.sql' file
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

// access to the content of the install.mysql.utf8.sql file
require_once (JPATH_COMPONENT_ADMINISTRATOR . '/classes/ImageReference.php');


/*------------------------------------------------------------------------------------
ImageReferences
--------------------------------------------------------------------------------------
 

------------------------------------------------------------------------------------*/


/**
 * Class ImageReferences
 */
class ImageReferences
{
    /**
     * @var ImageReference [] 
     */
    protected $ImageReferenceList;

    /**
     * @var bool
     */
	protected $IsAnyImageMissingInDB;
    protected $IsAnyImageMissingInDisplay;
    protected $IsAnyImageMissingInOriginal;
    protected $IsAnyImageMissingInThumb;
    protected $IsAnyImageMissingInWatermarked;

	protected $IsAnyOneImageMissing;
    /**
     * @var []
     */
    protected $DbImageList; // 'name', 'gallery_id'
    /**
     * @var []
     */
    protected $DbImageNames; // Name in lower case ?

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
        $this->ImageReferences = array ();
        
        $this->IsAnyImageMissingInDB = false;
        $this->IsAnyImageMissingInDisplay = false;
        $this->IsAnyImageMissingInOriginal = false;
        $this->IsAnyImageMissingInThumb = false;
        $this->IsAnyImageMissingInWatermarked = false;
	    $this->IsAnyOneImageMissing = false;
	    
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

	public function getImageReferenceList () {

		// ??? if empty -> CollectImageReferences ...



		return $this->ImageReferenceList;
	}

	// shall only be used for IsAny...
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}


    /**
     * @return string message of creating the data if any 
     */
    public function CollectImageReferences ()
    {
        global $rsgConfig;

        $msg = '';

        //--- Collect data ----------------------------------------------------

        $this->DbImageList  = $this->getDbImageList ();  // Is tunneled to create it only once
        $this->DbImageNames = $this->getDbImageNames ();
        $this->DbImageNames = array_map('strtolower', $this->DbImageNames);

        $files_display  = $this->getFilenameArray($rsgConfig->get('imgPath_display'));
        $files_original = $this->getFilenameArray($rsgConfig->get('imgPath_original'));
	    $files_thumb    = $this->getFilenameArray($rsgConfig->get('imgPath_thumb'));

	    // Watermarked: Start with empty array
	    $files_watermarked = array ();
	    if($this->UseWatermarked)
	    {
		    $files_watermarked = $this->getFilenameArray($rsgConfig->get('imgPath_watermarked'));
	    }

		$files_merged    = array_unique(array_merge($this->DbImageNames, $files_display,
            $files_original, $files_thumb, $files_watermarked));

        //--- Create image data from collection -----------------------------------

        $msg .= $this->CreateImagesData ($files_merged, $this->DbImageNames,
                        $files_display, $files_original, $files_thumb, $files_watermarked);

        return $msg;
    }

    /**
     * @return string [] name / gallery ID
     */
    private function getDbImageList () {
        /*
				$database = JFactory::getDBO();
				//Load all image names from DB in array
				$sql = "SELECT name FROM #__rsgallery2_files";
				$database->setQuery($sql);
				$names_db = rsg2_consolidate::arrayToLower($database->loadColumn());
		*/
        $db = JFactory::getDbo();
        $query = $db->getQuery (true);

        $query->select($db->quoteName(array('name', 'gallery_id')))
            ->from($db->quoteName('#__rsgallery2_files'));

        $db->setQuery($query);
        $DbImageList =  $db->loadAssocList();

        return $DbImageList;
    }

    /**
     * @return string [] image file names
     */
    private function getDbImageNames () {
        /*
				$database = JFactory::getDBO();
				//Load all image names from DB in array
				$sql = "SELECT name FROM #__rsgallery2_files";
				$database->setQuery($sql);
				$names_db = rsg2_consolidate::arrayToLower($database->loadColumn());
		*/
        $db = JFactory::getDbo();
        $query = $db->getQuery (true);

        $query->select($db->quoteName('name'))
            ->from($db->quoteName('#__rsgallery2_files'));

        $db->setQuery($query);
        $DbImageNames =  $db->loadColumn();

        return $DbImageNames;
    }


    /**
     * Fills an array with the file names, found in the specified directory
     * @param string $dir Directory from Joomla root
     * @return array Array with file names
     */
    static function getFilenameArray($dir){
        global $rsgConfig;

        //Load all image names from filesystem in array
        $dh  = opendir(JPATH_ROOT.$dir);

        //Files to exclude from the check
        $exclude = array('.', '..', 'Thumbs.db', 'thumbs.db');
        $allowed = array('jpg','gif');
        $names_fs = array();

        while (false !== ($filename = readdir($dh))) {
            $ext = explode(".", $filename);
            $ext = array_reverse($ext);
            $ext = strtolower($ext[0]);
            if (!is_dir(JPATH_ROOT.$dir."/".$filename) AND !in_array($filename, $exclude) AND in_array($ext, $allowed))
            {
                if ($dir == $rsgConfig->get('imgPath_display') OR $dir == $rsgConfig->get('imgPath_thumb'))
                {
                    //Recreate normal filename, eliminating the extra ".jpg"
                    $names_fs[] = substr(strtolower($filename), 0, -4);
                }
                else
                {
                    $names_fs[] = strtolower($filename);
                }
            }
            else
            {
                //Do nothing
                continue;
            }
        }
        closedir($dh);
        return $names_fs;

    }

    /**
     * Changes all values of an array to lowercase
     * @param array $array mixed case mixed or upper case values
     * @return array lower case values
     */
    static function arrayToLower($array) {
        $array = explode("|", strtolower(implode("|",$array)));
        return $array;
    }


    /**
     * @param string [] $AllFiles file names
     * @param string [] $DbImageNames in lower case
     * @param $files_display
     * @param $files_original
     * @param $files_thumb
     * 
     * @return string Message
     */
    private function CreateImagesData ($AllFiles, $DbImageNames,
        $files_display, $files_original, $files_thumb, $files_watermarked)
    {
        global $rsgConfig;

        $this->ImageReferenceList = array();

        foreach ($AllFiles as $BaseFile)
        {
            $MissingImage = false;

            $ImagesData = new ImageReference();
            $ImagesData->imageName = $BaseFile;
	        $ImagesData->UseWatermarked = $this->UseWatermarked;

            if (in_array($BaseFile, $DbImageNames))
            {
                $ImagesData->IsImageInDatabase = true;
            }

            if (in_array($BaseFile, $files_display))
            {
                $ImagesData->IsDisplayImageFound = true;
            }

            if (in_array($BaseFile, $files_original))
            {
                $ImagesData->IsOriginalImageFound = true;
            }

	        if (in_array($BaseFile, $files_thumb))
	        {
		        $ImagesData->IsThumbImageFound = true;
	        }

	        if (in_array($BaseFile, $files_watermarked))
	        {
		        $ImagesData->IsWatermarkedImageFound = true;
	        }

	        //-------------------------------------------------
	        // Does file need to be handled ?
	        //-------------------------------------------------
	        // "dont care" used as watermarked images are not missing as such. watermarked images will be created when displaying image
            if ($ImagesData->IsMainImageMissing(ImageReference::dontCareForWatermarked) || !$ImagesData->IsImageInDatabase)
            {
                //--- ImagePath ----------------------------------------------------

                if ($ImagesData->IsImageInDatabase == true)
                {
                    $ImagesData->ParentGalleryId = $this->getParentGalleryIdFromImageName($BaseFile);
                }
                else
                {
                    // Not existing
                    // $ImagesData->ParentGalleryId = -1; // '0';
                }

                //--- ImagePath ----------------------------------------------------

                // Assign most significant (matching destination) image
                $ImagesData->imagePath = '';

                if ($ImagesData->IsOriginalImageFound)
                {
                    $ImagesData->imagePath = $rsgConfig->get('imgPath_original') . '/' . $ImagesData->imageName;
                }

                if ($ImagesData->IsDisplayImageFound)
                {
                    $ImagesData->imagePath = $rsgConfig->get('imgPath_display') . '/' . $ImagesData->imageName . '.jpg';
                }

	            if ($ImagesData->IsThumbImageFound)
	            {
		            $ImagesData->imagePath = $rsgConfig->get('imgPath_thumb') . '/' . $ImagesData->imageName . '.jpg';
	            }

	            if ($ImagesData->IsWatermarkedImageFound)
	            {
		            $ImagesData->imagePath = $rsgConfig->get('imgPath_watermarked') . '/' . $ImagesData->imageName; // . '.jpg';
	            }

	            $this->ImageReferenceList [] = $ImagesData;
            }
        }

        //--- Set column bits: Is one entry missing in column ? --------------------------------------

        $this->IsAnyImageMissingInDB = false;
        foreach ($this->ImageReferenceList as $ImageReference)
        {
            $this->IsAnyImageMissingInDB |= !$ImageReference->IsImageInDatabase;
        }

        $this->IsAnyImageMissingInDisplay = false;
        foreach ($this->ImageReferenceList as $ImageReference)
        {
            $this->IsAnyImageMissingInDisplay |= !$ImageReference->IsDisplayImageFound;
        }

        $this->IsAnyImageMissingInOriginal = false;
        foreach ($this->ImageReferenceList as $ImageReference)
        {
            $this->IsAnyImageMissingInOriginal |= !$ImageReference->IsOriginalImageFound;
        }

        $this->IsAnyImageMissingInThumb = false;
        foreach ($this->ImageReferenceList as $ImageReference)
        {
            $this->IsAnyImageMissingInThumb |= !$ImageReference->IsThumbImageFound;
        }

	    $this->IsAnyOneImageMissing = false;
	    foreach ($this->ImageReferenceList as $ImageReference)
	    {
		    // dont care as watermarked images are not missing as such. watermarked images will be created when displaying image
		    $this->IsAnyOneImageMissing |= $ImageReference->IsMainImageMissing(ImageReference::dontCareForWatermarked);
	    }
	    
        $this->IsAnyImageMissingInWatermarked = false;

        if ($this->UseWatermarked)
        {
            foreach ($this->ImageReferenceList as $ImageReference)
            {
                $this->IsAnyImageMissingInWatermarked |= !$ImageReference->IsWatermarkedImageFound;
            }
        }
        
        return '' ;
    }

    /**
     * @param string $BaseFile Name of image
     */
    private function getParentGalleryIdFromImageName ($BaseFile)
    {
        $ParentGalleryId = -1;

        $db = JFactory::getDbo();
        $query = $db->getQuery (true);

        $query->select($db->quoteName('gallery_id'))
            ->from($db->quoteName('#__rsgallery2_files'))
            ->where('name = ' . $db->quote($BaseFile));
        $db->setQuery($query);
        //$DbGalleryId =  $db->loadAssocList();
        $DbGalleryId =  $db->loadResult();

        if(! empty ($DbGalleryId))
        {
            $ParentGalleryId = $DbGalleryId;
        }

        return $ParentGalleryId;
    }
    /**/

    public function createDbEntries () {
        $msg = "model.createDbEntries: " . '<br>';




        //--- optimized message -------------------------------------
        $msg .= '<br>' . JText::_('COM_RSGALLERY2_MAINT_OPTIMIZE_SUCCESS', true);

        return $msg;
    }


	 
}