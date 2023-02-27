<?php
/**
 * @package        com_rsgallery2
 *
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2023 RSGallery2 Team
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @link           https://www.rsgallery2.org
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

use Joomla\CMS\File;
use Joomla\CMS\Folder;

/**
 * Script (instfile of Rsgallery2 Component
 *
 * @since 4.7.0
 *
 */
class Com_Rsgallery2InstallerScript extends InstallerScript
{
	protected $newRelease;
	protected $oldRelease;

	protected $oldManifestData;

	/**
	 * @var string
	 * @since 4.7.0
	 */
	protected $minimumJoomla;
	/**
	 * @var string
	 * @since 4.7.0
	 */
	protected $minimumPhp;

	// protected $rsg2_basePath;


	/**
	 * Extension script constructor.
	 *
	 * @since 4.7.0
	 *
	 */
	public function __construct()
	{
		$this->minimumJoomla = '4.0.0';
		$this->minimumPhp    = JOOMLA_MINIMUM_PHP;   // (7.2.5)

		// Check if the default log directory can be written to, add a logger for errors to use it
		if (is_writable(JPATH_ADMINISTRATOR . '/logs'))
		{
			// Get the date for log file name
			$date = Factory::getDate()->format('Y-m-d');


			$logOptions['format']    = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
			$logOptions['text_file'] = 'rsgallery2.install.log.' . $date . '.php';
			$logType                 = Log::ALL;
			$logChannels             = ['rsg2']; //jerror ...
			Log::addLogger($logOptions, $logType, $logChannels);

			try
			{
				Log::add(Text::_('\n>>Installer construct'), Log::INFO, 'rsg2');
			}
			catch (\RuntimeException $exception)
			{
				// Informational log only
			}
		}
	}

    /*-------------------------------------------------------------------------
    preflight
    ---------------------------------------------------------------------------
    This is where most of the checking should be done before install, update
    or discover_install. Preflight is executed prior to any Joomla install,
    update or discover_install actions. Preflight is not executed on uninstall.
    A string denoting the type of action (install, update or discover_install)
    is passed to preflight in the $type operand. Your code can use this string
    to execute different checks and responses for the three cases.
    -------------------------------------------------------------------------*/

