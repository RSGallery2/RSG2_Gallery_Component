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
	 *
	 * @return string operation messages
	 */
	public function optimizeDB()
	{
		$msg = "model:optimizeDB: " . '<br>';

		if (empty($this->sqlFile))
		{
			$this->sqlFile = new SqlInstallFile ();
		}

		if (empty($this->tableNames))
		{
			$this->tableNames = $this->sqlFile->getTableNames();
		}

		$db = JFactory::getDbo();

		//--- optimize all tables -------------------------------

		foreach ($this->tableNames as $tableName)
		{
			$msg .= 'Table ' . $tableName . '<br>';
			$db->setQuery('OPTIMIZE TABLE ' . $db->quoteName($tableName));
			$db->execute();
		}

		//--- optimized message -------------------------------------
		$msg .= '<br>' . JText::_('COM_RSGALLERY2_MAINT_OPTIMIZE_SUCCESS', true);

		return $msg;
	}

	/**
	 * Creates table columns acces in table galleries and sets all values to '1'
	 *
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
			$msg              = "Creating not existing column: ";
			$columnProperties = 'INT  (10) UNSIGNED DEFAULT NULL';
			$columnExist      = $this->createColumn($tableName, $columnName, $columnProperties);
			// $msg .= '<br>' . '$IsColumnCreated : ' . json_encode ($columnExist);
			if (!$columnExist)
			{
				$msg .= '<br>' . '!!! Failed to create Column: ' . $columnName;
			}
			else
			{
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

			$msg .= '<br>' . '$result (update): ' . json_encode($result);
		}

		return $msg;
	}

	/**
	 * DeleteColumn
	 *
	 * @param string $tableName
	 * @param string $columnName
	 *
	 * @return mixed
	 */
	private function DeleteColumn($tableName, $columnName)
	{
		$db = JFactory::getDbo();
		// ALTER TABLE t2 DROP COLUMN c, DROP COLUMN d;
		$query = 'ALTER TABLE ' . $tableName . ' DROP COLUMN ' . $columnName;
		// $msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$result = $db->execute();

		return $result;
	}

	/**
	 * Is table existing in DB
	 *
	 * @param string $tableName
	 *
	 * @return bool
	 */
	public function IsTableExisting($tableName)
	{
		$IsTableExisting = false;

		if(!empty ($tableName))
		{
			$db = JFactory::getDbo();

			// $this->SqlTableNamesReplace4Prefix ($tableName);
			$dbTableName = $db->replacePrefix($tableName);
			$dbTables    = $db->getTableList();

			$IsTableExisting = in_array($dbTableName, $dbTables);
		}

		return $IsTableExisting;
	}

	/**
	 * Is column existing in table in DB
	 *
	 * @param string $tableName
	 * @param string $columnName
	 *
	 * @return bool
	 */
	private function IsColumnExisting($tableName, $columnName)
	{
		$IsColumnExisting = false;

		$db = JFactory::getDBO();

		// Column names of table in DB
		$columns          = $db->getTableColumns($tableName);
		$IsColumnExisting = isset($columns[$columnName]);

		return $IsColumnExisting;
	}

	/**
	 * createColumn
	 *
	 * @param string $tableName
	 * @param string $columnName
	 * @param string $columnProperties
	 *
	 * @return bool
	 */
	public function createColumn($tableName, $columnName, $columnProperties)
	{
		$db = JFactory::getDbo();

		// create column
		$query = 'ALTER TABLE ' . $db->quoteName($tableName) . ' ADD ' . $db->quoteName($columnName) . ' ' . $columnProperties;
		$db->setQuery($query);
		$result = $db->execute();

		$IsColumnCreated = !empty($result);

		return $IsColumnCreated;
	}


	/*------------------------------------------------------------------------------------
	completeSqlTables()
	------------------------------------------------------------------------------------*/
	/**
	 * Checks the existence of each table from the sql queries. If the table does not
	 * exist then it will be created. Afterwards the existence of the single columns
	 * are checked and repaired too
	 * ToDo: find columns which are not needed any more
	 *
	 * @return string
	 */
	public function completeSqlTables()
	{
		$msg = 'model:completeSqlTables: ' . '<br>';

		// d:\xampp\htdocs\Joomla3x\administrator\components\com_rsgallery2\sql\install.mysql.utf8.sql
		if (empty($this->sqlFile))
		{
			$this->sqlFile = new SqlInstallFile ();
		}

		//--- Check for not existing tables and create them --------
		if (empty($this->tableNames))
		{
			$this->tableNames = $this->sqlFile->getTableNames();
		}

		$msg .= 'Check for missing tables' . '<br>';
		foreach ($this->tableNames as $tableName)
		{
			$msg .= '   Table ' . $tableName . '<br>';

			// Create table if not existing
			$TableExist = $this->IsTableExisting($tableName);
			if (!$TableExist)
			{
				$msg .= $this->createNotExistingTable($tableName, $this->sqlFile);
			}
			else
			{
				// Table exists -> check all columns
				$msg .= $this->createMissingSqlFieldsInTable($tableName, $this->sqlFile);
			}
		}

		return $msg;
	}

	/**
	 * @param string $tableName
	 * @param        $sqlFile
	 * ToDo: Remove messages (should be generated in calling functions
	 *
	 * @return string
	 */
	public function createNotExistingTable($tableName, $sqlFile)
	{
		$msg = "Model: createNotExistingTable: " . '<br>';

		$query = $sqlFile->getTableQuery($tableName);
		if (!empty ($query))
		{
//			$msg .= '<br>' . '$query: ' . json_encode($query);

			$db = JFactory::getDbo();

			$db->setQuery($query);
			$result = $db->execute();

			$IsTableCreated = !empty ($result);
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

	/**
	 *
	 * @param $tableName
	 * @param $sqlFile
	 * ToDo: Remove messages (should be generated in calling functions
	 *
	 * @return string
	 */
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
			$columnName       = $column->name;
			$columnProperties = $column->properties;

			// Create table column
			$columnExist = $this->IsColumnExisting($tableName, $columnName);
			if (!$columnExist)
			{
				$msg .= $this->createColumn($tableName, $columnName, $columnProperties, $columnExist);
				if (!$columnExist)
				{
					$msg .= '   failed to create ' . $tableName . ':' . $columnName . '<br>';
				}
			}
		}

		return $msg;
	}

	public function check4Errors()
	{
		$errors = array();
/*
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
*/
		$db = JFactory::getDbo();

		/*----------------------------------------------
		Missing tables
		----------------------------------------------*/

		$missingTableNames = $this->check4MissingTables();
//		echo '<br>$missingTableNames: ' . json_encode($missingTableNames);
//		echo '<br>$missingTable empty: ' . empty($missingTableNames);

		foreach ($missingTableNames as $missingTableName)
		{
			// ToDo: better output
			// $errors [] = "Missing Table: " . $missingTableName;
			$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_MISSING_TABLE',
				'',
				$db->quote($missingTableName));
		}
