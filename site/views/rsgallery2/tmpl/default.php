<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;

defined('_JEXEC') or die;

//HTMLHelper::_('script', 'com_foo/script.js', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_foo/style.css', array('version' => 'auto', 'relative' => true));

if($this->item->params->get('show_title'))
{
    echo "<h1>";
    echo $this->item->title.(($this->item->params->get('show_category')) ? (' ('.$this->item->category.')') : '');
    echo "</h1>";
}
echo $this->item->description;


$layout = new FileLayout('rsgallery2.page');
$data = array();
$data['text'] = 'RSGallery2 Hello Joomla! (1)';
echo $layout->render($data);

