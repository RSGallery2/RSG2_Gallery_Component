<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

// access to the content of the install.mysql.utf8.sql file
require_once( JPATH_COMPONENT.'/classes/SqlInstallFile.php ' );


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

		$msg .= '!!! Not implemented yet !!!' . '<br>';

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
        foreach ($columns as $columnName => $columnProperties)
        {
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




}