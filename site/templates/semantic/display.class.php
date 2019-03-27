<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

jimport('joomla.html.pagination');

JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

/**
 * Template class for RSGallery2
 *
 * @package RSGallery2
 * @author  Ronald Smit <ronald.smit@rsdev.nl>
 */
class rsgDisplay_semantic extends rsgDisplay
{
	protected $gallery;
	protected $kids; // ToDo: galleryKids
    protected $pageNav;

	/**
	 * Show main gallery page
	 *
	 *
	 * @since version
	 * @throws Exception
	 */
	public function showMainGalleries()
	{
		$gallery = rsgGalleryManager::get();

		// main gallery
		$this->gallery = $gallery;

		//Get number of galleries including main gallery
		$this->kids    = $gallery->kids();

		// navigation
		$this->pageNav = $this->navigationRootGalleries ($gallery);

		// html outputs
		$this->display('gallery.php');
	}

	/**
	 * return pagination, may also chenge number of kids
	 * @param $gallery
	 *
	 * @return bool|JPagination
	 *
	 * @since version
	 * @throws Exception
	 */
	public function navigationRootGalleries ($gallery)
	{
		global $rsgConfig;

		$pageNav = false;

		$app = JFactory::getApplication();
		$input      = JFactory::getApplication()->input;

		//Get values for page navigation from URL
		//$limit = $app->getUserStateFromRequest("galleryviewlimit", 'limit', $rsgConfig->get('galcountNrs'), 'int');
		$limit = $app->getUserStateFromRequest("galleryviewlimit", 'limit', $rsgConfig->galcountNrs, 'int');
		$limitstart = $input->get('limitstart', 0, 'INT');
		$kidCountTotal = count($this->kids);

		//Show page navigation if selected in backend
		if (($rsgConfig->dispLimitbox == 1 && $kidCountTotal > $limit)
			|| $rsgConfig->dispLimitbox == 2
		)
		{
			// When users wants "All" galleries to show, $limit = 0, no need to slice
			if ($limit)
			{
				$this->kids = array_slice($this->kids, $limitstart, $limit);
			}
		}

		$pageNav = new JPagination($kidCountTotal, $limitstart, $limit);

		return $pageNav;
	}

	/**
	 * gallery view with one big image and pagination
     * "asinline"
	 */

	// ToDo: inlude used parts inti inline....php  and restructure

	function inline(){
		$this->display( 'inline.php' );
	}


	/***************************
    use by root and single gallery image overview
	***************************/

	/**
	 * Shows the gallery details block when set in the backend
	 *
	 * @param $kid
	 */
	function _showGalleryDetails($kid)
	{
		global $rsgConfig;
		$slideshow   = $rsgConfig->get('displaySlideshow') && $kid->itemCount() > 1;
		$owner       = $rsgConfig->get('showGalleryOwner');
		$size        = $rsgConfig->get('showGallerySize');
		$date        = $rsgConfig->get('showGalleryDate');
		$includeKids = $rsgConfig->get('includeKids', true);

		//Check if items need to be shown
		if (($slideshow + $owner + $size + $date) > 0)
		{
			?>
            <div class="rsg_gallery_details">
                <div class="rsg2_details">
					<?php
					if ($slideshow)
					{
						?>
                        <a href='<?php echo JRoute::_("index.php?option=com_rsgallery2&page=slideshow&gid=" . $kid->get('id')); ?>'>
							<?php echo JText::_('COM_RSGALLERY2_SLIDESHOW'); ?></a>
                        <br />
						<?php
					}

					if ($owner)
					{
						echo JText::_('COM_RSGALLERY2_OWNER_DBLPT');
						echo $kid->owner; ?>
                        <br />
						<?php
					}

					if ($size)
					{
						echo JText::_('COM_RSGALLERY2_SIZE_DBLPT');
						echo galleryUtils::getFileCount($kid->get('id'), $includeKids) . ' ' . JText::_('COM_RSGALLERY2_IMAGES'); ?>
                        <br />
						<?php
					}

					if ($date)
					{
						echo JText::_('COM_RSGALLERY2_CREATED') . "";
						echo JHTML::_("date", $kid->date, JText::_('COM_RSGALLERY2_DATE_FORMAT_LC3'));
						?>
                        <br />
						<?php
					}
					?>
                </div>
            </div>
			<?php
		}
	}

	/***************************
	 * private functions
	 ***************************/

	/**
	 * @todo this alternate gallery view needs to be moved to an html file and added as a template parameter
	 * Seems to be used nowhere
	 */
	/*    function _showDouble( $kids ) {
			global $rsgConfig;
			$i = 0;
			echo"<div class='rsg_double_fix'>";
			foreach ( $kids as $kid ) {
				$i++;
				?>
				<div class="rsg_galleryblock_double_<?php echo $i?>">
					<div class="rsg2-galleryList-status"><?php echo $kid->status;?></div>
					<div class="rsg2-galleryList-thumb_double">
						<?php echo $kid->thumbHTML; ?>
					</div>
					<div class="rsg2-galleryList-text_double">
						<?php echo $kid->galleryName;?>
						<span class='rsg2-galleryList-newImages'>
							<sup><?php echo galleryUtils::newImages($kid->get('id')); ?></sup>
						</span>
						<?php echo $this->_showGalleryDetails( $kid );?>
						<div class="rsg2-galleryList-description"><?php echo $kid->description;?>
						</div>
					</div>
					<div class="rsg_sub_url"><?php $this->_subGalleryList( $kid ); ?>
					</div>
				</div>
				<?php
				if($i>1){
					$i = 0;
				}
			}
			echo "</div>";
		}/**/

