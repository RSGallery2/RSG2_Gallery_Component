// Used joomla update as example and //http://hayageek.com/drag-and-drop-file-upload-jquery/
// upload.php gist: https://gist.github.com/karmicdice/e4d6bde183e13c14091d
// http://us3.php.net/move_uploaded_file
// show image http://jsfiddle.net/revathskumar/kGYc7/
// http://talkerscode.com/webtricks/drag-and-drop-image-upload-using-ajax-jquery-and-php.php
// + https://joomla.stackexchange.com/questions/146/what-is-the-proper-way-to-make-an-ajax-call-in-component
// https://techjoomla.com/blog/beyond-joomla/jquery-basics-getting-values-of-form-inputs-using-jquery.html


//--------------------------------------------------------------------------------------
// "old" submit buttons
//--------------------------------------------------------------------------------------

/* Deprecated old single image upload */
Joomla.submitButtonManualFileSingle = function () {

    // alert('Upload single images: legacy ...');

    // href="index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=upload"

    // yes transfer files ...
    form.task.value = 'upload'; // upload.uploadZipFile
    form.task.value = 'upload'; // upload.uploadZipFile
    form.batchmethod.value = 'zip';
    form.ftppath.value = "";
    form.xcat.value = "";
    form.selcat.value = "";

    jQuery('#loading').css('display', 'block');
    form.submit();
};

/** obsolete assign dropped files **/
Joomla.submitAssignDroppedFiles = function () {
//        alert('submitAssignDroppedFiles:  ...');
    var form = document.getElementById('adminForm');

    // ToDo: check if one image exists

    form.task.value = 'imagesProperties.PropertiesView'; // upload.uploadZipFile
    form.submit();
};
/**/

/* Zip file */
Joomla.submitButtonManualFileZipPcLegacy = function () {

    //alert('Upload Manual File Zip from Pc: legacy ...');

    var form = document.getElementById('adminForm');

    var zip_path = form.zip_file.value;
    //var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
    var gallery_id = jQuery('#SelectGalleries_01').val();

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

        // Is invalid galleryId selected ?
        if (bOneGalleryName4All && (gallery_id < 1)) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(1)');
        }
        else {

            // yes transfer files ...
            form.task.value = 'batchupload'; // upload.uploadZipFile
            form.rsgOption.value = "images";
            form.batchmethod.value = 'zip';
            form.ftppath.value = "";
            form.xcat.value = gallery_id;
            form.selcat.value = bOneGalleryName4All;

            jQuery('#loading').css('display', 'block');

            form.submit();
        }
    }
};

/* Test Zip file: Not used ? */
Joomla.submitButtonManualFileZipPc = function () {

    // alert('Upload Manual File Zip from Pc: controller upload.uploadFromZip ...');

    var form = document.getElementById('adminForm');

    var zip_path = form.zip_file.value;
    //var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
    var gallery_id = jQuery('#SelectGalleries_01').val();

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
        // Is invalid galleryId selected ?
        if (bOneGalleryName4All && (gallery_id < 1)) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(2)');
        }
        else {

            // yes transfer files ...
            form.task.value = 'upload.uploadFromZip'; // upload.uploadZipFile
            form.batchmethod.value = 'zip';
            form.ftppath.value = "";
            form.xcat.value = gallery_id;
            form.selcat.value = bOneGalleryName4All;
            form.rsgOption.value = "";


            jQuery('#loading').css('display', 'block');

            form.submit();

        }
    }
};
/**/

/* from server */
Joomla.submitButtonManualFileFolderServerLegacy = function () {

    // alert('Upload Folder server: legacy ...');

    var form = document.getElementById('adminForm');

    //var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
    var gallery_id = jQuery('#SelectGalleries_02').val();
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
        // Is invalid galleryId selected ?
        if (bOneGalleryName4All && (gallery_id < 1)) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(3)');
        }
        else {
            // yes transfer files ...
            form.task.value = 'batchupload'; // upload.uploadZipFile
            form.rsgOption.value = "images";
            form.batchmethod.value = 'FTP';
            form.ftppath.value = ftp_path;
            form.xcat.value = gallery_id;
            form.selcat.value = bOneGalleryName4All;

            jQuery('#loading').css('display', 'block');
            form.submit();
        }
    }
};

