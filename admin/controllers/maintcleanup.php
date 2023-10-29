<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.maintCleanUp.php ');
}

jimport('joomla.application.component.controlleradmin');

/**
 * Clean up / remove of RSGallery2 data and files
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerMaintCleanUp extends JControllerAdmin
{

    /**
     * Constructor.
     *
     * @param   array $config An optional associative array of configuration settings.
     *
     * @since 4.3.0
     */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

    /**
     * Deletes all images and removes them from database
     *
     * @since 4.3.0
     */
	function purgeImagesAndData()
	{
		$msg     = "removeImagesAndData: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{
			//--- Delete all images -------------------------------

			$imageModel = $this->getModel('MaintImageFiles');
			$msg .= $imageModel->removeAllImageFiles();

			//--- delete images reference in database ---------------

			$imageModel = $this->getModel('MaintDatabaseTables');
			$msg .= $imageModel->removeDataInTables();

			//--- purge message -------------------------------------
			$msg .= '\n' . JText::_('COM_RSGALLERY2_PURGED', true);
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

    /**
     * Works like purgeImagesAndData
     *
     * @param string $name
     * @param string $prefix
     * @param array $config
     * @return bool|JModelLegacy
     *
     * @since 4.3.0
     */
	public function getModel($name = 'MaintCleanUp',
		$prefix = 'rsgallery2Model',
		$config = array())
	{
		$config ['ignore_request'] = true;
		$model                     = parent::getModel($name, $prefix, $config);

		return $model;
	}

    /**
     * Deletes all images and removes them from database and deletes Tabbles
     *  ToDo: Replace with "prepare complete uninstall"
     *
     * @since 4.3.0
     */
	function removeImagesAndData()
	{
		$msg     = "removeImagesAndData: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{
			//--- Delete all images -------------------------------

			$imageModel = $this->getModel('MaintImageFiles');
			$msg .= $imageModel->removeAllImageFiles();

            //--- delete images reference in database ---------------

            $imageModel = $this->getModel('MaintDatabaseTables');
            $msg .= $imageModel->removeDataInTables();

			//--- delete tables in database ---------------

			$imageModel = $this->getModel('MaintDatabaseTables');
			$msg .= $imageModel->removeAllTables();

			//--- purge message -------------------------------------
			$msg .= '\n' . JText::_('COM_RSGALLERY2_REAL_UNINST_DONE', true);

            // ToDo: Message you may now deinstall and reinstall ... as all data and tables are gone
		}

		//$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
		$this->setRedirect('index.php?option=com_installer', $msg, $msgType);
	}

}
