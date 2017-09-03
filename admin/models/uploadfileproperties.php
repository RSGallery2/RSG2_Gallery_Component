<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017 - 2017 RSGallery2
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
        $files = array ();
	    $foundFiles = array ();

        // try ...

        $srcFolder = JPATH_ROOT . '/media/rsgallery2_' . $galleryId . '_' . $file_session_id;
        $srcUri = JURI::root() . 'media/rsgallery2_' . $galleryId . '_' . $file_session_id;
        // folder does exist
        if (is_dir($srcFolder)) {
            $foundFiles = array_diff(scandir($srcFolder), array('.', '..'));
        }

        foreach ($foundFiles as $file)
	    {
		    $files[] = $srcUri . '/' . $file;
	    }

        return $files;
    }

}

