<?php
/**
* access to the content of the 'install.mysql.utf8.sql' file
* @package Rsgallery2
* @copyright (C) 2016 - 2016 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author finnern
* RSGallery2 is Free Software
*/
// no direct access
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');


/*------------------------------------------------------------------------------------
SqlInstallFile
--------------------------------------------------------------------------------------
 \com_rsgallery2\sql\install.mysql.utf8.sql

------------------------------------------------------------------------------------*/

/**
 * Class SqlInstallFile
 */
class SqlInstallFile
{
    /**
     * @var string
     */
	protected $sqlPathFileName; //

	/**
	 * @var string [] list of 'create table queries'
	 */
	protected $sqlQueries;

    /**
     * Same as sql queries but in form table name -> query
     * @var array[string]string list of 'create table queries'
     */
	protected $tableQueries;

	/**
	 * @var string []
	 */
	private $tableNamesList;

	/**
	 * @var string [] Tables[][column IDs][additional Properties]
	 */
	private $tablePropertiesList;

	/*------------------------------------------------------------------------------------
	__construct()
	------------------------------------------------------------------------------------*/
	/**
	 * SqlInstallFile constructor.
	 *
	 * @param string $fileName
	 */
	public function __construct($fileName='')
	{
		if ($fileName == '') {
			$fileName = JPATH_COMPONENT_ADMINISTRATOR.'/sql/install.mysql.utf8.sql';
		}

		$this->sqlPathFileName = $fileName;
	}

	/*------------------------------------------------------------------------------------
	extract data from file
	------------------------------------------------------------------------------------*/
	
