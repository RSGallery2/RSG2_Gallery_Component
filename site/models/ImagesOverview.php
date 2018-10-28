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
class RSGallery2ModelImagesOverview extends JModelList
{
    /**
     * @var object
     *
     * @since ?
     */
    private $item;

    /**
     * Constructor
     *
     * @param   array $config An optional associative array of configuration settings
     *
     * @since ?
     */
    public function __construct($config = array())
    {
        /**/
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'publish_up', 'sermons.publish_up',
                'publish_down', 'sermons.publish_down',
            );
        }

        parent::__construct($config);

        // Adding viewname to context so UserStates aren't saved accross the various views
        $this->context .= '.' . JFactory::getApplication()->input->get('view', 'sermons');
        /**/
    }




}
