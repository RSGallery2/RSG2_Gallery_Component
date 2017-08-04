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

    /**/
    Joomla.submitButtonSingle = function()
    {
        alert('Upload single images: legacy ...');

        // href="index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=upload"

        // yes transfer files ...
        form.task.value = 'upload'; // upload.uploadZipFile
        form.batchmethod.value = 'zip';
        form.ftppath.value = "";
        form.xcat.value = "";
        form.selcat.value = "";

        jQuery('#loading').css('display', 'block');
        form.submit();
    };
    /**/

    Joomla.submitButtonZipPc = function () {
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

    Joomla.submitButtonZipPc2 = function () {
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

    Joomla.submitButtonFolderServer = function () {
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

    Joomla.submitButtonFolderServer2 = function () {
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

<?php
// Drag-drop installation
JFactory::getDocument()->addScriptDeclaration(
<<<JS
    jQuery(document).ready(function($) {

        //if (typeof FormData === 'undefined') {
        {
            $('#legacy-uploader').show();
            $('#uploader-wrapper').hide();
            alert ("exit");
//            return;
        }

		var dragZone  = $('#dragarea');
		var fileInput = $('#install_package');
		var button    = $('#select-file-button');
		var url       = 'index.php?option=com_installer&task=install.ajax_upload';
		var returnUrl = $('#installer-return').val();
		var token     = $('#installer-token').val();
         
        dragZone.on('dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();

            dragZone.addClass('hover');

            return false;
            });

        // Notify user when file is over the drop area
        dragZone.on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();

            dragZone.addClass('hover');

            return false;
        });

        dragZone.on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dragZone.removeClass('hover');

            return false;
        });
    });
JS
);
?>

<?php
JFactory::getDocument()->addStyleDeclaration(
<<<CSS
        #dragarea {
        background-color: #fafbfc;
        border: 1px dashed #999;
        box-sizing: border-box;
        padding: 5% 0;
        transition: all 0.2s ease 0s;
        width: 100%;
        }

        #dragarea p.lead {
        color: #999;
        }

        #upload-icon {
        font-size: 48px;
        width: auto;
        height: auto;
        margin: 0;
        line-height: 175%;
        color: #999;
        transition: all .2s;
        }

        #dragarea.hover {
        border-color: #666;
        background-color: #eee;
        }

        #dragarea.hover #upload-icon,
        #dragarea p.lead {
        color: #666;
        }

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

CSS
);
?>

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

                    <div class="control-group">
                        <div class="controls">
                            <div style=color:#FF0000;font-weight:bold;font-size:smaller;>
                                <?php echo JText::_('COM_RSGALLERY2_UPLOAD_LIMIT_IS') . ' ' . $this->UploadLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                            </div>
                            <div style=color:#FF0000;font-weight:bold;font-size:smaller;>
                                <?php echo JText::_('COM_RSGALLERY2_POST_MAX_SIZE_IS') . ' ' . $this->PostMaxSize . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                            </div>
                            <div style=color:#FF0000;font-weight:bold;font-size:smaller;>
                                <?php echo JText::_('COM_RSGALLERY2_POST_MEMORY_LIMIT_IS') . ' ' . $this->MemoryLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                            </div>
                        </div>
                    </div>


                    <?php
                    // All in one, Specify gallery
                    echo $this->form->renderFieldset('upload_drag_and_drop');
                    ?>

                    <legend><?php echo JText::_('*DRAG_FILE_ZONE '); ?></legend>

                    <div id="uploader-wrapper">
                        <div id="dragarea" class="">
                            <div id="dragarea-content" class="text-center">
                                <p>
                                    <span id="upload-icon" class="icon-upload" aria-hidden="true"></span>
                                </p>
                                <p class="lead">
                                    <?php echo JText::_('*DRAG_IMAGES_HERE'); ?>
                                </p>
                                <p>
                                    <button id="select-file-button" type="button" class="btn btn-success">
                                        <span class="icon-copy" aria-hidden="true"></span>
                                        <?php echo JText::_('*SELECT_FILE'); ?>
                                    </button>
                                </p>
                            </div>

                        </div>
                    </div>

                    <div id="legacy-uploader" style="display: none;">
                        <div class="control-group">
                            <label for="install_package" class="control-label"><?php echo JText::_('PLG_INSTALLER_PACKAGEINSTALLER_EXTENSION_PACKAGE_FILE'); ?></label>
                            <div class="controls">
                                <input class="input_box" id="install_package" name="install_package" type="file" size="57" /><br>
                                <?php echo JText::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', $maxSize); ?>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button class="btn btn-primary" type="button" id="installbutton_package" onclick="Joomla.XsubmitButtonpackage()">
                                <?php echo JText::_('PLG_INSTALLER_PACKAGEINSTALLER_UPLOAD_AND_INSTALL'); ?>
                            </button>
                        </div>
                        <div class="form-actions">
                            <!--a class="btn btn-primary" id="submitButtonSingle"
                               title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?>"
                               href="index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=upload">
                                <?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?>
                            </a-->
                            <label for="submitButtonSingle"><?php echo JText::_('COM_RSGALLERY2_LEGACY_UPLOAD_SINGLE_IMAGES'); ?></label>
                            <button type="button" class="btn btn-primary"  id="submitButtonSingle"
                                    title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?>"
                                    onclick="Joomla.submitButtonSingle()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'); ?></button>
                        </div>
                    </div>

                    <!-- Action button -->
                    <div class="form-actions">
                        <a class="btn btn-primary"
                           title="<?php echo JText::_('COM_RSGALLERY2_ASSIGN_UPLOADED_IMAGES'); ?>"
                           href="index.php?option=com_rsgallery2&amp;view=upload&amp;&amp;layout=UploadSingle">
                            <?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES') . ' Test'; ?>
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
                            <div style=color:#FF0000;font-weight:bold;font-size:smaller;>
                                <?php echo JText::_('COM_RSGALLERY2_POST_MEMORY_LIMIT_IS') . ' ' . $this->MemoryLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
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
                                onclick="Joomla.submitButtonZipPc()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'); ?></button>
                        <button type="button" class="btn btn-primary"
                                onclick="Joomla.submitButtonZipPc2()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'); ?> test</button>
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
                                onclick="Joomla.submitButtonFolderServer()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES'); ?></button>
                        <button type="button" class="btn btn-primary"
                                onclick="Joomla.submitButtonFolderServer2()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES'); ?> test</button>
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


