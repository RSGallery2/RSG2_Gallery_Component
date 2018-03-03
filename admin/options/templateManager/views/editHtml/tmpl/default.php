<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

?>
<form action="index.php" method="post" name="adminForm">

	<?php if ($this->ftp) : ?>
		<?php echo $this->loadTemplate('ftp'); ?>
	<?php endif; ?>

	<table class="adminform">
		<tr>
			<th>
				<?php echo $this->item->path; ?>
			</th>
		</tr>
		<tr>
			<td>
				<textarea style="width:100%;height:500px;" cols="110" rows="25" name="htmlcontent" class="inputbox"><?php echo $this->item->content; ?></textarea>
			</td>
		</tr>
	</table>

	<div class="clr"></div>

	<input type="hidden" name="template" value="<?php echo $this->item->template; ?>" />
	<input type="hidden" name="type" value="templateHTML" />
	<input type="hidden" name="rsgOption" value="installer" />
	<input type="hidden" name="option" value="com_rsgallery2" />
	<input type="hidden" name="filename" value="<?php echo $this->item->filename; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
