<?php
/**
 * @version        $Id: router.php 1085 2012-06-24 13:44:29Z mirjam $
 * @package        RSGallery2
 * @copyright      (C) 2005 - 2023 RSGallery2
 * @license        GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/*
- 	Advanced SEF logic at the bottom of this file

-	Not advanced SEF logic:
	* gid => gallery = gid value
	* id =>  item = id value
	* page -> as . upperCaseFirst (page)
	* limitstartg => categoryPage = limitstartg value  (gallery paging)
	* start => itemPage  = start value (image paging)
	* catid (old) -> transforms to gid (see above), deprecated

-	Advanced SEF logic:

  * rsgOption -> no further changes to URL
  * task == downloadfile -> no further changes to URL
  * gid -> gid value "-" gallery alias
    - page != inline -> remove gid
  * page ->
    - slideshow -> no further changes to URL
      (gid-galleryname was already added) leave page in URL
    - inline + (image) id
      ->  gid value "-" gallery alias
      ->  id value "-" image alias
    - inline + gid ->
      ->  (Start image) id value "-" image alias


	ToDo: ? use language translation for gallery image ... ?
    ToDo: New Joomla Router (allowing to register views into your system.)


 */

/**
 * @param bool
 */
global $isDebugSiteActive;

// $Rsg2DebugActive = true; // ToDo: $rsgConfig->get('debug');
if ($isDebugSiteActive) {
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> base.controller.php');
}


// ToDo: init rsgConfig once and for all ? or use
// ToDo: class and support of SEF for J3! https://docs.joomla.org/J3.x:Supporting_SEF_URLs_in_your_component

defined('_JEXEC') or die;

/**
 * Rsgallery2BuildRoute
 *
 * Transform an array of URL parameters into an array of segments that will form the SEF URL
 * Changes are applied for SEF and not SEF configuration
 *
 * @param $query array('view' => 'article', 'id' => 1, 'catid' => 20)
 * 
 * @return array
 * @throws Exception
 *
 * @since 2.1.1 or earlier
 */
