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
    //var urlSingle = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile&format=json';
    //var urlSingle = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile&format=raw';
    //var urlSingle = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile';
    //var urlSingle = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile&<?PHP echo JSession::getFormToken()?>=1&format=raw';
    var urlSingle = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile&<?PHP echo JSession::getFormToken()?>=1';
    //alert ('urlSingle: ' + urlSingle);
    var returnUrl = $('#installer-return').val();
    var token = $('#installer-token').val();
    var gallery_id = $('#SelectGalleries_03').val();

    var dropQueue = []; // File list to be uploaded

    /*----------------------------------------------------
    Red or green border for drag and drop images
    ----------------------------------------------------*/

    if ( ! $('#SelectGalleries_03').val()) {
        $('#dragarea').addClass ('dragareaDisabled')
    }
    /**/

    $('#SelectGalleries_03').change(function() {
        // drop disabled ?
        if ($(this).val() == 0)
        {
            // $('#dragarea').css('border', '4px dotted red');
            $('#dragarea').addClass ('dragareaDisabled')
        }
        else
        {
            //$('#dragarea').css('border', '4px dotted darkgreen');
            $('#dragarea').removeClass ('dragareaDisabled')
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

            var progressArea =  $('#uploadProgressArea');
            handleFileUpload(files, progressArea);
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
            var progressArea =  $('#uploadProgressArea');
            handleFileUpload(files, progressArea);
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

    function wait(ms)
    {
        var d = new Date();
        var d2 = null;
        do { d2 = new Date(); }
        while(d2-d < ms);
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
        this.statusbar   = $("<div class='statusbar " + row + "'></div>");
        this.filename    = $("<div class='filename'></div>").appendTo(this.statusbar);
        this.size        = $("<div class='filesize'></div>").appendTo(this.statusbar);
        this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
        this.abort       = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);

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
            console.log('progress: ' + this.progressBar.id + ':' + progress + '%');

            var progressBarWidth = progress * this.progressBar.width() / 100;
            this.progressBar.find('div').animate({width: progressBarWidth}, 10).html(progress + "%");
            if (parseInt(progress) >= 99.999) {

                this.abort.hide();

                // Here it is more immediate and parallel -> order wrong
                //// start next upload
                //sendState = 0; // 1 == busy
                //startSendFileToServer () ;
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
    function handleFileUpload(files, obj) {

        // ToDo: On first file upload disable gallery change and isone .. change

        var gallery_id = $('#SelectGalleries_03').val();

        // All files selected by user
        for (var idx = 0; idx < files.length; idx++) {

            console.log('in: ' + files[idx].name);

            var data = new FormData();
            data.append('upload_file', files[idx]);
            data.append('upload_type', 'single');
            data.append('token', token);
            data.append('gallery_id', gallery_id);
            data.append('idx', idx);

            // Set progress bar
            var status = new createStatusBar(obj);
            status.setFileNameSize(files[idx].name, files[idx].size);

            var queueObj = {};
            queueObj.data = data;
            queueObj.status = status ;

            dropQueue.push (queueObj);

            // console.log('test: ' + data);
            console.log(data);
        }

        startSendFileToServer ();
    }

    var sendState = 0; // 1 busy
    var sendTimeout = 10; // sec: continue sending next on no answer or error -> alarm ?

    function startSendFileToServer () {

        // Not busy
        if (sendState == 0)
        {
            if (dropQueue.length > 0) {
                var queueObj = dropQueue.shift();
                var data = queueObj.data;
                var status = queueObj.status;

                sendState = 1; // 1 busy

                sendFileToServer(data, status);
            }
        }
        else
        {
            alert ("0X.startSendFileToServer. !!! Busy !!!");
        }

    }

//        https://tutorialzine.com/2013/05/mini-ajax-file-upload-form

// Uploading files using HTML5 is actually a combination of three technologies -
// the new File Reader API, the also new Drag & Drop API,
// and the good ol' AJAX (with the addition of binary data transfer).
// Here is a description of a HTML5 file upload process:
// https://tutorialzine.com/2011/09/html5-file-upload-jquery-php
// !!! http://makitweb.com/drag-and-drop-file-upload-with-jquery-and-ajax/ thumb
//
// return PHP results as array -> echo json_encode($return_arr);
//        $return_arr = array("name" => $filename,"size" => $filesize, "src"=> $src);
//
//
//
// ...

    /**
     // Added thumbnail
     function addThumbnail(data){
		$("#uploadfile h1").remove();
		var len = $("#uploadfile div.thumbnail").length;

		var num = Number(len);
		num = num + 1;

		var name = data.name;
		var size = convertSize(data.size);
		var src = data.src;

		// Creating an thumbnail
		$("#uploadfile").append('<div id="thumbnail_'+num+'" class="thumbnail"></div>');
		$("#thumbnail_"+num).append('<img src="'+src+'" width="100%" height="78%">');
		$("#thumbnail_"+num).append('<span class="size">'+size+'</span>');
	}
     /**/

// https://stackoverflow.com/questions/6792878/jquery-ajax-error-function
    /* Deprecation Notice:
        The jqXHR.success(), jqXHR.error(), and jqXHR.complete()
        callbacks are deprecated as of jQuery 1.8. To prepare
        your code for their eventual removal,
        use jqXHR.done(), jqXHR.fail(), and jqXHR.always() instead.
    /**/

    function sendFileToServer(formData, status) {
//            console.log(formData);
//            console.log(status);

//            alert ('formData: "' + JSON.stringify(formData) + '"\r\n');
        // formData.get("email");
        var fileName = formData.get('upload_file').name;
        console.log('out: ' + fileName);

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
                        // if (event.lengthComputable) {
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
            data: formData


        })
        //--- On success / done --------------------------------
        .done(function(eData, textStatus, jqXHR) {
            //alert('done Success: "' + String(eData) + '"')

            // start next upload
            sendState = 0; // 1 == busy
            startSendFileToServer () ;

            var jData;

            // Handle PHP Error and notification messages first (separate)

            // first part dummy echo string ?
            // find start of json
            var StartIdx = eData.indexOf('{"');
            if (StartIdx == 0) {
                jData = jQuery.parseJSON(eData);
            }
            else
            {
                // find error html text
                var errorText = eData.substring (0, StartIdx -1);
                // append to be viewed
                var progressArea =  $('#uploadProgressArea');
                progressArea.append(errorText);

                // extract json data of uploaded image
                jsonText = eData.substring (StartIdx);
                jData = jQuery.parseJSON(jsonText);
            }

            // Check that JResponseJson data structure may be available
            //if (!defined (json.success))
            if ( ! 'success' in jData)
            {
                alert ('Drag and drop returned wrong data');
                return;
            }

            // ToDo: Handle JOOMLA Error and notification messages -> inside Json

            // file successful transferred
            if (jData.success == true)
            {
                //alert('Success 05');
                this.imageBox = $("<li></li>").appendTo($('#imagesAreaList'));
                this.thumbArea = $("<div class='thumbnail imgProperty'></div>").appendTo(this.imageBox);
                this.imgComntainer= $("<div class='imgContainer' ></div>").appendTo(this.thumbArea);
                this.imageDisplay= $("<img class='img-rounded' data-src='holder.js/600x400' src='" + jData.data.dstFile + "' alt='' />").appendTo(this.imgComntainer);

                this.caption = $("<div class='caption' ></div>").appendTo(this.imageBox);
                this.imageDisplay= $("<small>" + jData.data.file + "</small>").appendTo(this.caption);
                this.imageId= $("<small> (" + jData.data.cid + ")</small>").appendTo(this.imageDisplay);
                this.xxy = $("<input name='cid[]' class='imageCid' type='hidden' value='" + jData.data.cid + "' />").appendTo(this.imageBox);

                // ToDo: Notification may be ... anyhow
            }
            else
            {
                alert('Result Error 05');
                // error on file transfer
                var msg = jData.message;
                alert ('Error on file transfer (1): "' + msg + '"');
                alert ("eData: " + eData);

                // ToDo: Use count ....
                msg = jData.messages.error[0];
                alert ("Error on file transfer (2): " + msg);
            }

        })

        //--- On fail / error  --------------------------------
        .fail(function(jqXHR, textStatus, exceptionType) {

            // start next upload
            sendState = 0; // 1 == busy
            startSendFileToServer () ;

            // alert ('fail: Status: "' + textStatus + '" exceptionType: "' + exceptionType + '" [' + jqXHR.status + ']');
            alert ('Drag and drop upload failed: "' + textStatus + '" -> "' + exceptionType + '" [' + jqXHR.status + ']');

            console.log(jqXHR);
        })

        //--- On always / complete --------------------------------
        // the .always() method replaces the deprecated .complete() method.
        .always(function( eData, textStatus, jqXHR) {
            //alert ('always: "' + textStatus + '"');

        });

        status.setAbort(jqXHR);

        /*=========================================================

        */
    }

});
