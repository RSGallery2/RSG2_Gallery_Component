<?php
defined('_JEXEC') or die;

// control center 
// ToDo:: rename to rsg_control and use as default ...

global $Rsg2DebugActive;

$Rsg2DebugActive = false; // ToDo: $rsgConfig->get('debug');
if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');
	
	// identify active file
	JLog::add('==> ctrl.ctrl.rsgallery2.php ');
}

jimport('joomla.application.component.controllerform');

// ToDo: Check which functions are used

class Rsgallery2ControllerRsgallery2 extends JControllerForm
{
	function galleries()
	{
		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.rsgallery2.php/function galleries');
		}
		//$this->setRedirect('index.php?option=com_rsgallery2&view=galleries');
		//$this->redirect();
	}	

	function ClearRsg2DbItems ()
	{
		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.rsgallery2.php/function ClearRsg2DbItems');
		}
		
		$msg = 'Database entries are deleted. ';
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

		//$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2.default_maintenance', $msg);
		$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2', $msg);
		//$this->redirect();
	}

	function DeleteAllRsg2Images ()
	{
		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.rsgallery2.php/function DeleteAllRsg2Images');
		}
		
		$msg = 'All RSG2 Images and thumbs are deleted. ';
		// $app->redirect($link, $msg, $msgType='message');

		//$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2.default_maintenance', $msg);
		$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2', $msg);
		//$this->redirect();
	}


	function ConsolidateDatabase ()
	{
		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.rsgallery2.php/function ConsolidateDatabase');
		}
		
		$msg = 'RSG2 database is consolidated. ';
		// $app->redirect($link, $msg, $msgType='message');

		//$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2.default_maintenance', $msg);
		$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2', $msg);
		//$this->redirect();
	}

	
	
	function RegenerateFromImages ()
	{
		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.rsgallery2.php/function RegenerateFromImages');
		}
		
		$msg = 'Regeneration from exiting image done. ';
		// $app->redirect($link, $msg, $msgType='message');

		//$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2.default_maintenance', $msg);
		$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2', $msg);
		//$this->redirect();
	}

	
	
	function OptimizeDatabase ()
	{
		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.rsgallery2.php/function OptimizeDatabase');
		}
		
		$msg = 'RSG2 database is reorganized. ';
		// $app->redirect($link, $msg, $msgType='message');

		//$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2.default_maintenance', $msg);
		$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2', $msg);
		//$this->redirect();
	}

	
}

