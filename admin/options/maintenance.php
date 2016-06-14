<?php
/**
* Maintenance options for RSGallery2
* @version $Id: maintenance.php 1085 2012-06-24 13:44:29Z mirjam $
* @package RSGallery2
* @copyright (C) 2003 - 2016 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

// no direct access
defined( '_JEXEC' ) or die();

require_once( $rsgOptions_path . 'maintenance.html.php' );
require_once( $rsgOptions_path . 'maintenance.class.php' );

// Only those with core.manage can get here via $rsgOption = maintenance
// Check if core.admin is allowed
if (!JFactory::getUser()->authorise('core.admin', 'com_rsgallery2')) {
	// return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
    JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
	return;
} else {
	$input =JFactory::getApplication()->input;
	//$cid = JRequest::getVar("cid", array(), 'default', 'array' );
	$cid = $input->get( 'cid', array(), 'ARRAY');
	//$task = JRequest::getVar( 'task', null);
	$task = $input->get( 'task', '', 'CMD'); 

	switch ($task) {
		/* Regenerate thumbs calls */
		case 'regenerateThumbs':
			HTML_RSGALLERY::RSGalleryHeader('cpanel', JText::_('COM_RSGALLERY2_MAINT_REGEN'));
			regenerateImages();
			HTML_RSGALLERY::RSGalleryFooter();
			break;
		case 'executeRegenerateThumbImages':
			executeRegenerateThumbImages();
			break;
		case 'executeRegenerateDisplayImages';
			executeRegenerateDisplayImages();
			break;
		/* Consolidate database calls */
		case 'consolidateDB':
			consolidateDB();
			break;
		case 'createImages':
			createImages();
			break;
		case 'deleteImages':
			deleteImages();
			break;
		case 'createDbEntries':
			createDbEntries();
			break;
			
		/* Optimize DB calls*/
		case 'optimizeDB':
			optimizeDB();
			break;
		
		/* Migration calls ToDo: deprecated * /
		case 'showMigration':
			HTML_RSGALLERY::RSGalleryHeader('cpanel', JText::_('COM_RSGALLERY2_MIGRATION_OPTIONS'));
			showMigration();
			HTML_RSGALLERY::RSGalleryFooter();
			break;
		case 'doMigration':
			doMigration();
			break;
			/**/
		case 'test':
			test();
			break;
		default:
            /*
            // menu_rsg2_submenu::addRSG2Submenu ();
            HTML_RSGALLERY::RSGallerySidebar();
			HTML_RSGALLERY::RSGalleryHeader('cpanel', JText::_('COM_RSGALLERY2_MAINT_HEADER'));
			showMaintenanceCP( $option );
			HTML_RSGALLERY::RSGalleryFooter();
			*/
            $msg = 'Unexpected maintenance task: "' . $task . '"';
    		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg);

            break;
	}	// end task switch
}

/**
 *
 */
function test() {
	// http://JOOMLA/administrator/index.php?option=com_rsgallery2&rsgOption=maintenance&task=test
	echo '<a href='.JRoute::_('index.php?option=com_rsgallery2&rsgOption=maintenance&task=test').'>function test() in admin/options/maintenance </a><p></p>';
	// Put test code here:
	
	//echo galleryUtils::newImages(1);
	
	




}// end of function test()

/**
 * Shows Migration main screen
 * It shows detected gallerysystem and offers a migration option
 * @deprecated ToDo: remove
 * /
function showMigration() {
    require_once(JPATH_RSGALLERY2_ADMIN.'/includes/install.class.php');
    
    //Initialize new install instance
    $rsgInstall = new rsgInstall();

    if (isset($_REQUEST['type']))
        $type = mosGetParam ( $_REQUEST, 'type'  , '');
    else
        $type = NULL;


	if( $type=='' ) {
		$rsgInstall->showMigrationOptions();	
	} else {
        $result = $rsgInstall->doMigration( $type );
        if( $result !==true ) {
            echo $result;
            HTML_RSGallery::showCP();
    	} else {
        	echo JText::_('COM_RSGALLERY2_MIGRATION_SUCCESSFUL');
        	HTML_RSGallery::showCP();
    	}
	}
}
/**/