function Rsgallery2BuildRoute(&$query)
{
	//Get config values
	global $rsgConfig, $isDebugSiteActive;

	// standard joomla behaviour
	//$rsgConfig = JComponentHelper::getParams('com_rsgallery2');
	Rsgallery2InitConfig();

	// ToDo: As this is an entry point --> activate debug Log
	// $Rsg2DebugActive = true; // ToDo: $rsgConfig->get('debug');
	if ($isDebugSiteActive) {
		// identify active file
		JLog::add('==> Rsgallery2BuildRoute');
	}

	$segments = array();

	$advancedSef = $rsgConfig->get("advancedSef", false);

	// Now define non-advanced SEF as *v2* way and advanced SEF as **v3** way
	if ($advancedSef == true)
	{
		//-------------------------------------------------------------------------------
		//  advanced SEF
		//-------------------------------------------------------------------------------

		if ($isDebugSiteActive) {
			JLog::add('     $advancedSef == true');
		}

		// Find gid from menu
		$menuGid = getMenuGid($query);

		//if $rsgOption exists (e.g. myGalleries or rsgComments)
		if (isset($query['rsgOption']))
		{
			//do not SEFify (return now)
			return $segments;
		}

		//if $task = downloadfile
		if (isset($query['task']) AND ($query['task'] == 'downloadfile'))
		{
			//do not SEFify (return now)
			return $segments;
		}

		//if view is set
		if (isset($query['view']))
		{
			//remove view from URL
			unset($query['view']);
		}

		//if gid is set
		if (isset($query['gid']))
		{
			//check if it is the gallery in the menulink or not
			if ($query['gid'] != $menuGid)
			{
				//add gid-galleryname
				$segments[] = $query['gid'] . '-' . Rsgallery2GetGalleryName($query['gid']);
				if (!(isset($query['page']) AND ($query['page'] == 'inline')))
				{
					//remove gid from URL, no longer needed (page is not 'inline')
					unset($query['gid']);
				}
			} //else nothing to do
		}

		if (isset($query['page']))
		{
			switch ($query['page'])
			{
				case 'slideshow':
					//(gid-galleryname was already added), leave page in URL
					break;
				case 'inline':
					//remove page from URL
					unset($query['page']);

					if (isset($query['id']))
					{
						// find gallery id based on image id
						$gid = Rsgallery2GetGalleryIdFromItemId($query['id']);
						if ($gid != $menuGid)
						{
							//add gid-galleryname based on found $gid (not query gid)
							$segments[] = $gid . '-' . Rsgallery2GetGalleryName($gid);
						}

						//add id-item name based on id
						$segments[] = ($query['id']) . '-' . Rsgallery2GetItemName($query['id']);
						//remove id from URL
						unset($query['id']);
					}
					elseif ((isset($query['gid'])))
					{
						//find item id based on gid combined with limitstart
						$start = (isset($query['start'])) ? $query['start'] : 0;
						$id    = Rsgallery2GetItemIdFromGalleryIdAndLimitStart($query['gid'], $start);
						//add id-item name
						$segments[] = $id . '-' . Rsgallery2GetItemName($id);
						//remove gid and limitstart from URL
						unset($query['gid']);
						unset($query['start']);
						unset($query['limitstart']);
					}
					break;
				default:
					break;
			}
		}
	}
	else
	{
		//-------------------------------------------------------------------------------
		//  not advancedSEF
		//-------------------------------------------------------------------------------

		// static $items;

		if ($isDebugSiteActive) {
			JLog::add('     $advancedSef == false');
		}

		//Find gid from menu --> $menuGid (can be an independent function)
		$menuGid = getMenuGid($query);

		// rename catId to gId	// catId could be leftover from versions before 1.14.x
		// ToDo: remove catId here when not used in url links -> rsgcomments / rsgvoting ? mygalleries
		if (isset($query['catid']))
		{
			$query['gid'] = $query['catid'];
			unset($query['catid']);
		}

		// direct gallery link
		if (isset($query['gid']))
		{
			// add the gallery id only if it is not part of the menu link
			if ($query['gid'] != $menuGid)
			{
				$segments[] = 'gallery';
				// original: $segments[] = Rsgallery2GetGalleryName($query['gid']);
				// does nothing so
				$segments[] = $query['gid'];
			}
			unset($query['gid']);
		}

		// direct item link
		if (isset($query['id']))
		{
			$segments[] = 'item';
			// original: $segments[] = Rsgallery2GetItemName($query['id']);
			// does nothing so
			$segments[] = $query['id'];
			unset($query['id']);
		}

		// gallery paging
		// @deprecated
		if (isset($query['limitstartg']))
		{
			$segments[] = 'categoryPage';
			$segments[] = $query['limitstartg'];
			unset($query['limitstartg']);
		}

		// item paging
		// @deprecated
		if (isset($query['start']))
		{
			$segments[] = 'itemPage';
			$segments[] = $query['start'];
			unset($query['start']);
		}

		// how to show the item
		if (isset($query['page']))
		{
			$segments[] = 'as' . ucfirst($query['page']);
			unset($query['page']);
		}

	}
	
	if ($isDebugSiteActive) {
		// identify active file
		JLog::add('<== Rsgallery2BuildRoute');
	}


	return $segments;
}


/**
 * Find gid from menu
 * returns null if gid is not set
 * @param $query
 *
 * @return array
 *
 * @throws Exception
 * @since version
 */
function getMenuGid($query)
{
	$app  = JFactory::getApplication();
	$menu = $app->getMenu();

	// Menu item from current active one (ItemId is not set)
	if (empty($query['Itemid']))
	{
		$menuItem = $menu->getActive();
	}
	else
	{
		// Menu item is given from query
		$menuItem = $menu->getItem($query['Itemid']);
	}

    // bad: shows exception ==>  $menuGid = $menuItem->execute['gid'];
	// useful: empty does catch exception
    $menuGid = (empty($menuItem->execute['gid'])) ? null : $menuItem->execute['gid'];

	return $menuGid;
}

