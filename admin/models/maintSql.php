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
require_once( JPATH_COMPONENT_ADMINISTRATOR.'/classes/SqlInstallFile.php ' );

// ToDo: write repairs to logfile
// ToDo: assign db once


// Joel Lipman Jdatabase

/**
 * 
 */
class Rsgallery2ModelMaintSql extends  JModelList
{
//    protected $text_prefix = 'COM_RSG2';

	//protected $tableList;
	/**
	 * @var
	 */
	protected $tableNames;
	/**
	 * @var
	 */
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

		$columnExist = $this->IsColumnExisting($tableName, $columnName);

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

			/* toDO Use following:
			 $query = $db->getQuery(true);
			 $query->update($db->quoteName('#__my_users'))
			       ->set(array($db->quoteName('name') . '=\'JoÃ«l\'', $db->quoteName('username') . '=\'joel.lipman\''))
			       ->where(array($db->quoteName('user_id') . '=42'));
			 $db->setQuery($query);
			*/

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
		// $IsColumnExisting = false;

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
		$query = 'ALTER TABLE ' . $db->quoteName($tableName) . ' ADD COLUMN ' . $db->quoteName($columnName) . ' ' . $columnProperties;
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
	 * @param $sqlTableName
	 * @param $sqlFile
	 * ToDo: Remove messages (should be generated in calling functions
	 *
	 * @return string
	 */
	public function createMissingSqlFieldsInTable($sqlTableName, $sqlFile)
	{
		$msg = "Model: createMissingSqlFields: " . '   Table ' . $sqlTableName . '<br>';

		$msg .= 'Check for missing COLUMN IN TABLE <br>';

		//--- create not existing columns  -----------------

		// Original columns
		$columns = $sqlFile->getColumnsPropertiesOfTable($sqlTableName);

		// Create all not existing table columns
		foreach ($columns as $column)
		{
			$columnName       = $column->name;
			$columnProperties = $column->properties;

			// Create table column
			$columnExist = $this->IsColumnExisting($sqlTableName, $columnName);
			if (!$columnExist)
			{
				$msg .= $this->createColumn($sqlTableName, $columnName, $columnProperties, $columnExist);
				if (!$columnExist)
				{
					$msg .= '   failed to create ' . $sqlTableName . ':' . $columnName . '<br>';
				}
			}
		}

		return $msg;
	}

	/**
	 * @return array
	 */
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

		foreach ($missingTableNames as $missingTableName)
		{
			$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_MISSING_TABLE',
				'',
				$db->quote($missingTableName));
		}

		/*----------------------------------------------
		Missing columns
		----------------------------------------------*/

		$missingColumns = $this->check4MissingColumns();

		foreach ($missingColumns as $missingColumnName => $missingTableName)
		{
			$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_MISSING_COLUMN',
				'',
				$db->quote($missingTableName),
				$db->quote($missingColumnName));
		}

		/*----------------------------------------------
		ToDo: Wrong column types
		Wrong column types
		----------------------------------------------*/

		$wrongColumnTypes = $this->check4WrongColumnTypes();
//		echo '<br>$wrongColumnTypes: ' . json_encode($wrongColumnTypes);
//		echo '<br>$wrongColumnTypes empty: ' . empty($wrongColumnTypes);

		foreach ($wrongColumnTypes as $wrongColumnTableName => $columnTypes)
		{
//			echo '<br>$TableName: ' . json_encode($wrongColumnTableName);
//			echo '<br>$columnTypes: ' . json_encode($columnTypes);

			foreach ($columnTypes as $wrongColumnName => $deltaColumnType)
			{
//				echo '<br>&nbsp&nbsp&nbsp$columnName: ' . json_encode($wrongColumnName);
//				echo '<br>$deltaColumnTypes: ' . json_encode($deltaColumnTypes);

//				foreach ($deltaColumnTypes as $deltaColumnType)
//				{
//					echo '<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$ExpectedProperty: ' . $deltaColumnType->ExpectedProperty;
//					echo '<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$ExistingProperty: ' . $deltaColumnType->ExistingProperty;

				$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_WRONG_COLUMN_TYPE',
					'',
					$db->quote($wrongColumnTableName),
					$db->quote($wrongColumnName),
					$db->quote($deltaColumnType->ExpectedProperty),
					$db->quote($deltaColumnType->ExistingProperty));


//				}
			}

			//
			//$errors [] = "Superfluous Table: " . $wrongColumnType;
