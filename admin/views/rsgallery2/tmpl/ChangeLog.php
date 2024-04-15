<?php // no direct access
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2024 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/changelog.php');

$HtmlLog = $result = str_replace(' ', '&nbsp;', $ChangeLog);
$HtmlLog = nl2br($HtmlLog);
?>
<div class="container-popup">
	<?php
	echo $HtmlLog;
	?>
</div>
