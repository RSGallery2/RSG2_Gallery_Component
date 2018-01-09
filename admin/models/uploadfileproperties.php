<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * maintenance consolidate image database
 *
 * Checks for all appearances of a images as file or in database
 * On missing database entries or files the user gets a list
 * to choose which part to fix
 *
 * @since 4.3.0
 */
class rsgallery2ModeluploadFileProperties extends JModelList
{

	/**
     * Image artefacts as list
     * Each entry contains existing image objects where at least one is missing
     *
	 * @var yyy
     *
     * @since 4.3.0
     */
	protected $yyy;

    /**
     * Returns List of image "artefacts"
     *
     * @return yyy
     *
     * @since 4.3.0
     */
	public function Getyyy()
	{
		if (empty($this->yyy))
		{
			$this->CreateDisplayImageData();
		}

		return $this->yyy;
	}


	// $files = scandir($path);
    // $files = array_diff(scandir($path), array('.', '..'));

    public function RetrieveFileData ($galleryId=0, $file_session_id)
    {
	    $fileData = new stdClass();
	    $fileUrls = array ();
	    $fileNames = array (); // short name with extension
	    $filePathNames = array (); // complete path
	    $titles = array (); // short name with extension
	    $descriptions  = array ();

	    $foundFiles = array ();

        // try ...

        $srcFolder = JPATH_ROOT . '/media/rsgallery2_' . $galleryId . '_' . $file_session_id;
        $srcUri = JURI::root() . 'media/rsgallery2_' . $galleryId . '_' . $file_session_id;
        // folder does exist
        if (is_dir($srcFolder)) {
            $foundFiles = array_diff(scandir($srcFolder), array('.', '..'));
        }

        $Idx = 0;
        foreach ($foundFiles as $file)
	    {
		    $fileUrls[] = $srcUri . '/' . $file;
		    $fileNames[] = $file; // short name with extension
		    $filePathNames[] = $srcFolder . '/' . $file; // complete path
		    $title ['titles[]'] = $file;
		    //$title ['titles[' . $Idx . ']'] = $file;
		    $titles[] = $title;

		    $descriptions ['descriptions[]'] = $srcUri . '/' . $file;
		    //$descriptions ['descriptions[]'][$Idx] = $srcUri . '/' . $file; -> html code
		    //$descriptions ['descriptions'][$Idx] = $srcUri . '/' . $file;

		    $Idx++;
	    }

	    if ($Idx==0) {
        	echo 'No files found in :"' . $srcFolder . '"<br><br>';
	    }

	    // Return values
	    $fileData->fileUrls  = $fileUrls;
	    $fileData->fileNames = $fileNames;
	    $fileData->titles = $titles;
	    $fileData->descriptions = $descriptions;
	    $fileData->filePathNames = $filePathNames;

        return $fileData;
    }

}