/**
 * @throws Exception
 * @deprecated ToDo: remove
 * /
function doMigration() {
	//$type  	= JRequest::getVar('type', null);
	$input =JFactory::getApplication()->input;
	$type = $input->get( 'type', null);
	
	require_once(JPATH_RSGALLERY2_ADMIN.'/includes/install.class.php');
	
	$migrate_class = "migrate_".$type; 
	$migrate = new $migrate_class;
	$migrate->migrate();
}
/**/

/**
 * Shows Control Panel for maintenance of RSGallery2
 */
function showMaintenanceCP() {
	html_rsg2_maintenance::showMaintenanceCP();
}

/**
 *
 */
function regenerateImages() {
	//Select the right gallery, multiple galleries or select them all
	$lists['gallery_dropdown'] = galleryUtils::galleriesSelectList(null, "gid[]", true, "MULTIPLE");
	
	html_rsg2_maintenance::regenerateImages($lists);
}
/**
 * Function will regenerate thumbs for a specific gallery or set of galleries
 * Perhaps by sampling the oldest thumb from the gallery and checking dimensions against current setting.
 */
/**
 * @throws Exception
 */
function executeRegenerateThumbImages() {
//	global $rsgConfig;
	$app = JFactory::getApplication();
	$error = 0;
	//$gid = JRequest::getVar( 'gid', array());
	$input =JFactory::getApplication()->input;
	$gid = $input->get( 'gid', array(), 'ARRAY'); 

	if ( empty($gid) ) {
	    $app->enqueueMessage( JText::_('COM_RSGALLERY2_NO_GALLERY_SELECTED' ) );
		$app->redirect("index.php?option=com_rsgallery2&rsgOption=maintenance&task=regenerateThumbs");
		return;
	}

	foreach ($gid as $id) {
    	if ($id > 0) {
    		//Check if resize is really needed. It takes a lot of resources when changing thumbs when dimensions did not change!
    		if ( !rsg2_maintenance::thumbSizeChanged($id) ) {
				$app->enqueueMessage( JText::_('COM_RSGALLERY2_THUMBNAIL_SIZE_DID_NOT_CHANGE_REGENERATION_NOT_NEEDED') );
				$app->redirect("index.php?option=com_rsgallery2&rsgOption=maintenance&task=regenerateThumbs");
				return;
			} else {
				$gallery = rsgGalleryManager::_get($id);
				$images = $gallery->items();
				foreach ($images as $image) {
					//$imagename = imgUtils::getImgOriginalPath($image->name, true);
					$imagename = imgUtils::getImgOriginal($image->name, true);
					if (!imgUtils::makeThumbImage($imagename)) {
						//Error counter
						$error++;
					}
				}
    		}
    	}
    }
    if ($error > 0) {
    	$msg = JText::_('COM_RSGALLERY2_MAINT_REGEN_ERRORS');
    } else {
		$msg = JText::_('COM_RSGALLERY2_MAINT_REGEN_NO_ERRORS');
    }
    $app->enqueueMessage( $msg );
    $app->redirect("index.php?option=com_rsgallery2&rsgOption=maintenance&task=regenerateThumbs");
}
/**
 * Function will regenerate display images for a specific gallery or set of galleries
 * @todo Check if width really changed, else no resize needed.
 * @throws Exception
 */
