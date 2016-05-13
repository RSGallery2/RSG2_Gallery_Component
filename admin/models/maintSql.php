<?php
/**
 * Maintenance for RSGallery2 SQL tables and content
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

// access to the content of the install.mysql.utf8.sql file
require_once( JPATH_COMPONENT.'/classes/SqlInstallFile.php ' );

// ToDo: write all to logfile

// Joel Lipman Jdatabase

/**
 * 
 */
class Rsgallery2ModelMaintSql extends  JModelList
{
//    protected $text_prefix = 'COM_RSG2';

	//protected $tableList;
	protected $tableNames;
	protected $sqlFile;

	/**
	 * Runs optimization for each table 
	 * @return string operation messages
	 */
	public function optimizeDB()
	{
		$msg = "model:optimizeDB: " . '<br>';

		if (empty($this->sqlFile)) {
			$this->sqlFile = new SqlInstallFile ();
		}

		if (empty($this->tableNames)) {
			$this->tableNames = $this->sqlFile->getTableNamesList();
		}

		$db = JFactory::getDbo();

		//--- optimize all tables -------------------------------

		foreach ($this->tableNames as $tableName) {
			$msg .= 'Table ' . $tableName . '<br>';
			$db->setQuery('OPTIMIZE TABLE ' . $db->quoteName($tableName));
			$db->execute();
		}

		//--- optimized message -------------------------------------
		$msg .=  '<br>' . JText::_('COM_RSGALLERY2_MAINT_OPTIMIZE_SUCCESS', true );

		return $msg;
	}

	/**
	 * Creates table columns acces in table galleries and sets all values to '1'
	 * @return string operation messages
	 */
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

		$tableName  = '#__rsgallery2_galleries';
		$columnName = 'access';

		$columnExist = IsColumnExisting($tableName, $columnName);

		/* !!! test code -> delete actual column (field)
		if ($columnExist) {

			$result = $this->DeleteColumn($tableName, $columnName);
			$msg .= '<br>' . '$result (drop): ' . json_encode ($result);

			$columnExist = false;
		}
		/**/

		// Create table column
		if (!$columnExist)
		{
			$msg = "Creating not existing column: ";
			$columnProperties = 'INT  (10) UNSIGNED DEFAULT NULL';
			$columnExist = $this->createColumn($tableName, $columnName, $columnProperties);
			// $msg .= '<br>' . '$IsColumnCreated : ' . json_encode ($columnExist);
			if (!$columnExist) {
				$msg .= '<br>' . '!!! Failed to create Column: ' . $columnName;
			} else {
				$msg .= '<br>' . 'Created Column: ' . $columnName;
			}
		}
		else
		{
			$msg .= '<br>' . 'Column was existing: ' . $columnName;
		}

		// Set all access values to '1'
		if ($columnExist)
		{
			$msg .= '<br>' . 'Did set all access values to 1';
			
			$db = JFactory::getDbo();
			
			// update your_table set likes = null
			$query = 'UPDATE ' . $tableName . ' SET ' . $columnName . '=1';
			//$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$result = $db->execute();

			$msg .= '<br>' . '$result (update): ' . json_encode ($result);
		}
		
		return $msg;
	}


	/**
	 * DeleteColumn
	 * @param string $tableName
	 * @param string $columnName
	 * @return mixed
	 */
	private function DeleteColumn($tableName, $columnName)
	{
		$db = JFactory::getDbo();
		// ALTER TABLE t2 DROP COLUMN c, DROP COLUMN d;
		$query = 'ALTER TABLE ' . $tableName . ' DROP COLUMN ' . $columnName ;
		// $msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$result = $db->execute();

		return $result;
	}

	/**
	 * Is column existing in table in DB
	 * @param string $tableName
	 * @param string $columnName
	 * @return bool
	 */
	private function IsColumnExisting($tableName, $columnName)
	{
		$IsColumnExisting = false;

		// Column mames of table in DB
		$db = JFactory::getDBO();
		$columns = $db->getTableColumns($tableName);
		$IsColumnExisting = isset($columns[$columnName]);

		return $IsColumnExisting;
	}

	/**
	 * Is table existing in DB
	 * @param string $tableName
	 * @return bool
	 */
	public function IsTableExisting ($tableName)
	{
		$IsTableExisting = false;

		$db = JFactory::getDbo();
		$dbTableName = $db->replacePrefix($tableName);
		
		$dbTables= $db->getTableList();
		
		$IsTableExisting = in_array ($dbTableName, $dbTables);

		return $IsTableExisting;
	}

	/**
	 * createColumn
	 * @param string $tableName
	 * @param string $columnName
	 * @param string $columnProperties
	 * @return bool
	 */
	public function createColumn($tableName, $columnName, $columnProperties)
	{
		$db = JFactory::getDbo();

		$query = 'ALTER TABLE ' . $db->quoteName($tableName) . ' ADD ' . $db->quoteName($columnName) . ' ' . $columnProperties;

		$db->setQuery($query);
		$result = $db->execute();

		$IsColumnCreated = ! empty($result);

		return $IsColumnCreated;
	}


