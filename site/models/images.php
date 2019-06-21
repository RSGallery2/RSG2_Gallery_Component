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

    /**/
    protected $_total = null;
    protected $_pagination  = null;

     /**/
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
    public function setModelState ()
    {
        $this->populateState();
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
        $gid = $input->get('gid', '', 'INT');
        $this->setState('images.galleryId', $gid);
        $isGallerySingleImageView = $input->get('startShowSingleImage', 0, 'INT');

        /**
        // Get the parent id if defined.
        $parentId = $app->input->getInt('id');
        $this->setState('filter.parentId', $parentId);
        /**/

        // Load the config parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        /**
        // Load the list state.
        $this->setState('list.start', 0);
        /**/

        // thumbs per page
        $limit = $params['display_thumbs_maxPerPage'];

        // Limit is set to one for single image 'slider'
        /**/
        if ($isGallerySingleImageView > 0)
        {
            $limit = 1;
        }
        /**/

        $this->setState('list.limit', $limit);
		/**/

        $limitStart = $app->input->get('limitstart', 0, 'uint');
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
            ->order('ordering DESC');

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

        /*
        if ($limit !== 0) {
            $start = (int)$this->getState('list.start', 0);

            $items = array_slice($this->_items, $start, $start + $limit);
        }
        /**/
        return $items;
        /**/
    }

    /**
     * @param $images
     *
     *
     * @since 4.5.0.0
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

        foreach ($images as $image)
        {
            $urlPathThumbFile = $urlPathThumb . $image->name . '.jpg'; // /images/rsgallery/thumb
            $urlPathDisplayFile = $urlPathDisplay . $image->name . '.jpg'; // /images/rsgallery/display

	        // ToDo: Check if original exists otherwise use display
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

    /**
     * @param $images
     *
     *
     * @since 4.5.0.0
     */
    public function AssignImageRatingData($images)
    {
        global $rsgConfig;
        // path to image

        //$ratingModel = $this->getModel('rating');
	    $ratingModel = self::getInstance('rating', 'RSGallery2Model');

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
     * @since 4.5.0.0
     */
    public function AssignImageComments($images)
    {
        global $rsgConfig;

        // d:\xampp\htdocs\Joomla3x\components\com_rsgallery2\models\forms\comment.xml
        // D:\xampp\htdocs\joomla3x/components/rsgallery2/models/forms/comment.xml
        $xmlFile    = JPATH_SITE . '/components/com_rsgallery2/models/forms/comment.xml';
        $formFields = JForm::getInstance('comment', $xmlFile);

	    $commentsModel = self::getInstance('comments', 'RSGallery2Model');

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
	        $image->comments->comments = $commentsModel->getImageComments ($image->id);


            //$image->comments->;
        }
    }


    /**
     * @param $images
     *
     *
     * @since 4.5.0.0
     */
    public function AssignImageExifData($images)
    {
        global $rsgConfig;

        // preset result
        $ImgExifData = [];

        try
        {
            // user requested EXIF tags
            // $strExifTags = $rsgConfig->get('exifTags');
            // $useExifTags = explode("|", $strExifTags);
            $useExifTags = $rsgConfig->get('exifTags');
            $useExifTags = array_map('strtolower', $useExifTags);

            // all images (Normally one)
            foreach ($images as $image)
            {
                $fileName = $image->name;

                try {
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
                        if ( ! is_array ($exifValue)) {

                            if (in_array(strtolower($exifKey), $useExifTags))
                            {
                                $exifValue = $this->ExifValue2String ($exifKey, $exifValue);
                                $ImgExifData [$exifKey] = $exifValue;
                            }
                        }
                        else
                        {
                            foreach ($exifValue as $exifSubKey => $exifSubValue)
                            {
                                if (in_array(strtolower($exifSubKey), $useExifTags))
                                {
                                    $exifSubValue = $this->ExifValue2String ($exifSubKey, $exifSubValue);
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

                // $ImgExifData =
            } // all images

        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= ': Error executing AssignImageExifData (outer)' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }


        $image->exifData = $ImgExifData;

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

