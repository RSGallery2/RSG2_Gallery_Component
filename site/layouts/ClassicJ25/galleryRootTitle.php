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

echo "layout classic: galleryRootTitle: <br>";
/**
<!--button onclick="<?php echo $doTask; ?>" class="btn btn-small" data-toggle="collapse" data-target="#collapse-<?php echo $name; ?>"<?php echo $onClose; ?>>
<span class="icon-cog" aria-hidden="true"></span>
<?php echo $text; ?>
</button-->
/**/

// This displays the root gallery text

//$gallery = json_encode($displayData['item']);
//echo "Gallery: " . $gallery;
$intro_text =  $rsgConfig->get("intro_text")
?>
<div class="intro_text"><h3><strong><?php echo $intro_text; ?></strong></h3></div>