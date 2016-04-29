<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

// Joel Lipman Jdatabase


/**
 * 
 */
class Rsgallery2ModelMaintSql extends  JModelList
{
//    protected $text_prefix = 'COM_RSG2';

	protected $tableList;
	/**
	 *
	 */
	public function optimizeDB()
	{
		$msg = "model:optimizeDB: " . '<br>';

		$tables = $this->getTableListFromSqlFile();

		$db = JFactory::getDbo();

		//--- optimize all tables -------------------------------

		foreach ($tables as $table) {
			$msg .= 'Table ' . $table . '<br>';
			$db->setQuery('OPTIMIZE TABLE ' . $db->quoteName($table));
			$db->execute();
		}

		//--- optimized message -------------------------------------
		$msg .=  '<br>' . JText::_('COM_RSGALLERY2_MAINT_OPTIMIZE_SUCCESS', true );

		return $msg;
	}



	public function createGalleryAccessField()
	{
		// $msg = "Model: createGalleryAccessField: " . '<br>';
		$msg = '';

		/*  RSGallery2 user
			1054 Unknown column 'access' in 'field list' SQL=INSERT INTO `afs_rsgallery2_galleries`
		         (`id`,`parent`,`name`,`alias`,`description`,`published`,`checked_out`,
		          `checked_out_time`,`ordering`,`hits`,`date`,`params`,`user`,`uid`,`allowed`,
		          `thumb_id`,`access`) VALUES ('','0','fdtgsdg','fdtgsdg','','1','','','0','',
		          '2015-10-01 16:18:23','','','45','','','1')

			After that i simply go through to afs_rsgallery2_galleries table and create a new field
		    called "access" and then set then value "1" for access via update query.
		    And my gallery images make a path or url...
		*/

		$table  = '#__rsgallery2_galleries';
		$ColumnName = 'access';

		$ColumnExist = IsColumnExisting($table, $ColumnName);

		// !!! test code -> delete actual column (field)
		if ($ColumnExist) {

			$result = $this->DeleteColumn($table, $ColumnName);
			$msg .= '<br>' . '$result (drop): ' . json_encode ($result);

			$ColumnExist = false;
		}


		// Create table column
		if (!$ColumnExist)
		{
			$ColumnProperties = 'INT  (10) UNSIGNED DEFAULT NULL';
			createNotExistingColumn($table, $ColumnName, $ColumnProperties, $ColumnExist);
		}

		// Set all access values to '1'
		if ($ColumnExist)
		{

			$db = JFactory::getDbo();
			// update your_table set likes = null
			$query = 'UPDATE ' . $table . ' SET ' . $ColumnName . '=1';
			$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$result = $db->execute();
			$msg .= '<br>' . '$result (update): ' . json_encode ($result);
		}
		return $msg;
	}


	// *
	private function DeleteColumn($table, $ColumnName)
	{
		$db = JFactory::getDbo();
		// ALTER TABLE t2 DROP COLUMN c, DROP COLUMN d;
		$query = 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $ColumnName ;
		// $msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$result = $db->execute();

		return $result;
	}

	// *
	private function IsColumnExisting($table, $ColumnName)
	{
		$IsColumnExisting = false;

		$db = JFactory::getDbo();
		$query = 'SHOW COLUMNS FROM ' . $table . ' LIKE ' . $db->quote($ColumnName) ;
		// $msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$AccessField = $db->loadObject();
		$IsColumnExisting = isset($AccessField);
		// $msg .= '<br>' . '$ColumnExist: ' . json_encode ($ColumnExist);

		return $IsColumnExisting;
	}

	// *
	public function createNotExistingColumn($table, $ColumnName, $ColumnProperties, &$IsColumnCreated)
	{
		$msg = "Model: createNotExistingColumn: ";

		$db = JFactory::getDbo();

		$query = 'ALTER TABLE ' . $table . ' ADD ' . $ColumnName . ' ' . $ColumnProperties;
		$msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$IsColumnCreated = $db->execute();
		$msg .= '<br>' . '$IsColumnCreated (Added): ' . json_encode ($IsColumnCreated);

		return $msg . '<br>';
	}


	/**
	 * Original table names
	 * Reads installed sql file to retrieve all table anmes
	 * ToDO: Actual the read of the file is simulated -> implement read ....
	 * @return string [] Table names
	 */
	private function getTableListFromSqlFile()
	{
		// Create only once
		if (empty ($this->tableList)) {
			// ToDo: Read file to auto use in the future
			$this->tableList = array(
				'#__rsgallery2_galleries',
				'#__rsgallery2_files',
				'#__rsgallery2_comments',
				'#__rsgallery2_config',
				'#__rsgallery2_acl');
		}

		return $this->tableList;
	}


	public function completeSqlTables()
	{
		$msg = 'model:completeSqlTables: ' . '<br>';

		//--- Check for not existing tables and create them -----------
		$msg .= $this->createMissingSqlTables ();

		//--- Check for not existing tables and create them --------
		$tables = $this->getTableListFromSqlFile();
		foreach ($tables as $table) {
			$msg .= $this->createMissingSqlFieldsinTable($table);
		}
		
		$msg .= '!!! Not implemented yet !!!' . '<br>';

		return $msg;
	}


