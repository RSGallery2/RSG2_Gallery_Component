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
// http://talkerscode.com/webtricks/drag-and-drop-image-upload-using-ajax-jquery-and-php.php
// + https://joomla.stackexchange.com/questions/146/what-is-the-proper-way-to-make-an-ajax-call-in-component
// https://techjoomla.com/blog/beyond-joomla/jquery-basics-getting-values-of-form-inputs-using-jquery.html

defined('_JEXEC') or die();

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_rsgallery2/views/upload/css/upload.css');

//echo 'JURI_SITE: "' . JURI_SITE . '"';

JHtml::_('bootstrap.tooltip');
JHtml::_('bootstrap.framework');
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

/**
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

//--------------------------------------------------------------------------------------
// "old" submit buttons
//--------------------------------------------------------------------------------------

/* Deprecated old single image upload */
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

    /** obsolete assign dropped files **/
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

        // Is invalid galleryId selected ?
//        if (bOneGalleryName4All && (GalleryId < 1)) {
//            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST'));
//        }
//        else {
            // yes transfer files ...
//        alert('03');
            form.task.value = 'uploadFileProperties.prepareDroppedImages'; // upload.uploadZipFile
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
/**/

    /* Zip file */
    Joomla.submitbuttonManualFileZipPc = function () {

        alert('Upload Manual File Zip from Pc: legacy ...');
        
        var form = document.getElementById('adminForm');

        var zip_path = form.zip_file.value;
        //var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
        var gallery_id = $('#SelectGalleries_03').val();

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
    Joomla.submitbuttonManualFileZipPc2 = function () {

        alert('Upload Manual File Zip from Pc: upload.uploadFromZip ...');


        var form = document.getElementById('adminForm');

        var zip_path = form.zip_file.value;
        //var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
        var gallery_id = $('#SelectGalleries_03').val();
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
                form.xcat.value = GalleryId;
                form.selcat.value = bOneGalleryName4All;
                // form.rsgOption.value = "";

                jQuery('#loading').css('display', 'block');
                form.submit();
            }
        }
    };
    /**/

    /* from server */
    Joomla.submitbuttonManualFileFolderServer = function () {

        alert('Upload Folder server: legacy ...');
        
        var form = document.getElementById('adminForm');

        //var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
        var gallery_id = $('#SelectGalleries_03').val();
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
                form.batchmethod.value = 'FTP';
                form.ftppath.value = ftp_path;
                form.xcat.value = GalleryId;
                form.selcat.value = bOneGalleryName4All;

                jQuery('#loading').css('display', 'block');
                form.submit();
            }
        }
    };

/* Test from server: ? Not used ? */
   Joomla.submitbuttonManualFileFolderServer2 = function () {

       alert('Upload Folder server: upload.uploadFromFtpFolder ...');

       var form = document.getElementById('adminForm');

        //var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
        var gallery_id = $('#SelectGalleries_03').val();
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
                form.xcat.value = GalleryId;
                form.selcat.value = bOneGalleryName4All;
                // form.rsgOption.value = "";

                jQuery('#loading').css('display', 'block');
                form.submit();
            }
        }
    };
