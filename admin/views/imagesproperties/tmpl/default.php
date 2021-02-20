<?php
/**
 * @package       RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_rsgallery2/views/imagesproperties/css/ImagesProperties.css');
$doc->addscript(JUri::root() . '/administrator/components/com_rsgallery2/views/imagesproperties/js/modalImage.js');
JHtml::_('bootstrap.tooltip');
//JHtml::_('bootstrap.modal');

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
			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=imagesProperties'); ?>"
					method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"
					class="form-validate">

	            <legend><?php echo JText::_('COM_RSGALLERY2_PROPERTIES_UPLOADED_IMAGES'); ?></legend>

				<?php if (empty($this->items)) : ?>
                    <div class="alert alert-no-items">
						<?php echo JText::_('COM_RSGALLERY2_NO_IMAGES_SELECTED_FOR_VIEW'); ?>
                    </div>
				<?php else : ?>

                    <span class="">SelectAll <?php echo JHtml::_('grid.checkall'); ?><br><br></span>

	                <ul class="thumbnails">
                        <?php
                        $Idx = 0;

                        foreach ($this->items as $Idx => $item)
                        {
                            $src   = $this->HtmlPathDisplay . $this->escape($item->name) . '.jpg';
                        ?>
                            <li class="imagesAreaList" >
                                <div class="thumbnail imgProperty">
                                    <div class='imgContainer'>
                                        <img src="<?php echo $src; ?>" class="img-rounded modalActive" alt="<?php echo $this->escape($item->name);?>">
                                    </div>

                                    <div class="caption" >
	                                    <?php echo JHtml::_('grid.id', $Idx, $item->id, false, 'sid'); ?>
                                        <small>&nbsp;<?php echo $this->escape($item->name);?>&nbsp;(ID: <?php echo $this->escape($item->id);?>)</small><br>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="title[]"><?php echo JText::_('COM_RSGALLERY2_TITLE'); ?></label>
                                        <div class="controls">
                                            <input name="title[]" type="text" size="15" aria-invalid="false"
                                                   value="<?php echo $this->escape($item->title);?>"
                                                   style="width:95%;>
                                        </div>
                                    </div>

                                    <!-- Gallery can't be changed. Disable input -->
                                    <div class="control-group">
                                        </div>
                                        <label class="control-label" for="galleryID[]" ><?php echo JText::_('COM_RSGALLERY2_GALLERY'); ?></label>
                                        <div class="controls">
                                            <input type="text" name="galleryID[]" placeholder="Idx:"
                                                   value="<?php echo $this->escape($item->gallery_name);?>"
                                                   disabled style="width:95%;>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <!-- label class="control-label" for="description2[]" ><?php echo JText::_('COM_RSGALLERY2_DESCRIPTION'); ?></label>
                                        <div class="controls">
                                        <textarea cols="15" rows="" name="description[]"
                                                  placeholder="Text input"
                                                  style="width:95%;"><?php echo $this->escape($item->descr);?></textarea>
                                        </div-->

                                        <label class="control-label" for="description[]" ><?php echo JText::_('COM_RSGALLERY2_DESCRIPTION'); ?></label>
                                        <div class="controls">
                                            <?php
                                            if ( ! empty($this->editor))
                                            {
                                                // ToDo: Leave out some editor buttons : use config ...
	                                            echo $this->editor->display('description[]', $this->escape($item->descr), '90%', '100', '20', '20',
		                                            false, 'description_' . $Idx, null, null, $this->editorParams);
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <input type="hidden" name="cid[]" value="<?php echo $item->id;?>">
                                </div>
                            </li>

                        <?php
                        }
                        ?>
                    </ul>

                    <div id="popupModal" class="Xmodal">
                        <span id="popupClose" class="close">&times;</span>
                        <img  id="popupImage" class="modal-content">
                        <div id="popupCaption"></div>
                    </div>

				<?php endif; ?>

                <input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />

						<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
	</div>