	/**
	 * @todo this alternate gallery view needs to be moved to an html file and added as a template parameter
	 * Seems to be used nowhere
	 */
	/*    function _showBox( $kids, $subgalleries ) {
			?>
			<div class="rsg_box_block">
				<?php
				$i = 0;
				foreach ( $kids as $kid ) {
					$i++;
					if($i>3){
						$i = 1;
						}
				 ?>
					<div class="rsg_box_box_<?php echo $i;?>">
						<div class="rsg_galleryblock">
							<div>
								<div class="rsg2-galleryList-status"><?php echo $kid->status; ?></div>
								<?php echo $kid->galleryName;?>
								<sup><span class='rsg2-galleryList-newImages'><?php echo galleryUtils::newImages($kid->get('id')); ?></span></sup>
								<div class='rsg2-galleryList-totalImages'>(<?php echo galleryUtils::getFileCount($kid->get('id')).' '. JText::_('COM_RSGALLERY2_IMAGES');?>)</div>
							</div>
							<div>
								<div class="rsg2-galleryList-thumb_box">
									<?php echo $kid->thumbHTML; ?>
								</div>
								<div class="rsg2-galleryList-text_box">
									  <?php echo $this->_showGalleryDetails( $kid );?>
								</div>
							</div>
							<div class="rsg2-galleryList-description_box">
									<?php echo $kid->description;?>
							</div>
							<div class="rsg_sub_url">
									<?php $this->_subGalleryList( $kid ); ?> 
							</div>
						</div>
					</div>
					<?php
				}
				?>
				</div>
			<?php
		}/**/

	/**
	 * Shows thumbnails for one gallery
	 *
	 * @throws Exception
	 */
	public function showThumbs()
	{
		global $rsgConfig;

		$my = JFactory::getUser();
		$input = JFactory::getApplication()->input;

		$gallery = $this->gallery;

		// increase the gallery hit counter
		$gallery->hit();

		// For super administrators (they have core.admin) this includes the unpublished items
		$itemCount = $gallery->itemCount();

		// No images in gallery ? -> return
		if (!$itemCount)
		{
			if ($gallery->id)
			{
				// if gallery is not the root gallery display the message
				echo JText::_('COM_RSGALLERY2_NO_IMAGES_IN_GALLERY');
				echo $gallery->thumbHTML;
			}

			// no items to display, so we can return;
			return;
		}

		// Rights management. If user is owner or user is Super Administrator, you can edit this gallery
		// ToDo: Check authorise below. Looks like reference to gallery is needed for core.edit.own
		if ((($my->id <> 0) && ($gallery->uid == $my->id) && ($my->authorise('core.edit.own', 'com_rsgallery2')))
			// OR ( $my->usertype == 'Super Administrator' )))
			|| $my->authorise('core.admin', 'com_rsgallery2')
			|| $my->authorise('core.edit', 'com_rsgallery2')
		)
		{
			$this->allowEdit = true;
		}
		else
		{
			$this->allowEdit = false;
		}

		switch ($rsgConfig->get('display_thumbs_style'))
		{
			case 'float':
				$this->display('thumbs_float.php');
				break;
			case 'table':
				$this->display('thumbs_table.php');
				break;
		}
		?>
		<div class="pagination">
			<?php
            /**/
			$limit = $rsgConfig->get("display_thumbs_maxPerPage");
			if ($itemCount > $limit)
			{
				$limitstart = $input->get('limitstart', 0, 'INT');
				//instantiate page navigation
				$pageNav = new JPagination($itemCount, $limitstart, $limit);//MK gaat goed: thumbs in gallery

				echo $pageNav->getPagesLinks();
				//echo "<br /><br />" . $pageNav->getPagesCounter();
			}
            /**/
			?>
		</div>
		<?php
	}

