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

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_rsgallery2/views/uploadFileProperties/css/uploadFileProperties.css');

JHtml::_('bootstrap.tooltip');

//JText::script('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN');

//echo 'Test: "' . json_encode ($this->form);


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
              class="form-validate">

            <legend><?php echo JText::_('COM_RSGALLERY2_PROPERTIES_UPLOADED_FILES'); ?></legend>

	        <?php if (empty($this->fileData->fileUrls)) : ?>

                <div class="alert">
                    <?php echo JText::_('COM_RSGALLERY2_NO_FILES_FOUND_TO_PROCESS'); ?>
                </div>
                 <div class="alert alert-info">
                    <?php echo JText::_('COM_RSGALLERY2_ACTION_ON_MISSING_UPLOADED_FILES'); ?>
                </div>

            <?php else : ?>
                <?php /**
                <ul class="thumbnails">
                    <?php foreach($this->fileData as $file) : ?>
                        <li class="span3">
                            <div class="thumbnail">
                                <div class='rsg-container'>
                                    <img data-src="holder.js/200x180" src="<?php echo $file; ?>" class="img-polaroid rsg-image" alt=""
                                         style="width: 200px; height: 180px; max-width: 90%; ">
                                </div>
                                <div class="caption" >
                                    <small><?php echo basename($file);?></small><br>
                                    Title:
                                    <br>
                                    Gallery:
                                    <select id="category" name="category[]" class="inputbox" size="1" style="max-width: 90%; ">
                                        <option value="-1">Select galleries</option>
                                        <option value="1">USER_X2</option>
                                    </select><br><input name="ptitle[]" size="15" aria-invalid="false" type="text" style="max-width: 90%; ">
                                    Description:
                                    <!--textarea cols="15" rows="2" name="descr[]"></textarea-->
                                    <textarea cols="15" rows="2" name="descr[]" style="max-width: 90%; "></textarea>

                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                /**/
                ?>

                <ul class="thumbnails">

                    <?php
                    // foreach ($this->fileData->fileUrls as $file) :
                    //foreach (array_keys ($this->fileData->fileUrls) as $Idx=>$file) :
                    $Idx=0;
                    foreach ($this->fileData->fileUrls as $file) :
                        $Idx++;
                    ?>
                        <?php $baseName = basename($file) ?>
                        <li class="span3">
                            <div class="thumbnail">
                                <div class='rsg-container'>
                                    <!--img data-src="holder.js/200x180" src="<?php echo $file; ?>" class="img-polaroid rsg-image" alt=""
                                         style="width: 200px; height: 180px; max-width: 90%; "-->
                                    <!--img data-src="holder.js/200x180" src="<?php echo $file; ?>" class="img-rounded" alt=""
                                         style="width: 200px; height: 180px; max-width: 90%; "-->
                                    <img data-src="holder.js/200x180" src="<?php echo $file; ?>" class="img-rounded" alt="">
                                </div>

                                <div class="caption" >
                                    <small><?php echo $baseName;?></small><br>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="titleX[]"><?php echo JText::_('COM_RSGALLERY2_TITLE'); ?></label>
                                    <div class="controls">
                                        <!--input type="text" id="inputEmail" placeholder="Email"-->
                                        <input name="titleX[]" type="text" size="15" aria-invalid="false"
                                               value="<?php echo $baseName . ' (' . $Idx . ')'; ?>"
                                               style="max-width: 90%; ">
                                    </div>
                                </div>

                                <?php if (empty($this->isInOneGallery)) : ?>
                                    <!-- Seperate gallery for each image -->
                                    <div class="control-group" >
                                        <label class="control-label" for="galleryIdX[]"><?php echo JText::_('COM_RSGALLERY2_GALLERY'); ?>(1)</label>
                                        <div class="controls">
                                            <input name="galleryIdX[]" type="text" style="max-width: 90%; "
                                                   value="<?php echo $this->galleryId . ' (' . $Idx . ')';?>"
                                            >
                                        </div>
                                    </div>

                                    <!--?php
                                    // Specify parent gallery selection
                                    echo $this->form->renderFieldset('GallerySelect');
                                    ?-->

                                <?php else : ?>
                                    <!-- One gallery for all. Disable input -->
                                    <div class="control-group">
                                        <label class="control-label" for="galleryIDX[]"><?php echo JText::_('COM_RSGALLERY2_GALLERY'); ?>(2)</label>
                                        <div class="controls">
                                            <input type="text" name="galleryIDX[]" placeholder="Idx:"
                                                   value="<?php echo '0 (' . $Idx . ')';?>"
                                                   style="max-width: 90%; "  disabled>
                                        </div>
                                    </div>

                                    <?php
                                    // Specify parent gallery selection
                                    //echo "yyyy: " . $this->form->renderFieldset('GallerySelectDisabled');
                                    ?>

                                <?php endif; ?>

                                <div class="control-group">
                                    <label class="control-label" for="descrX[]"><?php echo JText::_('COM_RSGALLERY2_DESCRIPTION'); ?></label>
                                    <div class="controls">
                                        <textarea cols="15" rows="" name="descriptionX[]" style="max-width: 90%; "
                                                  placeholder="Text input"><?php echo '(' . $Idx . ')';?></textarea>
                                    </div>
                                </div>

                                <input  type="hidden" name="FileNameX[]" value="?<?php echo $file ;?>?">

                                <?php
                                // Specify parent gallery selection
                                echo $this->form->renderFieldset('GallerySelect');
                                ?>

                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
	        <?php endif; ?>

            <input type="hidden" name="option" value="com_rsgallery2">
            <input type="hidden" name="task" value="">

            <input type="hidden" name="fileSessionId" value="<?php $this->fileSessionId; ?>">
            <input type="hidden" name="isInOneGallery" value="<?php $this->isInOneGallery; ?>">

            <?php echo JHtml::_('form.token'); ?>
        </form>
        <div id="loading"></div>
    </div>
</div>