/* from server */
Joomla.submitButtonManualFileFolderServer = function () {

    // alert('Upload Folder server: upload.uploadFromFtpFolder ...');

    var form = document.getElementById('adminForm');

    //var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
    var gallery_id = jQuery('#SelectGalleries_02').val();
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
        // Is invalid galleryId selected ?
        if (bOneGalleryName4All && (gallery_id < 1)) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(4)');
        }
        else {

            // yes transfer files ...
            form.task.value = 'upload.uploadFromFtpFolder'; // upload.uploadZipFile
            form.batchmethod.value = 'FTP';
            form.ftppath.value = ftp_path;
            form.xcat.value = gallery_id;
            form.selcat.value = bOneGalleryName4All;
            form.rsgOption.value = "";

            //jQuery('#loading').css('display', 'block');

            form.submit();
        }
    }
};
/**/

//--------------------------------------------------------------------------------------
// drop files
//--------------------------------------------------------------------------------------

jQuery(document).ready(function ($) {

    // ToDo: Test following with commenting out
    if (typeof FormData === 'undefined') {
        $('#legacy-uploader').show();
        $('#uploader-wrapper').hide();
        alert("exit");
        return;
    }

    var dragZone = $('#dragarea');
    var fileInput = $('#hidden_file_input');
    var buttonManualFile = $('#select_manual_file');
    var urlFileUploAd = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile&<?PHP echo JSession::getFormToken()?>=1';
    //alert ('urlFileUploAd: ' + urlFileUploAd);
    var urlReserveDbImageId = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxReserveDbImageId&<?PHP echo JSession::getFormToken()?>=1';
    //alert ('urlReserveDbImageId: ' + urlReserveDbImageId);
    var returnUrl = $('#installer-return').val();
    var token = $('#installer-token').val();
    var gallery_id = $('#SelectGalleries_03').val();

    var dropQueue = []; // File list to be uploaded
    var dropList = [];

    /*----------------------------------------------------
    Red or green border for drag and drop images
    ----------------------------------------------------*/

    if (!$('#SelectGalleries_03').val()) {
        $('#dragarea').addClass('dragareaDisabled')
    }
    /**/

    $('#SelectGalleries_03').change(function () {
        // drop disabled ?
        if ($(this).val() == 0) {
            // $('#dragarea').css('border', '4px dotted red');
            $('#dragarea').addClass('dragareaDisabled')
        }
        else {
            //$('#dragarea').css('border', '4px dotted darkgreen');
            $('#dragarea').removeClass('dragareaDisabled')
        }
    });

    buttonManualFile.on('click', function (e) {
        //alert('buttonManualFile.on click: '); // + JSON.stringify($(this)));
        fileInput.click();
    });

    fileInput.on('change', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var gallery_id = $('#SelectGalleries_03').val();

        // prevent empty gallery
        if (gallery_id < 1) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(5)');
        }
        else {

            var files = e.target.files;
            // files exist ?
            if (!files.length) {
                return;
            }

            var progressArea = $('#uploadProgressArea');
            prepareFileUpload(files, progressArea);
        }
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

        return false;
    });

    dragZone.on('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();

        dragZone.removeClass('hover');

        return false;
    });

    dragZone.on('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var gallery_id = $('#SelectGalleries_03').val();

        // prevent empty gallery
        if (gallery_id < 1) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(6)');
        }
        else {
            var files = e.originalEvent.target.files || e.originalEvent.dataTransfer.files;
            if (!files.length) {
                return;
            }

            dragZone.removeClass('hover');

            // We need to send dropped files to Server
            var progressArea = $('#uploadProgressArea');
            prepareFileUpload(files, progressArea);
        } // empty gallery
    });

    //--- no other drop on the form ---------------------

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

    function wait(ms) {
        var d = new Date();
        var d2 = null;
        do {
            d2 = new Date();
        }
        while (d2 - d < ms);
    }

    // Uploading image count
    var imgCount = 0;

    // Handle status bar for one actual uploading image
    function createStatusBar(obj) {
        imgCount++;
        var row = "odd";
        if (imgCount % 2 == 0) {
            row = "even";
        }

        // Use this:
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
        };
        this.setProgress = function (progress) {

            var progressBarWidth = progress * this.progressBar.width() / 100;
            this.progressBar.find('div').animate({width: progressBarWidth}, 10).html(progress + "%");
            if (parseInt(progress) >= 99.999) {

                this.abort.hide();

                //// Here it is more immediate and parallel -> order wrong
                //// start next upload
                //sendState = 0; // 1 == busy
                //setTimeout(startReserveDbImageId, 2000);
            }
        };
        this.setAbort = function (jqxhr) {
            var sb = this.statusbar;
            this.abort.click(function () {
                jqxhr.abort();
                sb.hide();
            });
        }
    }

    // image names , container
    function prepareFileUpload(files, obj) {

        // ToDo: On first file upload disable gallery change and isone .. change

        var gallery_id = $('#SelectGalleries_03').val();

        // All files selected by user
        for (var idx = 0; idx < files.length; idx++) {

            console.log('in: ' + files[idx].name);

            // Save for later send

            dropList.push(files[idx]);
            dropListIdx = dropList.length -1;

            // for function reserveDbImageId
            var data = new FormData();
            // data.append('upload_file', files[idx]);
            data.append('upload_file', files[idx].name);
            data.append('dropListIdx', dropListIdx);

            data.append(token, "1");
            data.append('gallery_id', gallery_id);
            //data.append('idx', idx);

            // Set progress bar
            var statusBar = new createStatusBar(obj);
            statusBar.setFileNameSize(files[idx].name, files[idx].size);

            var queueObj = {};
            queueObj.data = data;
            queueObj.statusBar = statusBar;

            dropQueue.push(queueObj);

            // console.log('test: ' + data);
        }

        startReserveDbImageId();
    }

    var sendState = 0; // 1 busy
    var sendTimeout = 3000; // sec: continue sending next on no answer or error -> alarm ?

    function startReserveDbImageId() {

        // Not busy
        if (sendState == 0) {
            if (dropQueue.length > 0) {
                var queueObj = dropQueue.shift();
                var data = queueObj.data;
                var statusBar = queueObj.statusBar;

                sendState = 1; // 1 busy

                reserveDbImageId(data, statusBar);
            }
        }
        else {
            alert("0X.startReserveDbImageId. !!! Busy !!!");
        }
    }

    function sendFileToServer(formData, statusBar) {

        var fileName = formData.get('upload_file').name;
        console.log('sendFile: ' + fileName);

        /*=========================================================
           ajax file uploader
        =========================================================*/

        var jqXHR = jQuery.ajax({
            xhr: function () {
                var xhrobj = jQuery.ajaxSettings.xhr();
                if (xhrobj.upload) {
                    xhrobj.upload.addEventListener('progress', function (event) {
                        var percent = 0;
                        // if (event.lengthComputable) {
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        //Set progress
                        statusBar.setProgress(percent);
                    }, false);
                }
                return xhrobj;
            },
            url: urlFileUploAd,
            type: "POST",
            contentType: false,
            processData: false,
            cache: false,
            // timeout:20000, // 20 seconds timeout (was too short)
            data: formData
        })

        //--- On success / done --------------------------------
        .done(function (eData, textStatus, jqXHR) {
            //alert('done Success: "' + String(eData) + '"')

            console.log('sendFile: Success');

            var jData;

            //--- Handle PHP Error and notification messages first (separate) -------------------------

            // first part php error- or debug- echo string ?
            // find start of json
            var StartIdx = eData.indexOf('{"');
            if (StartIdx == 0) {
                jData = jQuery.parseJSON(eData);
            }
            else {
                // find error html text
                var errorText = eData.substring(0, StartIdx - 1);
                // append to be viewed
                var progressArea = $('#uploadProgressArea');
                progressArea.append(errorText);

                // extract json data of uploaded image
                jsonText = eData.substring(StartIdx);
                jData = jQuery.parseJSON(jsonText);
            }

            // Check that JResponseJson data structure may be available
            //if (!defined (json.success))
            if (!'success' in jData) {
                alert('Drag and drop returned wrong data');
                return;
            }

            // ToDo: Handle JOOMLA Error and notification messages -> inside Json

            // file successful transferred
            if (jData.success == true) {
                //alert('Success 05');
                this.imageBox = $("<li></li>").appendTo($('#imagesAreaList'));
                this.thumbArea = $("<div class='thumbnail imgProperty'></div>").appendTo(this.imageBox);
                this.imgComntainer = $("<div class='imgContainer' ></div>").appendTo(this.thumbArea);
                this.imageDisplay = $("<img class='img-rounded' data-src='holder.js/600x400' src='" + jData.data.dstFile + "' alt='' />").appendTo(this.imgComntainer);

                this.caption = $("<div class='caption' ></div>").appendTo(this.imageBox);
                this.imageDisplay = $("<small>" + jData.data.file + "</small>").appendTo(this.caption);
                this.imageId = $("<small> (" + jData.data.cid + ":" + jData.data.order + ")</small>").appendTo(this.imageDisplay);
                this.xxy = $("<input name='cid[]' class='imageCid' type='hidden' value='" + jData.data.cid + "' />").appendTo(this.imageBox);

                // ToDo: Notification may be ... anyhow
            }
            else {
                alert('Result Error 05');
                // error on file transfer
                var msg = jData.message;
                alert('Error on file transfer (1): "' + msg + '"');
                alert("eData: " + eData);

                // ToDo: Use count ....
                msg = jData.messages.error[0];
                alert("Error on file transfer (2): " + msg);
            }

        })

        //--- On fail / error  --------------------------------
        .fail(function (jqXHR, textStatus, exceptionType) {

            console.log('sendFile: fail');

            //// start next upload
            //sendState = 0; // 1 == busy
            //startReserveDbImageId () ;

            // alert ('fail: Status: "' + textStatus + '" exceptionType: "' + exceptionType + '" [' + jqXHR.status + ']');
            alert('Drag and drop upload failed: "' + textStatus + '" -> "' + exceptionType + '" [' + jqXHR.status + ']');

            console.log(jqXHR);
        })

        //--- On always / complete --------------------------------
        // the .always() method replaces the deprecated .complete() method.
        .always(function (eData, textStatus, jqXHR) {
            //alert ('always: "' + textStatus + '"');

        });

        // create abort HTML
        statusBar.setAbort(jqXHR);
    }

    function reserveDbImageId (formData, statusBar) {

        var fileName = formData.get('upload_file');
        console.log('reserve: ' + fileName);

        /*=========================================================
         Reserve database image entry for the order of the dropped
        =========================================================*/

        var jqXHR = jQuery.ajax({
            //xhr: function () {
            //    var xhrobj = jQuery.ajaxSettings.xhr();
            //}
            url: urlReserveDbImageId,
            type: "POST",
            contentType: false,
            processData: false,
            cache: false,
            // timeout:20000, // 20 seconds timeout (was too short)
            data: formData
        })

        //--- On success / done --------------------------------
        .done(function (eData, textStatus, jqXHR) {
            //alert('done reserveDbImageId.Success: "' + String(eData) + '"')

            console.log('reserve: Success');

            // start next reserveDbImageId
            sendState = 0; // 1 == busy
            startReserveDbImageId();

            // ToDo:
            //--- Handle PHP Error and notification messages first (separate) -------------------------

            // first part php error- or debug- echo string ?
            // find start of json
            var StartIdx = eData.indexOf('{"');
            if (StartIdx == 0) {
                jData = jQuery.parseJSON(eData);
            }
            else {
                // find error html text
                var errorText = eData.substring(0, StartIdx - 1);
                // append to be viewed
                var progressArea = $('#uploadProgressArea');
                progressArea.append(errorText);

                // extract json data of uploaded image
                jsonText = eData.substring(StartIdx);
                jData = jQuery.parseJSON(jsonText);
            }

            // Check that JResponseJson data structure may be available
            //if (!defined (json.success))
            if (!'success' in jData) {
                alert('reserveDbImageId: returned wrong data');
                return;
            }

            // ToDo: Handle JOOMLA Error and notification messages -> inside Json

            // file successful transferred
            if (jData.success == true) {

                var gallery_id = $('#SelectGalleries_03').val();

                // for function reserveDbImageId
                var data = new FormData();

                dropListIdx = jData.data.dropListIdx;
                if (dropListIdx < 0 || dropList.length < dropListIdx)
                {
                    alert('reserveDbImageId: dropListIdx: ' + dropListIdx + ' out of range (' + dropList.length + ')');

                    return;
                }
                var UploadFile = dropList [dropListIdx];

                data.append('upload_file', UploadFile);
                data.append(token, "1");
                data.append('gallery_id', gallery_id);
                data.append('cid', jData.data.cid);

                sendFileToServer(data, statusBar)
            }
            else {
                alert('Result Error 05');
                // error on file transfer
                var msg = jData.message;
                alert('Error on reserve DB image ID (1): "' + msg + '"');
                alert("eData: " + eData);

                // ToDo: Use count ....
                msg = jData.messages.error[0];
                alert("Error on reserveDbImageId (2): " + msg);
            }

        })

        //--- On fail / error  --------------------------------
        .fail(function (jqXHR, textStatus, exceptionType) {

            console.log('reserve: fail');


            //// start next upload
            //sendState = 0; // 1 == busy
            //startReserveDbImageId () ;

            // alert ('fail: Status: "' + textStatus + '" exceptionType: "' + exceptionType + '" [' + jqXHR.status + ']');
            alert('reserveDbImageId: Drag and drop upload failed: "' + textStatus + '" -> "' + exceptionType + '" [' + jqXHR.status + ']');

            console.log(jqXHR);
        })

        //--- On always / complete --------------------------------
        // the .always() method replaces the deprecated .complete() method.
        .always(function (eData, textStatus, jqXHR) {
            //alert ('always: "' + textStatus + '"');

        });





    }
}) // Joomla ready ... ?


