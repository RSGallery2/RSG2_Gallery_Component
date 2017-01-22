<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');

/**
 *
 */
class rsgallery2ModelUpload extends JModelLegacy  // JModelForm
{
	protected $text_prefix = 'COM_RSGallery2';

	protected $FtpPath;
	protected $LastUsedFtpPath;
	protected $isPreSelectLatestGallery;
	protected $LastUpdateType;
	protected $isUseOneGalleryNameForAllImages;

	// ToDo: Redesign: Extend from model rsgallery2ModelConfig, remove separate calls to db but keep function call here

	/**
	 * return given ftp_path in config
	 *
	 * @return string
	 */
	public function getFtpPath()
	{
		if (!isset($this->FtpPath))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('value'))
				->from($db->quoteName('#__rsgallery2_config'))
				->where($db->quoteName('name') . " = " . $db->quote('ftp_path'));

			$db->setQuery($query);
			$this->FtpPath = $db->loadResult();
		}

		return $this->FtpPath;
	}

	/**
	 * return last used ftp_path
	 *
	 * @return string
	 */
	public function getLastUsedFtpPath()
	{
		if (!isset($this->LastUsedFtpPath))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('value'))
				->from($db->quoteName('#__rsgallery2_config'))
				->where($db->quoteName('name') . " = " . $db->quote('last_used_ftp_path'));

			$db->setQuery($query);
			$this->LastUsedFtpPath = $db->loadResult();
		}

		return $this->LastUsedFtpPath;
	}

	/**
	 * allows to set the input LastUsedFtpPath
	 *
	 * @param string $NewLastUsedFtpPath
	 */
	public function setLastUsedFtpPath($NewLastUsedFtpPath)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->insert($db->quoteName('#__rsgallery2_config'))
			->columns($db->quoteName(array('name', 'value')))
			->values($db->quote('last_used_ftp_path') . ',' . $db->quote($NewLastUsedFtpPath));

		$db->setQuery($query);
		$db->execute();

		$this->LastUsedFtpPath = $NewLastUsedFtpPath;
	}

	/**
	 * retrieves state if the latest gallery shall be preseleted for upload
	 *
	 * @return bool
	 */
	public function getIsPreSelectLatestGallery()
	{
		if (!isset($this->isPreSelectLatestGallery))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('value'))
				->from($db->quoteName('#__rsgallery2_config'))
				->where($db->quoteName('name') . " = " . $db->quote('UploadPreselectLatestGallery'));

			$db->setQuery($query);
			$this->isPreSelectLatestGallery = $db->loadResult();
		}

		return $this->isPreSelectLatestGallery;
	}

	/**
	 * @return string ID of latest gallery
	 */
	public function getIdLatestGallery()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('id'))
			->from('#__rsgallery2_galleries')
			->order('ordering ASC');
//			->setLimit(1);  ==>  setQuery($query, $offset = 0, $limit = 0)

		$db->setQuery($query, 0, 1);
		$IdLatestGallery = $db->loadResult();

		return $IdLatestGallery;
	}

	/**
	 * @return string Name of last used update selection (register)
	 */
	public function getLastUpdateType()
	{
		if (!isset($this->LastUpdateType))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('value'))
				->from($db->quoteName('#__rsgallery2_config'))
				->where($db->quoteName('name') . " = " . $db->quote('last_update_type'));

			$db->setQuery($query);
			$this->LastUpdateType = $db->loadResult();
		}

		return $this->LastUpdateType;
	}

	/**
	 * allows to set the input LastUsedFtpPath
	 *
	 * @param string $NewLastUpdateType
	 */
	public function setLastUpdateType($NewLastUpdateType)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->insert($db->quoteName('#__rsgallery2_config'))
			->columns($db->quoteName(array('name', 'value')))
			->values($db->quote('last_update_type') . ',' . $db->quote($NewLastUpdateType));

		$db->setQuery($query);
		$db->execute();

		$this->LastUpdateType = $NewLastUpdateType;
	}

	/**
	 * retrieves state if the latest gallery shall be preseleted for upload
	 *
	 * @return bool
	 */
	public function getisUseOneGalleryNameForAllImages()
	{
		if (!isset($this->isUseOneGalleryNameForAllImages))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('value'))
				->from($db->quoteName('#__rsgallery2_config'))
				->where($db->quoteName('name') . " = " . $db->quote('isUseOneGalleryNameForAllImages'));

			$db->setQuery($query);
			$this->isUseOneGalleryNameForAllImages = $db->loadResult();
		}

		return $this->isUseOneGalleryNameForAllImages;
	}

}

 