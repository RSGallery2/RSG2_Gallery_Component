<?php
/**
 * This file contains the main template file for RSGallery2.
 *
 * @package       RSGallery2
 * @copyright (C) 2003 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

/**

ATTENTION!

This is built to imitate the Joomla 1.5.* style of templating.  Hopeful that info is enlightening.

 **/

defined('_JEXEC') or die();

//Load Tooltips
//JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip');

//include page navigation
jimport('joomla.html.pagination');//J!1.5

if ($isDebugSiteActive)
{
	JLog::add('semantic:::index.php');
}

//--- template definitions --------------------------

$templateName = $rsgConfig->get('template');

// bring in display code
$templatePath = JPATH_RSGALLERY2_SITE . '/templates' . '/' . $templateName;
require_once($templatePath . '/display.class.php');

$templateUri = JURI_SITE . "/components/com_rsgallery2/templates/" . $templateName;

//--- template class --------------------------

//$rsgDisplay = new rsgDisplay_semantic();
$templateClass = 'rsgDisplay_' . $templateName;
$rsgDisplay = new $templateClass ();

// base class: Insert meta data (gallery description) and page title into html document
$rsgDisplay->metadata();

// append bread crumps over sub galleries (and image) to Joomla's pathway
$rsgDisplay->showRSPathWay();

$doc = JFactory::getDocument();
$doc->addStyleSheet($templateUri . "/css/template.css", "text/css");

$user_css = $templateUri . "/css/user.css";
if (file_exists ($user_css))
{
	$doc->addStyleSheet($user_css, "text/css");
}

// ToDo: only when search is active
$doc->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css");

?>

<div class="rsg2">
	<?php $rsgDisplay->mainPage(); ?>
</div>
