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
		$msg = "optimizeDB: " . '<br>';

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
		$field = 'access';

		$db = JFactory::getDbo();
		$query = 'SHOW COLUMNS FROM ' . $table . ' LIKE ' . $db->quote($field) ;
		$msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$AccessField = $db->loadObject();
		$ColumnExist = isset($AccessField);
		$msg .= '<br>' . '$ColumnExist: ' . json_encode ($ColumnExist);

		// test code
		if ($ColumnExist) {
			// ALTER TABLE t2 DROP COLUMN c, DROP COLUMN d;
			$query = 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $field ;
			$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$result = $db->execute();
			$msg .= '<br>' . '$result (drop): ' . json_encode ($result);

			$ColumnExist = false;
		}


		// Create table column
		if (!$ColumnExist)
		{
			/*
               `access` int(10) unsigned DEFAULT NULL,

                ALTER TABLE yourtable ADD q6 VARCHAR( 255 ) after q5

                 $table  = 'your table name';
                 $column = 'q6'
                 $add = mysql_query("ALTER TABLE $table ADD $column VARCHAR( 255 ) NOT NULL");

				$db = JFactory::getDBO();
				$sql = "ALTER TABLE #__shoutbox ADD COLUMN user_id int(11) NOT NULL DEFAULT '0'";
				$db->setQuery($sql);
				$result = $db->query()

				 $db = JFactory::getDbo();
				$query='ALTER TABLE `#__virtuemart_categories_en_gb` ADD `short_desc` varchar(1200)';
				$db->setQuery($query);
				$result = $db->query();
            */

			//   `access` int(10) unsigned DEFAULT NULL
			$query = 'ALTER TABLE ' . $table . ' ADD ' . $field . ' INT  (10) UNSIGNED DEFAULT NULL';
			$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$ColumnExist = $db->execute();
			$msg .= '<br>' . '$ColumnExist (Add): ' . json_encode ($ColumnExist);
		}

		// Set all access values to '1'
		if ($ColumnExist)
		{
			// update your_table set likes = null
			$query = 'UPDATE ' . $table . ' SET ' . $field . '=1';
			$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$result = $db->execute();
			$msg .= '<br>' . '$result (update): ' . json_encode ($result);



		}
		return $msg;
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





	public function createMissingSqlFields()
	{
		$msg = "Model: createMissingSqlFields: " . '<br>';
		// $msg = '';

		$tables = $this->getTableListFromSqlFile();

		$msg .= 'Check for missing tables<br>';
		//--- create table if not exist
		foreach ($tables as $table) {
			$msg .= '   Table ' . $table . '<br>';
			// Original tabel names
			$msg .= createNotExistingTable($table);
		}

		$msg .= 'Check for missing files (columns) in tables<br>';
		//--- create table if not exist
		foreach ($tables as $table) {
			$msg .= '   Table ' . $table . '<br>';
			// Original columns
			$columns = getColumnsOfTable($table, $nnn, $mmm);
			$msg .= createNotExistingTable($table);
		}





		return $msg;
	}

	// * Original table names
	public function createNotExistingTable($table)
	{
		$msg = "Model: createMissingSqlFields: " . '<br>';


		return $msg;
	}

	// * Original table names
	public function getColumnsOfTable($table, $nnn, $mmm)
	{
		$msg = "Model: createMissingSqlFields: " . '<br>';


		return $msg;
	}


}