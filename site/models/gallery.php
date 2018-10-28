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
class RSGallery2ModelGallery extends JModelLegacy
{
    protected $text_prefix = 'COM_RSGALLERY2';

    protected $item;

    /**
     * populate internal state
     *
     * @return void
     */
    protected function populateState()
    {
        $app = JFactory::getApplication();

        // Get the gallery id
        $id = $app->input->get('gid', '', 'INT');
        $this->setState('gallery.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        parent::populateState();
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string  $type    The table type to instantiate
     * @param   string  $prefix  A prefix for the table class name. Optional.
     * @param   array   $config  Configuration array for table. Optional.
     *
     * @return  JTable A database object
     */
    public function getTable($type = 'Gallery', $prefix = '\'Rsgallery2Table\'', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Get the gallery data
     *
     * @return object The gallery to be displayed to the user
     */
    public function getItem()
    {
        if (!isset($this->item))
        {
            $db = JFactory::getDbo();
            $id = $this->getState('gallery.id');

            /**
            $query = $db->getQuery(true)->from('#__jobs as j')
                ->leftJoin('#__categories as c ON j.catid=c.id')
                ->select('j.title AS title, j.params, j.description, c.title as category')
                ->where('j.id=' . (int) $id);
            /**/
            $query = $db->getQuery(true);
            $query
                ->select('*')
                ->from($db->quoteName('#__rsgallery2_galleries'))
                ->where('id=' . (int) $id);
            $db->setQuery($query);

            $this->item = $db->loadObject();

            if ($this->item)
            {
                // Load the JSON encoded params
                $params = new \Joomla\Registry\Registry;
                $params->loadString($this->item->params, 'JSON');

                $this->item->params = $params;
                /**
                // Merge global params with item params
                $params = clone $this->getState('params');
                $params->merge($this->item->params);

                $this->item->params = $params;
                /**/
            }
        }

        return $this->item;
    }

    /**
     * Get the gallery data
     *
     * @return object The gallery to be displayed to the user
     */
    public function getImageCount()
    {
        $db = JFactory::getDbo();
		$imageCount = 0;

		try
        {
            $galleryId = $this->getState('gallery.id');

            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('count(1)');
            $query->from('#__rsgallery2_files');
            $query->where('gallery_id=' . (int) $galleryId);
            // Only for superadministrators this includes the unpublished items
            if (!JFactory::getUser()->authorise('core.admin', 'com_rsgallery2'))
            {
                $query->where('published = 1');
            }
            $db->setQuery($query);

            $imageCount = $db->loadResult();

            // ToDo: use following instead of above
            // get the count
            //$imageCount = $db->getNumRows();
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'countImages: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

		return $imageCount;


    }
}
