<?php
/**
* This file contains the install routine for RSGallery2
* @version $Id: install.class.php 1088 2012-07-05 19:28:28Z mirjam $
* @package RSGallery2
* @copyright (C) 2003 - 2015 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

// no direct access
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');

//require_once( $rsgClasses_path . 'file.utils.php' );

global $rsgConfig;
global $rsgVersion;

if( !isset( $rsgConfig )){
    
	JLog::add('install.class.php: require once: config.class.php /version.rsgallery2.php', JLog::DEBUG);
	
    require_once( JPATH_SITE. '/administrator/components/com_rsgallery2/includes/config.class.php' );
    require_once( JPATH_SITE. '/administrator/components/com_rsgallery2/includes/version.rsgallery2.php' );

	JLog::add('create config /version', JLog::DEBUG);
		
    $rsgVersion = new rsgalleryVersion();
    $rsgConfig = new rsgConfig( false );

    // report all errors if in debug mode
    if($rsgConfig->get('debug'))
        error_reporting(E_ALL);
}

/**
* Install class
* @package RSGallery2
* @author Ronald Smit <webmaster@rsdev.nl>
*/
class rsgInstall {
    /** @var string RSGallery base directory */
    var $galleryDir;
    /** @var string Directory to hold original image */
    var $dirOriginal;
    /** @var string Directory to hold thumbnail */
    var $dirThumbs;
    /** @var string Directory to hold display image */
    var $dirDisplay;
	/** @var string Directory to hold watermarked image */
	var $dirWatermarked;
	/** @var array Table list of RSGallery2 */
    var $tablelistNew;
    /** @var array Table list of old RSGallery versions */
    var $tablelistOld;
    /** @var array List migrator class instances */
    var $galleryList;
    /** @var array List of allowed image formats */
    var $allowedExt;
    
    /** Constructor */
    function rsgInstall(){

		JLog::add('Constructor rsgInstall class', JLog::DEBUG);
		
        $app =JFactory::getApplication();
		
		if (!defined("JURI_SITE")){
			define('JURI_SITE', $app->isSite() ? JUri::base() : JUri::root());
		}
		
        $this->galleryDir   = '/images/rsgallery';
        $this->dirThumbs    = '/images/rsgallery/thumb';
        $this->dirOriginal  = '/images/rsgallery/original';
        $this->dirDisplay   = '/images/rsgallery/display';
		$this->dirWatermarked  = '/images/rsgallery/watermarked';
		
        $this->tablelistNew = array('#__rsgallery2_galleries','#__rsgallery2_files','#__rsgallery2_comments','#__rsgallery2_config', '#__rsgallery2_acl');
        $this->tablelistOld = array('#__rsgallery','#__rsgalleryfiles','#__rsgallery_comments','');
		
        //TODO: this should use the master list in imgUtils
        $this->allowedExt   = array("jpg","gif","png");

		JLog::add('rsgInstall: exit constructor', JLog::DEBUG);

    }
    /** For debug purposes only */
    function echo_values(){
		echo JText::_('COM_RSGALLERY2_THUMBDIRECTORY_IS').$this->dirThumbs;
    }
	
    /** 
     * Creates the default gallery directory structure
	 * ToDo: create index.html for each folder if not exists
     */
    function createDirStructure() {
        
		JLog::add('rsgInstall: createDirStructure', JLog::DEBUG);
		
        $dirs = array($this->galleryDir, $this->dirOriginal, $this->dirThumbs, $this->dirDisplay, $this->dirWatermarked);
        $count = 0;
        
        foreach ($dirs as $dir) {
			if (file_exists(JPATH_SITE.$dir) && is_dir(JPATH_SITE.$dir)) {
				// Dir already exists, next
				$this->writeInstallMsg(JText::sprintf('COM_RSGALLERY2_ALREADY_EXISTS', $dir),"ok");
			}
			else {
				if(@mkdir(JPATH_SITE.$dir, 0777)) {
					$this->writeInstallMsg(JText::sprintf('COM_RSGALLERY2_FOLDER_IS_CREATED', $dir),"ok");
					$count++;
				}
				else {
					$this->writeInstallMsg(JText::sprintf('COM_RSGALLERY2_FOLDER_COULD_NOT_BE_CREATED', $dir),"error");
				}
			}
        }
    }

    /**
     * Writes an installation status message 
     * @param string $msg Message to write
     * @param string $type Type of message (ok,error)
     */
    static function writeInstallMsg($msg, $type = NULL) {
        if ($type == "ok") {
            $icon = "tick.png";
		} elseif ($type == "error") {
            $icon = "publish_x.png";
		} else {
            $icon = "downarrow.png";
		}
	?>
        <div align="center">
        <table width="500"><tr><td>
			<table class="adminlist" border="1">
			<tr>
				<td width="40">
					<img src="<?php echo JURI_SITE;?>/administrator/components/com_rsgallery2/images/<?php echo $icon;?>" alt="" border="0">
				</td>
				<?php if( $type=='error' ): ?>
				<td>
					<pre><?php print_r( $msg );?></pre>
				</td>
				<?php else: ?>
				<td>
					<?php echo $msg;?>
				</td>
				<?php endif; ?>
			</tr>
			</table>
        </td></tr>
		</table>
        </div>
	<?php
	}
        
     /**
      * Shows the "Installation complete" box with a link to the control panel
      */
    /**
     * @param string $msg
     */
     static function installComplete($msg = null){
		if($msg == null) $msg = JText::_('COM_RSGALLERY2_INSTALLATION_OF_RSGALLERY_IS_COMPLETED');
		?>
		<div align="center">
			<table width="500"><tr><td>
				<table class="adminlist" border="1">
				<tr>
					<td colspan="2">
						<div align="center">
							<h2><?php echo $msg; ?></h2> 
							<?php //echo JText::_('COM_RSGALLERY2_INSTALL_STATUS_MSGS')?>
							<br>
							<a href="index.php?option=com_rsgallery2">
								<img src="<?php echo JURI_SITE.'administrator/components/com_rsgallery2/images/icon-48-config.png';?>" alt=" <?php echo JText::_('COM_RSGALLERY2_CONTROL_PANEL') ?>" width="48" height="48" border="0">
								<h2>
									<?php echo JText::_('COM_RSGALLERY2_CONTROL_PANEL') ?>
								</h2>
							</a>
						</div>
					</td>
				</tr>
				</table>
			</td></tr></table>
		</div>
        <?php
    }
    	
	/**
	* Create dir structure, save config and tell user ... 
	*/
    function freshInstall() {
        global $rsgConfig;
		
        echo '<b>'.JText::_('COM_RSGALLERY2_FRESH_INSTALL').'</b>';
		
        //Create new directories
        $this->createDirStructure();
        
		//Create RSGallery2 table structure
        //$this->createTableStructure();

        // Save config to populate database with default config values
        $rsgConfig->saveConfig();
        
        //Now wish the user good luck and link to the control panel
        $this->installComplete();
    }
    
    
}	// End class rsgInstall



?>