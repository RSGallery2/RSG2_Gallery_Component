<?php
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

class Rsgallery2ControllerMaintSql extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
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
        $msg = "optimizeDB: ";
        $msgType = 'notice';

//		$msg .= '!!! Not implemented yet !!!';

		// Access check
        $canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
		if (!$canAdmin) {
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

			//--- optimize all tables -------------------------------
/**
			$app = JFactory::getApplication();
			$database = JFactory::getDBO();

			require_once(JPATH_ROOT . DS . "administrator" . DS . "components" . DS . "com_rsgallery2" . DS . "includes" . DS . "install.class.php");
			$install = new rsgInstall();
			$tables = $install->tablelistNew;
			foreach ($tables as $table) {
				$database->setQuery("OPTIMIZE TABLE $table");
				$database->execute();
			}
			$app->enqueueMessage( JText::_('COM_RSGALLERY2_MAINT_OPTIMIZE_SUCCESS') );
			$app->redirect("index.php?option=com_rsgallery2&amp;rsgOption=maintenance");
/**/

			$tables = $this->getTableListFromSqlFile();

			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			foreach ($tables as $table) {
				$msg .= 'Table ' . $table . '<br>';
				$db->setQuery('OPTIMIZE TABLE ' . $db->quoteName($table));
				$db->execute();
			}

			//--- optimized message -------------------------------------
            $msg .=  '<br>' . JText::_('COM_RSGALLERY2_MAINT_OPTIMIZE_SUCCESS', true );
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	/**
	 * Reads installed sql file to retrieve all table anmes
	 * ToDO: Actual the read of the file is simulated -> implement read ....
	 * @return string [] Table names
	 */
	private function getTableListFromSqlFile()
	{
		$TableList = array(
			'#__rsgallery2_galleries',
			'#__rsgallery2_files',
			'#__rsgallery2_comments',
			'#__rsgallery2_config',
			'#__rsgallery2_acl');

		// Read file to auto use in the future

		return $TableList;
	}

	function compareDb2SqlFile()
	{
		$msg = "compareDb2SqlFile: ";
		$msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function createGalleryAccessField()
	{
		$msg = "createGalleryAccessField: ";
		$msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}





}
