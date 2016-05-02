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

class SqlInstallFile
{
	protected $sqlPathFileName; //

	/**
	 * @var string []
	 */

	protected $tableQueries;
	protected $namedQueries;

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
	public function __construct($fileName=JPATH_COMPONENT_ADMINISTRATOR.'/sql/install.mysql.utf8.sql')
	{
		$this->sqlPathFileName = $fileName;
	}
    
	/*------------------------------------------------------------------------------------
	__construct()
	------------------------------------------------------------------------------------*/	
	/*
	public static function FromFile ($file='') 
	{
    	$instance = new self();
		$instance LoadFile ($file);
		return $instance;
	}
	/**

	public function LoadFile ($file='')
    {
    }
	/**/

	private function extractQueries (){

		if (empty($this->tableQueries))
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
			$this->tableQueries = JDatabaseDriver::splitSql($buffer);
		}

		return $this->tableQueries;
	}

	public function getTableNamesList()
	{
		// Create only once
		if (empty ($this->tableNamesList))
		{
			// Does file need to be read
			if (empty ($this->tableQueries))
			{
				// sets $this->tableQueries and $this->namedQueries
				$this->getQueries ();
			}
		}

		return $this->tableNamesList;
	}

	private function getQueries ()
	{
		$this->tableQueries = $this->extractQueries();
		$this->namedQueries = null;
		
		// Process each query in the $queries array (split out of sql file).
		foreach ($this->tableQueries as $query) {
			$tableName = $this->ExtractTableNameFromQuery($query);
			if (!empty ($tableName)) {
				$this->tableNamesList[] = $tableName;
				// Access query by name
				$this->namedQueries [$tableName] = $query;
			}
		}

		return $this->tableQueries;
	}	
		
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
					$TableProperties [] = $ColumnProperties;
				}
			}
			//$msg .= $Idx . ': ' . $line . '<br>';

		}

		return $TableProperties;
	}


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
	private function ExtractColumnNameFromQueryLine ($queryLine, &$EndPos)
	{
		$tableName = '';

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
				$tableName = substr($queryLine, $StartPos + 1, $EndPos - $StartPos - 1);
			}
		}

		return $tableName;
	}

	//  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	// First part must be already defined
	private function ExtractColumnPropertiesFromQueryLine ($queryLine, $StartPos)
	{
		// Find end of line -> ','
		$EndPos = strpos($queryLine, ",", $StartPos);

		$Properties = substr ($queryLine, $StartPos, $EndPos - $StartPos);

		return $Properties;
	}

	public function getTablePropertiesList ()
	{
		// Create only once
		if (empty ($this->tablePropertiesList))
		{
			// file needs to be read
			if (empty ($this->tableQueries))
			{
				// sets $this->tableQueries and $this->namedQueries
				$this->getQueries ();
			}

			// if (count($this->tableQueries) == 0)
			if (empty ($this->tableQueries))
			{
				// No queries to process
				return false;
			}

			// Assign queries and their properties to Content object 
			foreach ($this->namedQueries as $tableName => $query)
			{
				$TableProperties = $this->ExtractTablePropertiesFromQuery ($query);
				$this->tablePropertiesList [$tableName] = $TableProperties;
			}
		}

		return $this->namedQueries;
	}

	public function getTableQuery ($tableName)
	{
		if (empty ($this->tablePropertiesList))
		{
			$this->getTablePropertiesList ();
		}
            
        return $this->tablePropertiesList [$tableName];
	}

	public function getColumnsPropertiesOfTable ($tableName)
	{
		if (empty ($this->tablePropertiesList))
		{
			$this->getTablePropertiesList ();
		}


        return $this->tablePropertiesList [$tableName];
	}




}