/**
 * Rsgallery2ParseRoute
 * Transforms an array of segments back into an array of URL parameters.
 * It converts SEF URLs to system URLs.
 *
 * @param $segments
 *
 * @return array
 * @throws Exception
 */
function Rsgallery2ParseRoute($segments)
{
	//Note: segments show up like: '6:testimage' instead of expected '5-testimage' (don't know why)
	//Get config values
	global $rsgConfig, $isDebugSiteActive;

	// standard joomla behaviour
	//$rsgConfig = JComponentHelper::getParams('com_rsgallery2');
	Rsgallery2InitConfig();

	// ToDo: As this is an entry point --> activate debug Log
	// $Rsg2DebugActive = true; // ToDo: $rsgConfig->get('debug');
	if ($isDebugSiteActive) {
		// identify active file
		JLog::add('==> Rsgallery2ParseRoute');
	}

	$vars = array();

	//Now define non-advanced SEF as v2 way and advanced SEF as v3 way
	if ($rsgConfig->get("advancedSef") == true)
	{
		if ($isDebugSiteActive) {
			JLog::add('     $advancedSef == true');
		}

		//View doesn't need to be added (there is only one view).
		//Check number of parts:
		switch (count($segments))
		{
			case 0:
				//0: nothing to do
				break;
			case 1:
				//1: it's (most likely) a gallery, otherwise an item in a subgallery-menuitem
				//Get either gid and galleryname or id and itemname from 1st segment (explode into two parts)
				$partOne = explode(':', $segments[0], 2);

				//This could be gid and galleryname: check if it is the correct galleryname
				//or else an id and itemname: check if it it the correct itemname
//Check needed because we don't know if its a gallery or an item
				if (Rsgallery2GetGalleryName($partOne[0]) == $partOne[1])
				{
					//add gid //this is never the same as the gid in the menulink
					$vars['gid'] = $partOne[0]; //make sure we have an integer here
				}
//Check not needed per se
//				  elseif (Rsgallery2GetItemName($partOne[0]) == $partOne[1]) {
				else
				{
					//add id and &page=inline
					$vars['id']   = $partOne[0]; //make sure we have an integer here
					$vars['page'] = 'inline';
//				} else {
//					//error
				}
				break;
			case 2:
				//2: it's an item
				//Get id and itemname from part 2 (explode into two parts)
				$partTwo = explode(':', $segments[1], 2);
//Check not needed per se
//				if (Rsgallery2GetItemName($partTwo[0]) == $partTwo[1]) {
				//add id and &page=inline
				$vars['id']   = (int) $partTwo[0]; //make sure we have an integer here
				$vars['page'] = 'inline';
//				} else {
//					//error
//				}
				break;
			default:
				//error
		}
	}
	else
	{
	    // not advancedSEF

		if ($isDebugSiteActive) {
			JLog::add('     $advancedSef == false');
		}


		// Get the active menu item.
		//$menu	= JSite::getMenu();
		$app  = JFactory::getApplication();
		$menu = $app->getMenu();

		$item = $menu->getActive();

		if (!empty($item))
		{
			// We only want the gid from the menu-item-link when (this case the menulink refers to a subgallery)
			// - it is the only gid: e.g. no 'category' in $segments (it is not a subgallery of the gallery shown with the menu-item)
			// - we do not have id in the URL, e.g. no 'item' in $segments
			if (!in_array("gallery", $segments) AND !in_array("item", $segments) AND !in_array("category", $segments))
			{    //'category' for links created with RSG2 version <= 2.1.1
				if (preg_match("/gid=([0-9]*)/", $item->link, $matches) != 0)
				{
					$vars['gid'] = $matches[1];
				}
			}
		}

		for ($index = 0; $index < count($segments); $index++)
		{
			switch ($segments[$index])
			{
				// gallery link (subgallery of the gallery shown with the menu-item)
				case 'category':    //changed 'category' to 'gallery' after version 2.1.1
				case 'gallery':
					$vars['gid'] = Rsgallery2GetCategoryId($segments[++$index]);
					break;
				// item link
				case 'item':
					$vars['id'] = Rsgallery2GetItemId($segments[++$index]);
					break;
				// gallery paging
				case 'categoryPage':
					$vars['limitstartg'] = $segments[++$index];
					$vars['limitstart']  = 1;
					break;
				// item paging
				case 'itemPage':
					$vars['limitstart'] = $segments[++$index];
					break;
			}
			// how to show the item
			$pos = strpos($segments[$index], 'as');
			if ($pos !== false && $pos == 0)
			{
				$vars['page'] = strtolower(substr($segments[$index], 2));
			}
		}

		if (isset($vars["id"]) && !isset($vars['page']))
		{
			$vars['page'] = "inline";
		}
	}//END of if ($rsgConfig->get("advancedSef") == true)

	if ($isDebugSiteActive) {
		// identify active file
		JLog::add('<== Rsgallery2ParseRoute');
	}

	return $vars;
}

