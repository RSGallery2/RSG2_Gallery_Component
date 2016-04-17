<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
/**
 * 
 */
class rsgallery2ModelMaintConsolidateDB extends  JModelList
{


    public function GetDisplayImageData ()
    {



        return $this->GetDummyDisplayImageData ();
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




    public function GetDummyDisplayImageData () {


            $DisplayImageData = array ();

        return;

        $ImagesData = [];
        $ImagesData['imageName'] = 'image1';
        $ImagesData['IsImageInDatabase'] =  true;
        $ImagesData['IsDisplayImageFound'] =  false;
        $ImagesData['IsOrignalImageFound'] =  false;
        $ImagesData['IsThumbImageFound'] =  false;
        $ImagesData['IsWatermarkImageFound'] =  false;
        $ImagesData['ParentGalleryId'] =  '1';

        $DisplayImageData [] = $ImagesData;

        $ImagesData = [];
        $ImagesData['imageName'] = 'image2';
        $ImagesData['IsImageInDatabase'] =  true;
        $ImagesData['IsDisplayImageFound'] =  true;
        $ImagesData['IsOrignalImageFound'] =  true;
        $ImagesData['IsThumbImageFound'] =  true;
        $ImagesData['IsWatermarkImageFound'] =  false;
        $ImagesData['ParentGalleryId'] =  '2';

        $DisplayImageData [] = $ImagesData;

        $ImagesData = [];
        $ImagesData['imageName'] = 'image3';
        $ImagesData['IsImageInDatabase'] =  true;
        $ImagesData['IsDisplayImageFound'] =  false;
        $ImagesData['IsOrignalImageFound'] =  false;
        $ImagesData['IsThumbImageFound'] =  false;
        $ImagesData['IsWatermarkImageFound'] =  false;
        $ImagesData['ParentGalleryId'] =  '3';

        $DisplayImageData [] = $ImagesData;





        return $DisplayImageData;
    }



}