/*
			$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_SUPERFLUOUS_TABLE',
				'',
				$db->quote($wrongColumnType));
*/
			echo '<br>';
		}
//		echo '<br>';

		echo '<br>';


		/*----------------------------------------------
		Superfluous tables		
		----------------------------------------------*/

		$superfluousTableNames = $this->check4SuperfluousTables();

		foreach ($superfluousTableNames as $superfluousTableName)
		{
			$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_SUPERFLUOUS_TABLE',
				'',
				$db->quote($superfluousTableName));
		}

		/*----------------------------------------------
		Superfluous columns
		----------------------------------------------*/

		$superfluousColumns = $this->check4SuperfluousColumns();

		foreach ($superfluousColumns as $superfluousColumnName => $superfluousTableName)
		{
			$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_SUPERFLUOUS_COLUMN',
				'',
				$db->quote($superfluousTableName),
				$db->quote($superfluousColumnName));
		}

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
	 * @return string[] string
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

	/**
	 * @param $sqlTableName
	 * @param $sqlFile
	 *
	 * @return array
	 */
	public function check4MissingColumnsInTable($sqlTableName, $sqlFile)
	{
		$missingColumns = array();

		// Original columns
		$sqlColumnNames = $sqlFile->getTableColumnNames($sqlTableName);

		// Create all not existing table columns
		foreach ($sqlColumnNames as $columnName)
		{
			$columnExist = $this->IsColumnExisting($sqlTableName, $columnName);
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

	/**
	 * @return array
	 */
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

	/**
	 * @param $tableName
	 * @param $sqlFile
	 *
	 * @return array
	 */
	public function check4SuperfluousColumn($tableName, $sqlFile)
	{
		$superfluousColumnNames = array ();

		$db = JFactory::getDbo();

		// Column names of table in DB
		$dbColumns = $db->getTableColumns($tableName);

		// Original columns
		$sqlColumnNames = $sqlFile->getTableColumnNames($tableName);

		// All db table names
		foreach ($dbColumns as $dbColumnName => $dbColumnProperties) {
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

	public function check4WrongColumnTypes()
	{
		$wrongColumnTypes = array();

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
				//$missingColumns = $this->check4MissingColumnsInTable($tableName, $this->sqlFile);
				$nextWrongColumnTypes = $this->check4WrongColumnTypesInTable($tableName, $this->sqlFile);

				if (!empty ($nextWrongColumnTypes))
				{
					//foreach ($nextWrongColumnTypes as $ColumnName => $wrongColumnTypeDeltaProperty)
					//foreach ($nextWrongColumnTypes as $nextWrongColumnType)
					foreach ($nextWrongColumnTypes as $sqlColumnName => $deltaProperty)
					{
						// $wrongColumnTypes [$nextWrongColumnType] = $tableName;
						$wrongColumnTypes [$tableName][$sqlColumnName] = $deltaProperty;
					}
				}
			}
		}

		return $wrongColumnTypes;
	}

	/**
	 * @param $tableName
	 * @param $sqlFile
	 *
	 * @return array
	 */
	public function check4WrongColumnTypesInTable($tableName, $sqlFile)
	{
		$differentColumnTypes = array();

		$db = JFactory::getDbo();

		// Column names of table in DB
		$dbColumns = $db->getTableColumns($tableName);

		// Original columns
		$sqlColumns = $sqlFile->getTableColumns($tableName);

		// Create all not existing table columns
		foreach ($sqlColumns as $sqlColumnName => $sqlColumnProperties)
		{
			// sql column name exiting in table ?
			if (array_key_exists($sqlColumnName, $dbColumns))
			{
				$dbColumnProperties = $dbColumns [$sqlColumnName];

				if ($sqlColumnProperties != $dbColumnProperties)
				{
					$DeltaProperty                   = new stdClass();
					$DeltaProperty->ExpectedProperty = $sqlColumnProperties;
					$DeltaProperty->ExistingProperty = $dbColumnProperties;

					$differentColumnTypes [$sqlColumnName] = $DeltaProperty;
				}
			}
		}

		return $differentColumnTypes;
	}
}



