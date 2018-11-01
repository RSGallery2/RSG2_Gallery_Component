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
class RSGallery2ModelImages extends JModelList
{
    /**
     * Model context string.
     *
     * @var        string
     */
    // public $_context = 'com_rsgallery2.images';

    /**
     * @var     object
     * @since   1.6
     */
    protected $state;
    /**/

    /**
     * The category context (allows other extensions to derived from this model).
     *
     * @var        string
     */

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
     * Method to get a table object, load it if necessary.
     *
     * @param   string $type The table name. Optional.
     * @param   string $prefix The class prefix. Optional.
     * @param   array $config Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    /**
     * public function getTable($type = 'Image', $prefix = 'Rsgallery2Table', $config = array())
     * {
     * return JTable::getInstance($type, $prefix, $config);
     * }
     * /**/

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string $ordering An optional ordering field.
     * @param   string $direction An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   1.6
     */
    /**
     * protected function populateState($ordering = null, $direction = null)
     * {
     * $app = JFactory::getApplication();
     * $this->setState('filter.extension', $this->_extension);
     *
     * // Get the parent id if defined.
     * $parentId = $app->input->getInt('id');
     * $this->setState('filter.parentId', $parentId);
     *
     * $params = $app->getParams();
     * $this->setState('params', $params);
     *
     * $this->setState('filter.published',    1);
     * $this->setState('filter.access',    true);
     * }
     * /**/

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
        $gid = $input->get('gid', '', 'INT');
        $this->setState('images.galleryId', $gid);

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
        $galleryId = $this->getState('images.galleryId');

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select required fields
        $query->select('*')
            ->from($db->quoteName('#__rsgallery2_files'))
            ->where($db->quoteName('gallery_id') . '=' . (int)$galleryId)
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
        $limit = (int)$this->getState('list.limit', 5);

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
    public function AssignImageUrls($images)
    {
        global $rsgConfig;
        // path to image

        // ToDo: Watermarked path and create watermark image if does not exist
        $urlPathThumb = JUri::root() . $rsgConfig->get('imgPath_thumb') . '/';
        $urlPathDisplay = JUri::root() . $rsgConfig->get('imgPath_display') . '/';
        $urlPathOriginal = JUri::root() . $rsgConfig->get('imgPath_original') . '/';

        // Create URL for thumb
        // $urlThumbFile = JUri::root() . $rsgConfig->get('imgPath_thumb') . '/' . $singleFileName . '.jpg';

        foreach ($images as $image) {
            $urlPathThumbFile = $urlPathThumb . $image->name . '.jpg'; // /images/rsgallery/thumb
            $urlPathDisplayFile = $urlPathDisplay . $image->name; // /images/rsgallery/display
            $urlPathOriginalFile = $urlPathOriginal . $image->name; // /images/rsgallery/original

            $image->UrlThumbFile = $urlPathThumbFile;
            $image->UrlDisplayFile = $urlPathDisplayFile;
            $image->UrlOriginalFile = $urlPathOriginalFile;
        }
    }

    public function getRandomImages($limit)
    {
        $images = [];

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        try
        {
            // ToDo: Only select required fields
            $query->select('*')
                ->from($db->quoteName('#__rsgallery2_files'))
                ->where($db->quoteName('published') . '=' . (int) 1)
                ->setLimit((int) $limit)
                 ->order('RAND()')
                ;

            /**/
            $db->setQuery($query);
            $images = $db->loadObjectList();

        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= ': Error executing query in getRootGalleryData' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $images;
    }

    public function getLatestImages($limit)
    {
        $images = [];

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        try
        {
            // ToDo: Only select required fields
            $query->select('*')
                ->from($db->quoteName('#__rsgallery2_files'))
                ->where($db->quoteName('published') . '=' . (int) 1)
                ->setLimit((int) $limit)
                ->order($db->quoteName('date') . ' DESC');
            ;

            /**/
            $db->setQuery($query);
            $images = $db->loadObjectList();

        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= ': Error executing query in getRootGalleryData' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $images;
    }








}

