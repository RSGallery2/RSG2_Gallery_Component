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

	protected $_total = null;
	protected $_pagination  = null;

    function __construct()
    {
        parent::__construct();
    }

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
        //$input = $app->input;

        //$gid = $input->get('gid', '', 'INT');
        //$this->setState('images.galleryId', $gid);

        // Load the config parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        /**/
        // Load the list state.

        //$this->setState('list.start', 0);

        // thumbs per page
        $limit = $params['galcountNrs'];
        $this->setState('list.limit', $limit);
        /**/

	    /**/
        $limitStart = $app->input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $limitStart);
        /**/
    }

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Gallery', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
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
     * collects all data needed for displaying the root gallery on front page
     * 2018.01.01 Actually it supports the old 'legacy' view data
	 
     * @since version
     */
    public function getRootGalleryData()
    {
        global $rsgConfig;

        $galleries = [];

        try
        {
            // ToDo: pagination start / end ...
            // ToDo: either input or config
            $limit = $rsgConfig->get ('galcountNrs');

            // Create a new query object.
            $db = $this->getDbo();
            $query = $db->getQuery(true);

            // ToDo: Only select required fields
            $query->select('*')
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->where($db->quoteName('published') . '=' . (int) 1)
//                ->setLimit((int) $limit)
                ->order('ordering');

            /**/
            $db->setQuery($query);
            $galleries = $db->loadObjectList();

        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= ': Error executing query in getRootGalleryData' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // add further data to galleries
        $this->AddGalleryExtraData($galleries);

        return $galleries;
        /**/
    }
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

                $imageName = $this->thumbNameFromId ($thumbId);

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

        $limit = 1;

        // Select required fields
        $query->select('id')
            ->from($db->quoteName('#__rsgallery2_files'))
            ->where($db->quoteName('gallery_id') . '=' . (int) $galleryId)
            ->setLimit((int) $limit)
            ->order('RAND()')
        ;

        $db->setQuery($query);


        $list = $db->loadResult();

        if ($db->getErrorNum())
        {
            echo $db->stderr();
            return false;
        }

        return $list;
    }

    public function thumbNameFromId ($thumbId)
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


        $list = $db->loadResult();

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
            //    ->order('RAND() LIMIT 1')
        ;

        $db->setQuery($query);

        $count = $db->loadResult();

        return $count;
    }


    /**
     * @param $images
     *
     *
     * @since version
     */
    public function AssignHasNewImages ($galleries)
    {
        global $rsgConfig;
        // path to image


        // all galleries
        foreach ($galleries as $gallery)
        {
            $IsHasNewImages = false;

            try
            {
                //--- Create URL for thumb -----------------
                // toDo: ? new images defined as within 7 days use config ??
                $IsHasNewImages = $this->GalleryHasNewImages ($gallery->id);
            }
            catch (RuntimeException $e)
            {
                $OutTxt = '';
                $OutTxt .= 'AssignHasNewImages to ' . $gallery->name . ': Error executing query in hasNewImages' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

            $gallery->IsHasNewImages  = $IsHasNewImages;
        }
    }


    /**
     *
     * @param int $days amount of days to the past
     *
     * @return true if there is new images within the given time span
     * @todo rewrite the sql to use better date features
     * @since 4.3.0
     */
    function GalleryHasNewImages($galleryId, $days = 7)
    {
        $count = 0;

        $lastWeek = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));
        $lastWeek = date("Y-m-d H:m:s", $lastWeek);

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select required fields
        $query->select('COUNT(*)')
            ->from($db->quoteName('#__rsgallery2_files'))
            ->where($db->quoteName('gallery_id') . '=' . (int) $galleryId)
            ->where($db->quoteName('date') . '>=' . $db->quote($lastWeek))
            ->where($db->quoteName('published') . '=' . (int) 1)
            //    ->order('RAND() LIMIT 1')
        ;

        $db->setQuery($query);
        $count = $db->loadResult();

        return $count > 0;
    }

    /**
     * @param $images
     *
     *
     * @since version
     */
    public function AssignOwners ($galleries)
    {
        global $rsgConfig;
        // path to image


        // all galleries
        foreach ($galleries as $gallery)
        {
            $OwnerName = "";

            try
            {
                //--- Create URL for thumb -----------------
                // toDo: ? new images defined as within 7 days use config ??
                $OwnerName = $this->GalleryOwnerName ($gallery);
            }
            catch (RuntimeException $e)
            {
                $OutTxt = '';
                $OutTxt .= 'AssignHasNewImages to ' . $gallery->name . ': Error executing query in hasNewImages' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

            $gallery->OwnerName  = $OwnerName;
        }
    }
    /**/

    /**
     *
     * @param int $days amount of days to the past
     *
     * @return true if there is new images within the given time span
     * @todo rewrite the sql to use better date features
     * @since 4.3.0
     */
    function GalleryOwnerName($galleryId)
    {
        $user = JFactory::getUser($galleryId->uid);
        $userName=$user->get('username');

        return $userName;
    }



    public function getDisplayGalleryCount ()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select required fields
        $query->select('COUNT(*)')
            ->from($db->quoteName('#__rsgallery2_galleries'))
            ->where($db->quoteName('published') . '=' . (int) 1)
        ;

        $db->setQuery($query);
        $count = $db->loadResult();

        return $count;
    }


    public function getChildGalleries ($galleryId, $limit=0)
    {
        $galleries = [];

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        try
        {
            // ToDo: Only select required fields
            $query->select('*')
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->where($db->quoteName('published') . '=' . (int) 1)
                ->where('parent=' . (int) $galleryId)
                //->order('RAND()')
            ;

            if ($limit > 0)
            {
	            $query->setLimit($limit);
            }

            /**/
            $db->setQuery($query);
            $galleries = $db->loadObjectList();
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= ': Error executing query in getChildGalleries' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $galleries;
    }


	/**
	 * @param $galleries
	 *
	 *
	 * @since version
	 */
	public function AddGalleryExtraData($galleries)
	{
		if (!empty ($galleries)) {
			// ToDo: Optimize further runtime with quering data for all 'id's in list at once

			// Assign image count to galleries
			$this->AssignImageCount($galleries);

			// Assign thumb url link to galleries
			$this->AssignThumbUrls($galleries);

			// Assign has new image bool values
			$this->AssignHasNewImages($galleries);

			// Assign has new image bool values
			$this->AssignOwners($galleries);

		}
	}
	/**/




}
