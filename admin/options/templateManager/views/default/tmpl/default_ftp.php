<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

?>
<fieldset title="<?php echo JText::_('COM_RSGALLERY2_DESCFTPTITLE'); ?>">
	<legend><?php echo JText::_('COM_RSGALLERY2_DESCFTPTITLE'); ?></legend>

	<?php echo JText::_('COM_RSGALLERY2_DESCFTP'); ?>

	<?php 
	/* Outdated ...
	if (J Error::is Error($this->ftp)): 
		<p>echo JText::_($this->ftp->message);</p>
	endif; 
	/**/
	?>

	<table class="adminform nospace">
		<tbody>
		<tr>
			<td width="120">
				<label for="username"><?php echo JText::_('COM_RSGALLERY2_USERNAME'); ?>:</label>
			</td>
			<td>
				<input type="text" id="username" name="username" class="input_box" size="70" value="" />
			</td>
		</tr>
		<tr>
			<td width="120">
				<label for="password"><?php echo JText::_('COM_RSGALLERY2_PASSWORD'); ?>:</label>
			</td>
			<td>
				<input type="password" id="password" name="password" class="input_box" size="70" value="" />
			</td>
		</tr>
		</tbody>
	</table>

</fieldset>
