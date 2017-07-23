<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

// https://techjoomla.com/blog/beyond-joomla/jquery-basics-getting-values-of-form-inputs-using-jquery.html

defined('_JEXEC') or die();

JHtml::_('bootstrap.tooltip');
//JHtml::_('formbehavior.chosen', 'select');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 3));

JText::script('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN');
JText::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST');
JText::script('COM_RSGALLERY2_FTP_UPLOAD_CHOSEN_BUT_NO_FTP_PATH_PROVIDED');

?>

<script type="text/javascript">

    // Add spindle-wheel for installations:
    jQuery(document).ready(function ($) {
        var outerDiv = $('#installer-install');

        $('#loading').css({
            'top': outerDiv.position().top - $(window).scrollTop(),
            'left': outerDiv.position().left - $(window).scrollLeft(),
            'width': outerDiv.width(),
            'height': outerDiv.height(),
            'display': 'none'
        });
    });

    /*
     Joomla.submitbuttonSingle = function()
     {
     alert('Upload single images: use ...');
     };
     */

    Joomla.submitbuttonZipPc = function () {
        var form = document.getElementById('adminForm');

        var zip_path = form.zip_file.value;
        var GalleryId = jQuery('#SelectGalleries_01').chosen().val();
        var bOneGalleryName4All = jQuery('input[name="all_img_in_step1_01"]:checked').val();
//		var OutTxt = ''
//			+ 'GalleryId1: ' + GalleryId + '\r\n'
//			+ 'bOneGalleryName4All: ' + bOneGalleryName4All + '\r\n'
//			+ 'zip_path: ' + zip_path + '\r\n'
//		;
//		alert (OutTxt);

        // No file path given
        if (zip_path == "") {
            alert(Joomla.JText._('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN'));
        }
        else {
            // Is invalid gallerId selected ?
            if (bOneGalleryName4All && (GalleryId < 1)) {
                alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
            }
            else {
                // yes transfer files ...
                form.task.value = 'batchupload'; // upload.uploadZipFile
                form.batchmethod.value = 'zip';
                form.ftppath.value = "";
                form.xcat.value = GalleryId;
                form.selcat.value = bOneGalleryName4All;

                jQuery('#loading').css('display', 'block');
                form.submit();
            }
        }
    };

    Joomla.submitbuttonZipPc2 = function () {
        var form = document.getElementById('adminForm');

        var zip_path = form.zip_file.value;
        var GalleryId = jQuery('#SelectGalleries_01').chosen().val();
        var bOneGalleryName4All = jQuery('input[name="all_img_in_step1_01"]:checked').val();
//		var OutTxt = ''
//			+ 'GalleryId1: ' + GalleryId + '\r\n'
//			+ 'bOneGalleryName4All: ' + bOneGalleryName4All + '\r\n'
//			+ 'zip_path: ' + zip_path + '\r\n'
//		;
//		alert (OutTxt);

        // No file path given
        if (zip_path == "") {
            alert(Joomla.JText._('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN'));
        }
        else {
            // Is invalid gallerId selected ?
            if (bOneGalleryName4All && (GalleryId < 1)) {
                alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
            }
            else {
                // yes transfer files ...
                form.task.value = 'upload.uploadFromZip'; // upload.uploadZipFile
                form.batchmethod.value = 'zip';
                form.ftppath.value = "";
                form.xcat.value = GalleryId;
                form.selcat.value = bOneGalleryName4All;
                form.rsgOption.value = "";

                jQuery('#loading').css('display', 'block');
                form.submit();
            }
        }
    };

    Joomla.submitbuttonFolderServer = function () {
        var form = document.getElementById('adminForm');

        var GalleryId = jQuery('#SelectGalleries_02').chosen().val();
        var ftp_path = form.ftp_path.value;
        var bOneGalleryName4All = jQuery('input[name="all_img_in_step1_02"]:checked').val();

//		var OutTxt = ''
//			+ 'GalleryId2: ' + GalleryId + '\r\n'
//			+ 'bOneGalleryName4All: ' + bOneGalleryName4All + '\r\n'
//			+ 'ftp_path: ' + ftp_path + '\r\n'
//		;
//		alert (OutTxt);

        // ftp path is not given
        if (ftp_path == "") {
            alert(Joomla.JText._('COM_RSGALLERY2_FTP_UPLOAD_CHOSEN_BUT_NO_FTP_PATH_PROVIDED'));
        }
        else {
            // Is invalid gallerId selected ?
            if (bOneGalleryName4All && (GalleryId < 1)) {
                alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
            }
            else {
                // yes transfer files ...
                form.task.value = 'batchupload'; // upload.uploadZipFile
                form.batchmethod.value = 'FTP';
                form.ftppath.value = ftp_path;
                form.xcat.value = GalleryId;
                form.selcat.value = bOneGalleryName4All;

                jQuery('#loading').css('display', 'block');
                form.submit();
            }
        }
    };

    Joomla.submitbuttonFolderServer2 = function () {
        var form = document.getElementById('adminForm');

        var GalleryId = jQuery('#SelectGalleries_02').chosen().val();
        var ftp_path = form.ftp_path.value;
        var bOneGalleryName4All = jQuery('input[name="all_img_in_step1_02"]:checked').val();

//		var OutTxt = ''
//			+ 'GalleryId2: ' + GalleryId + '\r\n'
//			+ 'bOneGalleryName4All: ' + bOneGalleryName4All + '\r\n'
//			+ 'ftp_path: ' + ftp_path + '\r\n'
//		;
//		alert (OutTxt);

        // ftp path is not given
        if (ftp_path == "") {
            alert(Joomla.JText._('COM_RSGALLERY2_FTP_UPLOAD_CHOSEN_BUT_NO_FTP_PATH_PROVIDED'));
        }
        else {
            // Is invalid gallerId selected ?
            if (bOneGalleryName4All && (GalleryId < 1)) {
                alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
            }
            else {
                // yes transfer files ...
                form.task.value = 'upload.uploadFromFtpFolder'; // upload.uploadZipFile
                form.batchmethod.value = 'FTP';
                form.ftppath.value = ftp_path;
                form.xcat.value = GalleryId;
                form.selcat.value = bOneGalleryName4All;
                form.rsgOption.value = "";

                jQuery('#loading').css('display', 'block');
                form.submit();
            }
        }
    };

