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
use Joomla\Registry\Registry;

defined('_JEXEC') or die;


/**
 * Foo view.
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
 
class RSGallery2ViewImagesOverview extends JViewLegacy
{
    /**
     * @var     object
     * @since   1.6
     */
    protected $state;

    /**
     * @var     object
     * @since   1.6
     */
    protected $images;

    /**
     * @var     object
     * @since   1.6
     */
    protected $pagination;

    /**
     * @var     object
     * @since
     */
    protected $galleryId;

    /**
	 * Display job item
	 *
	 * @param   string  $tpl  template name
	 *
	 * @return void
	 */
	public function display($tpl = null)
	{
		echo "RSGallery2ViewImagesOverview<br />";

        // Get model data.
        $state = $this->get('State');

        $input = JFactory::getApplication()->input;
        $galleryId = $input->get('gid', 0, 'INT');

        $imagesModel = JModelLegacy::getInstance('images', 'rsgallery2Model');
        //$imagesModel->galleryId = $galleryId;
        $this->images  = $imagesModel->getItems();

        //$this->pagination = $imagesModel->get('Pagination');
        $this->pagination = $imagesModel->getPagination();
        $this->state      = $this->get('State');

        /**
        if ($images)
        {
            // Get Category Model data
            $categoryModel = JModelLegacy::getInstance('Category', 'NewsfeedsModel', array('ignore_request' => true));


            $categoryModel->setState('category.id', $item->catid);
            $categoryModel->setState('list.ordering', 'a.name');
            $categoryModel->setState('list.direction', 'asc');

            // @TODO: $items is not used. Remove this line?
            $items = $categoryModel->getItems();
        }
        /**/

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new RuntimeException(implode('<br />', $errors), 500);
		}

		// Display the view
		parent::display($tpl);
	}


}
