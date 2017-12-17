<?php
/**
 * Images option for RSGallery2
 *
 * @version       $Id: images.php 1085 2012-06-24 13:44:29Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

// no direct access
defined('_JEXEC') or die();

global $Rsg2DebugActive;

require_once($rsgClasses_path . 'file.utils.php');
require_once($rsgOptions_path . 'images.html.php');
require_once($rsgOptions_path . 'images.class.php');
require_once(JPATH_RSGALLERY2_ADMIN . '/admin.rsgallery2.html.php');

$input = JFactory::getApplication()->input;
$cid   = $input->get('cid', array(), 'ARRAY');

switch ($task)
{
	case 'new':
		editImage($option, 0);
		break;

	case 'batchupload':
		//HTML_RSGallery::RSGalleryHeader('', JText::_('COM_RSGALLERY2_SUBMENU_BATCH_UPLOAD'));
		batchupload($option);
		HTML_RSGallery::RSGalleryFooter();
		break;

	case 'save_batchupload':
		save_batchupload();
		break;

	// Single upload ?
	case 'upload':
		uploadImage($option);
		break;

	case 'save_upload':
		saveUploadedImage($option);
		break;

	case 'edit':
		$input->set('id', $cid[0]);
		editImage($option, $cid[0]);
		break;

	case 'editA':
		editImage($option, $id);
		break;

	case 'apply':
	case 'save':
		saveImage($option);
		break;

	case 'remove':
		removeImages($cid, $option);
		break;

	case 'publish':
		publishImages($cid, 1, $option);
		break;

	case 'unpublish':
		publishImages($cid, 0, $option);
		break;

	case 'approve':
		break;

	case 'cancel':
		cancelImage($option);
		break;

	case 'orderup':
		orderImages(intval($cid[0]), -1, $option);
		break;

	case 'orderdown':
		orderImages(intval($cid[0]), 1, $option);
		break;

	case 'saveorder':
		saveOrder($cid);
		break;

	case 'reset_hits':
		resetHits($cid);
		break;

	case 'copy_images':
		copyImage($cid, $option);
		break;

	case 'move_images':
		moveImages($cid, $option);
		break;

	case 'showImages':
		showImages($option);
		break;

	default:
		showImages($option);
}

/**
 * Compiles a list of records
 *
 * @param database $option A database connector object
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function showImages($option)
{
	global $mosConfig_list_limit;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: showImages');
	}

	require_once JPATH_ADMINISTRATOR . '/components/com_rsgallery2/models/images.php';

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();

	$gallery_id = intval($app->getUserStateFromRequest("gallery_id{$option}", 'gallery_id', 0));
	$limit      = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
	$limitstart = intval($app->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0));
	$search     = $app->getUserStateFromRequest("search{$option}", 'search', '');
	$search     = $database->escape(trim(strtolower($search)));

	$where = array();
	if ($gallery_id > 0)
	{
		$where[] = "a.gallery_id = $gallery_id";
	}
	if ($search)
	{
		$where[] = "LOWER(a.title) LIKE '%$search%'";
	}

	// get the total number of records
	$query = "SELECT COUNT(1)"
		. "\n FROM #__rsgallery2_files AS a"
		. (count($where) ? "\n WHERE " . implode(' AND ', $where) : "");
	$database->setQuery($query);
	$total = $database->loadResult();

	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	$query = "SELECT a.*, cc.name AS category, u.name AS editor"
		. "\n FROM #__rsgallery2_files AS a"
		. "\n LEFT JOIN #__rsgallery2_galleries AS cc ON cc.id = a.gallery_id"
		. "\n LEFT JOIN #__users AS u ON u.id = a.checked_out"
		. (count($where) ? "\n WHERE " . implode(' AND ', $where) : "")
		. "\n ORDER BY a.gallery_id, a.ordering";

	try
	{
		$database->setQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $database->loadObjectList();
	}
	catch (RuntimeException $e)
	{
		echo $e->getMessage();

		return false;
	}

	// add comment count to each image
	if (!empty ($rows))
	{
		foreach ($rows as $row)
		{
			$CommentCount = rsgallery2ModelImages::getCommentCount($row->id);
			if (!empty ($CommentCount))
			{
				$row->comments = $CommentCount;
			}
		}
	}

	// build list of categories
	$javascript          = 'onchange="document.adminForm.submit();"';
	$lists['gallery_id'] = galleryUtils::galleriesSelectList($gallery_id, 'gallery_id', false, $javascript);
	$lists['move_id']    = galleryUtils::galleriesSelectList($gallery_id, 'move_id', false, '', 0);

	html_rsg2_images::showImages($option, $rows, $lists, $search, $pageNav);

	return true;
}

/**
 * Compiles information to add or edit
 *
 * @param string $option
 * @param int    $id The unique id of the record to edit (0 if new)
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function editImage($option, $id)
{
	global $rsgOption;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: editImage');
	}

	$my       = JFactory::getUser();
	$database = JFactory::getDBO();
	$app      = JFactory::getApplication();

	$lists = array();

//	jimport('joomla.html.html.list'); 

	$row = new rsgImagesItem($database);
	// load the row from the db table
	$row->load((int) $id);

	$canAdmin         = $my->authorise('core.admin', 'com_rsgallery2');
	$canEditItem      = $my->authorise('core.edit', 'com_rsgallery2.item.' . $row->id);
	$canEditStateItem = $my->authorise('core.edit.state', 'com_rsgallery2.item.' . $row->id);

	if (!$canEditItem)
	{
		$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
		$app->redirect("index.php?option=$option&rsgOption=images");
	}

	// fail if checked out not by 'me'
	if ($row->isCheckedOut($my->id))
	{
		$app->enqueueMessage(JText::sprintf('COM_RSGALLERY2_MODULE_CURRENTLY_EDITED', $row->title));
		$app->redirect("index.php?option=$option&rsgOption=$rsgOption");
	}

	if ($id)
	{
		$row->checkout($my->id);
	}
	else
	{
		// initialise new record
		$row->published = 1;
		$row->approved  = 1;
		$row->ordering  = 0;
		$input           = JFactory::getApplication()->input;
		$row->gallery_id = $input->get('gallery_id', 0, 'INT');
	}

	// build the html select list for ordering
	$query = "SELECT ordering AS value, title AS text"
		. "\n FROM #__rsgallery2_files"
		. "\n WHERE gallery_id = " . (int) $row->gallery_id
		. "\n ORDER BY ordering";

	//$lists['ordering'] 		= JHtml::_('list.specificordering', $row, $id, $query, true );
	$lists['ordering'] = JHtml::_('list.ordering', "", $query, $row, $id, true);
	// build list of categories
	$lists['gallery_id'] = galleryUtils::galleriesSelectList($row->gallery_id, 'gallery_id', true, null, 0);
	// build the html select list
	if ($canEditStateItem)
	{
		$lists['published'] = JHtml::_("select.booleanlist", 'published', 'class="inputbox"', $row->published);
	}
	else
	{
		$lists['published'] = ($row->published ? JText::_('JYES') : JText::_('JNO'));
	}
	// build list of users when user has core.admin, else give owners name
	if ($canAdmin)
	{
		$lists['userid'] = JHtml::_('list.users', 'userid', $row->userid, 1, null, 'name', 0);
	}
	else
	{
		$lists['userid'] = JFactory::getUser($row->userid)->name;
	}

	//--- Add link info / text as form fields to be edited -----------------------
	// COM_RSGALLERY2_LINK_TEXT_DESC COM_RSGALLERY2_PARAM_IMAGES_LINK_DESC
	$file = JPATH_SITE . '/administrator/components/com_rsgallery2/options/images.item.xml';

	// ToDo: Debug / Test to check if following replacement is working 
	//$params = new JParameter( $row->params, $file);
	$jparams = new JRegistry();
	$params  = $jparams->get($row->params, $file);

///Try this for J3:
	/*
	$params2 = new JForm('params');
	$params2->loadFile($file);///var_dump($row);
	$params2->bind( $row->params );
	
	$fields = $params2->getFieldset('params');
	foreach( $fields AS $field => $obj ){
	  echo $params2->getLabel( $field, null );
	  echo $params2->getInput( $field, null, null );	
	}*/
