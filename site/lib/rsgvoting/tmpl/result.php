<?php
/**
 * This file contains Voting in RSG2
 *
 * @version       $Id: rsgvoting.php 1085 2012-06-24 13:44:29Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2019 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */ 

 defined('_JEXEC') or die;

 ?>
<table border="0" width="200">
	<tr>
		<td><?php echo JText::_('COM_RSGALLERY2_RATING'); ?>:</td>
		<td><?php echo rsgVoting::calculateAverage($id); ?>&nbsp;/&nbsp;<?php echo rsgVoting::getVoteCount($id); ?>&nbsp;<?php echo JText::_('COM_RSGALLERY2_VOTES'); ?></td>
	</tr>
</table>