/**/

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
    //var urlSingle = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile&format=json';
    //var urlSingle = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile&format=raw';
    var urlSingle = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile';
    var returnUrl = $('#installer-return').val();
    var token = $('#installer-token').val();
    var gallery_id = $('#SelectGalleries_03').val();

    // a) Use class to set conent light red and light green or other colors
    // when droppable...
    // b) Use jquery to activate and de activate some
    $('#dragarea').css('border', '4px dotted red');
    $('#SelectGalleries_03').change(function() {
        // drop disabled ?
        if ($(this).val() == 0)
        {
            $('#dragarea').css('border', '4px dotted red');
        }
        else
        {
            $('#dragarea').css('border', '4px dotted darkgreen');
        }
    });

	buttonManualFile.on('click', function (e) {
//            alert('buttonManualFile.on click: '); // + JSON.stringify($(this)));
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
            // alert('files: ' + JSON.stringify(files));
            // files exist ?
            if (!files.length) {
                return;
            }

            //alert('handleFileUpload: ' + files[0].name);

            // We need to send dropped files to Server
            handleFileUpload(files, dragZone);
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
		e.preventDefault();
		e.stopPropagation();

        var gallery_id = $('#SelectGalleries_03').val();

        // prevent empty gallery
        if (gallery_id < 1) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(6)');
        }
        else {
            $(this).css('border', '2px dotted #0B85A1');

            var files = e.originalEvent.target.files || e.originalEvent.dataTransfer.files;
            if (!files.length) {
                return;
            }

            // We need to send dropped files to Server
            handleFileUpload(files, dragZone);
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

	// Uploading image count
	var imgCount = 0;

	// Handle status bar for one actual uploading image
	function createStatusbar(obj) {
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

	// image names , container
	function handleFileUpload(files, obj) {

		// ToDo: On first file upload disable gallery change and isone .. change

        var gallery_id = $('#SelectGalleries_03').val();

		// All files selected by user
		for (var i = 0; i < files.length; i++) {
			var data = new FormData();
			data.append('upload_file', files[i]);
			data.append('upload_type', 'single');
			data.append('token', token);
			data.append('gallery_id', gallery_id);

            //alert ("test01");
			var status = new createStatusbar(obj); //Using this we can set progress.
			status.setFileNameSize(files[i].name, files[i].size);
            //alert ("test02");


			sendFileToServer(data, status);
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
		$("#thumbnail_"+num).append('<span class="size">'+size+'<span>');
	}
/**/

// https://stackoverflow.com/questions/6792878/jquery-ajax-error-function
/* Deprecation Notice:
    The jqXHR.success(), jqXHR.error(), and jqXHR.complete()
    callbacks are deprecated as of jQuery 1.8. To prepare
    your code for their eventual removal,
    use jqXHR.done(), jqXHR.fail(), and jqXHR.always() instead.
/**/

/**
// Assign handlers immediately after making the request,
// and remember the jqXHR object for this request
        var jqxhr = $.ajax("some_unknown_page.html")
            .done(function (response) {
                // success logic here
                $('#post').html(response.responseText);
            })
            .fail(function (jqXHR, exception) {
                // Our error logic here
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                $('#post').html(msg);
            })
            .always(function () {
                alert("complete");
            });
/**/


/**
        Category: Global JQuery Ajax Event Handlers
        These methods register handlers to be called when certain events, such as initialization or completion, take place for any Ajax request on the page. The global events are fired on each Ajax request if the global property in jQuery.ajaxSetup() is true, which it is by default. Note: Global events are never fired for cross-domain script or JSONP requests, regardless of the value of  global.

            .ajaxComplete()
        Register a handler to be called when Ajax requests complete. This is an AjaxEvent.
            .ajaxError()
        Register a handler to be called when Ajax requests complete with an error. This is an Ajax Event.
            .ajaxSend()
        Attach a function to be executed before an Ajax request is sent. This is an Ajax Event.
            .ajaxStart()
        Register a handler to be called when the first Ajax request begins. This is an Ajax Event.
            .ajaxStop()
        Register a handler to be called when all Ajax requests have completed. This is an Ajax Event.
            .ajaxSuccess()
        Attach a function to be executed whenever an Ajax request completes successfully. This is an Ajax Event.

/**/

/**
        success: function (html) {
            alert('successful : ' + html);
            $("#result").html("Successful");
        },
/**/



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
                data: formData,
                // use .done(), .fail() and .always() instead of success(), error() and complete().
                success: function (eData) {
                    // Not needed  as already done in progress
                    // status.setProgress(100);
                    //alert('Success');
                    //alert('Version jQuery: ' + jQuery.fn.jquery);
                    //alert ('Success2: ' + String(eData))
                    //$("#status1").append("File upload Done<br>");
                    var json = jQuery.parseJSON(eData);
                    // alert('Success2');
                    //alert ('Json: ' + String (json));
                    //alert('Success3');

                    // Use this: See Above
                    // $('#imagesList').append('<li><img src="' + this + '" /></li>');
                    // this.statusbar   = $("<div class='statusbar " + row + "'></div>");
                    // this.filename    = $("<div class='filename'></div>").appendTo(this.statusbar);

                    // imagesArea, imagesAreaList

                    this.imageBox = $("<li></li>").appendTo($('#imagesAreaList'));
                    this.thumbArea = $("<div class='imgProperty thumbnail'></div>").appendTo(this.imageBox);
                    this.imgComntainer= $("<img class='imgComntainer' >").appendTo(this.thumbArea);
                    this.imageDisplay= $("<img class='img-rounded' src='" + json.data.dstFile + "' alt=''/>").appendTo(this.imgComntainer);

                    this.caption = $("<div class='caption' ></div>").appendTo(this.imageBox);
                    this.imageDisplay= $("<small>" + json.data.file + "</small><br>").appendTo(this.caption);
                    this.xxy = $("<input name='imageCid' class='imageCid' type='hidden' value='" + json.data.cid + "' />").appendTo(this.imageBox);

                },
                error: function(status, thrownError) {
                    alert('error');
                    var responseText = jQuery.parseJSON(jqXHR.responseText);
					//alert('Error: ' + toJson (data))
					alert('responseText: ' + responseText)
                    // console.log(responseText);
                }
            }).done(function(eData, textStatus, jqXHR) {
                    // Not needed  as already done in progress
                    // status.setProgress(100);

                    // $( "#results" ).append( html );

                    //alert ('done: ' + String(eData))
            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert ('fail: ' + textStatus)
            })  // .always(function( data|jqXHR, textStatus, jqXHR|errorThrown ) {
                .always(function( eData, textStatus, jqXHR) {
                // $( "#results" ).append( html );
                //alert ('always: ' + textStatus)
            })
                //.then ... (function( data, textStatus, jqXHR ) {}, function( jqXHR, textStatus, errorThrown ) {});

            ;

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
						// All in one, specify gallery
						echo $this->form->renderFieldset('upload_drag_and_drop');
						?>

                        <legend><?php echo JText::_('COM_RSGALLERY2_DRAG_FILE_ZONE'); ?></legend>

                        <div id="uploader-wrapper" disabled>
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
                            <div id="imagesArea" class="span2">
                                <ul id="imagesAreaList" class="thumbnails">

                                </ul>
                            </div>

                        </div>

                        <!--Action buttonManualFile-->
                        <div class="form-actions" style="margin-top: 10px; ">
                            <buttonManualFile class="btn btn-primary" type="buttonManualFile"
                                              id="AssignUploadedFiles" onclick="Joomla.submitAssignDroppedFiles()"
                                              title="<?php echo JText::_('COM_RSGALLERY2_ADD_IMAGES_INFORMATION_DESC'); ?>">
			                    <?php echo JText::_('COM_RSGALLERY2_ADD_IMAGES_INFORMATION'); ?>
                            </buttonManualFile>
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

                        <input id="installer-return" name="installer-return" type="hidden" value="<?php echo $return; ?>"/>
                        <input id="installer-token" name="installer-token" type="hidden" value="<?php echo $token; ?>"/>
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