///JForm has no render method as used in images.html.php line  343

	html_rsg2_images::editImage($row, $lists, $params, $option);
}

/**
 * Saves the record on an edit form submit
 *
 * @param string $option
 * @param bool   $redirect
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function saveImage($option, $redirect = true)
{
	global $rsgOption;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: saveImage');
	}

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();
	$my       = JFactory::getUser();

	$input = JFactory::getApplication()->input;
	$id = $input->get('id', 0, 'INT');
	$task = $input->get('task', '', 'CMD');
	// Get the rules which are in the form … with the name ‘rules’ 
	// with type array (default value array())
	$data['rules'] = $input->post->get('rules', array(), 'ARRAY');

	$row = new rsgImagesItem($database);
	$row->load($id);
	// ToDo: Revisit check if $input->post->getArray(); is proper replacement for above
	if (!$row->bind($input->post->getArray()))
	{
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->descr = $input->post->get('descr', '', 'RAW');

	//Make the alias for SEF
	if (empty($row->alias))
	{
		$row->alias = $row->title;
	}
	$row->alias = JFilterOutput::stringURLSafe($row->alias);

	//XHTML COMPLIANCE
	$row->descr = str_replace('<br>', '<br />', $row->descr);

	// save params
	$input  = JFactory::getApplication()->input;
	$params = $input->get('params', array(), 'ARRAY');
	if (is_array($params))
	{
		$txt = array();
		foreach ($params as $k => $v)
		{
			$txt[] = "$k=$v";
		}
		$row->params = implode("\n", $txt);
	}

	// Joomla 1.6 ACL
	//Only save rules when there are rules (which were only shown to those with core.admin)
	if (!empty($data['rules']))
	{
		// Get the form library
		jimport('joomla.form.form');
		// Add a path for the form XML and get the form instantiated
		JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_rsgallery2/models/forms/');
		$form = JForm::getInstance('com_rsgallery2.params', 'item', array('load_data' => false));
		// Filter $data which means that for $data['rules'] the Null values are removed
		$data = $form->filter($data);
		if (isset($data['rules']) && is_array($data['rules']))
		{
			// Instantiate a JAccessRules object with the rules posted in the form
			jimport('joomla.access.rules');
			$rules = new JAccessRules($data['rules']);
			// $row is an rsgImagesItem object that extends JTable with method setRules
			// this binds the JAccessRules object to $row->_rules
			$row->setRules($rules);
		}
	}

	$row->date = date('Y-m-d H:i:s');
	if (!$row->check())
	{
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store())
	{
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	$row->reorder("gallery_id = " . (int) $row->gallery_id);

	if ($redirect)
	{
		if ($task == 'save')
		{
			$app->redirect("index.php?option=$option&rsgOption=$rsgOption");
		}
		else
		{ //apply
			$app->redirect("index.php?option=$option&rsgOption=$rsgOption&task=editA&hidemainmenu=1&id=$row->id");
		}
	}
}

/**
 * Deletes one or more records
 * Deletes the sister images in pathes original, display, thumb and watermark  too
 *
 * @param array  $cid    An array of unique category id numbers
 * @param string $option The current url option
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function removeImages($cid, $option)
{
	global $rsgOption, $rsgConfig;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: removeImages');
	}

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();

	$return = "index.php?option=$option&rsgOption=images";
	if (!is_array($cid) || count($cid) < 1)
	{
		echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
		exit;
	}
	//Delete images from filesystem
	if (count($cid))
	{

		$isWatermarked = $rsgConfig->get('watermark');

		//Delete images from filesystem
		foreach ($cid as $id)
		{
			$name     = galleryUtils::getFileNameFromId($id);
			$thumb    = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/' . imgUtils::getImgNameThumb($name);
			$display  = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . imgUtils::getImgNameDisplay($name);
			$original = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $name;

			$oWaterMarker       = new waterMarker();
			$WaterMakerDisplay  = $oWaterMarker->createWatermarkedPathFileName($name, 'display');
			$WaterMakerOriginal = $oWaterMarker->createWatermarkedPathFileName($name, 'original');

			if (file_exists($thumb))
			{
				if (!JFile::delete($thumb))
				{
					//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_DELETING_THUMB_IMAGE') ." ". $thumb);
					JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_DELETING_THUMB_IMAGE') . " " . $thumb, 'error');
					$app->redirect($return);

					return;
				}
			}
			if (file_exists($display))
			{
				if (!JFile::delete($display))
				{
					//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_DELETING_DISPLAY_IMAGE') ." ". $display);
					JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_DELETING_DISPLAY_IMAGE') . " " . $display, 'error');
					$app->redirect($return);

					return;
				}
			}
			if (file_exists($original))
			{
				if (!JFile::delete($original))
				{
					//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_DELETING_ORIGINAL_IMAGE') ." ". $original);
					JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_DELETING_ORIGINAL_IMAGE') . " " . $original, 'error');
					$app->redirect($return);

					return;
				}
			}

			if (file_exists($WaterMakerDisplay))
			{
				if (!JFile::delete($WaterMakerDisplay))
				{
					//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_DELETING_$WATERMARKED_DISPLAY_IMAGE') ." ". $WaterMakerDisplay);
					JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_DELETING_$WATERMARKED_DISPLAY_IMAGE') . " " . $WaterMakerDisplay, 'error');
					$app->redirect($return);

					return;
				}
			}
			if (file_exists($WaterMakerOriginal))
			{
				if (!JFile::delete($WaterMakerOriginal))
				{
					//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_DELETING_WATERMARKED_ORIGINAL_IMAGE') ." ". $WaterMakerOriginal);
					JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_DELETING_WATERMARKED_ORIGINAL_IMAGE') . " " . $WaterMakerOriginal, 'error');
					$app->redirect($return);

					return;
				}
			}

			//Delete from database
			$row = new rsgImagesItem($database);
			if (!$row->delete($id))
			{
				//JError::raiseNotice('ERROR_CODE', JText::sprintf('COM_RSGALLERY2_ERROR_DELETING_ITEMINFORMATION_DATABASE_ID',$id ));
				JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_RSGALLERY2_ERROR_DELETING_ITEMINFORMATION_DATABASE_ID', $id), 'error');
				$app->redirect($return);

				return;
			}
		}

	}
	// ToDo: Check below text constant
	$app->enqueueMessage(JText::_('COM_RSGALLERY2_IMAGE_S_DELETED_SUCCESFULLY'));
	$app->redirect($return);
}

/**
 * Moves one or more items (images) to another gallery, ordering each item as the last one.
 *
 * @param array  $cid    An array of unique category id numbers
 * @param string $option The current url option
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function moveImages($cid, $option)
{
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: moveImages');
	}

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();

	//Get gallery id to move item(s) to
	$input  = JFactory::getApplication()->input;
	$new_id = $input->get('move_id', 0, 'INT');
	if ($new_id == 0)
	{
		echo "<script> alert('No gallery selected to move to'); window.history.go(-1);</script>\n";
		exit;
	}

	$row = new rsgImagesItem($database);

	//Load each row, get new gallery_id/order and store (asset is stored as well with new gallery)
	foreach ($cid as $id)
	{
		$row->load((int) $id);
		if ($row->gallery_id == $new_id)
		{
			//Item is already in this gallery:
			continue;
		}
		$row->gallery_id = $new_id;
		$row->ordering   = $row->getNextOrder("gallery_id = " . (int) $row->gallery_id);
		if (!$row->store())
		{
			echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
			exit();
		}
	}

	$app->redirect("index.php?option=$option&rsgOption=images", '');
}

/**
 * Publishes or Unpublishes one or more records
 *
 * @param array  $cid     An array of unique category id numbers
 * @param int    $publish 0 if unpublishing, 1 if publishing
 * @param string $option  The current url option
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function publishImages($cid = null, $publish = 1, $option)
{
	global $rsgOption;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: publishImages');
	}

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();
	$my       = JFactory::getUser();

	if (!is_array($cid) || count($cid) < 1)
	{
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
		exit;
	}

	$cids = implode(',', $cid);

	$query = "UPDATE #__rsgallery2_files"
		. "\n SET published = " . intval($publish)
		. "\n WHERE id IN ( $cids )"
		. "\n AND ( checked_out = 0 OR ( checked_out = $my->id ) )";
	$database->setQuery($query);
	if (!$database->execute())
	{
		echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
		exit();
	}

	if (count($cid) == 1)
	{
		$row = new rsgImagesItem($database);
		$row->checkin($cid[0]);
	}
	$app->redirect("index.php?option=com_rsgallery2&rsgOption=$rsgOption");
}

/**
 * Moves the order of a record
 *
 * @param int $uid
 * @param int $inc The increment to reorder by
 * @param     $option
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function orderImages($uid, $inc, $option)
{
	global $rsgOption;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: orderImages');
	}

	$app = JFactory::getApplication();

	$database = JFactory::getDBO();

	$row = new rsgImagesItem($database);
	$row->load((int) $uid);
	$row->move($inc, "gallery_id = $row->gallery_id");

	$app->redirect("index.php?option=$option&rsgOption=$rsgOption");
}

/**
 * Cancels an edit operation
 *
 * @param string $option The current url option
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function cancelImage($option)
{
	global $rsgOption;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: cancelImage');
	}

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();

	$row = new rsgImagesItem($database);
	//$row->bind( $_POST );
	$input = JFactory::getApplication()->input;
	// ToDo: Revisit check if $input->post->getArray(); is proper replacement for above
	$row->bind($input->post->getArray());

	$row->checkin();
	$app->redirect("index.php?option=$option&rsgOption=$rsgOption");
}

/**
 * Uploads single images
 *
 * @param string $option
 *
 * @since version
 *
 * @deprecated Old 1.5 code
 */
