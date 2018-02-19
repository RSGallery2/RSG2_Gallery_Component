<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

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

$doc->addScript(JUri::root() . '/administrator/components/com_rsgallery2/views/upload/js/upload.js');

/**
The controller does the token check like this:
JSession::checkToken('get') or die( 'Invalid Token');

The view makes the ajax request using the token as a URL variable:
index.php?option=com_mycomponent&format=raw&task=ajax.myTask&50e6a74276c578d2ebfc40fd526a193f=1

The token is generated using an ajax call to another function that generates the token using:
echo JFactory::getSession()->getFormToken();
---------------------------------------
$.post('index.php',
{
'option':   'com_tieraerzte',
'task':     'parser.importColumns',
'tmpl':     'component',
'app':      sourceSelect.val(),
!!! '<?php echo JSession::getFormToken()?>': 1 !!!
},
function(result) {
$('td.add_column').html(result);
$('button#parse.btn').show();
//edit the result here
return;
},
'html'
);

/**/
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

					<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload_drag_and_drop', JText::_('COM_RSGALLERY2_UPLOAD_BY_DRAG_AND_DROP', true)); ?>
                    <fieldset class="uploadform">
                        <legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_BY_DRAG_AND_DROP_LABEL'); ?></legend>

						<?php
						// All in one, specify gallery
						echo $this->form->renderFieldset('upload_drag_and_drop');
						?>

                        <!--legend><?php echo JText::_('COM_RSGALLERY2_DRAG_FILE_ZONE'); ?></legend-->

                        <div id="uploader-wrapper" disabled>
                            <div id="dragarea" class="">
                                <div id="dragarea-content" class="text-center">
                                    <div id="imagesArea" class="span12">
                                        <ul id="imagesAreaList" class='thumbnails'>

                                        </ul>
                                    </div>
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
                            </div>
                        </div>

                        <!--Action buttonManualFile -->
                        <div class="form-actions" style="margin-top: 10px; ">
                            <buttonManualFile type="buttonManualFile" class="btn btn-primary"
                                              id="AssignUploadedFiles"
                                              onclick="Joomla.submitAssignDroppedFiles()"
                                              title="<?php echo JText::_('COM_RSGALLERY2_ADD_IMAGES_PROPERTIES_DESC'); ?>"
                            >
			                    <?php echo JText::_('COM_RSGALLERY2_ADD_IMAGES_PROPERTIES'); ?>
                            </buttonManualFile>
                        </div>

                        <input class="input_box hidden" id="hidden_file_input" name="hidden_file_input" type="file" multiple />

                        <div id="uploadProgressArea"></div>

                        <div id="uploadErrorArea"></div>

                        <div class="form-actions">
                            <a class="btn btn-primary" id="submitbuttonManualFileSingle"
                               title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_LEGACY_DESC'); ?>"
                               href="index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=upload">
								<?php echo JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES_LEGACY'); ?>
                            </a>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <div>
                                    <small class="help-block" style="color:darkred;">
	    								<?php echo JText::_('COM_RSGALLERY2_UPLOAD_LIMIT_IS') . ' ' . $this->UploadLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                                    </small>
                                </div>
                                <div>
                                    <small class="help-block" style="color:darkred;">
				    					<?php echo JText::_('COM_RSGALLERY2_POST_MAX_SIZE_IS') . ' ' . $this->PostMaxSize . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                                    </small>
                                </div>
                                <div>
                                    <small class="help-block" style="color:darkred;">
							    		<?php echo JText::_('COM_RSGALLERY2_POST_MEMORY_LIMIT_IS') . ' ' . $this->MemoryLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                                    </small>
                                </div>
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
                                <div>
                                    <small class="help-block" style="color:darkred;">
			                            <?php echo JText::_('COM_RSGALLERY2_UPLOAD_LIMIT_IS') . ' ' . $this->UploadLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                                    </small>
                                </div>
                                <div>
                                    <small class="help-block" style="color:darkred;">
			                            <?php echo JText::_('COM_RSGALLERY2_POST_MAX_SIZE_IS') . ' ' . $this->PostMaxSize . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                                    </small>
                                </div>
                                <div>
                                    <small class="help-block" style="color:darkred;">
			                            <?php echo JText::_('COM_RSGALLERY2_POST_MEMORY_LIMIT_IS') . ' ' . $this->MemoryLimit . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                                    </small>
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
                                              onclick="Joomla.submitButtonManualFileZipPcLegacy()"
                                              title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE_LEGACY_DESC'); ?>"
                            >
                                <?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE_LEGACY'); ?>
                            </buttonManualFile>
                            <buttonManualFile type="buttonManualFile" class="btn btn-primary"
                                              onclick="Joomla.submitButtonManualFileZipPc()"
                                              title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE_DESC'); ?>"
                            >
                                <?php echo JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'); ?>
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
                                              onclick="Joomla.submitButtonManualFileFolderServerLegacy()"
                                              title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES_SERVER_LEGACY_DESC'); ?>"
                            >
                                <?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES_SERVER_LEGACY'); ?>
                            </buttonManualFile>
                            <buttonManualFile type="buttonManualFile" class="btn btn-primary"
                                              onclick="Joomla.submitButtonManualFileFolderServer()"
                                              title="<?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES_SERVER_DESC'); ?>"
                            >
                                <?php echo JText::_('COM_RSGALLERY2_UPLOAD_IMAGES_SERVER'); ?>
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
                    <input type="hidden" value="" name="rsgOption">

				<?php endif; ?>

				<?php echo JHtml::_('form.token'); ?>
            </form>
            <div id="loading"></div>
        </div>
    </div>


