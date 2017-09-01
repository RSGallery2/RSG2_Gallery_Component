<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

// Used joomla update as example and //http://hayageek.com/drag-and-drop-file-upload-jquery/
// upload.php gist: https://gist.github.com/karmicdice/e4d6bde183e13c14091d
// http://us3.php.net/move_uploaded_file
// show image http://jsfiddle.net/revathskumar/kGYc7/
// http://hayageek.com/docs/jquery-upload-file.php

// https://techjoomla.com/blog/beyond-joomla/jquery-basics-getting-values-of-form-inputs-using-jquery.html

defined('_JEXEC') or die();

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_rsgallery2/views/upload/css/upload.css');

//echo 'JURI_SITE: "' . JURI_SITE . '"';

JHtml::_('bootstrap.tooltip');
//JHtml::_('formbehavior.chosen', 'select');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 3));

JText::script('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN');
JText::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST');
JText::script('COM_RSGALLERY2_FTP_UPLOAD_CHOSEN_BUT_NO_FTP_PATH_PROVIDED');

// Drag and Drop installation scripts
$token  = JSession::getFormToken();
$return = JFactory::getApplication()->input->getBase64('return');

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
    Joomla.submitbuttonManualFileSingle = function () {
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
    Joomla.submitAssignDroppedFiles = function () {
//        alert('submitAssignDroppedFiles:  ...');
        var form = document.getElementById('adminForm');

//(        alert('01');
        var bOneGalleryName4All = jQuery('input[name="all_img_in_step1_03"]:checked').val();

//        alert('02 bOneGalleryName4All: ' + bOneGalleryName4All);
//        var GalleryId1 = jQuery('#SelectGalleries_03')
//       alert('02b');
//        var GalleryId1 = jQuery('#SelectGalleries_03').chosen();
//        alert('02c');
        var GalleryId = jQuery('#SelectGalleries_03').chosen().val();

        // Is invalid gallerId selected ?
//        if (bOneGalleryName4All && (GalleryId < 1)) {
//            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
//        }
//        else {
            // yes transfer files ...
//        alert('03');
            form.task.value = 'uploadFileProperties.assignDroppedImages'; // upload.uploadZipFile
//        alert('03a');
            form.batchmethod.value = '';
//        alert('03b');
            form.ftppath.value = "";
            form.xcat.value = GalleryId;
            form.selcat.value = bOneGalleryName4All;

//        alert('04');
            jQuery('#loading').css('display', 'block');
//            alert('submitAssignDroppedFiles:  ...');

            form.submit();
//        }
    };


    Joomla.submitbuttonManualFileZipPc = function () {
        var form = document.getElementById('adminForm');

        var zip_path = form.zip_file.value;
        var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
        var bOneGalleryName4All = jQuery('input[name="all_img_in_step1_03"]:checked').val();
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

    Joomla.submitbuttonManualFileZipPc2 = function () {
        var form = document.getElementById('adminForm');

        var zip_path = form.zip_file.value;
        var GalleryId = jQuery('#SelectGalleries_01').chosen().val();
        var bOneGalleryName4All = jQuery('input[name="all_img_in_step1_03"]:checked').val();
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

    Joomla.submitbuttonManualFileFolderServer = function () {
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

    Joomla.submitbuttonManualFileFolderServer2 = function () {
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

    //--------------------------------------------------------------------------------------
    // drop files
    //--------------------------------------------------------------------------------------
    
    jQuery(document).ready(function ($) {

        // ToDO: Test following with commenting out
        if (typeof FormData === 'undefined') {
            $('#legacy-uploader').show();
            $('#uploader-wrapper').hide();
            alert("exit");
            return;
        }

        var dragZone = $('#dragarea');
        var fileInput = $('#hidden_file_input');
        var buttonManualFile = $('#select_manual_file');
        var urlSingle = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile';
        var returnUrl = $('#installer-return').val();
        var token = $('#installer-token').val();
        var gallery_id = $('#SelectGalleries_03').val();

        buttonManualFile.on('click', function (e) {
//            alert('buttonManualFile.on click: '); // + JSON.stringify($(this)));
            fileInput.click();
        });

        fileInput.on('change', function (e) {
//            Joomla.submitbuttonManualFilepackage();
            e.preventDefault();
            e.stopPropagation();

//            alert('filename: ' + e.target.files[0].name);

            var files = e.target.files;
            alert('files: ' + JSON.stringify(files));
            //if (!files.length) {
            if (!files.length) {
                return;
            }

            alert('handleFileUpload: ' + files[0].name);

            // We need to send dropped files to Server
            handleFileUpload(files, dragZone);
        });

        dragZone.on('dragenter', function (e) {
            e.preventDefault();
            e.stopPropagation();

            dragZone.addClass('hover');

            return false;
        });

        // Notify user when file is over the drop area
        dragZone.on('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();

            dragZone.addClass('hover');

            $(this).css('border', '2px solid #0B85A1');

            return false;
        });

        dragZone.on('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dragZone.removeClass('hover');

            return false;
        });

        dragZone.on('drop', function (e) {
            $(this).css('border', '2px dotted #0B85A1');
            e.preventDefault();
            e.stopPropagation();

            var files = e.originalEvent.target.files || e.originalEvent.dataTransfer.files;
            if (!files.length) {
                return;
            }

            // alert('handleFileUpload: ' + files[0].name);

            // We need to send dropped files to Server
            handleFileUpload(files, dragZone);
        });


        $(document).on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
        });
        $(document).on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            //obj.css('border', '2px dotted #0B85A1');
        });
        $(document).on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();
        });

        var rowCount = 0;

        function createStatusbar(obj) {
            rowCount++;
            var row = "odd";
            if (rowCount % 2 == 0) {
                row = "even";
            }
            this.statusbar = $("<div class='statusbar " + row + "'></div>");
            this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
            this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
            this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
            this.abort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
            obj.after(this.statusbar);

            this.setFileNameSize = function (name, size) {
                var sizeStr = "";
                var sizeKB = size / 1024;
                if (parseInt(sizeKB) > 1024) {
                    var sizeMB = sizeKB / 1024;
                    sizeStr = sizeMB.toFixed(2) + " MB";
                }
                else {
                    sizeStr = sizeKB.toFixed(2) + " KB";
                }

                this.filename.html(name);
                this.size.html(sizeStr);
            }
            this.setProgress = function (progress) {
                var progressBarWidth = progress * this.progressBar.width() / 100;
                this.progressBar.find('div').animate({width: progressBarWidth}, 10).html(progress + "%");
                if (parseInt(progress) >= 100) {
                    this.abort.hide();
                }
            }
            this.setAbort = function (jqxhr) {
                var sb = this.statusbar;
                this.abort.click(function () {
                    jqxhr.abort();
                    sb.hide();
                });
            }
        }

        function handleFileUpload(files, obj) {

            // ToDo: On first file upload disable gallery change and isone .. change

            // All files selected by user
            for (var i = 0; i < files.length; i++) {
                var data = new FormData();
                data.append('upload_file', files[i]);
                data.append('upload_type', 'single');
                data.append('session_id', token);
                data.append('gallery_id', gallery_id);

                var status = new createStatusbar(obj); //Using this we can set progress.
                status.setFileNameSize(files[i].name, files[i].size);

                sendFileToServer(data, status);
            }
        }

        function sendFileToServer(formData, status) {
            /**
            var uploadURL = "http://tomfinnern.de/examples/jquery/drag-drop-file-upload/upload.php"; //Upload URL
            var extraData = {}; //Extra Data.
            var jqXHR = $.ajax({
                xhr: function () {
                    var xhrobj = $.ajaxSettings.xhr();
                    if (xhrobj.upload) {
                        xhrobj.upload.addEventListener('progress', function (event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            //Set progress
                            status.setProgress(percent);
                        }, false);
                    }
                    return xhrobj;
                },
                url: uploadURL,
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                success: function (data) {
                    status.setProgress(100);

                    //$("#status1").append("File upload Done<br>");
                }
            });

            status.setAbort(jqXHR);
            /**/

            /*=========================================================

             */

            //JoomlaInstaller.showLoading();

            var jqXHR = $.ajax({
                xhr: function () {
                    var xhrobj = $.ajaxSettings.xhr();
                    if (xhrobj.upload) {
                        xhrobj.upload.addEventListener('progress', function (event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            //Set progress
                            status.setProgress(percent);
                        }, false);
                    }
                    return xhrobj;
                },
                url: urlSingle,
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                success: function (data) {
                    status.setProgress(100);

                    //$("#status1").append("File upload Done<br>");
                }
            });

            status.setAbort(jqXHR);


            /*=========================================================
            
            */

        }


    });