function uploadImage($option)
{
	global $rsgConfig;

	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: uploadImage');
	}

	$database = JFactory::getDBO();

	//Check if there are galleries created
	$database->setQuery("SELECT id FROM #__rsgallery2_galleries");
	$database->execute();
	if ($database->getNumRows() == 0)
	{
		HTML_RSGALLERY::requestCatCreation();

		return;
	}

	//--- Handle session data -------------------------------------------

	jimport('joomla.application.component.model');
	JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

	$rsgConfig->setLastUpdateType('upload_single');

	// Create gallery selectlist
	$lists['gallery_id'] = galleryUtils::galleriesSelectList(null, 'gallery_id', false, null, 0, true);
	html_rsg2_images::uploadImage($lists, $option);
}

/**
 * @param $option (Not used)
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function saveUploadedImage($option)
{
	global $id, $rsgOption;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: saveUploadedImage');
	}

	$app   = JFactory::getApplication();
	$input = JFactory::getApplication()->input;

	$title = $input->get('title', array(), 'ARRAY');
	$descr = $input->post->get('descr', '', 'RAW');
	$gallery_id = $input->get('gallery_id', 0, 'INT');
	// Old deprecated below: Each of 5 properties like name error .. had its own array
	// New access is a list of files containing the 5 properties as seperate array
	$files = $input->files->get('images', array(), 'FILES'); //

	//For each error that is found, store error message in array
	$errors = array();
	foreach ($files as $key => $file)
	{
		$error = $file['error'];
		if ($error != UPLOAD_ERR_OK)
		{
			if ($error == 4)
			{//If no file selected, ignore
				continue;
			}
			else
			{
				//Create meaning full error messages and add to error array
				$error    = fileHandler::returnUploadError($error);
				$errors[] = new imageUploadError($file["name"], $error);
				continue;
			}
		}

		//Special error check to make sure the file was not introduced another way.
		if (!is_uploaded_file($file["tmp_name"]))
		{
			$errors[] = new imageUploadError($file["tmp_name"], "not an uploaded file, potential malice detected!");
			continue;
		}
		//Actually importing the image
		$e = fileUtils::importImage($file["tmp_name"], $file["name"], $gallery_id, $title[$key], $descr);
		if ($e !== true)
		{
			$errors[] = $e;
		}

	}
	//Error handling if necessary
	if (count($errors) == 0)
	{
		$app->enqueueMessage(JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY'));
		$app->redirect("index.php?option=$option&rsgOption=$rsgOption");
	}
	else
	{
		//Show error message for each error encountered
		foreach ($errors as $e)
		{
			// Warnings are depending on fileUtils::importImage -> type imageUploadError
			// JError::raiseWarning(0, $e->toString());
			JFactory::getApplication()->enqueueMessage($e->toString(), 'warning');
		}
		//If there were more files than errors, assure the user the rest went well
		if (count($errors) < count($files))
		{
			echo "<br>" . JText::_('COM_RSGALLERY2_THE_REST_OF_YOUR_FILES_WERE_UPLOADED_FINE');
		}

// OneUploadForm $app->redirect( "index.php?option=com_rsgallery2&rsgOption=images&task=upload");
		$app->redirect('index.php?option=com_rsgallery2&view=upload&task=upload'); // Todo: More information fail ?
	}
}

/**
 * Resets hits to zero
 *
 * @param array $cid image id's
 *
 * @todo Warn user with alert before actually deleting
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function resetHits(&$cid)
{
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: resetHits');
	}

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();

	//Reset hits
	$cids = implode(',', $cid);

	$query = 'UPDATE `#__rsgallery2_files` SET ' .
		' `hits` = 0 ' .
		' WHERE `id` IN ( ' . $cids . ' )';
	$database->setQuery($query);

	if (!$database->execute())
	{
		echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
	}

	$app->enqueueMessage(JText::_('COM_RSGALLERY2_HITS_RESET_TO_ZERO_SUCCESSFUL'));
	$app->redirect("index.php?option=com_rsgallery2&rsgOption=images");
}

/**
 * @param $cid
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function saveOrder(&$cid)
{
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: saveOrder');
	}

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();

	$total = count($cid);
	$input = JFactory::getApplication()->input;
	$order = $input->get('order', array(), 'ARRAY');

	$row = new rsgImagesItem($database);

	$conditions = array();

	// update ordering values
	for ($i = 0; $i < $total; $i++)
	{
		$row->load((int) $cid[$i]);
		if ($row->ordering != $order[$i])
		{
			$row->ordering = $order[$i];
			if (!$row->store())
			{
				echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
				exit();
			} // if
			// remember to updateOrder this group
			$condition = "gallery_id=" . (int) $row->gallery_id;
			$found     = false;
			foreach ($conditions as $cond)
				if ($cond[1] == $condition)
				{
					$found = true;
					break;
				} // if
			if (!$found)
			{
				$conditions[] = array($row->id, $condition);
			}
		} // if
	} // for

	// execute updateOrder for each group
	foreach ($conditions as $cond)
	{
		$row->load($cond[0]);
		$row->reorder($cond[1]);
	} // foreach

	// clean any existing cache files
	$cache = JFactory::getCache();
	$cache->clean('com_rsgallery2');

	$msg = JText::_('COM_RSGALLERY2_NEW_ORDERING_SAVED');
	$app->enqueueMessage($msg);
	$app->redirect('index.php?option=com_rsgallery2&rsgOption=images');
} // saveOrder

/**
 * Copies one or more items (images) to the selected gallery, ordering each item as the last one.
 *
 * @param array  $cid    An array of unique category id numbers
 * @param string $option The current url option
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function copyImage($cid, $option)
{
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: copyImage');
	}

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();

	//For each error that is found, store error message in array
	$errors = array();

	//Get gallery id to copy item(s) to
	$input  = JFactory::getApplication()->input;
	$cat_id = $input->get('move_id', 0, 'INT');
	if (!$cat_id)
	{
		echo "<script> alert('No gallery selected to copy to'); window.history.go(-1);</script>\n";
		exit;
	}

	//Create unique copy name
	$tmpdir = uniqid('rsgcopy_');

	//Get full path to copy directory
	$tmpCopyDir = JPath::clean(JPATH_ROOT . DS . 'media' . DS . $tmpdir . DS);
	if (!JFolder::create($tmpCopyDir))
	{
		$errors[] = 'Unable to create temp directory ' . $tmpCopyDir;
	}
	else
	{
		foreach ($cid as $id)
		{
			$gallery  = rsgGalleryManager::getGalleryByItemID($id);
			$item     = $gallery->getItem($id);
			$original = $item->original();
			$source   = $original->filePath();

			$tmpFile = $tmpCopyDir . $item->name;

			if (is_dir($tmpCopyDir))
			{
				if (file_exists($source))
				{

					if (!JFile::copy($source, $tmpFile))
					{
						$errors[] = 'The file could not be copied!';
					}
					else
					{
						//Actually importing the image
						$e = fileUtils::importImage($tmpFile, $item->name, $cat_id, $item->title, $item->description);
						if ($e !== true)
						{
							$errors[] = $e;
						}
						if (!JFile::delete($tmpFile))
						{
							$errors[] = 'Unable to delete the file' . $item->name;
						}
					}
				}
			}
		}

		if (!rmdir($tmpCopyDir))
		{
			$errors[] = 'Unable to delete the temp directory' . $tmpCopyDir;
		}
	}

	//Error handling if necessary
	if (count($errors) == 0)
	{
		$app->enqueueMessage(JText::_('COM_RSGALLERY2_ITEM_S_COPIED_SUCCESSFULLY'));
		$app->redirect("index.php?option=$option&rsgOption=images");
	}
	else
	{
		//Show error message for each error encountered
		foreach ($errors as $e)
		{
			echo $e->toString();
		}
		//If there were more files than errors, assure the user the rest went well
		// Old : missing vat $files if ( count( $errors ) < count( $files["error"] ) ) {
		// ToDo: Debug if the change to $cid hold true
		if (count($errors) < count($cid))
		{
			echo "<br>" . JText::_('COM_RSGALLERY2_REST_OF_THE_ITEMS_COPIED_SUCCESSFULLY');
		}
	}
}

/**
 * @param string $option
 *
 * @deprecated Old 1.5 code
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function batchupload($option)
{
	global $rsgConfig;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: batchupload');
	}

	$database = JFactory::getDBO();
	$app      = JFactory::getApplication();
	$FTP_path = $rsgConfig->get('ftp_path');

	//Retrieve data from submit form
	$input       = JFactory::getApplication()->input;

	$batchMethod = $input->get('batchmethod', '', 'STRING');
	$config      = get_object_vars($rsgConfig);
	$uploaded    = $input->get('uploaded', null, 'BOOL');
	$selcat      = $input->get('selcat', null, 'INT');
	//	$zip_file       = $input->files->get('zip_file', array(), 'FILES');
	// 'FILES' is ignored as a *.zip file marked bad from function isSafeFile inside get
	$zip_file = $input->files->get('zip_file', array(), 'raw');

	$ftppath = $input->get('ftppath', null, 'RAW');
	if (substr($ftppath, -1) != '/' && substr($ftppath, -1) == '\\')
	{
		$ftppath .= '/';
	}

	$xcat = $input->get('xcat', null, 'INT');

	if ($Rsg2DebugActive)
	{
		//$Delim = "\n";
		$Delim = " ";
		// show active parameters
		$DebTxt = "==> images.batchupload.php$Delim----------$Delim";
		$DebTxt = $DebTxt . "\$batchMethod: " . $batchMethod . "$Delim";
//		$DebTxt = $DebTxt . "\$config: ".$config."$Delim";
		$DebTxt = $DebTxt . "\$uploaded: " . $uploaded . "$Delim";
		$DebTxt = $DebTxt . "\$selcat: " . $selcat . "$Delim";
		$DebTxt = $DebTxt . "\$xcat: " . $xcat . "$Delim";
		$DebTxt = $DebTxt . "\$ftppath: " . $ftppath . "$Delim";
		// array
		$DebTxt = $DebTxt . "\$zip_file: " . json_encode($zip_file) . "$Delim";;

		JLog::add($DebTxt); //, JLog::DEBUG);
	}

	//--- Handle session data -------------------------------------------

    /**
	jimport('joomla.application.component.model');
	JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

	$UploadModel = JModelLegacy::getInstance('upload', 'rsgallery2Model');

	// Batchupload is requested, data given
	if (isset($uploaded))
	{
		$app = JFactory::getApplication();

		if ($batchMethod == "zip")
		{
			$app->setUserState('com_rsgallery2.last_used_uploaded_zip', $zip_file);
		    $rsgConfig->setLastUpdateType('upload_zip_pc');
		}
		else
		{
			if ($batchMethod == "FTP")
			{
				$app->setUserState('com_rsgallery2.last_used_ftp_path', $ftppath);
                $rsgConfig->setLastUpdateType('upload_folder_server');
			}
		}
	}
    /**/

    // Batchupload is requested, data given
    if (isset($uploaded)) {
        $app = JFactory::getApplication();

        if ($batchMethod == "zip")
        {
            $app->setUserState('com_rsgallery2.last_used_uploaded_zip', $zip_file);
            // $rsgConfig->setLastUsedZipFile($zip_file);
            $rsgConfig->setLastUpdateType('upload_zip_pc');
        }
        else
        {
            if ($batchMethod == "FTP")
            {
                $app->setUserState('com_rsgallery2.last_used_ftp_path', $ftppath);
                $rsgConfig->setLastUsedFtpPath($ftppath);
                $rsgConfig->setLastUpdateType('upload_folder_server');
            }
        }
    }

	// ToDo: move to view with message before
	//Check if at least one gallery exists, if not link to gallery creation
	$database->setQuery("SELECT id FROM #__rsgallery2_galleries");
	$database->execute();
	if ($database->getNumRows() == 0)
	{
		HTML_RSGALLERY::requestCatCreation();

		return;
	}

	// Batchupload is requested, data given
	if (isset($uploaded))
	{

		//--- collect file list ----------------------

		//New instance of fileHandler
		$uploadFile = new fileHandler();

		// Upload zip files ?
		if ($batchMethod == "zip")
		{
			// file found ?
			if (count($zip_file) > 0)
			{ // if (is_array($zip_file)) {
				if ($uploadFile->checkSize($zip_file) == 1)
				{
					//$ziplist = $uploadFile->handleZIP($zip_file);//MK// [change] [handleZIP uses PclZip that is no longer in J1.6]
					$ziplist = $uploadFile->extractArchive($zip_file);//MK// [todo] [check extractArchive]
					if (!$ziplist)
					{
						// Extracting archive failed
						// OneUploadForm $app->redirect('index.php?option=com_rsgallery2&rsgOption=images&task=batchupload' );
						$app->redirect('index.php?option=com_rsgallery2&view=upload'); // Todo: More information fail ?
					}
				}
				else
				{
					// Error message: file size
					$app->enqueueMessage(JText::_('COM_RSGALLERY2_ZIP_MINUS_FILE_IS_TOO_BIG'));
					// OneUploadForm $app->redirect('index.php?option=com_rsgallery2&rsgOption=images&task=batchupload' );
					$app->redirect('index.php?option=com_rsgallery2&view=upload'); // Todo: More information fail ?
				}
			}
			else
			{
				// Error message: file name not given
				$app->enqueueMessage(JText::_('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN'));
				// OneUploadForm $app->redirect('index.php?option=com_rsgallery2&rsgOption=images&task=batchupload' );
				$app->redirect('index.php?option=com_rsgallery2&view=upload'); // Todo: More information fail ?
			}
		}
		else
		{
			//not zip thus ftp
			$ziplist = $uploadFile->handleFTP($ftppath);
		}

		html_rsg2_images::batchupload_2($ziplist, $uploadFile->extractDir);//Step 2 in batchupload process
	}
	else
	{
		// Select upload type (Old) Todo: Remove if not used by "Mygalleries"
		// Step 0 in batchupload process
		html_rsg2_images::batchupload($option);
	}
}//End function

