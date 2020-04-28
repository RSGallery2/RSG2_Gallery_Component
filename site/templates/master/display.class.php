<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2019 - 2020 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

/**
 * Template class for RSGallery2
 *
 * @package RSGallery2
 * @author rsgallery2 team. Main parts extracted from SchuWeb gallery author Sven Schultschik (https://github.com/svanschu/SchuWeb-Gallery) 
 */
class rsgDisplay_schuweb extends rsgDisplay
{
	protected $gallery;
	protected $kids;    // ToDo: galleryKids
    protected $pageNav;

	/**
	 * Show main gallery page
	 *
	 *
	 * @since 4.5.0.0
	 * @throws Exception
	 */
	public function showMainGalleries()
	{
		$gallery = rsgGalleryManager::get();

		// main gallery
		$this->gallery = $gallery;

		//Get number of galleries including main gallery
		$this->kids    = $gallery->kids();

		$this->pageNav = $this->navigation ($gallery);

		$this->display('gallery.php');
	}


	/**
     * return pagination, may also chenge number of kids
	 * @param $gallery
	 *
	 * @return bool|JPagination
	 *
	 * @since 4.5.0.0
	 * @throws Exception
	 */
	public function navigation ($gallery)
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
	 * Shows thumbnails for gallery
	 *
	 * @throws Exception
	 */
	public function showThumbs()
	{
		global $rsgConfig;
		$my = JFactory::getUser();

		// For superadministrators (they have core.admin) this includes the unpublished items
		$itemCount = $this->gallery->itemCount();

		$limit = $rsgConfig->get("display_thumbs_maxPerPage");
		$input      = JFactory::getApplication()->input;
		$limitstart = $input->get('limitstart', 0, 'INT');

		//instantiate page navigation
		jimport('joomla.html.pagination');
		$pagenav = new JPagination($itemCount, $limitstart, $limit);//MK gaat goed: thumbs in gallery

		// increase the gallery hit counter
		$this->gallery->hit();

		if (!$this->gallery->itemCount())
		{
			if ($this->gallery->id)
			{
				// if gallery is not the root gallery display the message
				echo JText::_('COM_RSGALLERY2_NO_IMAGES_IN_GALLERY');
			}

			// no items to display, so we can return;
			return;
		}

		?>
		<div class="pagination">
			<?php
			if ($itemCount > $limit)
			{
				echo $pagenav->getPagesLinks();
				echo "<br /><br />" . $pagenav->getPagesCounter();
			}
			?>
		</div>
		<?php
	}


}

