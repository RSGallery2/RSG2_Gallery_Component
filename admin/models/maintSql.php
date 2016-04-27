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
			// ALTER TABLE t2 DROP COLUMN c, DROP COLUMN d;
			$query = 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $ColumnName ;
			$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$result = $db->execute();
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
	public function IsColumnExisting($table, $ColumnName)
	{
		$IsColumnExisting = false;

		$db = JFactory::getDbo();
		$query = 'SHOW COLUMNS FROM ' . $table . ' LIKE ' . $db->quote($ColumnName) ;
		// $msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$AccessField = $db->loadObject();
		$IsColumnExisting = isset($AccessField);
		// $msg .= '<br>' . '$ColumnExist: ' . json_encode ($ColumnExist);

		retrun $IsColumnExisting;
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
		$TableList = array(
			'#__rsgallery2_galleries',
			'#__rsgallery2_files',
			'#__rsgallery2_comments',
			'#__rsgallery2_config',
			'#__rsgallery2_acl');

		// Read file to auto use in the future

		return $TableList;
	}


	public function completeSqlTables()
	{
		$msg = 'model:completeSqlTables: ' . '<br>';

		/*
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
		/**/

		$this->createMissingSqlFields ();

		$msg .= '!!! Not implemented yet !!!' . '<br>';


		return $msg;
	}






	public function createMissingSqlFields()
	{
		$msg = "Model: createMissingSqlFields: " . '<br>';
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

		//--- create not exisitn columns if not exist -----------------

		foreach ($tables as $table) {
			$msg .= '   Table ' . $table . '<br>';

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
		}

		return $msg;
	}

	//
	public function createNotExistingTable($table)
	{
		$msg = "Model: createNotExistingTable: " . '<br>';


		return $msg;
	}

	// * Original table names
	public function getColumnsPropertiesOfTable($table)
	{
//		$msg = "Model: createMissingSqlFields: " . '<br>';

//		$ColumnName, $ColumnProperties


//		return $msg;

		$ColumnsProperties = array ();

		// Test data
		$ColumnsProperties['access'] = 'INT  (10) UNSIGNED DEFAULT NULL';

		return ColumnsProperties;
	}


}