<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

/**
 * This is the legacy class for displaying the root gallery
 * and single gallery images view.
 * The layouts will be found in ...\layouts\ClassicJ25
 * The Classic25 form supports the 'classic' style of RSG2
 * until the front end sources and paths of sources
 * were changed to the J3x layout inend of 2018
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
class RSGallery2ViewGallery extends HtmlView
{
    // toDo: protected ...
    protected $pagination;
    protected $state;

    protected $images;
    protected $galleries;

	protected $isGallerySingleImageView;

    /**
     * Display job item
     *
     * @param   string  $tpl  template name
     *
     * @return void
     */
    public function display($tpl = null)
    {
        global $rsgConfig;

//        echo "RSGallery2ViewRSGallery2<br />";

        $input = JFactory::getApplication()->input;
        $this->galleryId = $input->get('gid', 0, 'INT');
		$this->isGallerySingleImageView = $input->get('startShowSingleImage', 0, 'INT');

        // collect parts of complete configuration (checks authorization)
        $this->config = $this->rootConfig ($this->galleryId);


        //--- fall backs ----------------------------------

        $this->gallery = new \stdClass();
        $this->galleries = new \stdClass();
        $this->images = new \stdClass();

        $this->imagesRandom = new \stdClass();
        $this->imagesLatest = new \stdClass();

        /*-------------------------------------------------
        where the music plays
        -------------------------------------------------*/

        // Single gallery view ?
        if($this->galleryId > 0) {

        	// Standard tiles ?
	        if(empty($this->isGallerySingleImageView))
	        {
		        /*--------------------------------------------------
				   prepare single gallery data
				--------------------------------------------------*/

		        // Get gallery data for the view
		        $this->gallery = $this->get('Item');

		        // Get Images of gallery
		        $ImageModel   = JModelLegacy::getInstance('images', 'rsgallery2Model');

				/**
		        $ImageModel->setModelState();
                $ImageModel->setState('list.limit', 1);
				/**/

                $this->images = $ImageModel->getItems();

		        // Assign image url link to images
		        $ImageModel->AssignImageUrls($this->images);

		        // Child galleries
		        $galleriesModel  = JModelLegacy::getInstance('Galleries', 'rsgallery2Model');
		        $this->galleries = $galleriesModel->getChildGalleries($this->gallery->id, 10);

		        $galleriesModel->AddGalleryExtraData($this->galleries);

		        // $this->state = $ImageModel->getState();

		        //$this->pagination = $ImageModel->get('Pagination');
		        $this->pagination = $ImageModel->getPagination();
	        }
	        else
	        {
		        /*--------------------------------------------------
				   prepare gallery single images slider
				--------------------------------------------------*/

		        // Get gallery data for the view
		        $this->gallery = $this->get('Item');

		        // Get Images of gallery
		        $ImageModel = JModelLegacy::getInstance('images', 'rsgallery2Model');

		        $ImageModel->setState('list.limit', 1); // always one image per page

		        $this->images = $ImageModel->getItems();

		        $this->pagination = $ImageModel->getPagination();

		        //-- additional image data -----------------------------------

                // Assign image url link to images
                $ImageModel->AssignImageUrls($this->images);

                // Assign voting data to images
                if ($this->config->displayVoting)
                {
                    $ImageModel->AssignImageRatingData($this->images);
                }

                // Assign comments to images
                if ($this->config->displayComments)
                {
                    $ImageModel->AssignImageComments($this->images);
                }

                // Assign EXIF data to images
                if ($this->config->displayEXIF)
                {
                    $ImageModel->AssignImageExifData($this->images);
                }

            }
        }
        else
        {
            /*--------------------------------------------------
               prepare root galleries data
            --------------------------------------------------*/

            // Root galleries
            $galleriesModel = JModelLegacy::getInstance('Galleries', 'rsgallery2Model');

            // ToDO: _> get items,  AddGalleryExtraData seperate
            //$this->galleries = $galleriesModel->getRootGalleryData ();
            $this->galleries = $galleriesModel->getItems ();
            $galleriesModel->AddGalleryExtraData($this->galleries);

            // ToDo: is this needed ?
            $this->galleryCount = $galleriesModel->getDisplayGalleryCount ();

            // $this->state = $galleriesModel->getState();

            $this->pagination = $galleriesModel->getPagination();

            /*--------------------------------------------------
               random and latest images
            --------------------------------------------------*/

            // image model needed ?
            if ($this->config->displayRandom or $this->config->displayLatest)
            {
                $ImageModel = JModelLegacy::getInstance('images', 'rsgallery2Model');
            }


            if ($this->config->displayRandom)
            {
                // prepare root galleries data
                $RandomCount = 3;
                $this->imagesRandom = $ImageModel->getRandomImages($RandomCount);
                // Assign image url links to images
                $ImageModel->AssignImageUrls($this->imagesRandom);
            }

            if ($this->config->displayLatest)
            {
                // prepare root galleries data
                $LatestCount = 3;
                $this->imagesLatest = $ImageModel->getLatestImages($LatestCount);
                // Assign image url links to images
                $ImageModel->AssignImageUrls($this->imagesLatest);
            }
        }

	    // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new RuntimeException(implode('<br />', $errors), 500);
        }

        // Display the view
        parent::display($tpl);
    }

	/**
	 * Collect all rsgConfig variables which are used
	 * on the 'front' page like root galleries and
	 *
	 * @since version
	 */
	public function rootConfig($galleryId)
	{
		global $rsgConfig;

		$config = new \stdClass;

		$config->intro_text = $rsgConfig->get('intro_text');

		$config->displaySearch = $rsgConfig->get('displaySearch');
		$config->displayRandom = $rsgConfig->get('displayRandom');
		$config->displayLatest = $rsgConfig->get('displayLatest');

		$config->displayBranding = $rsgConfig->get('displayBranding');
		$config->displayDownload = $rsgConfig->get('displayDownload');
		$config->displayStatus = $rsgConfig->get('displayStatus');
		$config->dispLimitBox = $rsgConfig->get('dispLimitbox');
		$config->rootGalleriesCount = $rsgConfig->get('galcountNrs');
		$config->showGalleryOwner = $rsgConfig->get('showGalleryOwner');
		$config->showGallerySize = $rsgConfig->get('showGallerySize');
		$config->includeKids = $rsgConfig->get('includeKids');
		$config->showGalleryDate = $rsgConfig->get('showGalleryDate');
		$config->displaySlideshow = $rsgConfig->get('displaySlideshow');
		$config->displaySlideshowGalleryView = $rsgConfig->get('displaySlideshowGalleryView');
		$config->displayThumbsStyle  = $rsgConfig->get('display_thumbs_style');
		$config->displayThumbsShowName = $rsgConfig->get('display_thumbs_showImgName');

		$config->thumb_width = $rsgConfig->get('thumb_width');
		$config->floatDirection =  $rsgConfig->get('display_thumbs_floatDirection');
		$config->template = $rsgConfig->get('template');

		$config->thumbsColPerPage = $rsgConfig->get('display_thumbs_colsPerPage');
		$config->displaySlideshowImageDisplay = $rsgConfig->get('displaySlideshowImageDisplay');
		$config->displayPaginationBarMode = $rsgConfig->get('display_navigation_bar_mode');

        $config->imagePopupMode = $rsgConfig->get('displayPopup');

        $config->displayDesc = $rsgConfig->get("displayDesc");
        $config->displayVoting = $rsgConfig->get("displayVoting");
        $config->displayComments = $rsgConfig->get("displayComments");
        $config->displayEXIF = $rsgConfig->get("displayEXIF");

        $config->isDisplayExit = $rsgConfig->get("displayEXIF");
        $config->isDisplayImgHits = $rsgConfig->get('displayHits');

        //--- Correct vars for authorisation issues ----------------------------------------

        // voting authorisated
        if ($config->displayVoting)
        {
            //Check if user is allowed to vote (permission rsgallery2.vote on asset com_rsgallery2.gallery."gallery id"
            // Only valid for single gallery
            if (! JFactory::getUser()->authorise('rsgallery2.vote', 'com_rsgallery2.gallery.' . $galleryId))
            {
                // ToDo: activate again $config->displayVoting = false;
            }
        }

        return $config;
	}




}
