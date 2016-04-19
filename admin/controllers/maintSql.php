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
	 * Proxy for getModel.
	 */
	public function getModel($name = 'maintSql', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
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

// ToDO: move to model !!!


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

		/*
			1054 Unknown column 'access' in 'field list' SQL=INSERT INTO `afs_rsgallery2_galleries`
		         (`id`,`parent`,`name`,`alias`,`description`,`published`,`checked_out`,
		          `checked_out_time`,`ordering`,`hits`,`date`,`params`,`user`,`uid`,`allowed`,
		          `thumb_id`,`access`) VALUES ('','0','fdtgsdg','fdtgsdg','','1','','','0','',
		          '2015-10-01 16:18:23','','','45','','','1')

			After that i simply go through to afs_rsgallery2_galleries table and create a new field
		    called "access" and then set then value "1" for access via update query.
		    And my gallery images make a path or url...
		*/


/*
		$model = $this->getModel('Config');
		$item=$model->save($key);
*/


// ToDO: move to model !!!

		$table  = '#__rsgallery2_galleries';

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query = 'SHOW COLUMNS FROM ' . $table;
		$db->setQuery($query);
		$ColumnExist = $db->loadObject();

		/*
                $result = mysql_query("SHOW COLUMNS FROM `table` LIKE 'fieldname'");
                $exists = (mysql_num_rows($result))?TRUE:FALSE;

				$result = 'SHOW COLUMNS IN ' . $wordArray[2] . ' WHERE field = ' . $this->fixQuote($wordArray[5]);
				$this->queryType = 'ADD_COLUMN';
				$this->msgElements = array($this->fixQuote($wordArray[2]), $this->fixQuote($wordArray[5]));

		*/




		/*
		   `access` int(10) unsigned DEFAULT NULL,

			ALTER TABLE yourtable ADD q6 VARCHAR( 255 ) after q5

		 	$table  = 'your table name';
 			$column = 'q6'
 			$add = mysql_query("ALTER TABLE $table ADD $column VARCHAR( 255 ) NOT NULL");
        */

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}





}
