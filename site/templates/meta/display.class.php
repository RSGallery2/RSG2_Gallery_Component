<?php
/**
 * This class encapsulates the HTML for the non-administration RSGallery pages.
 *
 * @version       $Id: display.class.php 1098 2012-07-31 11:54:19Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2019 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

require_once (JPATH_RSGALLERY2_SITE .'/templates/meta/templateParameter.php');
jimport('joomla.filesystem.files');

class rsgDisplay extends JObject
{
	var $params = null; // Type of Jregistry

	var $currentItem = null;

	function __construct()
	{
		global $rsgConfig;

		$this->gallery = rsgGalleryManager::get();

		//Pre 3.0.2: always got template 'semantic' even when showing a slideshow;
        //           $template is only used here to get template parameters
		//Does the page show the slideshow? Then get slideshow name, else get template name.
		$input = JFactory::getApplication()->input;
		$page  = $input->get('page', '', 'CMD');
		if ($page == 'slideshow')
		{
			$template = $rsgConfig->get('current_slideshow');
		}
		else
		{
			$template = $rsgConfig->get('template');
		}

		// template given by plugin or URL
		$template  = $input->get('rsgTemplate', $template, 'CMD');

		// Load parameter names
		$xmlPath      = JPATH_RSGALLERY2_SITE . '/templates/' .  $template; // . '/templateDetails.xml';

		$userParameter = new Rsg2TemplateParameter ($xmlPath);
		$this->params = $userParameter->params;
	}

	/**
	 * Switch for the main page, when not handled by rsgOption
	 *
	 * @throws Exception
	 */
	function mainPage()
	{
		global $rsgConfig;

		//$page = JRequest::getCmd( 'page', '' );
		$input = JFactory::getApplication()->input;
		$page  = $input->get('page', '', 'CMD');
		switch ($page)
		{
			case 'slideshow':
				$gallery = rsgGalleryManager::get();
				if (!empty ($gallery))
				{
				//JRequest::setVar( 'rsgTemplate', $rsgConfig->get('current_slideshow'));
				$input->set('rsgTemplate', $rsgConfig->get('current_slideshow'));

				//@todo This bit is leftover from J!1.5: look into whether or not this can be removed and how. remove first or second call to ::instance
				rsgInstance::instance(array('rsgTemplate' => $rsgConfig->get('current_slideshow'), 'gid' => $gallery->id));
				}
				break;
			case 'inline':
				// only semantic -> templates\semantic\display.class.php
				$this->inline();
				break;
			case 'viewChangelog':
				$this->viewChangelog();
				break;
			case 'test':
				$this->test();
				break;
			default:
				$this->showMainGalleries();
				$this->showThumbs();
		}
	}

	/**
	 * Debug only
	 */
	static function test()
	{

		echo "test code goes here!";
		$folders = JFolder::folders('components/com_rsgallery2/templates');
		foreach ($folders as $folder)
		{
			if (preg_match("/slideshow/i", $folder))
			{
				$folderlist[] = $folder;
			}
		}
		echo "<pre>";
		print_r($folderlist);
		echo "</pre>";

	}

	/**
	 *  write the footer
	 */
	static function showRsgFooter()
	{
		global $rsgConfig, $rsgVersion;

		$hidebranding = '';
		if ($rsgConfig->get('displayBranding') == false)
		{
			$hidebranding = "style='display: none'";
		}

		?>
		<div id='rsg2-footer' <?php echo $hidebranding; ?>>
			<br /><br /><?php echo $rsgVersion->getShortVersion(); ?>
		</div>
		<div class='rsg2-clr'>&nbsp;</div>
		<?php
	}

	/**
	 * @param string $file
	 *
	 * @throws Exception
	 */
	function display($file = null)
	{
		global $rsgConfig;

		// $template = preg_replace( '#\W#', '', JRequest::getCmd( 'rsgTemplate', $rsgConfig->get('template') ));
		// --> JRequest::getCmd( 'rsgTemplate', $rsgConfig->get('template') )
		$input       = JFactory::getApplication()->input;
		$PreTemplate = $input->get('rsgTemplate', $rsgConfig->get('template'), 'CMD');

		$template    = preg_replace('#\W#', '', $PreTemplate);
		$templateDir = JPATH_RSGALLERY2_SITE . '/templates' . '/' .  $template . '/html';

		$file = preg_replace('/[^A-Z0-9_\.-]/i', '', $file);

		$includeName = $templateDir . '/' .  $file;
		if (JFile::exists($includeName))
		{
			include $includeName;
		}
	}

	/**
	 * Shows the top bar for the RSGallery2 screen
	 *
	 * @throws Exception
	 */
	function showRsgMyGalleryHeader()
	{
		// $rsgOption 	= JRequest::getCmd( 'rsgOption'  , '');
		$input     = JFactory::getApplication()->input;
		$rsgOption = $input->get('rsgOption', '', 'CMD');

		//$gid 		= JRequest::getInt( 'gid', null);
		$gid = $input->get('gid', null, 'INT');

		if (!$rsgOption == 'mygalleries' AND !$gid)
		{
			?>
			<div class="rsg2-mygalleries">
				<a class="rsg2-mygalleries_link" href="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries"); ?>"><?php echo JText::_('COM_RSGALLERY2_MY_GALLERIES') ?></a>
			</div>
			<div class="rsg2-clr"></div>
			<?php
		}
	}

	/**
	 * Shows contents of changelog.php in preformatted layout
	 */
	static function viewChangelog()
	{
		global $rsgConfig;

		if (!$rsgConfig->get('debug'))
		{
			echo JText::_('COM_RSGALLERY2_FEATURE_ONLY_AVAILABLE_IN_DEBUG_MODE');

			return;
		}

		echo '<pre style="text-align: left;">';
		readfile(JPATH_SITE . '/administrator/components/com_rsgallery2/changelog.php');
		echo '</pre>';
	}

	/**
	 * Shows the proper Joomla path
	 *
	 * @throws Exception
	 */
	function showRSPathWay()
	{
		$app = JFactory::getApplication();
		$pathway   = $app->getPathway();

		// Only show pathway if rsg2 is the component
		//$option = JRequest::getCmd('option');
		$input  = JFactory::getApplication()->input;
		$option = $input->get('Option', '', 'CMD');
		if ($option != 'com_rsgallery2')
		{
			return;
		}

		//Check from where the path should be taken: if there is no gid in the 
		// menu-link, it is the root, e.g. gid=0, if there is a gid that's the 
		// start for this pathway

		$theMenu           = $app->getMenu();
		$theActiveMenuItem = $theMenu->getActive();
		if (isset($theActiveMenuItem->query['gid']))
		{
			$gidInActiveMenutItem = $theActiveMenuItem->query['gid'];
		}
		else
		{
			$gidInActiveMenutItem = '0';
		}

		//Get the gallery id (gid) of the currently gallery shown
		//gallery = rsgInstance::getGallery(); deprecated
		$gallery = rsgGalleryManager::get();

		$currentGallery = $gallery->id;

		//Get the current item shown
		// $item = rsgInstance::getItem(); deprecated
		$gallery = rsgGalleryManager::get();
		$item    = $gallery->getItem();

		//If the current gallery id (gid) is the one in the menu, no parent 
		// galleries are needed, if not, get the parent galleries up until 
		// the active one. 
		if (!($currentGallery == $gidInActiveMenutItem))
		{
			$galleries   = array();
			$galleries[] = $gallery;
			//stop at the active one
			while ($gallery->parent != $gidInActiveMenutItem)
			{
				$gallery     = $gallery->parent();
				$galleries[] = $gallery;
			}

			$galleries = array_reverse($galleries);
			foreach ($galleries as $gallery)
			{
				if ($gallery->id == $currentGallery && empty($item))
				{
					$pathway->addItem($gallery->name);
				}
				else
				{
					if ($gallery->id != 0)
					{
						$link = 'index.php?option=com_rsgallery2&gid=' . $gallery->id;
						$pathway->addItem($gallery->name, $link);
					}
				}
			}
		}

		//Add image name to pathway if an image is displayed (page in URL is the string 'inline')
		//$page = JRequest::getCmd( 'page', '' );
		$page = $input->get('page', '', 'CMD');
		if ($page == 'inline')
		{
			$pathway->addItem($item->title);
		}

	}

	/**
	 * Insert meta data and page title into head
	 *
	 * @throws Exception
	 */
	function metadata()
	{
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$input = JFactory::getApplication()->input;

		//$option 	= JRequest::getCmd('option');
		$option = $input->get('Option', '', 'CMD');
		//$Itemid 	= JRequest::getInt('Itemid',Null);
		$Itemid = $input->get('Itemid', null, 'INT');
		//$gid 		= JRequest::getInt('gid',Null);
		$gid = $input->get('gid', null, 'INT');
		//$id 		= JRequest::getInt('id',Null);
		$id = $input->get('id', null, 'INT');
		//$limitstart = JRequest::getInt('limitstart',Null);
		$limitstart = $input->get('limitstart', null, 'INT');
		//$page		= JRequest::getCmd('page',Null);
		$page = $input->get('page', '', 'CMD');

		// Get the gid in the URL of the active menu item
		// $app = JFactory::getApplication();
		if (isset($app->getMenu()->getActive()->query['gid']))
		{
			$menuGid = $app->getMenu()->getActive()->query['gid'];
		}
		else
		{
			$menuGid = null;
		}

		// If RSG2 isn't the component being displayed, don't append meta data
		if ($option != 'com_rsgallery2')
		{
			return;
		}

		// Get the title and description from gallery and (if shown) item
		if (isset($gid))
		{
			if ($menuGid == $gid)
			{
				// Do nothing: showing menu item
				return;
			}
			else
			{
				// Get gallery title/description
				$title       = $this->gallery->name;
				$description = $this->gallery->description;
				// Only add item title/description when item is shown (when page=='inline')
				if (isset($page) and $page = 'inline')
				{
					// Get current item, add item title to pagetitle 
					// and set item description in favor of gallery description
					$item = array_slice($this->gallery->items, $limitstart, 1);
					$title .= ' - ' . $item[0]->title;
					$description = $item[0]->descr;
				}
			}
		}
		else
		{
			// No gid, only id
			// $this rsgDisplay_semantic object holds rsgGallery2 object with current gallery info
			$title = $this->gallery->name;
			$title .= ' - ';
			// Add image title
			// $title .= rsgInstance::getItem()->title;
			$gallery = rsgGalleryManager::get();
			$title .= $gallery->title;
			// Get image description
			$description = $gallery->descr;
		}

		// Clean up description
		$description = htmlspecialchars(stripslashes(strip_tags($description)), ENT_QUOTES);

		// Set page title and meta description
		$document->setTitle($title);
		$document->setMetadata('description', $description);

		return;
	}

	/**
	 * @return string
	 */
	function getGalleryLimitBox()
	{
		$pagelinks = $this->pageNav->getLimitBox("index.php?option=com_rsgallery2");
		// add form for LimitBox
		$pagelinks = '<form action="' . JRoute::_("index.php?option=com_rsgallery2&gid=" . $this->gallery->id) . '" method="post">' .
			$pagelinks .
			'</form>';

		return $pagelinks;
	}

	/**
	 * @return mixed
	 */
	function getGalleryPageLinks()
	{
		$pagelinks = $this->pageNav->getPagesLinks("index.php?option=com_rsgallery2");

		return $pagelinks;

	}

	/**
	 * @return mixed
	 */
	function getGalleryPagesCounter()
	{
		return $this->pageNav->getPagesCounter();
	}

	/***************************
	 * private functions
	 ***************************/

	/**
	 * shows the image
	 *
	 * @param string $name
	 * @param string $title  (!!! was $descr )
	 */
	function _showImageBox($name, $title)
	{
		global $rsgConfig;

		if ($rsgConfig->get('watermark') == true)
		{
			?>
			<img class="rsg2-displayImage" src="<?php echo waterMarker::showMarkedImage($name); ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" />
			<?php
		}
		else
		{
			?>
			<img class="rsg2-displayImage" src="<?php
			//echo imgUtils::getImgDisplayPath($name);
			echo imgUtils::getImgDisplay($name);
			?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" />
			<?php
		}
	}

	/**
	 * Shows the comments screen
	 */
	function _showComments()
	{
		global $rsgConfig;
		$gallery = rsgGalleryManager::get();

		//Check if user is allowed to comment (permission rsgallery2.comment on asset com_rsgallery2.gallery."gallery id"
		if (JFactory::getUser()->authorise('rsgallery2.comment', 'com_rsgallery2.gallery.' . $gallery->id))
		{
			$item = $gallery->getItem();
			$id   = $item->id;

			//Adding stylesheet for comments (is this needed as it is in rsgcomments.class.php as well?)
			$doc = JFactory::getDocument();
			$doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgcomments/rsgcomments.css");

			$comment = new rsgComments();
			$comment->showComments($id);
			$comment->editComment($id);
		}
		else
		{
			echo JText::_('COM_RSGALLERY2_COMMENTING_IS_DISABLED');
		}
	}

	/**
	 * Shows the voting screen
	 */
	function _showVotes()
	{
		global $rsgConfig;
		$gallery = rsgGalleryManager::get();

		//Check if user is allowed to vote (permission rsgallery2.vote on asset com_rsgallery2.gallery."gallery id"
		if (JFactory::getUser()->authorise('rsgallery2.vote', 'com_rsgallery2.gallery.' . $gallery->id))
		{
			//Adding stylesheet for comments 
			$doc = JFactory::getDocument();
			$doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgvoting/rsgvoting.css");

			$voting = new rsgVoting();
			$voting->showScore();
			$voting->showVoting();
		}
		else
		{
			echo JText::_('COM_RSGALLERY2_VOTING_IS_DISABLED');
		}
	}

	/**
	 * Shows either random or latest images, depending on parameter
	 *
	 * @param String $type   Type of images. Options are 'latest' or 'random'
	 * @param Int    $number Number of images to show. Defaults to 3
	 * @param String $style  Style, options are 'vert' or 'hor'.(Vertical or horizontal)
	 *                       string HTML representation of image block.
	 */
	function showImages($type = "latest", $number = 3, $style = "hor")
	{
		global $rsgConfig;
		$database = JFactory::getDBO();

		//Check if backend permits showing these images
		if ($type == "latest" AND !$rsgConfig->get('displayLatest'))
		{
			return;
		}
		elseif ($type == "random" AND !$rsgConfig->get('displayRandom'))
		{
			return;
		}

		switch ($type)
		{
			case 'random':
				$query = 'SELECT file.date, file.gallery_id, file.ordering, file.id, file.name, file.title ' .
					' FROM #__rsgallery2_files as file, #__rsgallery2_galleries as gal ' .
					' WHERE file.gallery_id = gal.id and gal.published = 1 AND file.published = 1 ' .
					' ORDER BY rand() limit ' . (int) $number;
				$database->setQuery($query);
				$rows  = $database->loadObjectList();
				$title = JText::_('COM_RSGALLERY2_RANDOM_IMAGES');
				break;
			case 'latest':
				$query = 'SELECT file.date, file.gallery_id, file.ordering, file.id, file.name, file.title ' .
					' FROM #__rsgallery2_files as file, #__rsgallery2_galleries as gal ' .
					' WHERE file.gallery_id = gal.id AND gal.published = 1 AND file.published = 1 ' .
					' ORDER BY file.date DESC LIMIT ' . (int) $number;
				$database->setQuery($query);
				$rows  = $database->loadObjectList();
				$title = JText::_('COM_RSGALLERY2_LATEST_IMAGES');
				break;
		}

		if ($style == "vert")
		{
			?>
			<ul id='rsg2-galleryList'>
				<li class='rsg2-galleryList-item'>
					<table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td><?php echo $title; ?></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<?php
						foreach ($rows as $row)
						{
							$l_start = $row->ordering - 1;
							$url     = JRoute::_("index.php?option=com_rsgallery2&page=inline&id=" . $row->id);
							?>
							<tr>
								<td align="center">
									<div class="shadow-box">
										<div class="img-shadow">
											<a href="<?php echo $url; ?>">
												<img src="<?php
												//echo imgUtils::getImgThumbPath($row->name);
												echo imgUtils::getImgThumb($row->name);
												?>" alt="<?php echo $row->title; ?>" width="<?php echo $rsgConfig->get('thumb_width'); ?>" />
											</a>
										</div>
										<div class="rsg2-clr"></div>
										<div class="rsg2_details"><?php echo JHtml::_("date", $row->date); ?></div>
									</div>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<?php
						}
						?>
					</table>
				</li>
			</ul>
			<?php
		}
		else
		{
			?>
			<ul id='rsg2-galleryList'>
				<li class='rsg2-galleryList-item'>
					<table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td colspan="3"><?php echo $title; ?></td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<?php
							foreach ($rows as $row)
							{
								$l_start = $row->ordering - 1;
								$url     = JRoute::_("index.php?option=com_rsgallery2&page=inline&id=" . $row->id);
								?>
								<td align="center">
									<div class="shadow-box">
										<div class="img-shadow">
											<a href="<?php echo $url; ?>">
												<img src="<?php
												//echo imgUtils::getImgThumbPath($row->name);
												echo imgUtils::getImgThumb($row->name);
												?>" alt="<?php echo $row->title; ?>" width="<?php echo $rsgConfig->get('thumb_width'); ?>" />
											</a>
										</div>
										<div class="rsg2-clr"></div>
										<div class="rsg2_details"><?php echo JText::_('COM_RSGALLERY2_UPLOADED_DBLPT') ?>&nbsp;<?php echo JHTML::_("date", $row->date); ?></div>
									</div>
								</td>
								<?php
							}
							?>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
					</table>
				</li>
			</ul>
			<?php
		}
	}

	/**
	 * Write downloadlink for image
	 *
	 * @param int    $id   image ID
	 * @param bool   $showtext
	 * @param string $type ? Text below button
	 *                     return HTML for downloadlink
	 */
	function _writeDownloadLink($id, $showtext = true, $type = 'button')
	{
		global $rsgConfig;
		if ($rsgConfig->get('displayDownload'))
		{
			echo "<div class=\"rsg2-toolbar\">";

            echo '<a href="' . JRoute::_('index.php?option=com_rsgallery2&task=downloadfile&id=' . $id) . '"';
			echo '   title="' . JText::_("COM_RSGALLERY2_DOWNLOAD") . '"';
			if ($type == 'button') // or link
			{
                echo ' class="btn btn-mini"';
			}
			else
			{
				echo ' class="btn btn-link btn-mini"';
			}
			echo '>';
			echo '<i class="icon-download icon-white"> </i>';
//            echo  JText::_('COM_RSGALLERY2_DOWNLOAD');
			echo '</a>';

			echo "</div><div class=\"rsg2-clr\">&nbsp;</div>";
		}
	}

	/**
	 * Provides unformatted EXIF data for the current item (image)
	 *
	 * @result Array with EXIF values
	 */
	function _showEXIF()
	{
		require_once(JPATH_ROOT . '/components/com_rsgallery2/lib/exifreader/exifReader.php');
		// $image = rsgInstance::getItem();
		$gallery = rsgGalleryManager::get();
		$image   = $gallery->getItem();;
		$filename = JPATH_ROOT . $image->original->name;

		$exif = new phpExifReader($filename);
		$exif->showFormattedEXIF();
	}

	/**
	 *
	 */
	function showSearchBox()
	{
		global $rsgConfig;

		if ($rsgConfig->get('displaySearch') != 0)
		{
//			require_once(JPATH_ROOT . '/components/com_rsgallery2/lib/rsgsearch/search.html.php');
//			html_rsg2_search::showSearchBox();

            //--- search box ----------------------------------------

            //echo '<div align="right" class="j25search_box">';
			echo '<div class="j25search_box pull-right">';
			echo '	<form name="rsg2_search" class="form-search form-inline warning" method="post" action="' . JRoute::_('index.php') . '" >';
			echo '   <div class="input-prepend">';
			echo '            <button type="submit" class="btn">Search</button>';
			echo '            <input type="search" name="searchtextX"  maxlength="200"';
			echo '                   class="inputbox search-query input-medium"';
			echo '                   placeholder="'. JText::_('COM_RSGALLERY2_KEYWORDS') . '">';
			echo '        </div>';
			echo '        <input type="hidden" name="option" value="com_rsgallery2" />';
			echo '        <input type="hidden" name="rsgOption" value="search" />';
			echo '        <input type="hidden" name="task" value="showResults" />';
			echo '	</form>';
			echo '</div>';

		}
	}


	/**
	 * @param $pagination
	 *
	 *
	 * @since version
	 */
	function showNavLimitBox($pagination)
	{
	    // ? more than one page ?
		// if ($pagination->total)
		if ($pagination->total > $pagination->limit)
		{
			echo '<div class="btn-group pull-right hidden-phone">';
			echo '   <label for "limit" class="element-invisible">';
			echo '      ' . JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');
			echo '   </label>';
			echo '   ' . $pagination->getlimitBox();
			echo '</div>';
		}
	}



    // Show gallery title (name)
	function showGalleryName($gallery)
    {
	    echo '<h2>';
        echo '    <div class="rsg_gallery_title">';
	    echo          $gallery->name;
	    echo '    </div>';
	    echo '</h2>';
    }

        // Show gallery description
    function showGalleryDescription($gallery)
    {
	    global $rsgConfig;

	    echo '<div class="intro_text">';
        echo      $gallery->description;
        echo '</div>';
    }



}

