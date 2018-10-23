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
    /**
     * Display job item
     *
     * @param   string  $tpl  template name
     *
     * @return void
     */
    public function display($tpl = null)
    {
        echo "RSGallery2ViewRSGallery2<br />";

        // Get gallery data for the view
        $this->gallery = $this->get('Item');

        // Get Images of gallery
	    $ImageModel = JModelLegacy::getInstance('images', 'rsgallery2Model');
	    $this->images = $ImageModel->get('Items');
	    $this->images = $ImageModel->get('items');

	    // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new RuntimeException(implode('<br />', $errors), 500);
        }

        // Display the view
        parent::display($tpl);
    }
}
