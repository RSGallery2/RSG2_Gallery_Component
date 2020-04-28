<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// ToDo: Add GID and use it for search only images in given gallery

//JHtml::_('behavior.core');

//$doTask  = $displayData['doTask'];
 echo "layout classic: search: <br>";
/**
<!--button onclick="<?php echo $doTask; ?>" class="btn btn-small" data-toggle="collapse" data-target="#collapse-<?php echo $name; ?>"<?php echo $onClose; ?>>
	<span class="icon-cog" aria-hidden="true"></span>
	<?php echo $text; ?>
</button--> 
/**/

$document = JFactory::getDocument();

if ($document->getType() == 'html')
{
	$document->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css");
}
?>

<div align="right">
	<form name="rsg2_search" method="post" action="<?php echo JRoute::_('index.php'); ?>">
		<?php echo JText::_('COM_RSGALLERY2_SEARCH'); ?>
		<input type="text" name="searchtext" class="searchbox" 
			onblur="if(this.value=='') this.value='<?php echo JText::_('COM_RSGALLERY2_KEYWORDS'); ?>';" 
			onfocus="if(this.value=='<?php echo JText::_('COM_RSGALLERY2_KEYWORDS'); ?>') this.value='';" value='<?php echo JText::_('COM_RSGALLERY2_KEYWORDS'); ?>' />
		<input type="hidden" name="option" value="com_rsgallery2" />
		<input type="hidden" name="rsgOption" value="search" />
		<input type="hidden" name="task" value="showResults" />
	</form>
	
	<form class="form-search">
	  <input type="text" class="input-medium search-query">
	  <button type="submit"  placeholder="Query" class="btn">Search</button>
	  
		<div class="input-append">
		  <input class="span2" id="appendedInputButtons" type="text">
		  <button class="btn" type="button">Search</button>
		  <button class="btn" type="button">Options</button>
		</div>
	  
	</form>
	
	<form class="form-search">
	  <div class="input-append">
		<input type="text" class="span2 search-query">
		<button type="submit" class="btn">Search</button>
	  </div>
	  <div class="input-prepend">
		<button type="submit" class="btn">Search</button>
		<input type="text" class="span2 search-query">
	  </div>
	</form>
	
	
</div> 
