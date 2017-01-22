<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
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

//$this->tablelistNew = array('#__rsgallery2_galleries','#__rsgallery2_files','#__rsgallery2_comments','#__rsgallery2_config', '#__rsgallery2_acl');
//$this->tablelistOld = array('#__rsgallery','#__rsgalleryfiles','#__rsgallery_comments','');

jimport('joomla.application.component.controlleradmin');

/**
 *
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerMaintSql extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @since
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 *
	 */
	public function optimizeDB()
	{
		$msg     = '<strong>' . JText::_('COM_RSGALLERY2_MAINT_OPTDB') . ':</strong><br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintSql');
			$msg .= $model->optimizeDB();
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'maintSql', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/*
		public function createMissingSqlFields()
		{
			$msg = '<strong>' . JText::_('COM_RSGALLERY2_MAINT_OPTDB') . ':</strong><br>';
			$msg = "Ctrl:compareDb2SqlFile: ";
			$msgType = 'notice';

			// Access check
			$canAdmin	= JFactory::getUser()->authorise('core.manage',	'com_rsgallery2');
			if (!$canAdmin) {
				$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
				$msgType = 'warning';
				// replace newlines with html line breaks.
				str_replace('\n', '<br>', $msg);
			} else {

				// Model tells if successful
				$model = $this->getModel('maintSql');
				$msg .= $model->createMissingSqlFields();
			}

			$msg .= '!!! Not implemented yet !!!';

			$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
		}
	*/

	public function createGalleryAccessField()
	{
		$msg     = '<strong>' . JText::_('COM_RSGALLERY2_CREATE_GALLERY_ACCESS_FIELD') . ':</strong><br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintSql');
			$msg .= $model->createGalleryAccessField();
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	public function repairSqlTables()
	{
		$msg     = '<strong>' . JText::_('COM_RSGALLERY2_DATABASE_REPAIR_DESC') . ':</strong><br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintSql');
			$msg .= $model->repairSqlTables();
		}

		// Back to check of database
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintDatabase', $msg, $msgType);
	}

	public function updateCommentsVoting()
	{
		$msg     = '<strong>Ctrl:' . JText::_('updateCommentsVoting') . ':</strong><br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintSql');
			$msg .= $model->updateComments();
		}

		// Back to check of database
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

}