/**
 * Get the alias of a gallery based on its id (gid)
 *
 * @param int $gid Numerical value of the gallery
 *
 * @return string String Alias of the gallery
 *
 **/
function Rsgallery2GetGalleryName($gid)
{
	//Get config values
	global $rsgConfig;

	// standard joomla behaviour
	//$rsgConfig = JComponentHelper::getParams('com_rsgallery2');
	Rsgallery2InitConfig();

	// Fetch the gallery alias from the database if advanced sef is active,
	// else return the numerical value	
	if ($rsgConfig->get("advancedSef") == true)
	{
		$db   = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query
			->select('alias')
			->from($db->quoteName('#__rsgallery2_galleries'))
			->where($db->quoteName('id') . ' = ' . (int) $gid);
		$db->setQuery($query);

		$result = $db->execute();

		if ($db->getNumRows($result) != 1)
		{
			// Gallery alias was not unique or is unknown, use the numeric value instead.
			$segment = $gid;
		}
		else
		{
			$segment = $db->loadResult();
		}
	}
	else
	{
	    // No advanced SEF
		$segment = $gid;
	}

	return $segment;
}

/**
 * Converts a category SEF alias to its id
 *
 * @param string $categoryName mixed SEF alias or id of the category
 *
 * @return string id of the category
 *
 **/
function Rsgallery2GetCategoryId($categoryName)
{
	//Get config values
	global $rsgConfig;

	// fetch the gallery id from the database if advanced sef is active
	/* old
	if($rsgConfig->get("advancedSef") == true) {
		//not used
	} else {
		$id = $categoryName;
	}
	*/

	if ($rsgConfig->get("advancedSef") != true)
	{
		$id = $categoryName;
	}
	else
	{
		$id = '';
	}

	return $id;
}

/**
 * Converts a item SEF alias to its id
 *
 * @param string $itemName $categoryName mixed SEF alias or id of the category
 *
 * @return int id of the category
 *
 **/
function Rsgallery2GetItemId($itemName)
{
	// Get config values
	global $rsgConfig;

	// standard joomla behaviour
	//$rsgConfig = JComponentHelper::getParams('com_rsgallery2');
	Rsgallery2InitConfig();

	// fetch the gallery id from the database if advanced sef is active
	/* old
	if($rsgConfig->get("advancedSef") == true) {
		//not used
	} else {
		$id = $categoryName;
	}
	*/

	if ($rsgConfig->get("advancedSef") != true)
	{
		$id = $itemName;
	}
	else
	{
		$id = '';
	}

	return $id;
}

/**
 * Get an item (image) alias based on its id
 *
 * @param int $id Numeral id of the item
 *
 * @return string Alias of the item
 *
 **/
