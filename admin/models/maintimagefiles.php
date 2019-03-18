<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * image file access function for maintenance
 *
 * @since 4.3.0
 */
class Rsgallery2ModelMaintImageFiles extends JModelList
{
//    protected $text_prefix = 'COM_RSG2';

	protected $imagePath_thumb;
	protected $imagePath_display;
	protected $imagePath_original;
	protected $imagePath_watermarked;

    /**
     * Remove all image files of RSG2
     *
     * @return string Success message per folder
     *
     * @since 4.3.0
     */
	public function removeAllImageFiles()
	{
		$msg = "Remove image files: \n";

		// assign class variables
		$this->getImagePaths();

		$msg .= $this->RemoveImagesInFolder($this->imagePath_thumb) . '<br>';
		$msg .= $this->RemoveImagesInFolder($this->imagePath_display) . '<br>';
		$msg .= $this->RemoveImagesInFolder($this->imagePath_original) . '<br>';
		$msg .= $this->RemoveImagesInFolder($this->imagePath_watermarked) . '<br>';

		return $msg;
	}

    /**
     * Remove image files in given folder
     *
     * @param string $fullPath Path to images to be deleted
     *
     * @return string Success message of folder
     *
     * @since 4.3.0
     */
	private function RemoveImagesInFolder($fullPath = '')
	{
		$msg = 'Remove images in folder: "' . $fullPath . '"';

		//--- valid path ? ----------------------------------

		if (empty ($fullPath))
		{
			$errMsg = JText::_('COM_RSGALLERY2_FOLDER_DOES_NOT_EXIST');
			$msg .= "\n" . $errMsg;

			JFactory::getApplication()->enqueueMessage($msg, 'error');

			return $msg;
		}

		// Check that path is valid
		if (!is_dir($fullPath))
		{
			$errMsg = JText::_('COM_RSGALLERY2_FOLDER_DOES_NOT_EXIST') . ': "' . $fullPath . '""';
			$msg .= "\n" . $errMsg;

			JFactory::getApplication()->enqueueMessage($msg, 'error');

			return $msg;
		}

		/* ToDo: check that path is valid and not a base path to "anywhere"
		if(! ($fullPath == JPATH_ROOT))
		{
			$errMsg = JText::_('COM_RSGALLERY2_FOLDER_DOES_NOT_EXIST') . ': "' . $fullPath . '""';
			$msg .= "\n" . $errMsg;

			JFactory::getApplication()->enqueueMessage($msg, 'error');
			return $msg;
		}
		/**/

		//--- remove display images ------------------------

		try
		{
			foreach (glob($fullPath . '\*') as $filename)
			{
				if (is_file($filename))
				{
					unlink($filename);
				}
			}

			$msg .= ' successful';
		}
		catch (Exception $e)
		{
			$msg .= '. error found: ' . $e->getMessage();
		}

		return $msg;
	}

	// ToDo: Move to model image path and inherit from there or rename this class ...
    // ToDo: Add try catch
    /**
     * Retrieve all pathes to RSG2 image files from database
     *
     * @since 4.3.0
     */
	private function getImagePaths()
	{
		// ToDo: Throws .... \Jdatabaseexception ....

		// preset if following code fails
		$this->imagePath_thumb       = '';
		$this->imagePath_display     = '';
		$this->imagePath_original    = '';
		$this->imagePath_watermarked = '';

		//--- thumb -------------------------------------
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('value'))
			->from($db->quoteName('#__rsgallery2_config'))
			->where($db->quoteName('name') . " = " . $db->quote('imgPath_thumb'));

		$db->setQuery($query);
		$Path = $db->loadResult();
		if (strlen(trim($Path)) > 0)
		{
			$this->imagePath_thumb = JPATH_ROOT . $Path;
		}

		//--- display -------------------------------------
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('value'))
			->from($db->quoteName('#__rsgallery2_config'))
			->where($db->quoteName('name') . " = " . $db->quote('imgPath_display'));

		$db->setQuery($query);
		$Path = $db->loadResult();
		if (strlen(trim($Path)) > 0)
		{
			$this->imagePath_display = JPATH_ROOT . $Path;
		}

		//--- original -------------------------------------
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('value'))
			->from($db->quoteName('#__rsgallery2_config'))
			->where($db->quoteName('name') . " = " . $db->quote('imgPath_original'));

		$db->setQuery($query);
		$Path = $db->loadResult();
		if (strlen(trim($Path)) > 0)
		{
			$this->imagePath_original = JPATH_ROOT . $Path;
		}

		//--- water marked -------------------------------------
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('value'))
			->from($db->quoteName('#__rsgallery2_config'))
			->where($db->quoteName('name') . " = " . $db->quote('imgPath_watermarked'));

		$db->setQuery($query);
		$Path = $db->loadResult();
		if (strlen(trim($Path)) > 0)
		{
			$this->imagePath_watermarked = JPATH_ROOT . $Path;
		}

		/* ToDo ?
        if($db->getErrorMsg()){
            $msf = $msg . $db->getErrorMsg();
        }
        else{
            $msg = $successMsg;
        }
		
        return $msg;
		/**/
	}

	/**
	 *
	 *
	 * @return string Success message per folder
	 *
	 * @since 4.4.2
	 */
	public function repairImagePermissions()
	{
		//$msg = "Repaired image permissions: \n";
		$msg = "";

		// assign class variables
		$this->getImagePaths();

		$msg .= 'thumb ' . $this->repairImagePermissionsInFolder($this->imagePath_thumb) . '<br>';
		$msg .= 'display ' . $this->repairImagePermissionsInFolder($this->imagePath_display) . '<br>';
		$msg .= 'original ' . $this->repairImagePermissionsInFolder($this->imagePath_original) . '<br>';
		$msg .= 'watermarked ' . $this->repairImagePermissionsInFolder($this->imagePath_watermarked) . '<br>';

		return $msg;
	}

	/**
	 *
	 *
	 * @return string Success message per folder
	 *
	 * @since 4.4.2
	 */
	public function repairImagePermissionsInFolder($imgFolder)
	{
		$count = 0;
		$msg = "";

		// ? folder exists

		// all files
		$files = new DirectoryIterator($imgFolder);
		foreach ($files as $file)
		{
			if ($file->isFile())
			{
				$pathFileName = $file->getPathname();
				$filePermission = JPath::getPermissions($pathFileName);
				$mode = @ decoct(@ fileperms($pathFileName) & 0777);

				if ($mode != '644')
				{
					JPath::setPermissions($pathFileName, '0644');
					//$msg .= $pathFileName . ":" . $filePermission . ":" . $mode . ", ";
					$count += 1;
				}
}