//		echo '<br>';

		/*----------------------------------------------
		Missing columns
		----------------------------------------------*/

		$missingColumns = $this->check4MissingColumns();
//		echo '<br>$missingColumnNames: ' . json_encode($missingColumns);
//		echo '<br>$missingColumn empty: ' . empty($missingColumns);

		foreach ($missingColumns as $missingColumnName => $missingTableName)
		{
			// ToDo: better output
			//$errors [] = "Missing Column: ' . $missingColumnName . ' in table: " . $missingTableName;
			$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_MISSING_COLUMN',
				'',
				$db->quote($missingTableName),
				$db->quote($missingColumnName));
		}
//		echo '<br>';

		/*----------------------------------------------
		ToDo: Wrong column types
		Wrong column types
		----------------------------------------------*/

		/*----------------------------------------------
		Superfluous tables		
		----------------------------------------------*/

		$superfluousTableNames = $this->check4SuperfluousTables();
//		echo '<br>$superfluousTableNames: ' . json_encode($superfluousTableNames);
//		echo '<br>$superfluousTable empty: ' . empty($superfluousTableNames);

		foreach ($superfluousTableNames as $superfluousTableName)
		{
			// ToDo: better output
			//$errors [] = "Superfluous Table: " . $superfluousTableName;
			$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_SUPERFLUOUS_TABLE',
				'',
				$db->quote($superfluousTableName));
		}
//		echo '<br>';

		/*----------------------------------------------
		Superfluous columns
		----------------------------------------------*/

		$superfluousColumns = $this->check4SuperfluousColumns();
//		echo '<br>$superfluousColumnNames: ' . json_encode($superfluousColumns);
//		echo '<br>$superfluousColumn empty: ' . empty($superfluousColumns);

		foreach ($superfluousColumns as $superfluousColumnName => $superfluousTableName)
		{
			// ToDo: better output
			//$errors [] = "Superfluous Column: ' . $superfluousColumnName . ' in table: " . $superfluousTableName;
			$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_SUPERFLUOUS_COLUMN',
				'',
				$db->quote($superfluousTableName),
				$db->quote($superfluousColumnName));
		}