</script>

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

						<?php
						// All in one, Specify gallery
						echo $this->form->renderFieldset('upload_drag_and_drop');
						?>

                        <legend><?php echo JText::_('COM_RSGALLERY2_DRAG_FILE_ZONE'); ?></legend>

                        <div id="uploader-wrapper">
                            <div id="dragarea" class="span6">
                                <div id="dragarea-content" class="text-center">
                                    <p>
                                        <span id="upload-icon" class="icon-upload" aria-hidden="true"></span>
                                    </p>
                                    <p class="lead">
										<?php echo JText::_('COM_RSGALLERY2_DRAG_IMAGES_HERE'); ?>
                                    </p>
                                    <p>
                                        <buttonManualFile id="select_manual_file" type="buttonManualFile"
                                                          class="btn btn-success"
                                                          title="<?php echo JText::_('COM_RSGALLERY2_SELECT_FILES_ZIP_DESC'); ?>"
                                        >
                                            <span class="icon-copy" aria-hidden="true"></span>
											<?php echo JText::_('COM_RSGALLERY2_SELECT_FILES'); ?>
                                        </buttonManualFile>
                                    </p>
                                </div>
                                <br><br>
                                <div id="status1" class="span6">

                                </div>
                            </div>

                            <!--Action buttonManualFile-->
                            <div class="form-actions">
                                <buttonManualFile class="btn btn-primary" type="buttonManualFile"
                                                  id="AssignUploadedFiles" onclick="Joomla.submitAssignDroppedFiles()"
                                                  title="<?php echo JText::_('COM_RSGALLERY2_ASSIGN_DROPPED_IMAGES_DESC'); ?>">
									<?php echo JText::_('COM_RSGALLERY2_ASSIGN_DROPPED_IMAGES'); ?>
                                </buttonManualFile>
                            </div>
                        </div>

                        <div id="legacy-uploader" style="display: none;">
                            <div class="control-group">
                                <label for="hidden_file_input"
                                       class="control-label"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FILE'); ?></label>
                                <div class="controls">
                                    <input class="input_box" id="hidden_file_input" name="hidden_file_input" type="file"
                                           size="57"  multiple /><br>
									<?php echo JText::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', $this->MaxSize); ?>
                                </div>
                                <div class="controls">
                                    <buttonManualFile class="btn btn-primary" type="buttonManualFile"
                                                      id="TransferImageFile" onclick="Joomla.XsubmitTransferImageFile()"
                                                      title="<?php echo JText::_('COM_RSGALLERY2_TRANSFER_FILE_DESC'); ?>">
										<?php echo JText::_('COM_RSGALLERY2_TRANSFER_FILE'); ?>
                                    </buttonManualFile>
                                </div>
                            </div>

                            <div class="form-actions">
                                <buttonManualFile class="btn btn-primary" type="buttonManualFile"
                                                  id="AssignUploadedFiles" onclick="Joomla.XsubmitAssignUploadedFiles()"
                                                  title="<?php echo JText::_('COM_RSGALLERY2_ASSIGN_UPLOADED_FILES_DESC'); ?>">
									<?php echo JText::_('COM_RSGALLERY2_ASSIGN_UPLOADED_FILES'); ?>
                                </buttonManualFile>
                            </div>

                            <input id="installer-return" name="return" type="hidden" value="<?php echo $return; ?>"/>
                            <input id="installer-token" name="return" type="hidden" value="<?php echo $token; ?>"/>
                        </div>

                        <div class="form-actions">
                            <a class="btn btn-primary" id="submitbuttonManualFileSingle"
                               title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_DESC'); ?>"
                               href="index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=upload">
								<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_LEGACY'); ?>
                            </a>
                            <!--label for="submitbuttonManualFileSingle"><?php echo JText::_('COM_RSGALLERY2_LEGACY_UPLOAD_SINGLE_IMAGES'); ?></label>
                            <buttonManualFile type="buttonManualFile" class="btn btn-primary"  id="submitbuttonManualFileSingle"
                                title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_DESC'); ?>"
                                onclick="Joomla.submitbuttonManualFileSingle()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_LEGACY'); ?>
                            </buttonManualFile-->
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <small class "help-block" style=color:#FF0000;>
									<?php echo JText::_('COM_RSGALLERY2_UPLOAD_LIMIT_IS') . ' ' . $this->UploadLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                                </small>
                                <div class "help-block">
									<?php echo JText::_('COM_RSGALLERY2_POST_MAX_SIZE_IS') . ' ' . $this->PostMaxSize . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                                </small>
                                <small style=font-size:smaller;>
									<br><?php echo JText::_('COM_RSGALLERY2_POST_MEMORY_LIMIT_IS') . ' ' . $this->MemoryLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                                </small>
                            </div>
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

                        <!-- Action buttonManualFile -->
                        <div class="form-actions">
                            <buttonManualFile type="buttonManualFile" class="btn btn-primary"
                                              onclick="Joomla.submitbuttonManualFileZipPc()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'); ?></buttonManualFile>
                            <buttonManualFile type="buttonManualFile" class="btn btn-primary"
                                              onclick="Joomla.submitbuttonManualFileZipPc2()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'); ?>
                                test
                            </buttonManualFile>
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
                                       value="<?php echo $this->FtpUploadPath; ?>" />
                                <div class="help-bock">
                                    <small style="color:#FF0000;font-weight:bold;font-size:smaller;">
                                        <?php echo JText::_('COM_RSGALLERY2_PATH_MUST_START_WITH_BASE_PATH'); ?>
                                    </small>
                                </div>
                                <div class="help-bock">
                                    <small>
                                        <?php echo JText::sprintf('COM_RSGALLERY2_FTP_BASE_PATH', ""); ?>&nbsp;<?php echo JPATH_SITE; ?>
                                    </small>
                                </div>
                            </div>
                        </div>

						<?php
						// All in one, Specify gallery
						echo $this->form->renderFieldset('upload_folder');
						?>

                        <div class="form-actions">
                            <buttonManualFile type="buttonManualFile" class="btn btn-primary"
                                              onclick="Joomla.submitbuttonManualFileFolderServer()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES'); ?>
                            </buttonManualFile>
                            <buttonManualFile type="buttonManualFile" class="btn btn-primary"
                                              onclick="Joomla.submitbuttonManualFileFolderServer2()"><?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES'); ?>
                                test
                            </buttonManualFile>
                        </div>
                    </fieldset>
					<?php echo JHtml::_('bootstrap.endTab'); ?>

					<?php echo JHtml::_('bootstrap.endTabSet'); ?>

                    <input type="hidden" value="com_rsgallery2" name="option">
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