function Rsgallery2GetItemName($id)
{
	// Get config values
	global $rsgConfig;

	// standard joomla behaviour
	//$rsgConfig = JComponentHelper::getParams('com_rsgallery2');
	Rsgallery2InitConfig();

	// Get the item alias from the database if advanced sef is active,
	// else return the numerical value	
	if ($rsgConfig->get("advancedSef") == true)
	{
		$db   = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query
			->select('alias')
			->from($db->quoteName('#__rsgallery2_files'))
			->where($db->quoteName('id') . ' = ' . (int) $id);
		$db->setQuery($query);

		$result = $db->execute();

		if ($db->getNumRows($result) != 1)
		{
			// Item id not found (or found multiple times?!)
			$segment = $id;
		}
		else
		{
			$segment = $db->loadResult();
		}
	}
	else
	{
		$segment = $id;
	}

	return $segment;
}

/**
 * Get the gallery id (gid) based on the id of an item (image)
 *
 * @param int $id Numeral id of the item
 *
 * @return int Id of the gallery (gid)
 *
 **/
function Rsgallery2GetGalleryIdFromItemId($id)
{
	//Get config values
	global $rsgConfig;

	// standard joomla behaviour
	//$rsgConfig = JComponentHelper::getParams('com_rsgallery2');
	Rsgallery2InitConfig();

	// Set standard return value
	$gid = 0;

	// fetch the gallery id (gid) from the database based on the id of an item
	$db   = JFactory::getDBO();
	$query = $db->getQuery(true);

	$query
		->select('gallery_id')
		->from($db->quoteName('#__rsgallery2_files'))
		->where($db->quoteName('id') . ' = ' . (int) $id);
	$db->setQuery($query);

	$result = $db->execute();

	$countRows = $db->getNumRows($result);
	if ($countRows == 1)
	{
		// Item id found (or found multiple times?!)
		$gid = $db->loadResult();
	}
	else
	{
		//Redirect user and display error...
		if ($countRows == 0)
		{
			//...item not found
			$msg = JText::sprintf('COM_RSGALLERY2_ROUTER_IMAGE_ID_NOT_FOUND', $id);
		}
		else
		{
			//...non unique id in table, should never happen
			$msg = JText::_('COM_RSGALLERY2_SHOULD_NEVER_HAPPEN');
		}

		$app = JFactory::getApplication();
		JFactory::getLanguage()->load("com_rsgallery2");
		$app->redirect("index.php", $msg);
	}

	return $gid;
}

/**
 * Get the id of an item based on the given gallery id and limitstart
 *
 * @param int $gid        Numeral id of the gallery (gid)
 * @param int $limitstart Numeral
 *
 * @return int Id of the item (id)
 * @throws Exception
 */
function Rsgallery2GetItemIdFromGalleryIdAndLimitStart($gid, $limitstart)
{
	//Get config values
	global $rsgConfig;

	// standard joomla behaviour
	//$rsgConfig = JComponentHelper::getParams('com_rsgallery2');
	Rsgallery2InitConfig();

	$id = 0;

	// Get the gallery id (gid) from the database based on the id of an item

	$db   = JFactory::getDBO();
	$query = $db->getQuery(true);

	$query
		->select('alias')
		->from($db->quoteName('#__rsgallery2_galleries'))
		->where($db->quoteName('id') . ' = ' . (int) $gid);
	$db->setQuery($query);

	$result = $db->execute();

	$db   = JFactory::getDBO();
	$query = $db->getQuery(true);

	$query
		->select('id')
	    ->from('#__rsgallery2_files')
	    ->where('`gallery_id`=' . (int) $gid)
		->order('ordering');

	// Only for super administrators this includes the unpublished items
	if (!JFactory::getUser()->authorise('core.admin', 'com_rsgallery2'))
	{
		$query->where('`published` = 1');
	}
	$db->setQuery($query);

	$result = $db->execute();

	$countRows = $db->getNumRows($result);
	if ($countRows > 0)
	{
		$column = $db->loadColumn();
		$id     = $column[$limitstart];
	}
	else
	{
		//todo: error //need to have non-zero number of items
		//Redirect user and display error...
		$app = JFactory::getApplication();
		JFactory::getLanguage()->load("com_rsgallery2");
		$app->redirect("index.php", JText::sprintf('COM_RSGALLERY2_COULD_NOT_FIND_IMAGE_BASED_ON_GALLERYID_AND_LIMITSTART', (int) $gid, (int) $limitstart));//todo add to languange file
	}

	return $id;
}