ToDo: check following lines

	/*------------------------------------------------------------------------------------
	completeSqlTables()
	------------------------------------------------------------------------------------*/
	/**
	 * does go through each table of the sql field if it does not exist then
	 * it will be created. Then the existence of the single columns are checked
	 *
	 * ToDo: find columns which are not needed any more
	 * @return string
	 */
	public function completeSqlTables()
	{
		$msg = 'model:completeSqlTables: ' . '<br>';
		
		// d:\xampp\htdocs\Joomla3x\administrator\components\com_rsgallery2\sql\install.mysql.utf8.sql
		$sqlFile = new SqlInstallFile ();
		
		//--- Check for not existing tables and create them --------

		$tableNames = $sqlFile->getTableNamesList();

		$msg .= 'Check for missing tables' . '<br>';
		foreach ($tableNames as $tableName) {
			$msg .= '   Table ' . $tableName . '<br>';

			// Create table if not existing
			$TableExist = $this->IsTableExisting($tableName);
			if (!$TableExist) {
				$msg .= $this->createNotExistingTable($tableName, $sqlFile);
			}
			else
			{
				// Table exists -> check all columns
				$msg .= $this->createMissingSqlFieldsInTable ($tableName, $sqlFile);
			}
		}

		return $msg;
	}



	//
	public function createNotExistingTable($tableName, $sqlFile)
	{
		$msg = "Model: createNotExistingTable: " . '<br>';

		$query = $sqlFile->getTableQuery ($tableName);
		if (!empty ($query))
		{
//			$msg .= '<br>' . '$query: ' . json_encode($query);

			$db = JFactory::getDbo();
			$db->setQuery($query);
			$IsTableCreated = $db->execute();

			if ($IsTableCreated)
			{
				$msg .= 'Table: ' . $tableName . ' created successful' . '<br>';
			}
			else
			{
				$msg .= '!!! Table: ' . $tableName . ' not created !!!' . '<br>';
                $msg .= '<br>' . '$query: ' . json_encode($query);
			}
		}
		else
		{
			$msg .= '!!! Query for Table: ' . $tableName . ' not found !!!' . '<br>';
		}

		return $msg;
	}

	public function createMissingSqlFieldsInTable($tableName, $sqlFile)
	{
		$msg = "Model: createMissingSqlFields: " . '   Table ' . $tableName . '<br>';

		$msg .= 'Check for missing COLUMN IN TABLE <br>';

		//--- create not existing columns if not exist -----------------

        // Original columns
        $columns = $sqlFile->getColumnsPropertiesOfTable($tableName);

        // Create all not existing table columns
        foreach ($columns as $column)
        {
	        $columnName = $column->name;
	        $columnProperties = $column->properties;

            // Create table column
            $columnExist = $this->IsColumnExisting($tableName, $columnName);
            if (!$columnExist)
            {
                $msg .= $this->createColumn($tableName, $columnName, $columnProperties, $columnExist);
                if (!$columnExist) {
                    $msg .= '   failed to create ' . $tableName . ':' . $columnName . '<br>';
                }
            }
        }

		return $msg;
	}

	public function check4Errors ()
	{
		$errors = array ();

		$errors [] = "Database schema version (3.3.6-2014-09-30) does not match CMS version (3.5.1-2016-03-29).";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'hits'. (From file 2.5.5.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'header'. (From file 3.4.0-2014-09-16.sql.)";
		$errors [] = "Table 'mmbty_session' does not have column 'session_id' with type varchar(191). (From file 3.5.0-2015-07-01.sql.)";
		$errors [] = "Table 'mmbty_user_keys' does not have column 'series' with type varchar(191). (From file 3.5.0-2015-07-01.sql.)";
		$errors [] = "Table 'mmbty_contentitem_tag_map' should not have index 'idx_tag'. (From file 3.5.0-2015-10-26.sql.)";
		$errors [] = "Table 'mmbty_contentitem_tag_map' should not have index 'idx_type'. (From file 3.5.0-2015-10-26.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' should not have index 'idx_link_old'. (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'old_url' with type VARCHAR(2048). (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'new_url' with type VARCHAR(2048). (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have column 'referer' with type VARCHAR(2048). (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_redirect_links' does not have index 'idx_old_url'. (From file 3.5.0-2016-03-01.sql.)";
		$errors [] = "Table 'mmbty_user_keys' does not have column 'user_id' with type varchar(150). (From file 3.5.1-2016-03-25.sql.)";
		$errors [] = "The Joomla! Core database tables have not been converted yet to UTF-8 Multibyte (utf8mb4)";

		$missingTableNames = check4TableMissing ();
		
		
		return $errors;
	}

	public function check4TableMissing ()
	{
		$missingTableNames = array ();

		// d:\xampp\htdocs\Joomla3x\administrator\components\com_rsgallery2\sql\install.mysql.utf8.sql
		$sqlFile = new SqlInstallFile ();

		//--- Check for not existing tables and create them --------

		if(empty ($this->tables))
		{
			$this->tables = $db->getTableList();
		}


		$tableNames = $sqlFile->getTableNamesList();

		foreach ($tableNames as $tableName) {
			// Create table if not existing
			$TableExist = $this->IsTableExisting($tableName);
			if (!$TableExist) {
				$missingTableNames [$tableName];
			}
		}

		return $missingTableNames;
	}

}