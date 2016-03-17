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
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg);


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
		global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function DeleteAllRsg2Images');
		}
		
		$msg = 'All RSG2 Images and thumbs are deleted. ';
		// $app->redirect($link, $msg, $msgType='message');

		//$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2.default_maintenance', $msg);
		$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2', $msg);
		//$this->redirect();
	}


	function ConsolidateDatabase ()
	{
		global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function ConsolidateDatabase');
		}
		
		$msg = 'RSG2 database is consolidated. ';
		// $app->redirect($link, $msg, $msgType='message');

		//$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2.default_maintenance', $msg);
		$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2', $msg);
		//$this->redirect();
	}

	
	
	function OptimizeDatabase ()
	{
		global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function OptimizeDatabase');
		}
		
		$msg = 'RSG2 database is reorganized. ';
		// $app->redirect($link, $msg, $msgType='message');

		//$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2.default_maintenance', $msg);
		$this->setRedirect('index.php?option=com_rsgallery2&view=rsg2', $msg);
		//$this->redirect();
	}

	
	function consolidateDB()
	{
        $msg = "consolidateDB: ";
        $msgType = 'notice';

        echo 'consolidateDB: Not implemented yet';

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
    }

	function regenerateThumbs()
	{
        $msg = "regenerateThumbs: ";
        $msgType = 'notice';

        echo 'regenerateThumbs: Not implemented yet';

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function optimizeDB()
	{
        $msg = "optimizeDB: ";
        $msgType = 'notice';

		echo 'optimizeDB: Not implemented yet';

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function viewConfigPlain()
	{
        $msg = "viewConfigPlain: ";
        $msgType = 'notice';

        echo 'config_dumpVars: Not implemented yet';

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function editConfigRaw()
	{
        $msg = "editConfigRaw: ";
        $msgType = 'notice';

        echo 'config_rawEdit: Not implemented yet';

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function purgeImagesAndData()
	{
        $msg = "removeImagesAndData: ";
        $msgType = 'notice';

        //Access check
        $canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
        if (!$canAdmin) {
            // return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
//			JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
//			return;	// 150518 Does not return JError::raiseWarning object $error

            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);

//            $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
        } else {

            //--- delete all images ----------------------------------------
/*
            $fullPath_thumb = JPATH_ROOT.$rsgConfig->get('imgPath_thumb') . '/';
            $fullPath_display = JPATH_ROOT.$rsgConfig->get('imgPath_display') . '/';
            $fullPath_original = JPATH_ROOT.$rsgConfig->get('imgPath_original') . '/';

            //--- delete all images ----------------------------------------
            // remove thumbnails
            $msg .=  JText::_('COM_RSGALLERY2_REMOVING_THUMB_IMAGES');
            foreach ( glob( $fullPath_thumb.'*' ) as $filename ) {
                if( is_file( $filename )) unlink( $filename );
            }

            // remove display images
            $msg .=  JText::_('COM_RSGALLERY2_REMOVING_ORIGINAL_IMAGES');
            foreach ( glob( $fullPath_display.'*' ) as $filename ) {
                if( is_file( $filename )) unlink( $filename );
            }

            // remove display images
            $msg .=  JText::_('COM_RSGALLERY2_REMOVING_ORIGINAL_IMAGES');
            foreach ( glob( $fullPath_original.'*' ) as $filename ) {
                if( is_file( $filename )) unlink( $filename );
            }

            //--- delete images reference in db ---------------------------------
            $msg .= this->removeImageReferences();


            $msg .= ( JText::_('COM_RSGALLERY2_PURGED'), true );
*/

        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function removeImagesAndData()
	{
		$msg = "removeImagesAndData: ";
		$msgType = 'notice';
		
		//Access check
		$canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
		if (!$canAdmin) {
			// return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
//			JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
//			return;	// 150518 Does not return JError::raiseWarning object $error 
			
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);			

			$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
		} else {

            //--- delete all images ----------------------------------------
/*
            $fullPath_thumb = JPATH_ROOT.$rsgConfig->get('imgPath_thumb') . '/';
//ToDo:            Check 4 valid path !
//            passthru( "rm -r ".$fullPath_thumb);

            $fullPath_display = JPATH_ROOT.$rsgConfig->get('imgPath_display') . '/';
//ToDo:            Check 4 valid path !
//            passthru( "rm -r ".$fullPath_display);

            $fullPath_original = JPATH_ROOT.$rsgConfig->get('imgPath_original') . '/';
//ToDo:            Check 4 valid path !
//            passthru( "rm -r ".$fullPath_original);

            passthru( "rm -r ".JPATH_SITE."/images/rsgallery");

            //--- delete all data ----------------------------------------
			
			// HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_USED_RM_MINUS_R_TO_ATTEMPT_TO_REMOVE_JPATH_SITE_IMAGES_RSGALLERY') );
			$msg = $msg . JText::_('COM_RSGALLERY2_USED_RM_MINUS_R_TO_ATTEMPT_TO_REMOVE_JPATH_SITE_IMAGES_RSGALLERY');

            // ToDO: use model to delete data
            // load model -> drop data


            // call remove
			$msg = $msg . this->removeImageReferences ();

			//			HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_REAL_UNINST_DONE') );
			$msg = $msg . JText::_('COM_RSGALLERY2_REAL_UNINST_DONE');
			
			// ToDo: Message you may now deinstall and reinstall ... as all data and tables are gone
			
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);			
*/

			$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
		}

	}

	function compareDb2SqlFile()
	{
		$msg = "compareDb2SqlFile: ";
		$msgType = 'notice';

		echo 'compareDb2SqlFile: Not implemented yet';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}


}


