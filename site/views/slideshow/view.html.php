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
 * Foo view.
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
class RSGallery2ViewSlideshow extends HtmlView
{
    // toDo: protected ...
    protected $pagination;
    protected $state;

    protected $images;

    /**
     * Collect all rsgConfig variables which are used
     * on the 'front' page like root galleries and
     *
     * @since version
     */
    public function rootConfig()
    {
        global $rsgConfig;

        $config = new \stdClass;

		/**
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

		/**/

        $config->thumb_width = $rsgConfig->get('thumb_width');

        $config->template = $rsgConfig->get('template');


	    return $config;
    }

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

        $this->config = $this->rootConfig ();

        $input = JFactory::getApplication()->input;
        $this->galleryId = $input->get('gid', 0, 'INT');

        //--- fall backs ----------------------------------

        $this->gallery = new \stdClass();
        $this->images = new \stdClass();

        /*-------------------------------------------------
        where the music plays
        -------------------------------------------------*/

        /*--------------------------------------------------
           prepare gallery data
        --------------------------------------------------*/

        // Get gallery data for the view
        $galleryModel = JModelLegacy::getInstance('gallery', 'rsgallery2Model');
        $this->gallery = $galleryModel->getItem();

        // Get Images of gallery
        $ImageModel = JModelLegacy::getInstance('images', 'rsgallery2Model');
        $this->images = $ImageModel->getItems();

        // Assign image url link to images
        $ImageModel->AssignImageUrls($this->images);

        //$this->pagination = $ImageModel->get('Pagination');
        $this->pagination = $ImageModel->getPagination();

	    // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new RuntimeException(implode('<br />', $errors), 500);
        }

        // Display the view
        parent::display($tpl);
    }


}