function executeRegenerateDisplayImages() {
	global $rsgConfig;
	$app = JFactory::getApplication();

	//$gid = JRequest::getVar( 'gid', array());
	$input =JFactory::getApplication()->input;
	$gid = $input->get( 'gid', array(), 'ARRAY'); 
	
	if ( empty($gid) ) {
	    $app->enqueueMessage( JText::_('COM_RSGALLERY2_NO_GALLERY_SELECTED') );
		$app->redirect("index.php?option=com_rsgallery2&rsgOption=maintenance&task=regenerateThumbs");
		return;
	}

    $error = 0;
	foreach ($gid as $id) {
    	if ($id > 0) {
			$gallery = rsgGalleryManager::_get($id);
			$images = $gallery->items();
            // All Images
			foreach ($images as $image) {
				//Get full path of the original image, e.g. 
				//  "C:\xampp\htdocs\images\rsgallery\original\test space in name.jpg" or 
				//  "/public_html/httpdocs/images/rsgallery/original/test space in name.jpg"
				//  So get path not URL (2nd argument "local" false in getImgOriginalPath)
				//  clean it (get correct Directory Separator and remove double slashes)
				//  and convert "%20" to spaces: " " with rawurldecode.
				//$originalImageFullPath = imgUtils::getImgOriginalPath($image->name, true);
				$originalImageFullPath = imgUtils::getImgOriginal($image->name, true);
				//Get the name of the image
				$parts = pathinfo( $originalImageFullPath );
				$newName = $parts['basename'];
				//Get the correct width for the display image
				$width = getimagesize( $originalImageFullPath );
				if( !$width ){
					//error (no width found)
					$app->enqueueMessage(JText::sprintf('COM_RSGALLERY2_COULD_NOT_CREATE_DISPLAY_IMAGE_WITH_NOT_FOUND', $newName), $type= 'error');
					$error++;
				} else {
					//the actual image width and height and its max
					$height = $width[1];
					$width = $width[0];
					if ($height > $width) {
						$maxSideImage = $height;
					} else {
						$maxSideImage = $width;
					}
					// if original is wider or higher than display size, create a display image
					if( $maxSideImage > $rsgConfig->get('image_width') ) {
						$result = imgUtils::makeDisplayImage( $originalImageFullPath, $newName, $rsgConfig->get('image_width') );
					} else {
						$result = imgUtils::makeDisplayImage( $originalImageFullPath, $newName, $maxSideImage );
					}
					//If creation of image failed: let user know
					if( !$result ){
					//	imgUtils::deleteImage( $newName );
						$app->enqueueMessage(JText::sprintf('COM_RSGALLERY2_COULD_NOT_CREATE_DISPLAY_IMAGE', $newName), $type= 'error');
						$error++;
					}
				}
			}
    	}
    }
    if ($error > 0) {
    	$msg = JText::_('COM_RSGALLERY2_MAINT_REGEN_ERRORS_DISPLAY');
    } else {
		$msg = JText::_('COM_RSGALLERY2_MAINT_REGEN_NO_ERRORS');
    }
    $app->enqueueMessage( $msg );
    $app->redirect("index.php?option=com_rsgallery2&rsgOption=maintenance&task=regenerateThumbs");
}

/**
 *
 */
function consolidateDB() {
	$consolidate = new rsg2_consolidate();
	$consolidate->consolidateDB();
}

/**
 * @throws Exception
 */
function createImages() {
	global $rsgConfig;
	$app = JFactory::getApplication();
	$input =JFactory::getApplication()->input;

	//Check if id or name is set
	if ( isset( $_REQUEST['id'] ) ) {
		//$id = JRequest::getInt( 'id', null);
		$id = $input->get( 'id', null, 'INT');					
		$name = galleryUtils::getFileNameFromId($id);
	}
	elseif ( isset($_REQUEST['name'] ) ) {
		//$name    = JRequest::getVar( 'name', null);
		$name = $input->get( 'name', null, 'STRING');					
	} else {
	    $app->enqueueMessage( JText::_('COM_RSGALLERY2_NO_FILEINFORMATION_FOUND_THIS_SHOULD_NEVER_HAPPEN') );
		$app->redirect("index.php?option=com_rsgallery2&rsgOption=maintenance");
		return;
	}
	
	//Just for readability of code
	$original = JPATH_ORIGINAL . DS . $name;
	$display  = JPATH_DISPLAY . DS . imgUtils::getImgNameDisplay($name);
	$thumb    = JPATH_THUMB . DS . imgUtils::getImgNameThumb($name);
	
	//If only thumb exists, no generation possible so redirect.
	if (!file_exists($original) AND !file_exists($display) AND file_exists($thumb) ) {
	    $app->enqueueMessage( JText::_('COM_RSGALLERY2_MAINT_REGEN_ONLY_THUMB') );
		$app->redirect("index.php?option=com_rsgallery2&rsgOption=maintenance&task=consolidateDB");
		return;
	}
	//Go make images
	if ( file_exists($original) ) {
		//Check if display image exists, if not make it.
		if (!file_exists($display)) {
	    	imgUtils::makeDisplayImage($original, NULL, $rsgConfig->get('image_width') );
	    }
		if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($original);
	    }
	} else {
	    if (file_exists($display)) {
	        copy($display, $original);
	    }
	    if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($display);
	    }
	}
    $app->enqueueMessage( $name.' '.JText::_('COM_RSGALLERY2_MAINT_REGEN_SUCCESS') );
	$app->redirect("index.php?option=com_rsgallery2&rsgOption=maintenance&task=consolidateDB");
}

