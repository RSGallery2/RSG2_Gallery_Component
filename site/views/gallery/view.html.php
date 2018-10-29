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
class RSGallery2ViewGallery extends HtmlView
{
    // toDo: protected ...


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

        $config->intro_text = $rsgConfig->get('intro_text');

        $config->displaySearch = $rsgConfig->get('displaySearch');
        $config->displayRandom = $rsgConfig->get('displayRandom');
        $config->displayLatest = $rsgConfig->get('displayLatest');

        $config->displayBranding = $rsgConfig->get('displayBranding');
        $config->displayDownload = $rsgConfig->get('displayDownload');
        $config->displayStatus = $rsgConfig->get('displayStatus');
        $config->dispLimitbox = $rsgConfig->get('dispLimitbox');
        //$config->galcountNrs = $rsgConfig->get('galcountNrs');
        $config->rootGalleriesCount = $rsgConfig->get('galcountNrs');
        $config->showGalleryOwner = $rsgConfig->get('showGalleryOwner');
        $config->showGallerySize = $rsgConfig->get('showGallerySize');
        $config->includeKids = $rsgConfig->get('includeKids');
        $config->showGalleryDate = $rsgConfig->get('showGalleryDate');

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

        echo "RSGallery2ViewRSGallery2<br />";

        $this->config = $this->rootConfig ();

        $input = JFactory::getApplication()->input;
        $this->galleryId = $input->get('gid', 0, 'INT');

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
            // Get gallery data for the view
	        //$galleryModel = JModelLegacy::getInstance('gallery', 'rsgallery2Model');
	        //$this->gallery = $galleryModel->get('Item');
	        $this->gallery = $this->get('Item');

        // Get Images of gallery
            $ImageModel = JModelLegacy::getInstance('images', 'rsgallery2Model');
            $this->images = $ImageModel->getItems();

            // Assign image url links to images
            $ImageModel->AssignImageUrls($this->images);
        }
        else
        {
            // prepare root galleries data
            $galleryModel = JModelLegacy::getInstance('galleries', 'rsgallery2Model');
            //$this->galleries = $galleryModel->getRootGalleries($this->config->rootGalleriesCount);
            // $galleryModel->populatestate (...);
            $this->galleries = $galleryModel->getItems ();

            // image model needed ?
            if ($this->config->displayRandom or $this->config->displayLatest)
            {
                $ImageModel = JModelLegacy::getInstance('images', 'rsgallery2Model');
            }


            if ($this->config->displayRandom)
            {
                // prepare root galleries data
                // $this->imagesRandom = $ImageModel->getRandomImages(count);
                // Assign image url links to images
                //$ImageModel->AssignImageUrls($this->imagesRandom);
            }

            if ($this->config->displayLatest)
            {
                // prepare root galleries data
                // $this->imagesLatest = $ImageModel->getLatestImages(count);
                // Assign image url links to images
                //$ImageModel->AssignImageUrls($this->imagesLatest);

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
}