</script>

<style type="text/css">
    #loading {
        background: rgba(255, 255, 255, .8) url('<?php echo JHtml::_('image', 'jui/ajax-loader.gif', '', null, true, true); ?>') 50% 15% no-repeat;
        position: fixed;
        opacity: 0.8;
        -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);
        filter: alpha(opacity=80);
    }

    /*
        .j-jed-message {
            margin-bottom: 40px;
            line-height: 2em;
            color:#333333;
        }
    */
</style>

<div id="installer-install" class="clearfix">
    <?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
    <div id="j-main-container">
    <?php endif; ?>

        <form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=upload'); ?>"
              method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"
              class="form-validate form-horizontal">

            <?php if (!$this->is1GalleryExisting) : ?>
                <div class="form-actions">
                    <label for="ToGallery"
                           class="control-label"><?php echo JText::_('COM_RSGALLERY2_ONE_GALLERY_MUST_EXIST'); ?></label>
                    <a class="btn btn-primary"
                       name="ToGallery"
                       class="input_box"
                       title="<?php echo JText::_('COM_RSGALLERY2_ONE_GALLERY_MUST_EXIST'); ?>"
                       href="index.php?option=com_rsgallery2&amp;view=galleries">
                        <?php echo JText::_('COM_RSGALLERY2_MENU_GALLERIES'); ?>
                    </a>

                </div>
            <?php else : ?>

                <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => $this->ActiveSelection)); ?>

                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload_single', JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES', true)); ?>
                <fieldset class="uploadform">
                    <legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_MORE'); ?></legend>
                    <div class="form-actions">
                        <a class="btn btn-primary"
                           title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?>"
                           href="index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=upload">
                            <?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?>
                        </a>

                    </div>
                </fieldset>
                <?php echo JHtml::_('bootstrap.endTab'); ?>


                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload_zip_pc', JText::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP', true)); ?>
                <fieldset class="uploadform">
                    <legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP_FROM_LOCAL_PC'); ?></legend>

                    <!-- Zip filename -->
                    <div class="control-group">
                        <label for="zip_file"
                               class="control-label"><?php echo JText::_('COM_RSGALLERY2_ZIP_MINUS_FILE'); ?></label>
                        <div class="controls">
                            <!--input type="text" id="zip_file" name="zip_file" class="span5 input_box" size="70" value="http://" /-->
                            <input type="file" class="input_box  span5" id="zip_file" name="zip_file" size="57"/>
                            <div style=color:#FF0000;font-weight:bold;font-size:smaller;>
                                <?php echo JText::_('COM_RSGALLERY2_UPLOAD_LIMIT_IS') . ' ' . $this->UploadLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                            </div>
                            <div style=color:#FF0000;font-weight:bold;font-size:smaller;>
                                <?php echo JText::_('COM_RSGALLERY2_POST_MAX_SIZE_IS') . ' ' . $this->PostMaxSize . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    // All in one, Specify gallery
                    echo $this->form->renderFieldset('upload_zip');
                    ?>

                    <!-- Action button -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-primary"
                                onclick="Joomla.submitbuttonZipPc()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'); ?></button>
                        <button type="button" class="btn btn-primary"
                                onclick="Joomla.submitbuttonZipPc2()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'); ?> test</button>
                    </div>
                </fieldset>
                <?php echo JHtml::_('bootstrap.endTab'); ?>

                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload_folder_server', JText::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_SERVER', true)); ?>
                <fieldset class="uploadform">
                    <legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_PATH_ON_SERVER'); ?></legend>
                    <div class="control-group">
                        <label for="ftp_path"
                               class="control-label"><?php echo JText::_('COM_RSGALLERY2_FTP_PATH'); ?></label>
                        <div class="controls">
                            <input type="text" id="ftp_path" name="ftp_path" class="span5 input_box" size="70"
                                   value="<?php echo $this->FtpUploadPath; ?>"/>
                            <!-- red size -->
                            <div style="color:#FF0000;font-weight:bold;font-size:smaller;margin-top: 0px;padding-top: 0px;">
                                <?php echo JText::_('COM_RSGALLERY2_PATH_MUST_START_WITH_BASE_PATH'); ?>
                            </div>
                            <div style="color:#000000;font-size:smaller;margin-top: 0px;padding-top: 0px;">
                                <?php echo JText::sprintf('COM_RSGALLERY2_FTP_BASE_PATH', ""); ?><!-- br -->&nbsp;<?php echo JPATH_SITE; ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    // All in one, Specify gallery
                    echo $this->form->renderFieldset('upload_folder');
                    ?>

                    <div class="form-actions">
                        <button type="button" class="btn btn-primary"
                                onclick="Joomla.submitbuttonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES'); ?></button>
                        <button type="button" class="btn btn-primary"
                                onclick="Joomla.submitbuttonFolderServer2()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES'); ?> test</button>
                    </div>
                </fieldset>
                <?php echo JHtml::_('bootstrap.endTab'); ?>

                <?php echo JHtml::_('bootstrap.endTabSet'); ?>

                <input type="hidden" value="com_rsgallery2" name="option">
                <input type="hidden" value="images" name="rsgOption">
                <input type="hidden" value="0" name="boxchecked">

                <input type="hidden" value="1" name="uploaded">
                <input type="hidden" value="" name="task">
                <input type="hidden" value="" name="ftppath">
                <input type="hidden" value="" name="batchmethod">
                <input type="hidden" value="" name="xcat">
                <input type="hidden" value="" name="selcat">

            <?php endif; ?>

            <?php echo JHtml::_('form.token'); ?>
        </form>
        <div id="loading"></div>
    </div>
</div>