//		echo '<br>';
//		echo '<br>';

		return $errors;
	}

	/**
	 *
	 * @return string [] List of missing table names
	 */
	public function check4MissingTables()
	{
		$missingTableNames = array();

		// d:\xampp\htdocs\Joomla3x\administrator\components\com_rsgallery2\sql\install.mysql.utf8.sql
		if (empty($this->sqlFile))
		{
			$this->sqlFile = new SqlInstallFile ();
		}

		if (empty($this->tableNames))
		{
			$this->tableNames = $this->sqlFile->getTableNames();
		}

		foreach ($this->tableNames as $tableName)
		{
			$TableExist = $this->IsTableExisting($tableName);
			// Save table name if not existing
			if (!$TableExist)
			{
				$missingTableNames [] = $tableName;
			}
		}

		return $missingTableNames;
	}

	/**
	 *
	 * @return array
	 */
	public function check4MissingColumns()
	{
		$missingColumnNames = array();

		// d:\xampp\htdocs\Joomla3x\administrator\components\com_rsgallery2\sql\install.mysql.utf8.sql
		if (empty($this->sqlFile))
		{
			$this->sqlFile = new SqlInstallFile ();
		}

		if (empty($this->tableNames))
		{
			$this->tableNames = $this->sqlFile->getTableNames();
		}

		foreach ($this->tableNames as $tableName)
		{
			$TableExist = $this->IsTableExisting($tableName);
			// Save table name if not existing
			if ($TableExist)
			{
				$missingColumns = $this->check4MissingColumnsInTable($tableName, $this->sqlFile);
				if (!empty ($missingColumns))
				{
					foreach ($missingColumns as $missingColumn)
					{
						$missingColumnNames [$missingColumn] = $tableName;
					}
				}
			}
		}

		return $missingColumnNames;
	}

	public function check4MissingColumnsInTable($tableName, $sqlFile)
	{
		$missingColumns = array();

		// Original columns
		$sqlColumnNames = $sqlFile->getTableColumns($tableName);

		// Create all not existing table columns
		foreach ($sqlColumnNames as $columnName)
		{
			$columnExist = $this->IsColumnExisting($tableName, $columnName);
			// Save Column name if not existing
			if (!$columnExist)
			{
				$missingColumns [] = $columnName;
			}
		}

		return $missingColumns;
	}

	/**
	 * Collects all superfluous table names
	 * @return string [] superfluous table names, may be empty
	 */
	public function check4SuperfluousTables()
	{
		$superfluousTableNames = array();

		// d:\xampp\htdocs\Joomla3x\administrator\components\com_rsgallery2\sql\install.mysql.utf8.sql
		if (empty($this->sqlFile))
		{
			$this->sqlFile = new SqlInstallFile ();
		}

		if (empty($this->tableNames))
		{
			$this->tableNames = $this->sqlFile->getTableNames();
		}

		// Replace '#__' with db table prefix
		$sqlTableNamesWithPrefix = $this->SqlTableNamesReplace4Prefix ($this->tableNames);

		$db = JFactory::getDbo();

		// table name begins with #__rsgallery2
		$StartRsgDbName = $db->replacePrefix('#__rsgallery2_');

		$dbTableNames = $db->getTableList();

		// All db table names
		foreach ($dbTableNames as $dbTableName) {
			// db table name matches rsgallery2 start
			if (substr($dbTableName, 0, strlen($StartRsgDbName)) === $StartRsgDbName) {
				// Old table name missing in new sql definition ?
				if(! in_array ($dbTableName, $sqlTableNamesWithPrefix)) {
					$superfluousTableNames [] = $dbTableName;
				}
			}
		}

		return $superfluousTableNames;
	}

	public function check4SuperfluousColumns()
	{
		$superfluousColumns = array();

		// d:\xampp\htdocs\Joomla3x\administrator\components\com_rsgallery2\sql\install.mysql.utf8.sql
		if (empty($this->sqlFile))
		{
			$this->sqlFile = new SqlInstallFile ();
		}

		if (empty($this->tableNames))
		{
			$this->tableNames = $this->sqlFile->getTableNames();
		}

		foreach ($this->tableNames as $tableName)
		{
			$TableExist = $this->IsTableExisting($tableName);
			// Save table name if not existing
			if ($TableExist)
			{
				$nextSuperfluousColumns = $this->check4SuperfluousColumn($tableName, $this->sqlFile);
				if(! empty ($nextSuperfluousColumns)) {
					foreach ($nextSuperfluousColumns as $nextSuperfluousColumn)
					{
						$superfluousColumns [$nextSuperfluousColumn] = $tableName;
					}
				}
			}
		}

		return $superfluousColumns;
	}


	public function check4SuperfluousColumn($tableName, $sqlFile)
	{
		$superfluousColumnNames = array ();

		$db = JFactory::getDbo();

		// Column names of table in DB
		$dbColumnNames = $db->getTableColumns($tableName);

		// Original columns
		$sqlColumnNames = $sqlFile->getTableColumns($tableName);

		// All db table names
		foreach ($dbColumnNames as $dbColumnName => $dbColumnProperties) {
			// Old column name missing in new sql definition ?
			if(! in_array ($dbColumnName, $sqlColumnNames)) {
				$superfluousColumnNames [] = $dbColumnName;
			}
		}

		return $superfluousColumnNames;
	}

	/**
	 * Replaces the '#--' in front of component table names
	 * with the matching database name
	 * @param string [] $tableNames
	 * @return string [] replaced table names
	 */
	private function SqlTableNamesReplace4Prefix ($tableNames)
	{
		$SqlTableNamesWithPrefix = array ();

		$db = JFactory::getDbo();

		foreach ($tableNames as $tableName)
		{
			$SqlTableNamesWithPrefix[] = $db->replacePrefix($tableName);
		}

		return $SqlTableNamesWithPrefix;
	}

	/**
	 * Replaces the '#--' in front of component table names
	 * with the matching database name
	 * @param string $tableName
	 * @return string
	 */
	private function SqlTableNameReplace4Prefix ($tableName)
	{
		$db = JFactory::getDbo();

		$SqlTableNameWithPrefix = $db->replacePrefix($tableName);

		return $SqlTableNameWithPrefix;
	}


}



