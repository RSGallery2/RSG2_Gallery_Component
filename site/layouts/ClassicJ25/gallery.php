<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//JHtml::_('behavior.core');

//$doTask  = $displayData['doTask'];
 echo "layout classic: gallery: <br>";
/**
<!--button onclick="<?php echo $doTask; ?>" class="btn btn-small" data-toggle="collapse" data-target="#collapse-<?php echo $name; ?>"<?php echo $onClose; ?>>
	<span class="icon-cog" aria-hidden="true"></span>
	<?php echo $text; ?>
</button--> 
/**/

$Found = json_encode($displayData['item']);
echo "Item: " . $Found;

?>

