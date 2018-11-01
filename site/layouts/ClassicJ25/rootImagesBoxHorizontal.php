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

echo "layout classic J25: rootImagesBoxHorizontal: <br>";
/**
<!--button onclick="<?php echo $doTask; ?>" class="btn btn-small" data-toggle="collapse" data-target="#collapse-<?php echo $name; ?>"<?php echo $onClose; ?>>
<span class="icon-cog" aria-hidden="true"></span>
<?php echo $text; ?>
</button-->
/**/

// This displays the root gallery text 2

//$gallery = json_encode($displayData['item']);
//echo "Gallery: " . $gallery;
$images = $displayData['images'];
$title =  $displayData['title'];
echo 'Images count: ' . count($images);
$config =  $displayData['config'];
$thumb_width = $config->thumb_width;

/**/
echo '<ul id="rsg2-galleryList">';
echo '<li class="rsg2-galleryList-item">';
echo '  <table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">';
echo '      <tr>';
echo '          <td colspan="3">' . $title . '</td>';
echo '      </tr>';
echo '      <tr>';
echo '          <td colspan="3">&nbsp;</td>';
echo '      </tr>';
echo '      <tr>';

foreach ($images as $image)
{
//    echo '              $l_start = $row->ordering - 1;';
//    echo '              $url     = JRoute::_("index.php?option=com_rsgallery2&page=inline&id=" . $row->id);';

    echo '              <td align="center">';
    echo '                  <div class="shadow-box">';
    echo '                      <div class="img-shadow">';
    echo '    						<a href="/Joomla3x/index.php/gallery-test/item/1925/asInline">' . '<br>';
    echo '                              <img src="' . $image->UrlThumbFile . '" alt="'
                                            .  $image->title . '"  width="' . $thumb_width . '" />' . '<br>';
    echo '			    			</a>' . '<br>';
    echo '                      </div>';
    echo '                      <div class="rsg2-clr"></div>';
    echo '                         <div class="rsg2_details">';
    echo '                         ' . JText::_('COM_RSGALLERY2_UPLOADED_DBLPT');
    echo '                         ' . '&nbsp' . JHTML::_("date", $image->date);
    echo '                          </div>';
    echo '                  </div>';
    echo '              </td>';
}

echo '      </tr>';
echo '      <tr>';
echo '          <td colspan="3">&nbsp;</td>';
echo '      </tr>';
echo '  </table>';
echo '    </li>';
echo '</ul>';

?>


