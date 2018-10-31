<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die;

/**
 * Foo model.
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
class RSGallery2ModelGalleries extends JModelList
{
    /**
     * @var     object
     * @since   1.6
     */
    protected $state;
    /**/

    protected $_extension = 'com_rsgallery2';

    protected $_items = array();

    /**
     * protected $_total = null;
     * protected $_pagination  = null;
     *
     * /**/
    function __construct()
    {
        parent::__construct();
    }
    /**/

    /**
     * populate internal state
     *
     * @return void
     */
    protected function populateState($ordering = 'ordering', $direction = 'dsc')
    {
        // List state information.
        parent::populateState($ordering, $direction);

        /**/
        $app = JFactory::getApplication();
        // Get the job id
        $input = $app->input;

        //$gid = $input->get('gid', '', 'INT');
        //$this->setState('images.galleryId', $gid);

        // Load the config parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        /**/
        // Load the list state.
        $this->setState('list.start', 0);
        $this->setState('list.limit', 10); // ToDo: thumbs per page
        /**/

        /**/
        //$limit = $app->input->get('limit', $app->get('list_limit', 0), 'uint');
        //$limit = $params->display_thumbs_maxPerPage;
        $limit = $params['display_thumbs_maxPerPage'];
        $this->setState('list.limit', $limit);

        //$limitStart = $app->input->get('limitstart', 0, 'uint');
        $limitStart = 0;
        $this->setState('list.start', $limitStart);
        /**/
    }

    protected function getListQuery()
    {
        /**/
        //$galleryId = $this->getState('images.galleryId');

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select required fields
        $query->select('*')
            ->from($db->quoteName('#__rsgallery2_galleries'))
            ->order('ordering');

        return $query;
        /**/
    }


    /**
     * Method to get a list of articles.
     *
     * @return  mixed  An array of objects on success, false on failure.
     *
     * @since   1.6
     */
    public function getItems()
    {
        /**/
        // Get the items.
        $this->_items = parent::getItems();

        /**
         * // Convert them to a simple array.
         * foreach ($items as $k => $v)
         * {
         * $items[$k] = $v->term;
         * }
         * /**/

        /**/
        // Process pagination.
        $limit = (int)$this->getState('list.limit', 5); // ToDo: origin of list limit ?

        // Sets the total for pagination.
        $this->_total = count($this->_items);

        $items = $this->_items;
        if ($limit !== 0) {
            $start = (int)$this->getState('list.start', 0);

            $items = array_slice($this->_items, $start, $limit);
        }
        return $items;
        /**/
    }

    /**
     * Method to get the starting number of items for the data set.
     *
     * @return  integer  The starting number of items available in the data set.
     *
     * @since   12.2
     */
    /**
     * public function getStart()
     * {
     * return $this->getState('list.start');
     * }
     * /**/

    /**
     * @return JPagination|object
     * @since 4.3.0
     *
     * function getPagination()
     * {
     * if (empty($this->_pagination))
     * {
     * // Make sure items are loaded for a proper total
     * if (empty($this->_items))
     * {
     * // Load the items
     * $this->_loadItems();
     * }
     * // Load the pagination object
     * jimport('joomla.html.pagination');
     * $this->_pagination = new JPagination($this->state->get('pagination.total'), $this->state->get('pagination.offset'), $this->state->get('pagination.limit'));
     * }
     *
     * return $this->_pagination;
     * }
     * /**/


	/**
	 * @param $images
	 *
	 *
	 * @since version
	 */
	public function AssignThumbUrls ($galleries)
	{
		global $rsgConfig;
		// path to image

		// ToDo: Watermarked path and create watermark image if does not exist
		$urlPathThumb = JUri::root() . $rsgConfig->get('imgPath_thumb') . '/';
		// prepare empty gallery
		$urlPathEmptyThumbFile = JURI_SITE . "/components/com_rsgallery2/images/no_pics.gif";
		//$urlPathThumbFile = $urlPathEmptyThumbFile;

		// all galleries
		foreach ($galleries as $gallery)
		{
			// images existing ?
			if ($gallery->imgCount > 0)
			{
				//--- Create URL for thumb -----------------

				$thumbId = $gallery->thumb_id;
				// Random thumb
				if ($thumbId == 0)
				{
					$thumbId = $this->randomThumb ($gallery->id);
				}

				$imageName = $this->imageNameFromId ($thumbId);

				$urlPathThumbFile = $urlPathThumb . $imageName . '.jpg'; // /images/rsgallery/thumb
			}
			else
			{
				// empty gallery
				$urlPathThumbFile = $urlPathEmptyThumbFile;
			}

			$gallery->UrlThumbFile = $urlPathThumbFile;
		}
	}

	/**
	 * @param $images
	 *
	 *
	 * @since version
	 */
	public function AssignImageCount ($galleries)
	{
		global $rsgConfig;
		// path to image


		// all galleries
		foreach ($galleries as $gallery)
		{
			$imgCount = -1;

			try
			{
				//--- Create URL for thumb -----------------
				$imgCount = $this->GalleryImageCount ($gallery->id);
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'AssignImageCount to ' . $gallery->name . ': Error executing query in GalleryImageCount' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

			$gallery->imgCount  = $imgCount;
		}
	}

	public function randomThumb ($galleryId)
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields
		$query->select('id')
			->from($db->quoteName('#__rsgallery2_files'))
			->where($db->quoteName('gallery_id') . '=' . (int) $galleryId)
			->order('RAND() LIMIT 1');

		$db->setQuery($query);


		$list = $db->loadAssoc();

		if ($db->getErrorNum())
		{
			echo $db->stderr();
			return false;
		}

		return $list;
	}

	public function imageNameFromId ($thumbId)
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields
		$query->select('name')
			->from($db->quoteName('#__rsgallery2_files'))
			->where($db->quoteName('id') . '=' . (int) $thumbId)
			;

		$db->setQuery($query);


		$list = $db->loadAssoc();

		if ($db->getErrorNum())
		{
			echo $db->stderr();
			return false;
		}

		return $list;
	}

	public function GalleryImageCount ($galleryId)
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields
		$query->select('COUNT(*)')
			->from($db->quoteName('#__rsgallery2_files'))
			->where($db->quoteName('gallery_id') . '=' . (int) $galleryId)
			->order('RAND() LIMIT 1');

		$db->setQuery($query);

		$count = $db->loadResult();

		return $count;
	}

}