<?php
/**
 * @package       RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_rsgallery2/views/imagesProperties/css/ImagesProperties.css');
JHtml::_('bootstrap.tooltip');
JHtml::_('bootstrap.modal');


//global $Rsg2DebugActive;
// global $rsgConfig;

/**
$sortColumn    = $this->escape($this->state->get('list.ordering')); //Column
$sortDirection = $this->escape($this->state->get('list.direction'));
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
			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=imagesProperties'); ?>"
					method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"
					class="form-validate">

	            <legend><?php echo JText::_('COM_RSGALLERY2_PROPERTIES_UPLOADED_FILES'); ?></legend>

				<?php
				// Search tools bar
                // ToDo: activate ....
				// OK: echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));

				//echo JLayoutHelper::render('joomla.searchtools.default', $data, null, array('component' => 'none'));
				// I managed to add options as always open
				//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filtersHidden' => false ($hidden) (true/false) )));
				?>

				<?php if (empty($this->items)) : ?>
                    <div class="alert alert-no-items">
						<?php echo JText::_('COM_RSGALLERY2_NO_IMAGES_SELECTED_FOR_VIEW'); ?>
                    </div>
				<?php else : ?>

                    <span class"">SelectAll <?php echo JHtml::_('grid.checkall'); ?><br></span>

	                <ul class="thumbnails">
                        <?php
                        $Idx = 0;

                        foreach ($this->items as $Idx => $item)
                        {
                            $src   = $this->HtmlPathDisplay . $this->escape($item->name) . '.jpg';
                            // <img data-src="holder.js/200x180" src="<?php echo $file;  class="img-rounded" alt="">
                            ?>
                            <li class="imagesAreaList" >
                                <div class="thumbnail imgProperty">
                                    <div class='imgContainer'>
                                        <a class="modal" href="<?php echo $src; ?>">
                                            <img src="<?php echo $src; ?>" class="img-rounded" alt="">
                                        </a>
                                    </div>

                                    <div class="caption" >
	                                    <?php echo JHtml::_('grid.id', $Idx, $item->id); ?>
                                        <small>&nbsp;<?php echo $this->escape($item->name);?> (ID: <?php echo $this->escape($item->id);?>)</small><br>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="title[]"><?php echo JText::_('COM_RSGALLERY2_TITLE'); ?></label>
                                        <div class="controls">
                                            <!--input type="text" id="inputEmail" placeholder="Email"-->
                                            <input name="title[]" type="text" size="15" aria-invalid="false"
                                                   value="<?php echo $this->escape($item->title);?>"
                                                   style="width:95%;>
                                        </div>
                                    </div>

                                    <!-- Gallery can't be changed. Disable input -->
                                    <div class="control-group">
                                        <label class="control-label" for="galleryID[]" ><?php echo JText::_('COM_RSGALLERY2_GALLERY'); ?>(2)</label>
                                        <div class="controls">
                                            <input type="text" name="galleryID[]" placeholder="Idx:"
                                                   value="<?php echo $this->escape($item->gallery_name);?>"
                                                   disabled style="width:95%;>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="description[]" ><?php echo JText::_('COM_RSGALLERY2_DESCRIPTION'); ?></label>
                                        <div class="controls">
                                        <textarea cols="15" rows="" name="description[]"
                                                  placeholder="Text input"
                                                  style="width:95%;"><?php echo $this->escape($item->descr);?></textarea>
                                        </div>
                                    </div>

                                    <input  type="hidden" name="iid[]" value="<?php echo $item->id;?>">
                                    <!--input  type='hidden' name="cid[]" value="<?php echo $item->id;?>"-->

                                    <!-- div class="control-group">
                                        <div class="control-label"><label id="delete__-lbl" for="delete__" class="hasPopover" title="" data-content="COM_RSGALLERY2_DELETE_IMAGE_DO_NOT_ASSIGN" data-original-title="Delete Image">
                                                Delete Image</label>
                                        </div>
                                        <div class="controls">
                                            <fieldset class="btn-group btn-group-yesno radio">
                                                <input name="delete[][]" value="0" checked="checked" type="radio">
                                                <label class="btn btn-default active btn-success">Save</label>
                                                <input name="delete[][]" value="1" type="radio">
                                                <label for="delete[][]" class="btn ">Delete</label>
                                            </fieldset>
                                        </div>
                                    </div -->

                                </div>
                            </li>

                            <?php
                        }
                        ?>

                    </ul>
				<?php endif; ?>










                <input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />

						<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
	</div>

