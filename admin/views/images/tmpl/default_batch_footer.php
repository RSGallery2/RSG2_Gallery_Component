<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

?>
<button class="btn" type="button"
		onclick="document.getElementById('batch-category-id').value=''; \
		document.getElementById('batch-access').value=''; \
		document.getElementById('batch-language-id').value=''; \
		document.getElementById('batch-user-id').value=''; \
		document.getElementById('batch-tag-id').value=''"
		data-dismiss="modal">
	<?php echo JText::_('JCANCEL'); ?>
</button>
<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('images.');">
	<?php echo JText::_('COM_RSGALLERY2_ADD_IMAGE_PROPERTIES'); ?>
</button>
<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('images.moveImagesTo');">
	<?php echo JText::_('COM_RSGALLERY2_MOVE_TO'); ?>
</button>
<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('images.copyImagesTo');">
	<?php echo JText::_('COM_RSGALLERY2_COPY'); ?>
</button>