/**
 *
 * @deprecated Old 1.5 code
 *
 * @throws Exception
 *
 * @deprecated Old 1.5 code
 */
function save_batchupload()
{
	global $rsgConfig;
	global $Rsg2DebugActive;

	if ($Rsg2DebugActive)
	{
		JLog::add(' deprecated ==> options/images.php: save_batchupload');
	}

	$app      = JFactory::getApplication();
	$database = JFactory::getDBO();
	//Try to bypass max_execution_time as set in php.ini
	set_time_limit(0);

	$input = JFactory::getApplication()->input;

	$FTP_path = $rsgConfig->get('ftp_path');
	$teller = $input->get('teller', null, 'INT');
	$delete = $input->post->get('delete', null, 'ARRAY');
	$filename = $input->post->get('filename', null, 'ARRAY');
	$ptitle = $input->post->get('ptitle', null, 'ARRAY');
	$descr = $input->post->get('descr', array(0), 'ARRAY');
	$extractdir = $input->get('extractdir', null, 'CMD');

	//Check if all categories are chosen
	if (isset($_REQUEST['category']))
	{
		$category = $input->post->get('category', array(0), 'ARRAY');
	}
	else
	{
		$category = array(0);
	}

	if (in_array('0', $category) ||
		in_array('-1', $category)
	)
	{
		$app->enqueueMessage(JText::_('COM_RSGALLERY2_ALERT_NOCATSELECTED'));
// OneUploadForm $app->redirect('index.php?option=com_rsgallery2&rsgOption=images&task=batchupload' );
		$app->redirect('index.php?option=com_rsgallery2&view=upload'); // Todo: More information fail ?
	}

	for ($i = 0; $i < $teller; $i++)
	{
		//If image is marked for deletion, delete and continue with next iteration
		if (isset($delete[$i]) AND ($delete[$i] == 'true'))
		{
			//Delete file from server
			unlink(JPATH_ROOT . DS . "media" . DS . $extractdir . DS . $filename[$i]);
			continue;
		}
		else
		{
			//Setting variables for importImage()
			$imgTmpName = JPATH_ROOT . DS . "media" . DS . $extractdir . DS . $filename[$i];
			$imgName    = $filename[$i];
			$imgCat     = $category[$i];
			$imgTitle   = $ptitle[$i];
			$imgDesc    = $descr[$i];

			//Import image
			$e = imgUtils::importImage($imgTmpName, $imgName, $imgCat, $imgTitle, $imgDesc);

			//Check for errors
			if ($e !== true)
			{
				$errors[] = $e;
			}
		}
	}
	//Clean up mediadir
	fileHandler::cleanMediaDir($extractdir);

	// Error handling
	if (isset($errors))
	{
		if (count($errors) == 0)
		{
			echo JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY');
		}
		else
		{
			foreach ($errors as $err)
			{
				echo $err->toString();
			}
		}
	}
	else
	{
		//Everything went smoothly, back to Control Panel
		$app->enqueueMessage(JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY'));
		$app->redirect("index.php?option=com_rsgallery2");
	}
}
