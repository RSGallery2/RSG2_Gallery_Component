



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
        $canAdmin = $user->authorise('core.manage');
        return $canAdmin;
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

    function Delete_1_5_LangFiles ()
    {
        $msg = "Delete_1_5_LangFiles: ";
        $msgType = 'notice';

        // \administrator\language\
        $startDir = JPATH_ADMINISTRATOR . '/language';
        $IsDeleted = $this->recursiveDelete_1_5_LangFiles ($startDir);
        if($IsDeleted) {
            $msg .= " is successful";
        }
        else {
            $msg .= " is !!! not !!! successful";
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
    }

    /**
     * @param $startDir Example: \administrator\language\
     * recursive delete joomla 1.5 version or older style component language files
     * @since version 4.3
     */
    function recursiveDelete_1_5_LangFiles($startDir) {

        $IsDeleted = false;

        if($startDir != '') {
            // ...original function code...
            // ...\en-GB\en-GB.com_rsgallery2.ini
            // ...\en-GB\en-GB.com_rsgallery2.sys.ini
            $files = array();

            /**
            //$files = preg_grep('~\.(jpeg|jpg|png)$~', scandir($dir_f));
            foreach (glob("/path/to/folder/*.txt") as $file) {
            $files[] = $file;
            }
            foreach ( glob( $fullPath_thumb.'*' ) as $filename ) {
            if( is_file( $filename )) unlink( $filename );
            }
            /**/

            $Directories = new RecursiveDirectoryIterator($startDir, FilesystemIterator::SKIP_DOTS);
            $Files = new RecursiveIteratorIterator($Directories);
            $LangFiles = new RegexIterator($Files, '/^.+\.com_rsgallery2\..*ini$/i', RecursiveRegexIterator::GET_MATCH);

            $msg = 'Found files: ';
            $IsFileFound = false;
            foreach ($LangFiles as $LangFile)
            {
                $IsFileFound = true;

                $msg .= '<br>' . $LangFile[0];
            }

            // One or more files found ?
            if($IsFileFound){
                $IsDeleted = true;
            } else {
                $msg .= 'No files found: ';
            }

            JFactory::getApplication()->enqueueMessage($msg, 'notice');
        }

        return $IsDeleted;
    }


} // class


