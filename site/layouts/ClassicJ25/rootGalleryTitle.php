<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 * @copyright   (C) 2017-2018 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

global $rsgConfig;

//JHtml::_('behavior.core');

echo "layout classic J25: galleryRootTitle: <br>";


//echo "Gallery: " . $gallery;
//$intro_text =  $rsgConfig->get("intro_text")
$config    = $displayData['config'];
$intro_text = $config->intro_text;

?>
<div class="intro_text warning"><?php echo $intro_text; ?></div>