    /**
	 * Function to act prior to installation process begins
	 *
	 * @param   string     $type       Which action is happening (install|uninstall|discover_install|update)
	 * @param   Installer  $installer  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @throws Exception
     * @since  4.7.0
     */
	public function preflight($type, $installer)
    {
		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_PREFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');

		// Check for the minimum PHP version before continuing
		if (version_compare(PHP_VERSION, $this->minimumPhp, '<'))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), Log::WARNING, 'jerror');
			Factory::getApplication()->enqueueMessage(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), 'error');

			return false;
		}

		// Check for the minimum RSG/Joomla version before continuing
		if (version_compare(JVERSION, $this->minimumJoomla, '<='))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), Log::WARNING, 'jerror');
			Factory::getApplication()->enqueueMessage(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), 'error');

			return false;
        }

		//--- new release version --------------------------------------

		$manifest         = $installer->getManifest();
		$this->newRelease = (string) $manifest->version;

		Log::add('newRelease:' . $this->newRelease, Log::INFO, 'rsg2');

		//--- old release version --------------------------------------

		$this->oldRelease = '0.0';

        if ($type === 'update') 
		{
			Log::add(Text::_('-> pre update'), Log::INFO, 'rsg2');

			//--- Read manifest with old version ------------------------

			// could also be done by $xml=simplexml_load_file of manfiest on
			// 'old'==actual RSG2 admin path $this->oldRelease = $xml->version;

			$this->oldRelease = $this->getOldVersionFromManifestParam();

			// old release not found but rsgallery2 data still kept in database -> error message
			if (empty ($this->oldRelease))
			{
				$outTxt = 'Can not install RSG2: Old Rsgallery2 data found in db or RSG2 folders. Please try to deinstall previous version or remove folder artifacts';
				Factory::getApplication()->enqueueMessage($outTxt, 'error');
				Log::add('oldRelease:' . outTxt, Log::WARNING, 'rsg2');

				// May be error on install ?
				// return false;

				$this->oldRelease = '%';
			}

			Log::add('oldRelease:' . $this->oldRelease, Log::INFO, 'rsg2');

        } else { // $type == 'install'

            JLog::add('-> pre freshInstall', JLog::DEBUG);
        }

		Log::add(Text::_('newRelease:') . $this->newRelease, Log::INFO, 'rsg2');
		Log::add(Text::_('exit preflight') . $this->newRelease, Log::INFO, 'rsg2');

        return true;
    }

    /*-------------------------------------------------------------------------
    install
    ---------------------------------------------------------------------------
    Install is executed after the Joomla install database scripts have
    completed. Returning 'false' will abort the install and undo any changes
    already made. It is cleaner to abort the install during preflight, if
    possible. Since fewer install actions have occurred at preflight, there
    is less risk that that their reversal may be done incorrectly.
    -------------------------------------------------------------------------*/
    /**
     * Method to install the extension
     *
     * @param InstallerAdapter $parent The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  4.7.0
     */
    public function install($parent)
    {
		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_INSTALL'), Log::INFO, 'rsg2');

// ToDo: yyy check what has to be done on instll !!! ? move to post install ?
/**
        require_once(JPATH_SITE . '/administrator/components/com_rsgallery2/includes/install.class.php');

        JLog::add('freshInstall', JLog::DEBUG);

        //Initialize install
        $rsgInstall = new rsgInstall();
        $rsgInstall->freshInstall();
**/

		Log::add(Text::_('exit install') , Log::INFO, 'rsg2');

		return true;
    }

    /*-------------------------------------------------------------------------
    update
    ---------------------------------------------------------------------------
    Update is executed after the Joomla update database scripts have completed.
    Returning 'false' will abort the update and undo any changes already made.
    It is cleaner to abort the update during preflight, if possible. Since
    fewer update actions have occurred at preflight, there is less risk that
    that their reversal may be done incorrectly.
    -------------------------------------------------------------------------*/
    /**
     * Method to update the extension
     *
     * @param InstallerAdapter $parent The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  4.7.0
     *
     */
    public function update($parent)
    {
		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_UPDATE'), Log::INFO, 'rsg2');

// ToDo: yyy check what has to be done on instll !!! ? move to post install ?
/*
        require_once(JPATH_SITE . '/administrator/components/com_rsgallery2/includes/VersionId.php');
        require_once(JPATH_SITE . '/administrator/components/com_rsgallery2/includes/install.class.php');

        // now that we know a previous rsg2 was installed, we need to reload it's config
        global $rsgConfig;
        $rsgConfig = new rsgConfig();

        //--- Initialize install  --------------------------------------------

        $rsgInstall = new rsgInstall();
        $rsgInstall->writeInstallMsg(JText::sprintf('COM_RSGALLERY2_MIGRATING_FROM_RSGALLERY2', $this->oldRelease), 'ok');

        /* Removed as plugins couldn't find lang files *
        //--- delete RSG2 J!1.5 language files ------------------------------

        // .../administrator/language/
        $startDir = JPATH_ADMINISTRATOR . '/language';
        $msg = '';
        $IsDeleted = $this->findAndDelete_1_5_LangFiles ($startDir, $msg);
        if($IsDeleted) {
            // Write action to user
            $msg = 'Deleted old RSGallery2 J!1.5 admin language files: <br>' . $msg;
            $rsgInstall->writeInstallMsg ($msg, 'ok');
        }

        $startDir = JPATH_SITE . '/language';
        $msg = '';
        $IsDeleted = $this->findAndDelete_1_5_LangFiles ($startDir, $msg);
        if($IsDeleted) {
            // Write action to user
            $msg = 'Deleted old RSGallery2 J!1.5 site language files: <br>' . $msg;
            $rsgInstall->writeInstallMsg ($msg, 'ok');
        }
        /**

        //--- install complete message --------------------------------

        // Now wish the user good luck and link to the control panel
        echo $rsgInstall->installCompleteMsg(JText::_('COM_RSGALLERY2_RSGALLERY_UPGRADE_IS_INSTALLED'));

        /* May be used later. Actual versions older then "3.2.0" are checked in preflight
            if (version_compare ($this->oldRelease, '3.2.0', 'lt' )) {

        actual
        JLog::add('Before migrate', JLog::DEBUG);

        //Initialize rsgallery migration
        $migrate_com_rsgallery = new migrate_com_rsgallery();

        JLog::add('Do migrate', JLog::DEBUG);
        //Migrate from earlier version
        $result = $migrate_com_rsgallery->migrate();

        if( $result === true ){
            $rsgInstall->writeInstallMsg( JText::sprintf('COM_RSGALLERY2_SUCCESS_NOW_USING_RSGALLERY2', $rsgConfig->get( 'version' )), 'ok');
        }
        else{
            $result = print_r( $result, true );
            $rsgInstall->writeInstallMsg( JText::_('COM_RSGALLERY2_FAILURE')."\n<br><pre>$result\n</pre>", 'error');
        }
        */

		Log::add(Text::_('exit update') , Log::INFO, 'rsg2');

		return true;
    }

    /*-------------------------------------------------------------------------
    postflight
    ---------------------------------------------------------------------------
    Postflight is executed after the Joomla install, update or discover_update
    actions have completed. It is not executed after uninstall. Postflight is
    executed after the extension is registered in the database. The type of
    action (install, update or discover_install) is passed to postflight in
    the $type operand. Postflight cannot cause an abort of the Joomla
    install, update or discover_install action.
    -------------------------------------------------------------------------*/
    /**
     * Function called after extension installation/update/removal procedure commences
     *
     * @param string $type The type of change (install, update or discover_install, not uninstall)
     * @param InstallerAdapter $parent The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  4.7.0
     *
     */
    public function postflight($type, $parent)
    {
		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_POSTFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');

		// fall back
		$installMsg = '';

		switch ($type)
		{

			case 'install':

				Log::add('post->install: updateDefaultParams', Log::INFO, 'rsg2');

				$this->updateDefaultParams($parent);


				$installMsg = $this->installMessage($type);

				Log::add('post->install: finished', Log::INFO, 'rsg2');

				break;

			case 'update':

				Log::add('post->update: init gallery tree', Log::INFO, 'rsg2');

				Log::add('post->install: updateDefaultParams', Log::INFO, 'rsg2');

				$this->updateDefaultParams($parent);

				$installMsg = $this->installMessage($type);

				Log::add('post->update: finished', Log::INFO, 'rsg2');

				break;

			case 'uninstall':

				$outText = 'Uninstall of RSG2 finished. <br>'
					. 'Configuration was deleted. <br>'
					. 'Galleries and images table may still exist';
				Log::add('post->uninstall: ' . $outText, Log::INFO, 'rsg2');
				// ToDo: check existence of galleries/images table and then write
                /**
				echo 'Uninstall of RSG2 finished. <br>Configuration may be deleted. <br>'
					. 'Galleries and images table may still exist';
				// ToDo: uninstall Message
				*/
				Factory::getApplication()->enqueueMessage($outText, 'info');

				// $installMsg = $this->uninstallMessage);

				Log::add('post->uninstall: finished', Log::INFO, 'rsg2');

				break;

			case 'discover_install':

				Log::add('post->discover_install: updateDefaultParams', Log::INFO, 'rsg2');

				$this->updateDefaultParams($parent);


				$installMsg = $this->installMessage($type);

				Log::add('post->discover_install: finished', Log::INFO, 'rsg2');

				break;

			default:

				break;
		}

		echo $installMsg;

		// wonderworld 'good by' icons finnern
		echo '<br><h4>&oplus;&infin;&omega;</h4><br>';
		Log::add(Text::_('--- exit postflight ------------'), Log::INFO, 'rsg2');

		return true;
    }

    /*-------------------------------------------------------------------------
    uninstall
    ---------------------------------------------------------------------------
    The uninstall method is executed before any Joomla uninstall action,
    such as file removal or database changes. Uninstall cannot cause an
    abort of the Joomla uninstall action, so returning false would be a
    waste of time
    -------------------------------------------------------------------------*/
    /**
     * Method to uninstall the extension
     *
     * @param InstallerAdapter $parent The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  4.7.0
     */
    public function uninstall($parent)
    {
		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_UNINSTALL'), Log::INFO, 'rsg2');

		// ToDo: enquire .. message to user
		Factory::getApplication()->enqueueMessage(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), 'error');

        Log::add(Text::_('exit uninstall'), Log::INFO, 'rsg2');

		return true;
    }

	/**
	 *
	 * Used in preflight update when the 'new' rsg2 files are not copied
	 * Can not use standard function therefore
	 * @return mixed|string
	 *
	 * @throws Exception
	 * @since 5.0.0
	 */
	protected function getOldVersionFromManifestParam()
	{
		//$oldRelease = '4.7.0.999';
		$oldRelease = '';

		$this->oldManifestData = $this->readRsg2ExtensionManifest();
		if (!empty ($this->oldManifestData['version']))
		{
			$oldRelease = $this->oldManifestData['version'];
		}

		return $oldRelease;
	}


	/**
	 * readRsg2ExtensionManifest
	 * Used in preflight update when the 'new' rsg2 files are not copied
	 * Can not use standard function therefore
	 *
	 * @return array
	 *
	 * @throws Exception
	 * @since 5.0.0
	 */
	protected function readRsg2ExtensionManifest()
	{
		$manifest = [];

		try
		{
			$db    = Factory::getDbo();
			$query = $db->getQuery(true)
				->select('manifest_cache')
				->from($db->quoteName('#__extensions'))
				->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
			$db->setQuery($query);

			$jsonStr = $db->loadResult();

			if (!empty ($jsonStr))
			{
				$manifest = json_decode($jsonStr, true);
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'readRsg2ExtensionManifest: Error executing query: "' . "" . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $manifest;
	}






    /*
     * sets parameter values in the component's row of the extension table
     *
    /**
     * @param $param_array
     *
    function setParams($param_array)
    {
        if (count($param_array) > 0) {
            // read the existing component value(s)
            $db = JFactory::getDbo();
            $db->setQuery('SELECT params FROM #__extensions WHERE name = "com_rsgallery2"');
            $params = json_decode($db->loadResult(), true);

            // add the new variable(s) to the existing one(s)
            foreach ($param_array as $name => $value) {
                $params[(string)$name] = (string)$value;
            }
            // store the combined new and existing values back as a JSON string
            $paramsString = json_encode($params);
            $db->setQuery('UPDATE #__extensions SET params = ' .
                $db->quote($paramsString) .
                ' WHERE name = "com_rsgallery2"');
            $db->execute();
        }
    }


    /**
     * @param $startDir Example: \administrator\language\
     * recursive delete joomla 1.5 version or older style component language files
     * @since 4.3
     *
    public function findAndDelete_1_5_LangFiles($startDir, &$msg)
    {

        $IsDeleted = false;

        if ($startDir != '') {
            // ...original function code...
            // ...\en-GB\en-GB.com_rsgallery2.ini
            // ...\en-GB\en-GB.com_rsgallery2.sys.ini
            $files = array();

            $Directories = new RecursiveDirectoryIterator($startDir, FilesystemIterator::SKIP_DOTS);
            $Files = new RecursiveIteratorIterator($Directories);
            $LangFiles = new RegexIterator($Files, '/^.+\.com_rsgallery2\..*ini$/i', RecursiveRegexIterator::GET_MATCH);

            $msg = '';
            $IsFileFound = false;
            foreach ($LangFiles as $LangFile) {
                $IsFileFound = true;

                $msg .= '<br>' . $LangFile[0];
                $IsDeleted = unlink($LangFile[0]);
                if ($IsDeleted) {
                    $msg .= ' is deleted';

                } else {
                    $msg .= ' is not deleted';
                }
            }

            return $IsFileFound;
        }
    }



    /**
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     *
     * @since version
     */
	protected function updateDefaultParams($parent)
	{
		try
		{

			Log::add(Text::_('upd (20) Rsg2ExtensionModel -----------------------'), Log::INFO, 'rsg2');

			Log::add(Text::_('upd (01) '), Log::INFO, 'rsg2');
			
			$Rsg2ExtensionModelFileName  = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/model/Rsg2ExtensionModel.php';
			$Rsg2ExtensionClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Model\Rsg2ExtensionModel';
			\JLoader::register($Rsg2ExtensionClassName, $Rsg2ExtensionModelFileName);
            $Rsg2ExtensionClass = new Rsgallery2\Component\Rsgallery2\Administrator\Model\Rsg2ExtensionModel();

			Log::add(Text::_('upd (03) '), Log::INFO, 'rsg2');

			$this->actualParams = $Rsg2ExtensionClass->readRsg2ExtensionConfiguration ();

			Log::add(Text::_('upd (04) '), Log::INFO, 'rsg2');

			$this->defaultParams = $Rsg2ExtensionClass->readRsg2ExtensionDefaultConfiguration();

			Log::add(Text::_('upd (05) '), Log::INFO, 'rsg2');

			$this->mergedParams = $Rsg2ExtensionClass->mergeDefaultAndActualParams ($this->defaultParams, $this->actualParams);

			Log::add(Text::_('upd (06) '), Log::INFO, 'rsg2');

			$Rsg2ExtensionClass->replaceRsg2ExtensionConfiguration ($this->mergedParams);

			Log::add(Text::_('upd (07) '), Log::INFO, 'rsg2');

		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: updateDefaultParams: '), Log::INFO, 'rsg2');
		}

		Log::add(Text::_('Exit updateDefaultParams'), Log::INFO, 'rsg2');
		
		return;
	}

}
