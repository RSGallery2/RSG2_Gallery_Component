<?php
/**
 * Must have debug enabled to use this template.  Lists all galleries and items.
 *
 * @package       RSGallery2
 * @copyright (C) 2003 - 2019 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

defined('_JEXEC') or die();

// bring in display code
$templatePath = JPATH_RSGALLERY2_SITE . '/templates' . '/debug_listeverything';
require_once($templatePath . '/display.class.php');

global $mainframe;
$template_dir = "JURI_SITE/components/com_rsgallery2/templates/debug_listeverything";
?>
<link href="<?php echo $template_dir ?>/css/template.css" rel="stylesheet" type="text/css" />
<?php

$input = JFactory::getApplication()->input;
$gid = $input->get('gid', 0, 'INT');

echo JText::_('COM_RSGALLERY2_LISTING_CONTENTS_OF_GALLERIES');

$cmd = $input->get('task', 'listEverything', 'CMD');
switch ($cmd)
{
	case 'dumpGallery':
		dumpGallery($gid);
		break;
	case 'listEverything':
	default:
		listEverything($gid);
		break;
}

?>
