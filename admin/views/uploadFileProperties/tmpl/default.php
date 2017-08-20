<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

JHtml::_('bootstrap.tooltip');

//JText::script('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN');
?>

<div id="uploadFileProperties" class="clearfix">
    <?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
    <div id="j-main-container">
    <?php endif; ?>

        <form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=UploadFileProperties.'); ?>"
              method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"
              class="form-validate form-horizontal">






            <input type="hidden" value="com_rsgallery2" name="option">
            <input type="hidden" value="" name="task">
            <input type="hidden" value="<?php $this->galleryId ?>" name="gallery_id">

            <?php echo JHtml::_('form.token'); ?>
        </form>
        <div id="loading"></div>
    </div>
</div>
