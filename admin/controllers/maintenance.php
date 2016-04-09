



<?php
defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.maintenanc.php ');
}


jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerMaintenance extends JControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function Cancel ()
	{
        /*
        global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function Cancel');
		}

		$msg = 'All RSG2 Images and thumbs are deleted. ';
		// $app->redirect($link, $msg, $msgType='message');
        */
        $msg = '';
		$msgType = 'notice';

		// ToDo: Use Jroute before link for setRedirect
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

    /**
     * Checks if user has root status (is re.admin')
     *
     * @return	bool
     */
    function IsUserRoot ()
    {
        $user = JFactory::getUser();
        $canAdmin = $user->authorise('core.admin');
        return $canAdmin;
    }


    //-------------------------------------------------
	function ClearRsg2DbItems ()
	{
		global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function ClearRsg2DbItems');
		}
		
		$msg = 'Database entries are deleted. ';
		$msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);

		// $app->redirect($link, $msg, $msgType='message');
		
/*		
		Delete All Data

It is possible to delete all rows in a table without deleting the table. This means that the table structure, attributes, and indexes will be intact:
DELETE FROM table_name;

or

DELETE * FROM table_name;



!! insert ...

http://www.java2s.com/Tutorial/Oracle/0080__Insert-Update-Delete/Deleteallrowsfromatable.htm


SQL>
SQL> -- prepare data
SQL> insert into Employee(ID,  First_Name, Last_Name, Start_Date,                     End_Date,                       Salary,  City,       Description)
  2               values ('01','Jason',    'Martin',  to_date('19960725','YYYYMMDD'), to_date('20060725','YYYYMMDD'), 1234.56, 'Toronto',  'Programmer')
  3  /

1 row created.

SQL> insert into Employee(ID,  First_Name, Last_Name, Start_Date,                     End_Date,                       Salary,  City,       Description)
  2                values('02','Alison',   'Mathews', to_date('19760321','YYYYMMDD'), to_date('19860221','YYYYMMDD'), 6661.78, 'Vancouver','Tester')
  3  /

1 row created.

SQL> insert into Employee(ID,  First_Name, Last_Name, Start_Date,                     End_Date,                       Salary,  City,       Description)
  2                values('03','James',    'Smith',   to_date('19781212','YYYYMMDD'), to_date('19900315','YYYYMMDD'), 6544.78, 'Vancouver','Tester')
  3  /

1 row created.

SQL> insert into Employee(ID,  First_Name, Last_Name, Start_Date,                     End_Date,                       Salary,  City,       Description)
  2                values('04','Celia',    'Rice',    to_date('19821024','YYYYMMDD'), to_date('19990421','YYYYMMDD'), 2344.78, 'Vancouver','Manager')
  3  /

*/
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function DeleteAllRsg2Images ()
	{
		global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function DeleteAllRsg2Images');
		}
		
		$msg = 'All RSG2 Images and thumbs are deleted. ';
		$msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}


	function ConsolidateDatabase ()
	{
		global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function ConsolidateDatabase');
		}
		
		$msg = 'RSG2 database is consolidated. ';
		$msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	
	
	function OptimizeDatabase ()
	{
		global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function OptimizeDatabase');
		}
		
		$msg = 'RSG2 database is reorganized. ';
		$msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	
	function consolidateDB()
	{
        $msg = "consolidateDB: ";
        $msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
    }

	function regenerateThumbs()
	{
        $msg = "regenerateThumbs: ";
        $msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function optimizeDB()
	{
        $msg = "optimizeDB: ";
        $msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function viewConfigPlain()
	{
        $msg = "viewConfigPlain: ";
        $msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function editConfigRaw()
	{
        $msg = "editConfigRaw: ";
        $msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function compareDb2SqlFile()
	{
		$msg = "compareDb2SqlFile: ";
		$msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}


}