	/**
     * Extract the queries in the sql associated file
     * It will be a list of create table queries
     * @return bool|string[] list of create table queries
     */
	private function extractQueries (){

		if (empty($this->sqlQueries))
		{
			// Check that sql files exists before reading. Otherwise raise error for rollback
			if (!file_exists($this->sqlPathFileName))
			{
				JLog::add(JText::sprintf('JLIB_INSTALLER_ERROR_SQL_FILENOTFOUND', $this->sqlPathFileName), JLog::WARNING, 'jerror');

				return false;
			}

			$buffer = file_get_contents($this->sqlPathFileName);

			// Graceful exit and rollback if read not successful
			if ($buffer === false)
			{
				JLog::add(JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'), JLog::WARNING, 'jerror');

				return false;
			}

			// Create an array of queries from the sql file
			$this->sqlQueries = JDatabaseDriver::splitSql($buffer);
		}

		return $this->sqlQueries;
	}

    /**
     * Extracts the used table name from the given query
     * @param string $query
     * @return string
     */
	private function ExtractTableNameFromQuery ($query)
	{
		// check if command matches (nearly) a table command
		$pos = strpos($query, 'TABLE');
		if ($pos === false)
		{
			// ToDo: Notice

			return '';
		}

		// ToDo: May be done with regular expression in one go
		// find beginning of name
		$StartPos = strpos($query, "`", $pos +1);

		// Find end of name
		$EndPos = strpos($query, "`", $StartPos +1);

		$tableName = substr ($query, $StartPos+1, $EndPos - $StartPos -1);

		return $tableName;
	}

	/**
	 * @param $query
	 *
	 * @return array
	 */
	/**
	 * ToDO: continue doc yyyyyyyyyyyyyyyyyy
	 * @param $query
	 * @return array
	 */
	private function ExtractTablePropertiesFromQuery ($query)
	{
		$TableProperties = array ();

		$queryLines = preg_split('/\n|\r\n?/', $query);

		$Idx = -1;
		foreach ($queryLines as $queryLine) {
			$Idx += 1;
			// leave out command line
			if($Idx > 0)
			{
				$queryLine = trim($queryLine);
				$ColumnProperties = $this->ExtractColumnPropertiesFromLine($queryLine);
				if (!empty ($ColumnProperties))
				{
					$TableProperties [$ColumnProperties->name] = $ColumnProperties->properties;
					// $TableProperties [] = $ColumnProperties;
				}
			}
		}

		return $TableProperties;
	}

	/**
	 * @param $queryLine
	 *
	 * @return stdClass|string
	 */
	private function ExtractColumnPropertiesFromLine ($queryLine)
	{
		$Properties = new stdClass();


		$Properties->name = $this->ExtractColumnNameFromQueryLine ($queryLine, $EndPos);
		if (empty ($Properties->name)) {
			// no name found -> part of table command
			return '';
		}

		$Properties->properties = $this->ExtractColumnPropertiesFromQueryLine ($queryLine, $EndPos+2);

		return $Properties;
	}

	//  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	/**
	 * @param $queryLine
	 * @param $EndPos
	 *
	 * @return string
	 */
	private function ExtractColumnNameFromQueryLine ($queryLine, &$EndPos)
	{
		$columnName = '';

		// ToDo: May be done with regular expression in one go
		// find beginning of name
		$StartPos = strpos($queryLine, "`", 0);
		//if ($StartPos )
		// Pos found
		if ($StartPos !== false) {
			// At start of string
			if ($StartPos == 0) {
				// Extract to end of name
				$EndPos = strpos($queryLine, "`", $StartPos + 1);
				$columnName = substr($queryLine, $StartPos + 1, $EndPos - $StartPos - 1);
			}
		}

		return $columnName;
	}

	//  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	// First part must be already defined
	/**
	 * @param $queryLine
	 * @param $StartPos
	 *
	 * @return string
	 */
	private function ExtractColumnPropertiesFromQueryLine ($queryLine, $StartPos)
	{
		// Find end of line -> ','
		$EndPos = strpos($queryLine, ",", $StartPos);
		$PropertyLine = substr ($queryLine, $StartPos, $EndPos - $StartPos);

		// `id` int(11) NOT NULL auto_increment,
		// `parent` int(11) NOT NULL default 0,
		// `name` varchar(255) NOT NULL default '',
		// `description` text NOT NULL,
		// `published` tinyint(1) NOT NULL default '0',
		// `uid` int(11) unsigned NOT NULL default '0',
		// `params` text,
		// ...

		//--- Just return type + signed ----------------------

/* debug *
		$TestPos = strpos($queryLine, 'params', 0);
		if ($TestPos !== FALSE) {
			$Properties = ' text';
		}
/**/
		// after type before NOT
		$EndPos = strpos($PropertyLine, ' ', 0);
		if($EndPos === FALSE)
		{
			$EndPos = strlen ($PropertyLine);
		}
		$Properties = substr($PropertyLine, 0, $EndPos);

		// May contain (number). Remove it
		$EndPos = strpos($Properties, '(', 0);
		if($EndPos !== FALSE)
		{
			$Properties = substr($PropertyLine, 0, $EndPos);
		}

		if (strpos($queryLine, 'unsigned', $EndPos) !== FALSE) {
			$Properties .= ' unsigned';
		}

		return $Properties;
	}

	/*------------------------------------------------------------------------------------
	Access to data
	------------------------------------------------------------------------------------*/

	/**
	 * Extract the queries in the sql associated file if not already existing
	 * Creates the named list additionally
	 * @return bool|string[] list of 'create table queries'
	 */
	private function getSqlQueries ()
	{
		// file needs to be read
		if (empty ($this->sqlQueries))
		{
			$this->sqlQueries = $this->extractQueries();
		}
		
		return $this->sqlQueries;
	}

	/**
	 * array[string]string list of 'create table queries'
	 * @return string[] list of table names with queries from sql queries file
	 */
	public function getTableQueries()
	{
		// Create only once
		if (empty ($this->tableQueries))
		{
			$this->tableQueries = array ();

			// Process each query in the $queries array (split out of sql file).
			foreach ($this->getSqlQueries() as $query) {
				$tableName = $this->ExtractTableNameFromQuery($query);
				if (!empty ($tableName)) {
					// Access query by name
					$this->tableQueries [$tableName] = $query;
				}
			}
		}

		return $this->tableQueries;
	}

	/**
	 * @return string[] list of table names from sql queries file
	 */
	public function getTableNames()
	{
		// Create only once
		if (empty ($this->tableNamesList))
		{
			$this->tableNamesList = array ();

			// Process each query in the $queries array (split out of sql file).
			foreach ($this->getSqlQueries() as $query) {
				$tableName = $this->ExtractTableNameFromQuery($query);
				if (!empty ($tableName)) {
					$this->tableNamesList[] = $tableName;
				}
			}
		}

		return $this->tableNamesList;
	}

	/**
	 * @param $tableName
	 *
	 * @return array
	 */
	public function getTableColumns ($tableName)
	{
		$ColumnNames = array ();

		$tablePropertiesList = $this->getTablePropertiesList ();
		if (! empty ($tablePropertiesList))
		{
			$tableProperties = $tablePropertiesList [$tableName];

			if (! empty ($tableProperties))
			{
				foreach ($tableProperties as $name => $property)
				{
					$ColumnNames [$name] = $property;
				}
			}
		}

		return $ColumnNames;
	}

	/**
	 * @param $tableName
	 *
	 * @return array
	 */
	public function getTableColumnNames ($tableName)
	{
		$ColumnNames = array ();

		$tablePropertiesList = $this->getTablePropertiesList ();
		if (! empty ($tablePropertiesList))
		{
			$tableProperties = $tablePropertiesList [$tableName];

			if (! empty ($tableProperties))
			{
				foreach ($tableProperties as $name => $property)
				{
					$ColumnNames [] = $name;
				}
			}
		}

		return $ColumnNames;
	}

	/**
	 * array[string]string list of 'create table queries'
	 * @return string[] list of table names with queries from sql queries file
	 */
	/**
	 * @param $TableName
	 *
	 * @return string
	 */
	public function getTableQuery($TableName)
	{
		$query = '';
		
		$tableQueries = $this->getTableQueries ();
		if (! empty($tableQueries)) {
			$query = $tableQueries [$TableName];
		}
		
		return $query;
	}

	/**
	 * @return string[]
	 */
	public function getTablePropertiesList ()
	{
		// Create only once
		if (empty ($this->tablePropertiesList))
		{
			$tableQueries = $this->getTableQueries();
			if (!empty($tableQueries))
			{
				// Assign queries and their properties to Content object 
				foreach ($tableQueries as $tableName => $query)
				{
					$TableProperties = $this->ExtractTablePropertiesFromQuery($query);
					$this->tablePropertiesList [$tableName] = $TableProperties;
				}
			}
		}
		
		return $this->tablePropertiesList;
	}

	/**
	 * @param $tableName
	 *
	 * @return array|string
	 */
	public function getColumnsPropertiesOfTable ($tableName)
	{
		$ColumnsProperties = array ();

		$tablePropertiesList = $this->tablePropertiesList;
		if (! empty ($tablePropertiesList))
		{
			$ColumnsProperties = $tablePropertiesList [$tableName];
		}
		
		return $ColumnsProperties;
	}
	
}