	/**
	 * Shows main item (from semantic /html/inline.php)
	 */
	function showItem()
	{
		global $rsgConfig;

		// $item = rsgInstance::getItem(); deprecated
		$gallery = rsgGalleryManager::get();
		$item    = $gallery->getItem();;

		// increase hit counter
		if (is_object($item))
		{    //Can this be achieved in a better way? When an item is unpublished (there is no $item object) a user gets a "Call to a member function hit() on a non-object" error without this check. With Joomla SEF we get a Notice "Could not find an image with image id ." (without the id number) and without Joomla SEF we get a blank page?!
			$item->hit();
		}

		?>
		<table <?php echo ($item->published) ? "" : "class='system-unpublished'"; ?> border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td>
					<h2 class='rsg2_display_name' align="center"><?php echo htmlspecialchars(stripslashes($item->title), ENT_QUOTES); ?></h2>
				</td>
			</tr>
			<tr>
				<td>
					<div align="center">
						<?php
						$this->currentItem = $item;
						$this->display("item_" . $item->type . ".php");
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td><?php $this->_writeDownloadLink($item->id); ?></td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Show page navigation for Display image (from semantic /html/inline.php)
	 *
	 * @throws Exception
	 */
	function showDisplayPageNav()
	{//MK this is where the images are shown with limit=1
		$gallery = rsgGalleryManager::get();
		//$itemId = JRequest::getInt( 'id', 0 );
		$input  = JFactory::getApplication()->input;
		$itemId = $input->get('id', 0, 'INT');
		if ($itemId != 0)
		{
			// if the item id is set then we need to set the gid instead
			// having the id variable set in the querystring breaks the page navigation

			// i have not found any other way to remove a query variable from the router
			// JPagination uses the router to build the current route, so removing it from the 
			// request variables only does not work.
			$app    = JFactory::getApplication();
			$router = $app->getRouter();

			$router->setVar('gid', $gallery->id);
			$router->setVar('id', null);                //unsets the var id from JRouter

			// set the limitstart so the pagination knows what page to start from
			$itemIndex = $gallery->indexOfItem($itemId);
			$router->setVar("limitstart", $itemIndex);
			// Todo: 150130
			// JRequest::setVar('limitstart', $itemIndex);
			$input->set('limitstart', $itemIndex);
		}

		$pageNav   = $gallery->getPagination();
		$pageLinks = $pageNav->getPagesLinks();

		?>
		<div align="center">
			<div class="pagination">
				<?php echo $pageLinks; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Shows details of image (from semantic /html/inline.php)
	 */
	function showDisplayImageDetails()
	{
		global $rsgConfig;

		$gallery = rsgGalleryManager::get();
		$image    = $gallery->getItem();

		// if no details need to be displayed then exit
		$isDisplayDesc = $rsgConfig->get('displayDesc');
		$isDisplayVoting = $rsgConfig->get('displayVoting');
		$isDisplayComments = $rsgConfig->get('displayComments');
		$isDisplayEXIF = $rsgConfig->get('displayEXIF');

		$isDisplayImgHits = $rsgConfig->get('displayHits');
		$isVotingEnabled = JFactory::getUser()->authorise('rsgallery2.vote', 'com_rsgallery2.gallery.' . $gallery->id);
		// ToDo: $isVotingEnabled = $isVotingEnabled  & $rsgConfig->get('isVotingEnabled');
		$isVotingEnabled = True;

		$this->AssignImageRatingData(array($image));
		$this->AssignImageComments(array($image));
		$this->AssignImageExifData(array($image));

		// any data to be displayed ?
		$isDisplayImgDetails = false;
		if ($isDisplayDesc || $isDisplayVoting || $isDisplayComments || $isDisplayEXIF)
		{
			$isDisplayImgDetails = true;
		}

        // Display image details
		if ($isDisplayImgDetails)
		{
			//--- active tab --------------------------------

			$isDisplayDescActive = '';
			$isDisplayVotingActive = '';
			$isDisplayCommentsActive = '';
			$isDisplayEXIFActive = '';

			/**/
			if ($isDisplayDesc)
			{
			$isDisplayDescActive = 'active';
			}
			else
			{
				if ($isDisplayVoting)
				{
					$isDisplayVotingActive = 'active';
				}
				else
				{
					if ($isDisplayComments)
					{
						$isDisplayCommentsActive = 'active';
					}
					else
					{
						if ($isDisplayEXIF)
						{
							$isDisplayEXIFActive = 'active';
						}
					}
				}
			}
			/**
			$isDisplayCommentsActive = 'active';
            /**/
			echo '<div class="well">';

			echo '    <div class="tabbable">'; // <!-- Only required for left/right tabs -->
			echo '        <ul class="nav nav-tabs">';

			/*---------------------------------------------------------------
			   tab headers
			---------------------------------------------------------------*/

			//--- tab headers --------------------------------

			if ($isDisplayDesc)
			{
				echo '        <li class="' . $isDisplayDescActive . '"><a href="#tabDesc" data-toggle="tab">' . JText::_('COM_RSGALLERY2_DESCRIPTION') . '</a></li>';
			}
			if ($isDisplayVoting)
			{
				echo '        <li class="' . $isDisplayVotingActive . '"><a href="#tabVote" data-toggle="tab">' . JText::_('COM_RSGALLERY2_VOTING') . '</a></li>';
			}
			if ($isDisplayComments)
			{
				echo '        <li class="' . $isDisplayCommentsActive . '"><a href="#tabComments" data-toggle="tab">' . JText::_('COM_RSGALLERY2_COMMENTS') . '</a></li>';
			}
			if ($isDisplayEXIF)
			{
				echo '        <li class="' . $isDisplayEXIFActive . '"><a href="#tabExif" data-toggle="tab">' . JText::_('COM_RSGALLERY2_EXIF') . '</a></li>';
			}
			echo '        </ul>';


			/*---------------------------------------------------------------
			   tab content
			---------------------------------------------------------------*/

			echo '        <div class="tab-content">';

			//--- image description --------------------------------

			if ($isDisplayDesc)
			{
				echo '        <div class="tab-pane ' . $isDisplayDescActive . '" id="tabDesc">';
				echo '            <div  class="page_inline_tabs_description" >';

				echo $this->htmlDescription ($image, $isDisplayImgHits);

				//echo '                <p>Howdy, I\'m in Section 1.</p>';
				echo '            </div>';
				echo '        </div>';
			}

			//--- voting --------------------------------

			if ($isDisplayVoting)
			{
				echo '        <div class="tab-pane ' . $isDisplayVotingActive . '" id="tabVote">';
				echo '            <div  class="page_inline_tabs_voting" >';
				if ( ! empty ($image->ratingData))
				{
					echo $this->htmlRatingData ($image->ratingData, $isVotingEnabled, $image->gallery_id, $image->id);
				}
				else
				{
					echo '                <p>' . JText::_('COM_RSGALLERY2_VOTING_IS_DISABLED') . '</p>';
				}
				//echo '                <p>Howdy, I\'m in Section 2.</p>';
				echo '            </div>';
				echo '        </div>';
			}

			//--- comments --------------------------------

			if ($isDisplayComments)
			{
				echo '        <div class="tab-pane ' . $isDisplayCommentsActive . '" id="tabComments">';
				echo '            <div  class="page_inline_tabs_comments" >';
				if (! empty ($image->comments))
				{
					echo $this->htmlComments ($image->comments, $image->gallery_id, $image->id);
				}
				else
				{
					echo '                <p>' . JText::_('COM_RSGALLERY2_COMMENTING_IS_DISABLED') . '</p>';
				}
				// echo '                <p>Howdy, I\'m in Section 3.</p>';
				echo '            </div>';
				echo '        </div>';
			}

			//--- EXIF data --------------------------------

			if ($isDisplayEXIF)
			{
				echo '        <div class="tab-pane ' . $isDisplayEXIFActive . '" id="tabExif">';
				echo '            <div  class="page_inline_tabs_exif" >';
				if ( ! empty ($image->exifData))
				{
					echo $this->htmlExifData ($image->exifData);
				}
				else
				{
					// echo '                <p>' . JText::_('COM_RSGALLERY2_NO_EXIF_ITEM_SELECTED_') . '</p>';
				}
				echo '                <p>Howdy, I\'m in Section 4.</p>';
				echo '            </div>';
				echo '        </div>';
			}

			echo '        </div>'; // tab-content
			echo '    </div>'; // tabbable

			echo '</div>'; // well

		}

		echo '</div>'; // <div class="rsg2">


        /**
			// JHtmlTabs
		$options = array(
			'onActive'     => 'function(title, description){
				description.setStyle("display", "block");
				title.addClass("open").removeClass("closed");
			}',
			'onBackground' => 'function(title, description){
				description.setStyle("display", "none");
				title.addClass("closed").removeClass("open");
			}',
			'startOffset'  => 0,  // 0 starts on the first tab, 1 starts the second, etc...
			'useCookie'    => true, // this must not be a string. Don't use quotes.
		);
		echo JHtml::_('tabs.start', 'page_inline_tabs', $options);

		if ($rsgConfig->get("displayDesc"))
		{
			echo JHtml::_('tabs.panel', JText::_('COM_RSGALLERY2_DESCRIPTION'), 'page_inline_tabs_description');
			$this->_showDescription();
		}

		if ($rsgConfig->get("displayVoting"))
		{
			echo JHtml::_('tabs.panel', JText::_('COM_RSGALLERY2_VOTING'), 'page_inline_tabs_voting');
			$this->_showVotes();
		}

		if ($rsgConfig->get("displayComments"))
		{
			echo JHtml::_('tabs.panel', JText::_('COM_RSGALLERY2_COMMENTS'), 'page_inline_tabs_comments');
			$this->_showComments();
		}

		if ($rsgConfig->get("displayEXIF"))
		{
			echo JHtml::_('tabs.panel', JText::_('COM_RSGALLERY2_EXIF'), 'page_inline_tabs_exif');
			$this->_showEXIF();
		}

		echo JHtml::_('tabs.end');
        /**/
	}

	function htmlStars ($idx, $average, $lastRating)
	{
		$html = [];

		$intAvg = (int) floor($average);
		$avgRem = ((double) $average) - $intAvg; // reminder

		$isSelected = "";
		if ($lastRating > 0 && ($lastRating -1) == $idx)
		{
			$isSelected = "checked";
		}

		$isButtonActive = false;
		$isHalfStar = false;
		if ($idx < $intAvg)
		{
			$isButtonActive = true;
		}

		if ($idx == $intAvg)
		{
			if ($avgRem > 0.49)
			{
				$isHalfStar = true;
				$isButtonActive = true;
			}
		}

		if ($isHalfStar) {
			$iconClass = "icon-star-2";
		}
		else
		{
			$iconClass = "icon-star";
		}

		$buttonClassAdd = 'btn-warning ';
		if ( ! $isButtonActive)
		{
			$buttonClassAdd = 'btn-default btn-grey ';
		}

		$html[] = '<button id="star_' . ($idx+1) . '" type="button" class="btn ' .  $buttonClassAdd . ' btn-mini btn_star ' .  $isSelected . '" aria-label="Left Align">';
		$html[] = '    <span class="' . $iconClass . '" aria-hidden="true"></span>';
		$html[] = '</button>';

		return implode("\n", $html);
	}

	function htmlRatingData($ratingData, $isVotingEnabled, $gid, $imageId)
	{
		$html = [];

		$html[] = '<div class="container span12">';

		$html[] =  '        <div class="rsg2_rating_container">';

		//--- result of rating ------------------------------------

		// ToDo: add limit here and remove from *js
		$html[] = '                <form name="rsgvoteform" method="post" action="' . JRoute::_('index.php?option=com_rsgallery2&view=gallery&gid=' . $gid) .'&startShowSingleImage=1" id="rsgVoteForm">';

		$html[] = '                <div class="rating-block row-fluid text-center" >';

		$html[] = '                    <h4>' . JText::_('COM_RSGALLERY2_AVERAGE_USER_RATING') . '</h4>';
		$html[] = '                    <h2 class="bold padding-bottom-7">' . $ratingData->average . '&nbsp<small>/&nbsp' . $ratingData->count . '</small></h2>';

		for ($idx = 0; $idx < 5; $idx++)
		{
			$html[] =  '                    ' . $this->htmlStars ($idx, $ratingData->average, $ratingData->lastRating);
		}

		if ($isVotingEnabled)
		{
			$html[] = '                <label id="DoVote" title="' . JText::_('COM_RSGALLERY2_AVERAGE_RATE_IMAGE_DESC') . '">' . JText::_('COM_RSGALLERY2_AVERAGE_RATE_IMAGE') . '&nbsp;&nbsp;</label>';

			JHtml::script (JURI_SITE . '/components/com_rsgallery2/layouts/ClassicJ25/OneImageVote.js');
		}

		$html[] = '                </div>'; //

		$html[] = '                <input type="hidden" name="task" value="rating.rateSingleImage" />';
		$html[] = '                <input type="hidden" name="rating" value="" />';
		$html[] = '                <input type="hidden" name="paginationImgIdx" value="" />';
		$html[] = '                <input type="hidden" name="id" value="' . $imageId . '" />';
		$html[] = '                <input id="token" type="hidden" name="' . JSession::getFormToken() . '" value="1" />';

		$html[] = '                </form>';

		$html[] =  '		</div>'; // rsg2_exif_container

		$html[] = '</div>'; // class="container span12">';

		return implode("\n", $html);
	}

	function htmlDescription ($image, $isDisplayImgHits)
	{
		$html = [];


		/**
		$html[] = '<div class ="alert alert-info">';
		$html[] = '</div>';
		$html[] = '';
		$html[] = '';
		$html[] = '';
		$html[] = '';
		$html[] = '';
		/**/
		/**
		$html[] = '<div class ="info">';
		$html[] = '<caption>';
		/**/

		$html[] = '<div class="container span12">';

		//--- Hits --------------------------------

		if ($isDisplayImgHits)
		{
			//$html[] = '<div class="well well-small">';
			//$html[] = '    <span class="' . $iconClass . '" aria-hidden="true"></span>';
			//echo '            <p class="rsg2_hits"> ' . JText::_('COM_RSGALLERY2_HITS') . '&nbsp;<span>' . $image->hits . '</span>';
			//$html[] = '<div class ="rsg2_hits">';
			$html[] = '            <dl class="dl-horizontal ">'; // dl-horizontal rsg2_hits
			//$html[] = '                <dt>' . JText::_('COM_RSGALLERY2_HITS') . ' <i class="icon-flag"></i> </dt><dd>' . $image->hits . '</dd>';
			$html[] = '                <dt> <i class="icon-flag"></i> ' . JText::_('COM_RSGALLERY2_HITS') . '</dt><dd><strong>' . $image->hits . '</strong></dd>';
			$html[] = '            </dl>';
			//$html[] = '</div>';
		}

		//--- Description --------------------------

		//$html[] = '<div class ="alert alert-info">';
		$html[] = '<div class ="well">';

		$html[] = '                <p class="rsg2_description">' . nl2br(stripslashes($image->descr)) . '</p>';
		//$html[] = '                <p class="rsg2_description">' . $image->descr . '</p>';

		$html[] = '</div>';

		/**
		 * $html[] = '</caption>';
		/**/
		/**/
		$html[] = '</div>'; // class="container span12">';

		return implode("\n", $html);
	}

	function htmlComments ($comments, $gid, $imageId)
	{
		// toDO improve ....
		// https://bootsnipp.com/snippets/Vp4P
		// https://bootsnipp.com/snippets/featured/comment-posts-layout
		// https://bootsnipp.com/snippets/featured/blog-post-footer
		// sophisticated
		// https://bootsnipp.com/snippets/featured/collapsible-tree-menu-with-accordion

		$formFields = $comments->formFields;
		$imgComments = $comments->comments;

		$html = [];

		$html[] = '<div class="container span12">';

		$html[] =  '        <div class="rsg2_comments_container">';

		if (empty($imgComments))
		{
			$html[] = '<div id="comment">';
			$html[] = '    <table width="100%" class="comment_table">';
			$html[] = '        <tr>';
			$html[] = '            <td class="title">';
			$html[] = '                <span class="posttitle">' . JText::_('COM_RSGALLERY2_NO_COMMENTS_YET') . ' <br></span>';
			$html[] = '                 ';
			$html[] = '                 <br>';
			$html[] = '            </td>';
			$html[] = '        </tr>';
			$html[] = '    </table>';
			$html[] = '</div>';
		}
		else
		{
			// Comments existing

			//--- add comment link bar -------------------------------------------------

			/**
			$html[] = '<div id="comment">';
			$html[] = '    <table width="100%" class="comment_table">';
			$html[] = '        <tr>';
			//$html[] = '	           <td class="title" width="25%"' .  JText::_('COM_RSGALLERY2_COMMENTS') . '</td>';
			//$html[] = '	           <td class="title" width="50%">' . JText::_('COM_RSGALLERY2_COMMENTS_ADDED') . '</td>';
			$html[] = '	           <td class="title pull-right">';
			//$html[] = '	               <div class="addcomment">';
			$html[] = '    <i class="icon-comment"></i>';
			$html[] = '	                   <a class="special" href="#lblAddCcomment">' . JText::_('COM_RSGALLERY2_ADD_COMMENT') . '</a>';
			//$html[] = '	               </div>';
			$html[] = '	           </td>';
			$html[] = '	       </tr>';
			$html[] = '    </table>';
			$html[] = '    <br />';
			$html[] = '</div>';
			$html[] = '';
			/**/

			$html[] = '<div id="comment" class="title pull-right">';

			$html[] = '<button class="btn btn-success" type="button">';
			$html[] = '    <i class="icon-comment"></i>';
			$html[] = '	   <a class="special" href="#lblAddCcomment">' . JText::_('COM_RSGALLERY2_ADD_COMMENT') . '</a>';
			$html[] = '</button>';
			$html[] = '';

			$html[] = '</div>';



			// https://bootsnipp.com/snippets/a35Pl

			//--- existing comments -----------------------------------------------------

			// each comment
			foreach ($imgComments as $comment)
			{

				// $html[] = '<div class="row">';

				// $html[] = '<div class="media">';
				/**/
				$html[] = '    <a class="pull-left span2" href="#">';
				//$html[] = '<div class="thumbnail">';

				// $html[] = '<img class="img-responsive user-photo" src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png">';
				$html[] = '        <div>';
				$html[] = '            <i class="icon-user"></i>';
				$html[] = '            <strong>' . $comment->user_name . '</strong>';
				//$html[] = '            <br> <span class="text-muted">commented 5 days ago</span>';
				$html[] = '        </div>';

				//$html[] = '</div>'; //<!-- /thumbnail -->
				$html[] = '    </a>';
				/**/

				/**/
				$html[] = '<div class="media-body  span10">';
				$html[] = '    <i class="icon-comment"></i>';
				$html[] = '    <strong class="media-heading title">' . $comment->subject . '</strong>';
				//$html[] = '    <strong>myusername</strong> <span class="text-muted">commented 5 days ago</span>';

				$html[] = '    <p><div>' . $comment->comment . '</div></p>';
				$html[] = '<hr>';

				$html[] = '</div>';
				$html[] = '';
				/**/

				/**
				$html[] = '</div>'; // class="media">';
				$html[] = '';
				// $html[] = '</div>'; // row
				$html[] = '';
				/**/


				$html[] = '<hr>';
				/**/
			}

			/**/
		}

		//--- add comment -----------------------------------------------------

		$html[] = '';

		$html[] = '<a name="lblAddCcomment"></a>';

		/**/
		//$html[] = '<hr>';
		$html[] = '';

		$html[] = '                <form name="rsgCommentForm" class="form-horizontal" method="post"';
		$html[] = '                    action="' . JRoute::_('index.php?option=com_rsgallery2&view=gallery&gid=' . $gid) .'&startShowSingleImage=1" id="rsgCommentForm">';

		$html[] = '                    <div class ="well">';
		$html[] = '                        <h4>'. JText::_('COM_RSGALLERY2_CREATE_COMMENT') . '</h4>';

		// ToDo: text-align="center
		$html[] = '                        <button id="commitSend" class="btn btn-primary pull-right" ';
		$html[] = '                            type="submit" ';
//    $html[] = '						       onclick="Joomla.submitbutton(\'comment.saveComment\')"';
		$html[] = '						       onclick="Joomla.submitbutton(this.form);return false" ';
		$html[] = '							   title="' . JText::_('COM_RSGALLERY2_SEND_COMMENT_DESC') . '">';
		$html[] = '						       <i class="icon-save"></i> ' . JText::_('COM_RSGALLERY2_ADD_COMMENT') . '';
		$html[] = '						   </button>';

		$html[] = '                        ' . $formFields->renderFieldset ('comment');

		$html[] = '                    	   <input type="hidden" name="task" value="comment.addComment" />';
		$html[] = '                    	   <input type="hidden" name="rating" value="" />';
		$html[] = '                    	   <input type="hidden" name="paginationImgIdx" value="" />';
		$html[] = '                    	   <input type="hidden" name="id" value="' . $imageId . '" />';
		$html[] = '                    	   <input id="token" type="hidden" name="' . JSession::getFormToken() . '" value="1" />';

		$html[] = '                    </div>';
		$html[] = '                </form>';
		/**/

		$html[] = '            </div>'; // container

		$html[] = '</div>'; // class="container">';

		$html[] = '';

		return implode("\n", $html);
	}

	function htmlExifData ($exifData)
	{
		$html = [];

		$html[] = '<div class="container span12">';

		$html[] =  '        <div class="rsg2_exif_container">';
		$html[] =  '            <dl class="dl-horizontal">';

		// user requested EXIF tags
		foreach ($exifData as $exifKey => $exifValue)
		{
			$html[] =  '            <dt>' . $exifKey . '</dt><dd>' . $exifValue . '</dd>';
		}

		$html[] =  '            </dl>';
		$html[] =  '		</div>'; // rsg2_exif_container

		$html[] = '</div>'; // class="container span12">';

		return implode("\n", $html);
	}



//==========================================================================================================

//==========================================================================================================

	/**
	 * Show description (from semantic /html/inline.php)
	 */
	function _showDescription()
	{
		global $rsgConfig;
		// $item = rsgInstance::getItem(); deprecated
		$gallery = rsgGalleryManager::get();
		$item    = $gallery->getItem();;

		if ($rsgConfig->get('displayHits')):
			?>
			<p class="rsg2_hits"><?php echo JText::_('COM_RSGALLERY2_HITS'); ?> <span><?php echo $item->hits; ?></span>
			</p>
			<?php
		endif;

		if ($item->descr):
			?>
			<p class="rsg2_description"><?php echo stripslashes($item->descr); ?></p>
			<?php
		endif;
	}

	/**
	 * list sub galleries in a gallery
	 *
	 * @param rsgGallery $parent gallery
	 */
	function _subGalleryList($parent)
	{
		global $rsgConfig;
		$includeKids = $rsgConfig->get('includeKids', true);
		$user        = JFactory::getUser();
		$kids        = $parent->kids();

		if (count($kids) == 0)
		{
			return;
		}

		echo JText::_('COM_RSGALLERY2_SUBGALLERIES');

		$kid = array_shift($kids);

		while (true)
		{
			?>
		<a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&gid=" . $kid->id); ?>">
			<?php echo htmlspecialchars(stripslashes($kid->name), ENT_QUOTES); ?>
			(<?php echo galleryUtils::getFileCount($kid->get('id'), $includeKids) . ' ' . JText::_('COM_RSGALLERY2_IMAGES'); ?>)</a><?php

			//Show owner icon (blue with O)
			if ($kid->uid == $user->id)
			{
				echo JHTML::tooltip(JText::_('COM_RSGALLERY2_YOU_ARE_THE_OWNER_OF_THIS_GALLERY'), null, '../../../components/com_rsgallery2/images/status_owner.png', null, null, 0);
			}
			//Show unpublished icon (red with H)
			if ($kid->published == 0)
			{
				echo JHTML::tooltip(JText::_('COM_RSGALLERY2_THIS_GALLERY_IS_NOT_PUBLISHED'), null, '../../../components/com_rsgallery2/images/status_hidden.png', null, null, 0);
			}
			//Show upload possible icon (green with U)
			if (rsgAuthorisation::authorisationCreate($kid->id))
			{
				echo JHTML::tooltip(JText::_('COM_RSGALLERY2_YOU_CAN_UPLOAD_IN_THIS_GALLERY'), null, '../../../components/com_rsgallery2/images/status_upload.png', null, null, 0);
			}

			if ($kid = array_shift($kids))
			{
				echo ', ';
			}
			else
			{
				break;
			}
		}
	}


	/**
	 * @param $images
	 *
	 *
	 * @since version
	 */
	public function AssignImageRatingData($images)
	{
		global $rsgConfig;
		// path to image

		//$ratingModel = $this->getModel('rating');
		$ratingModel = JModelLegacy::getInstance('rating', 'RSGallery2Model');

		foreach ($images as $image)
		{
			/**/
			$ratingData = new stdClass();

			$SumAndVotes = $ratingModel->getRatingSumAndVotes ($image->id);

			$average = $ratingModel->calculateAverage($SumAndVotes->rating, $SumAndVotes->votes);
			$ratingData->average = $average;
			$ratingData->count = $SumAndVotes->votes;

			// Only if voting is only once
			$ratingData->lastRating = $ratingModel->isUserHasRated($image->id);

			// for test of view
			//$ratingData->average = 0.4;
			//$ratingData->average = 0.5;
			//$ratingData->average = 0.9;
			//$ratingData->average = 1.0;
			//$ratingData->average = 1.1;
			//$ratingData->average = 2.4;
			//$ratingData->average = 2.5;
			//$ratingData->average = 2.9;
			//$ratingData->average = 3.0;
			//$ratingData->average = 3.1;

			//$ratingData->average = 4.4;
			//$ratingData->average = 4.5;
			//$ratingData->average = 4.6;
			//$ratingData->average = 4.9;
			//$ratingData->average = 5.0;

			// catch
			$image->ratingData = $ratingData;
			/**/




		}
	}


	/**
	 * @param $images
	 *
	 *
	 * @since version
	 */
	public function AssignImageComments($images)
	{
		global $rsgConfig;

		// d:\xampp\htdocs\Joomla3x\components\com_rsgallery2\models\forms\comment.xml
		// D:\xampp\htdocs\joomla3x/components/rsgallery2/models/forms/comment.xml
		$xmlFile    = JPATH_SITE . '/components/com_rsgallery2/models/forms/comment.xml';
		$formFields = JForm::getInstance('comment', $xmlFile);

		/**
		$params = YireoHelper::toRegistry($this->item->params)->toArray();
		$params_form = JForm::getInstance('params', $file);
		$params_form->bind(array('params' => $params));
		$this->params_form = $params_form;
		/**/

		foreach ($images as $image)
		{
			$image->comments = new stdClass();

			$image->comments->formFields = $formFields;
			$image->comments->comments = [];
		}
	}


	/**
	 * @param $images
	 *
	 *
	 * @since version
	 */
	public function AssignImageExifData($images)
	{
		global $rsgConfig;

		try
		{
		    /**/
			// user requested EXIF tags
			// $strExifTags = $rsgConfig->get('exifTags');
			// $useExifTags = explode("|", $strExifTags);
			//$useExifTags = $rsgConfig->get('exifTags');
			$useExifTags = explode("|", $rsgConfig->get('exifTags'));

			if (is_array ($useExifTags))
			{
				$useExifTags = array_map('strtolower', $useExifTags);

				// all images (Normally one)
				foreach ($images as $image)
				{
					// preset result
					$ImgExifData = [];

					$fileName = $image->name;

					try
					{
						$pathFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $fileName;
						if (!file_exists($pathFileName))
						{
							$pathFileName = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $fileName;
						}

						if (!file_exists($pathFileName))
						{
							continue;
						}

						// ToDo: Cache exif Data
						// $exifData = exif_read_data($pathFileName, 'IFD0');
						$exifData = exif_read_data($pathFileName);

						foreach ($exifData as $exifKey => $exifValue)
						{
							// single value pair
							if (!is_array($exifValue))
							{

								if (in_array(strtolower($exifKey), $useExifTags))
								{
									$exifValue              = $this->ExifValue2String($exifKey, $exifValue);
									$ImgExifData [$exifKey] = $exifValue;
								}
							}
							else
							{
								foreach ($exifValue as $exifSubKey => $exifSubValue)
								{
									if (in_array(strtolower($exifSubKey), $useExifTags))
									{
										$exifSubValue              = $this->ExifValue2String($exifSubKey, $exifSubValue);
										$ImgExifData [$exifSubKey] = $exifSubValue;
									}
								}
							}
						}
					}
					catch (RuntimeException $e)
					{
						$OutTxt = '';
						$OutTxt .= ': Error executing AssignImageExifData (inner): "' . $fileName . '"<br>';
						$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

						$app = JFactory::getApplication();
						$app->enqueueMessage($OutTxt, 'error');
					}


					/**
					 * $exif = exif_read_data('tests/test1.jpg', 'IFD0');
					 * echo $exif===false ? "No header data found.<br />\n" : "Image contains headers<br />\n";
					 *
					 * $exif = exif_read_data('tests/test2.jpg', 0, true);
					 * echo "test2.jpg:<br />\n";
					 * foreach ($exif as $key => $section) {
					 * foreach ($section as $name => $val) {
					 * echo "$key.$name: $val<br />\n";
					 * }
					 * }
					 * /**/

					/**
					 * $filedata = exif_read_data($images[$i]);
					 * if(is_array($filedata) && isset($filedata['ImageDescription'])){
					 * $filename = $filedata['ImageDescription'];
					 * } else{
					 * $filename = explode('.', basename($images[$i]));
					 * $filename = $filename[0];
					 * }
					 * /**/

					$image->exifData = $ImgExifData;

				} // all images
			} // is array
            /**/
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= ': Error executing AssignImageExifData (outer)' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}


	}


	private function  ExifValue2String ($exifKey, $exifValue)
	{

		try
		{
			switch ($exifKey)
			{
				case 'FileDateTime':  $exifValue = date("d-M-Y H:i:s", $exifValue); break;


			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= ': Error executing ExifValue2String exifKey: ' . $exifKey . ' exifValue: ' . $exifValue . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return  $exifValue;
	}




}