	public function createMissingSqlTables()
	{
		$msg = "Model: createMissingSqlTables: " . '<br>';
		// $msg = '';

		// Original table names
		$tables = $this->getTableListFromSqlFile();

		$msg .= 'Check for missing tables<br>';

		//--- creates all tables if not exist ----------------------

		foreach ($tables as $table) {
			$msg .= '   Table ' . $table . '<br>';

			// Create table column
			$TableExist = $this->IsTableExisting($table);
			if (!$TableExist) {
				$msg .= $this->createNotExistingTable($table);
			}
		}

		$msg .= 'Check for missing files (columns) in tables<br>';
		
		return $msg;
	}
	public function createMissingSqlFieldsinTable($table)
	{
		$msg = "Model: createMissingSqlFields: " . '   Table ' . $table . '<br>';

		$msg .= 'Check for missing COLUMN IN TABLE <br>';

		//--- create not existing columns if not exist -----------------

        // Original columns
        $columns = $this->getColumnsPropertiesOfTable($table);

        // Create all not existing table columns
        foreach ($columns as $ColumnName => $ColumnProperties)
        {
            // Create table column
            $ColumnExist = $this->IsColumnExisting($table, $ColumnName);
            if (!$ColumnExist)
            {
                $msg .= $this->createNotExistingColumn($table, $ColumnName, $ColumnProperties, $ColumnExist);
                if (!$ColumnExist) {
                    $msg .= '   failed to create ' . $table . ':' . $ColumnName . '<br>';
                }
            }
        }

		return $msg;
	}

	//
	public function createNotExistingTable($table)
	{
		$msg = "Model: createNotExistingTable: " . '<br>';


		return $msg;
	}





    public function XXX
    {
        $msg = ": " . '<br>';

        $sqlfile = $this->getPath('extension_root') . '/' . trim($file);

        // Check that sql files exists before reading. Otherwise raise error for rollback
        if (!file_exists($sqlfile))
        {
            JLog::add(JText::sprintf('JLIB_INSTALLER_ERROR_SQL_FILENOTFOUND', $sqlfile), JLog::WARNING, 'jerror');

            return false;
        }

        $buffer = file_get_contents($sqlfile);

        // Graceful exit and rollback if read not successful
        if ($buffer === false)
        {
            JLog::add(JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'), JLog::WARNING, 'jerror');

            return false;
        }

        // Create an array of queries from the sql file
        $queries = JDatabaseDriver::splitSql($buffer);

        if (count($queries) == 0)
        {
            // No queries to process
            return 0;
        }

        // Process each query in the $queries array (split out of sql file).
        foreach ($queries as $query)
        {
            // If we don't have UTF-8 Multibyte support we'll have to convert queries to plain UTF-8
            if ($doUtf8mb4ToUtf8)
            {
                $query = $this->convertUtf8mb4QueryToUtf8($query);
            }

            $db->setQuery($query);

            if (!$db->execute())
            {
                JLog::add(JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)), JLog::WARNING, 'jerror');

                return false;
            }

            $update_count++;
        }

        return $msg;
    }























    // * Original table names
	public function getColumnsPropertiesOfTable($table)
	{
//		$msg = "Model: createMissingSqlFields: " . '<br>';

//		$ColumnName, $ColumnProperties

		$ColumnsProperties = array ();

		// Test data
		$ColumnsProperties['access'] = 'INT  (10) UNSIGNED DEFAULT NULL';


        switch ($table) {
            case '#__rsgallery2_galleries':

                `id` int(11) NOT NULL auto_increment,
  `parent` int(11) NOT NULL default 0,
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `user` tinyint(4) NOT NULL default '0',
  `uid` int(11) unsigned NOT NULL default '0',
  `allowed` varchar(100) NOT NULL default '0',
  `thumb_id` int(11) unsigned NOT NULL default '0',
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `access` int(10) unsigned DEFAULT NULL,
                break;
            case '#__rsgallery2_files':
                `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `descr` text,
  `gallery_id` int(9) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `hits` int(11) unsigned NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `rating` int(10) unsigned NOT NULL default '0',
  `votes` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '1',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(9) unsigned NOT NULL default '0',
  `approved` tinyint(1) unsigned NOT NULL default '1',
  `userid` int(10) NOT NULL,
  `params` text NOT NULL,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
                break;
            case '#__rsgallery2_comments':
                `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_ip` varchar(50) NOT NULL default '0.0.0.0',
  `parent_id` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL,
  `item_table` varchar(50) default NULL,
  `datetime` datetime NOT NULL,
  `subject` varchar(100) default NULL,
  `comment` text NOT NULL,
  `published` tinyint(1) NOT NULL default '1',
  `checked_out` int(11) default NULL,
  `checked_out_time` datetime default NULL,
  `ordering` int(11) NOT NULL,
  `params` text,
  `hits` int(11) NOT NULL,
                break;
            case '#__rsgallery2_config':
                `name` text NOT NULL,
                  `value` text NOT NULL,
                break;
            case '#__rsgallery2_acl':
                `gallery_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL default '0',
  `public_view` tinyint(1) NOT NULL default '1',
  `public_up_mod_img` tinyint(1) NOT NULL default '0',
  `public_del_img` tinyint(1) NOT NULL default '0',
  `public_create_mod_gal` tinyint(1) NOT NULL default '0',
  `public_del_gal` tinyint(1) NOT NULL default '0',
  `public_vote_view` tinyint( 1 ) NOT NULL default '1',
  `public_vote_vote` tinyint( 1 ) NOT NULL default '0',
  `registered_view` tinyint(1) NOT NULL default '1',
  `registered_up_mod_img` tinyint(1) NOT NULL default '1',
  `registered_del_img` tinyint(1) NOT NULL default '0',
  `registered_create_mod_gal` tinyint(1) NOT NULL default '1',
  `registered_del_gal` tinyint(1) NOT NULL default '0',
  `registered_vote_view` tinyint( 1 ) NOT NULL default '1',
  `registered_vote_vote` tinyint( 1 ) NOT NULL default '1',
                break;


            default:
              ' Enquire messafe error !!'
        }


		return ColumnsProperties;
	}


}