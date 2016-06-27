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
require_once (JPATH_COMPONENT_ADMINISTRATOR . '/classes/sqlinstallfile.php');

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
	 * @var
	 */
	protected $db;

	/**
	 * Runs optimization for each table
	 *
	 * @return string operation messages
	 */
	public function optimizeDB()
	{
		$msg = ''; //  "model:optimizeDB: " . '<br>';

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

		$IsColumnExisting  = $this->IsColumnExisting($tableName, $columnName);

		/* !!! test code -> delete actual column (field)
		if ($IsColumnExisting ) {

			$result = $this->DeleteColumn($tableName, $columnName);
			$msg .= '<br>' . ' Debug extra delete column: ' . json_encode ($result);

			$IsColumnExisting = false;
		}
		/**/

		// Create table column
		if (!$IsColumnExisting)
		{
			$msg              = "Creating not existing column: ";
			$columnProperties = 'INT  (10) UNSIGNED DEFAULT NULL';
			$isColumnExisting = $this->createSqlFileColumn($tableName, $columnName, $columnProperties);
			// $msg .= '<br>' . '$IsColumnCreated : ' . json_encode ($columnExist);
			if (!$isColumnExisting)
			{
				$msg .= '!!! Failed to create Column: ' . $columnName . '<br>';
			}
			else
			{
				$msg .= 'Created Column: ' . $columnName . '<br>';
			}
		}
		else
		{
			$msg .= 'Column was existing: ' . $columnName . '<br>';
		}

		// Set all access values to '1'
		if ($isColumnExisting)
		{
			$msg .= '<br>' . 'Did set all access values to 1';

			$db = JFactory::getDbo();

			$query = $db->getQuery(true);

			//$query = 'UPDATE ' . $db->quoteName($tableName) . ' SET ' . $db->quoteName($columnName) . '=1';
			$query->update($db->quoteName($tableName))
			      ->set($db->quoteName($columnName) . '=\'1\'');
			$db->setQuery($query);
			/**/

			$result = $db->execute();
			$IsSuccessful = ! empty ($result);
			if ($IsSuccessful)
			{
				$msg .= 'Assigned `1` to every row in column ' . $columnName . '<br>';
			}
			else
			{
				$msg .= '!!! Failed to assign `1` to every row in column: ' . $columnName . '<br>';
			}
		}

		return $msg;
	}

	/**
	 * Delete Column
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
		$query = 'ALTER TABLE ' . $db->quoteName($tableName) . ' DROP COLUMN ' . $db->quoteName($columnName);
		$db->setQuery($query);
		$result = $db->execute();

		$IsColumnDeleted = !empty($result);

		return $IsColumnDeleted;
	}

	/**
	 * Is table existing in DB
	 *
	 * @param string $tableName
	 *
	 * @return bool
	 */
	public function IsSqlTableExisting($tableName)
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
	 * Delete table 
	 *
	 * @param string $tableName
	 *
	 * @return bool success of delete
	 */
	private function deleteTable($tableName)
	{
		$db = JFactory::getDbo();
		// ALTER TABLE t2 DROP COLUMN c, DROP COLUMN d;
		$query = 'DROP Table ' . $db->quoteName($tableName);
		$db->setQuery($query);
		$result = $db->execute();

		$IsTableDeleted = !empty($result);

		return $IsTableDeleted;
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
	public function createSqlFileColumn($tableName, $columnName, $columnProperties)
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
	repairSqlTables()
	------------------------------------------------------------------------------------*/
	/**
	 * The function will be called from Maintenance Database if a mismatch between database and
	 * component sql file is found.
	 * It will check and repair following issues
	 *    * Missing tables -> create
	 *    * Missing columns -> create
	 *    * Superfluous tables -> delete
	 *    * Superfluous columns -> delete
	 *    * ToDO: Wrong column types -> !!! not fixed
	 *
	 * @return string with info about operation (successfull/failed)
	 */
	public function repairSqlTables()
	{
		$msg = ''; //  'model:completeSqlTables: ' . '<br>';

		if (empty($this->sqlFile))
		{
			$this->sqlFile = new SqlInstallFile ();
		}

		/*----------------------------------------------
		Missing tables
		----------------------------------------------*/

		$missingTableNames = $this->check4MissingTables();
//		if(! empty ($missingTableNames))
//		{
		foreach ($missingTableNames as $missingTableName)
		{
			$msg .= 'Fix missing Table name: ' . $missingTableName . '<br>';
			$IsTableCreated = $this->createSqlFileTable($missingTableName, $this->sqlFile);
			if ($IsTableCreated)
			{
				$msg .= 'Table: ' . $missingTableName . ' created successful' . '<br>';
			}
			else
			{
				$msg .= '!!! Table: ' . $missingTableName . ' not created !!!' . '<br>';
			}
		}

		/*----------------------------------------------
		Missing columns
		----------------------------------------------*/

		$missingColumns = $this->check4MissingColumns();
		foreach ($missingColumns as $missingColumnName => $missingTableName)
		{
			// Get column type from sql file
			$TableColumnsProperties = $this->sqlFile->getTableColumns ($missingTableName);
			$TableColumnsProperty = $TableColumnsProperties [$missingColumnName];
			// create column
			$IsColumnCreated = $this->createSqlFileColumn($missingTableName, $missingColumnName, $TableColumnsProperty);
			if ($IsColumnCreated)
			{
				$msg .= 'Column: ' . $missingColumnName . ' in table  '. $missingTableName . ' created successful' . '<br>';
			}
			else
			{
				$msg .= '!!! Column: ' . $missingColumnName . ' in table  '. $missingTableName . ' not created !!!' . '<br>';
			}
		}

		/*----------------------------------------------
		ToDo: Wrong column types
		Wrong column types
		----------------------------------------------*/

		$wrongColumnTypes = $this->check4WrongColumnTypes();
		if(! empty ($wrongColumnTypes))
		{
			foreach ($wrongColumnTypes as $wrongColumnTableName => $columnTypes)
			{
				foreach ($columnTypes as $wrongColumnName => $deltaColumnType)
				{

					$msg .= '!!! Wrong column type for : ' . $wrongColumnName . ' in table  ' . $wrongColumnTableName . ' not created !!!' . '<br>';
				}
			}

			$msg .= 'Wrong column type are not repaired automatically. Please check info and do it by hand (phpmyadmin)' . '<br>';
		}

		/*----------------------------------------------
		Superfluous tables
		----------------------------------------------*/

		$superfluousTableNames = $this->check4SuperfluousTables();
		foreach ($superfluousTableNames as $superfluousTableName)
		{
			$IsTableDeleted = $this->deleteTable ($superfluousTableName);
			if ($IsTableDeleted)
			{
				$msg .= 'Table: ' . $superfluousTableName . ' deleted successful' . '<br>';
			}
			else
			{
				$msg .= '!!! Table: ' . $superfluousTableName . ' not deleted !!!' . '<br>';
			}
		}


		/*----------------------------------------------
		Superfluous columns
		----------------------------------------------*/

		$superfluousColumns = $this->check4SuperfluousColumns();
		foreach ($superfluousColumns as $superfluousColumnName => $superfluousTableName)
		{
			$IsColumnDeleted = $this->DeleteColumn ($superfluousTableName, $superfluousColumnName);
			if ($IsColumnDeleted)
			{
				$msg .= 'Column: ' . $superfluousColumnName . ' in table  '. $superfluousTableName . ' deleted successful' . '<br>';
			}
			else
			{
				$msg .= '!!! Column: ' . $superfluousColumnName . ' in table  '. $superfluousTableName . ' not deleted !!!' . '<br>';
			}
		}

		return $msg;
	}

	/**
	 * @param string $tableName
	 * @param        $sqlFile
	 * ToDo: Remove messages (should be generated in calling functions
	 *
	 * @return boolean
	 */
	public function createSqlFileTable($tableName, $sqlFile)
	{
		$IsTableCreated = false;

		// Direct command (query) from sql file
		$query = $sqlFile->getTableQuery($tableName);
		if (!empty ($query))
		{
			$db = JFactory::getDbo();

			$db->setQuery($query);
			$result = $db->execute();

			$IsTableCreated = !empty ($result);
			if (!$IsTableCreated)
			{
				// ToDO: Log
			}
			else
			{
				// ToDO: Log
			}
		}
		else
		{
			// ToDO: Log
			JFactory::getApplication()->enqueueMessage('!!! Query for Table: ' . $tableName . ' not found !!!', 'warning');
		}

		return $IsTableCreated;
	}

	/**
	 * @return array
	 */
	public function check4Errors()
	{
		$errors = array();

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
		Wrong column types
		----------------------------------------------*/

		$wrongColumnTypes = $this->check4WrongColumnTypes();

		foreach ($wrongColumnTypes as $wrongColumnTableName => $columnTypes)
		{

			foreach ($columnTypes as $wrongColumnName => $deltaColumnType)
			{

				$errors [] = JText::sprintf('COM_RSGALLERY2_MSG_DATABASE_WRONG_COLUMN_TYPE',
					'',
					$db->quote($wrongColumnTableName),
					$db->quote($wrongColumnName),
					$db->quote($deltaColumnType->ExpectedProperty),
					$db->quote($deltaColumnType->ExistingProperty));
			}

		}
//		echo '<br>';

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
			$TableExist = $this->IsSqlTableExisting($tableName);
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
			$TableExist = $this->IsSqlTableExisting($tableName);
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
			$TableExist = $this->IsSqlTableExisting($tableName);
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
			$TableExist = $this->IsSqlTableExisting($tableName);
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

	public function updateComments()
	{
		$msg = "model:updateComments: " . '<br>';

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		//--- find mismatch in comments number ---------------------------

		// list of images
		$query->select ($db->quoteName(array('id', 'comments')))
			->from($db->quoteName('#__rsgallery2_files'));

		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$msg .=  'id: ' . $row->id . ' comments: ' . $row->comments;


			$query = $db->getQuery(true);
			$query->select ($db->quoteName('item_id'))
				->from($db->quoteName('#__rsgallery2_comments'))
				->where($db->quoteName('item_id') . ' = '. $row->id);
			$db->setQuery($query);

			$my_count = $db->getNumRows();

			$Brows = $db->loadObjectList();
			
			$msg .=  ' found: ' . $my_count;
			$msg .= '<br>';


		}



		/*
				$query->select ();

				// build the SQL query
				$query->select($db->quoteName(array('p.user_id', 'u.username', 'u.real_name')));
				$query->from($db->quoteName('#__user_profiles p'));
				$query->join('INNER', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('u.id') . ' = ' . $db->quoteName('p.user_id') . ')')
				$query->where($db->quoteName('u.real_name') . ' LIKE '. $db->quote('\'%smith%\''));
				$query->order('u.real_name ASC');

				// Load the results as a list of stdClass objects (see later for more options on retrieving data).
				$rows = $db->loadObjectList();

				// Retrieve each value in the ObjectList
				foreach( $rows as $row ) {
					$this_user_id = $row->user_id;
					$this_user_name = $row->username;
					$this_user_realname = $row->real_name;
				}

		/**/

		//--- optimized message -------------------------------------
		// $msg .= '<br>' . JText::_('COM_RSGALLERY2_MAINT_OPTIMIZE_SUCCESS', true);

		return $msg;
	}
}



