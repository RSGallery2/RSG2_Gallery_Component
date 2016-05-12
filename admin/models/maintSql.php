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

	protected $tableList;
	/**
	 *
	 */
	public function optimizeDB()
	{
		$msg = "model:optimizeDB: " . '<br>';

		$sqlFile = new SqlInstallFile ();
		$tableNames = $sqlFile->getTableNamesList();

		$db = JFactory::getDbo();

		//--- optimize all tables -------------------------------

		foreach ($tableNames as $tableName) {
			$msg .= 'Table ' . $tableName . '<br>';
			$db->setQuery('OPTIMIZE TABLE ' . $db->quoteName($tableName));
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

		$tableName  = '#__rsgallery2_galleries';
		$ColumnName = 'access';

		$ColumnExist = IsColumnExisting($tableName, $ColumnName);

		// !!! test code -> delete actual column (field)
		if ($ColumnExist) {

			$result = $this->DeleteColumn($tableName, $ColumnName);
			$msg .= '<br>' . '$result (drop): ' . json_encode ($result);

			$ColumnExist = false;
		}


		// Create table column
		if (!$ColumnExist)
		{
			$ColumnProperties = 'INT  (10) UNSIGNED DEFAULT NULL';
			createNotExistingColumn($tableName, $ColumnName, $ColumnProperties, $ColumnExist);
		}

		// Set all access values to '1'
		if ($ColumnExist)
		{

			$db = JFactory::getDbo();
			// update your_table set likes = null
			$query = 'UPDATE ' . $tableName . ' SET ' . $ColumnName . '=1';
			$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$result = $db->execute();
			$msg .= '<br>' . '$result (update): ' . json_encode ($result);
		}
		return $msg;
	}


	// *
	private function DeleteColumn($tableName, $ColumnName)
	{
		$db = JFactory::getDbo();
		// ALTER TABLE t2 DROP COLUMN c, DROP COLUMN d;
		$query = 'ALTER TABLE ' . $tableName . ' DROP COLUMN ' . $ColumnName ;
		// $msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$result = $db->execute();

		return $result;
	}

	/**
	 * Is column existing in table in DB
	 * @param string $tableName
	 * @param string $ColumnName
	 *
	 * @return bool
	 */
	private function IsColumnExisting($tableName, $ColumnName)
	{
		$IsColumnExisting = false;

		$db = JFactory::getDBO();
		$columns = $db->getTableColumns($tableName);
		$IsColumnExisting = isset($columns[$ColumnName]);

/*

		$db = JFactory::getDbo();
		$query = 'SHOW COLUMNS FROM ' . $tableName . ' LIKE ' . $db->quote($ColumnName) ;
		// $msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$AccessField = $db->loadObject();
		$IsColumnExisting = isset($AccessField);
		// $msg .= '<br>' . '$ColumnExist: ' . json_encode ($ColumnExist);
*/
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

	//
	public function createNotExistingColumn($tableName, $ColumnName, $ColumnProperties, &$IsColumnCreated)
	{
		$msg = "Model: createNotExistingColumn: ";

		$db = JFactory::getDbo();


		//$query = "ALTER TABLE #__shoutbox ADD COLUMN user_id int(11) NOT NULL DEFAULT '0'";
		//$query = 'ALTER TABLE `#__virtuemart_categories_en_gb` ADD `short_desc` varchar(1200)';
		//$query = "ALTER TABLE #__test_plugin ADD linkimageflag varchar(10) NOT NULL";

		//   `access` int(10) unsigned DEFAULT NULL
		//$query = 'ALTER TABLE ' . $table . ' ADD ' . $ColumnName . ' ' . $ColumnProperties;
		//$query = 'ALTER TABLE ' . $tableName . ' ADD ' . $ColumnName . ' ' . $ColumnProperties.properties;

		$query = 'ALTER TABLE ' . $db->quoteName($tableName) . ' ADD ' . $db->quoteName($ColumnName) . ' ' . $ColumnProperties;

		$msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$IsColumnCreated = $db->execute();
		$msg .= '<br>' . '$IsColumnCreated (Added): ' . json_encode ($IsColumnCreated);

		return $msg . '<br>';
	}
	
	/*------------------------------------------------------------------------------------
	completeSqlTables()
	------------------------------------------------------------------------------------*/
	/**
	 * does go through each table of the sql field if it does not exist then
	 * it will be created. Then the existance of the single columns are checked
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
                $msg .= $this->createNotExistingColumn($tableName, $columnName, $columnProperties, $columnExist);
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