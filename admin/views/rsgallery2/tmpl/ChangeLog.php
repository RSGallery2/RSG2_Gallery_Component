<?php // no direct access
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