/**
 * Gets the configuration settings for RSGallery
 */
/**/
function Rsgallery2InitConfig()
{
	/** 2018.09.02 */
	global $rsgConfig, $rsgVersion;

	if (empty ($rsgConfig))
	{
		if (!defined('JPATH_RSGALLERY2_ADMIN'))
		{
			define('JPATH_RSGALLERY2_ADMIN', JPATH_ROOT . '/' . 'administrator' . '/' . 'components' . '/' . 'com_rsgallery2');
		}

		// Needed by rsgConfig
		require_once(JPATH_RSGALLERY2_ADMIN . '/' . 'includes' . '/' . 'version.rsgallery2.php');
		$rsgVersion = new rsgalleryVersion();

		// Initialize the rsg config file
		require_once(JPATH_RSGALLERY2_ADMIN . '/' . 'includes' . '/' . 'config.class.php');
		$rsgConfig = new rsgConfig();
	}
}
/**/

/*	SEF logic and info (before RSG2 4.5)
==> All links have option and Itemid for the menu-item
==> Then we have 
	view	only in menulink: discard for now with only 1 view --> remove view from URL
	gid		with limitstart: shows an item --> add galleryname and itemname
			without limitstart and not in menulink: shows subgallery --> add galleryname
			without limitstart and in menulink: shows subgallery --> do not add galleryname
	id		without task=downloadfile: shows item --> add galleryname and itemname
			with task=downloadfile --> do not SEFify
	page	page=slideshow --> add galleryname, leave page in URL
			page=inline, needed to show item --> remove page from URL 
	limitstart	only in combination with gid --> see gid on what to do
	task	task=downloadfile --> do not SEFify

==> Logic to SEFify link:
	//Find task, view, gid, page, id from query
	//Find gid from menu
	//Check if gid from menu is equal to gid from query
	if (there is a rsgOption)) {
		//do not SEFify (return now)
	}
	if ($task = 'downloadfile') {
		//do not SEFify (return now)
	}
	if (view is set) {
		//remove view from URL
	}
	if (gid is set) {
		//check if it is the gallery in the menulink or not
		if (gid is not the one in the menulink) {
			//add gid-galleryname
			if (page is not 'inline') {
				//remove gid from URL, no longer needed
			}
		} //else nothing to do
	}
	if (page is set) {
		$page = 'slideshow'
			//(gid-galleryname was already added), leave page in URL
		$page = 'inline'
			//remove page from URL
			if (id is set) {
				//find gid-galleryname based on id
				if (gid found not equal to gid in menulink) {
					//add gid-galleryname
				}
				//add id-itemname based on id
				//remove id from URL
			} elseif 
				// add id-itemname based on gid combined with limitstart (where limitstart=0 if it isn't there)
				//remove gid and limitstart from URL			
			}
	}
		
==> unSEFify logic
	//View doesn't need to be added (there is only one view).
	//Check number of parts:
	//0: nothing to do
	//1: it's (most likely) a gallery, otherwise an item in a subgallery-menuitem
	If (only 1 part) {
		//Get either gid and galleryname or id and itemname from 1st segment (explode)
		if (gid-galleryname combination exists) {
			//add gid //this is never the same as the gid in the menulink
		} elseif (id-itemname combination exists) {
			//add id and &page=inline
		} else {
			//error
		}
	}
	//2: it's an item
	If (two parts) {
		//Get id and itemname from part 2 (explode)
		if (id-itemname combination exists) {
			//add id and &page=inline
		} else {
			//error
		}
	}
*/

