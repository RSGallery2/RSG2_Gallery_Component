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

echo "layout classic J25: rootLatestImages: <br>";

// $config    = $displayData['config'];

/**

//Show limitbox
if ($this->pageNav->total):
    ?>
    <div class="rsg2-pagenav-limitbox">
        <form action="<?php echo JRoute::_("index.php?option=com_rsgallery2"); ?>" method="post">
            <?php echo $this->pageNav->getLimitBox(); ?>
        </form>
    </div>
<?php
endif;

/**/


?>
<div class="rsg2-pagenav-limitbox">
    <form action="/Joomla3xRelease/index.php/galleries-overview" method="post">
        <select id="limit" name="limit" class="inputbox input-mini" size="1" onchange="this.form.submit()">
            <option value="5" selected="selected">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
            <option value="30">30</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="0">All</option>
        </select>
    </form>
</div>