/**
 * @throws Exception
 */
function deleteImages() {
	$app = JFactory::getApplication();
	//$name = JRequest::getVar('name', null);
	$input =JFactory::getApplication()->input;
	$name = $input->get( 'name', null, 'STRING');					

    if ( imgUtils::deleteImage( $name ) ) {
    	$txt = JText::_('COM_RSGALLERY2_MAGE_S_DELETED_SUCCESFULLY');
    } else {
    	$txt = JText::_('COM_RSGALLERY2_IMAGE_S_WERE_NOT_DELETED');
    }
    
    $app->enqueueMessage( $txt );
    $app->redirect("index.php?option=com_rsgallery2&rsgOption=maintenance&task=consolidateDB");
}

/**
 * @throws Exception
 */
function createDbEntries() {
	$input =JFactory::getApplication()->input;
	//$name = JRequest::getVar('name'  , null);
	$name = $input->get( 'name', null, 'STRING');					
	//$t_id = JRequest::getVar('t_id'  , null);
	$t_id = $input->get( 't_id', null); 
    // $gid = JRequest::getInt('gallery_id'  , null);
	//$gallery_id = $input->get( 'gallery_id', 0, 'INT');
    echo "<pre>";
    print_r($name);
    echo "</pre>";
    echo "We are going to create an entry for $name in $t_id.";
}

/**
 * Used in the consolidate database function
 * Creates images based on an image id or an image name
 * @throws Exception
 */
function regenerateImage() {
	$app = JFactory::getApplication();
	global $rsgConfig;
	//$database = JFactory::getDBO();
	$input =JFactory::getApplication()->input;
	
	//Check if id or name is set
	if ( isset( $_REQUEST['id'] ) ) {
		//$id = JRequest::getInt( 'id', null);
		$input =JFactory::getApplication()->input;
		$id = $input->get( 'id', null, 'INT');					
		$name = galleryUtils::getFileNameFromId($id);
	}
	elseif ( isset($_REQUEST['name'] ) ) {
		//$name    = JRequest::getVar( 'name', null);
		$name = $input->get( 'name', null, 'STRING');
	} else {
	    $app->enqueueMessage( JText::_('COM_RSGALLERY2_NO_FILEINFORMATION_FOUND_THIS_SHOULD_NEVER_HAPPEN') );
// OneUploadForm $app->redirect('index.php?option=com_rsgallery2&rsgOption=images&task=batchupload' );
		$app->redirect('index.php?option=com_rsgallery2&view=upload' ); // Todo: More information on fail ?
		return;
	}
	
	// Just for readability of code
	$original = JPATH_ORIGINAL . DS . $name;
	$display  = JPATH_DISPLAY . DS . imgUtils::getImgNameDisplay($name);
	$thumb    = JPATH_THUMB . DS . imgUtils::getImgNameThumb($name);
	    
	if ( file_exists($original) ) {
		//Check if display image exists, if not make it.
		if (!file_exists($display)) {
	    	imgUtils::makeDisplayImage($original, NULL, $rsgConfig->get('image_width') );
	    }
		if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($original);
	    }
	} else {
	    if (file_exists($display)) {
	        copy($display, $original);
	    }
	    if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($display);
	    }
	}
}

/**
 * Checks database for problems and optimizes tables
 * @throws Exception
 */
function optimizeDB() {
	$app = JFactory::getApplication();
	$database = JFactory::getDBO();
	
	require_once(JPATH_ROOT . DS . "administrator" . DS . "components" . DS . "com_rsgallery2" . DS . "includes" . DS . "install.class.php");
	$install = new rsgInstall();
	$tables = $install->tablelistNew;
	foreach ($tables as $table) {
		$database->setQuery("OPTIMIZE TABLE $table");
		$database->execute();
	}
    $app->enqueueMessage( JText::_('COM_RSGALLERY2_MAINT_OPTIMIZE_SUCCESS') );
	$app->redirect("index.php?option=com_rsgallery2&amp;rsgOption=maintenance");
}
?>