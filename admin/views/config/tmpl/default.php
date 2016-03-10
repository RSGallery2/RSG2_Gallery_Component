<?php // no direct access
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2016 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined( '_JEXEC' ) or die();

JHtml::_('behavior.tooltip');

global $Rsg2DebugActive;

JHtml::_('formbehavior.chosen', 'select');

// public static $extension = 'COM_RSG2';

//$doc = JFactory::getDocument();
//$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/Maintenance.css");


?>

<div id="installer-install" class="clearfix">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif;?>

			<form action="<?php JRoute::_('index.php?option=com_rsgallery2&view=config'); ?>"
				  method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" >

				<?php echo JHtml::_('bootstrap.startTabSet', 'Config', array('active' => 'Images')); ?>

<!--- general --->

                <?php echo JHtml::_('bootstrap.addTab', 'Config', 'General', JText::_('COM_RSGALLERY2_GENERAL', true)); ?>

                        <legend><?php echo JText::_('COM_RSGALLERY2_GENERAL'); ?></legend>

                <?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_general_group', array('active' => 'cfg_general_id_1')); ?>


                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_general_group',
                    JText::_('COM_RSGALLERY2_GENERAL_SETTINGS'), 'cfg_general_id_1'); ?>

                <strong><?php echo JText::_('COM_RSGALLERY2_GENERAL_TXT'); ?></strong>
			    		<?php
                            echo JText::_('COM_RSGALLERY2_VERSION') . JText::_('COM_RSGALLERY2_GENERAL_SETTINGS') . '<br><br>';

							//--- render general ------------------------------------------------------>
							echo $this->form->renderFieldset('General');

						?>

                        <?php echo JHtml::_('bootstrap.endSlide'); ?>

                        <?php echo JHtml::_('bootstrap.endAccordion'); ?>

                        <?php echo JHtml::_('bootstrap.endTab'); ?>
    
<!--- images --->

	        			<?php echo JHtml::_('bootstrap.addTab', 'Config', 'Images', JText::_('COM_RSGALLERY2_IMAGES', false)); ?>

                        <legend><?php echo JText::_('COM_RSGALLERY2_IMAGES'); ?></legend>
                <?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_images_group', array('active' => 'cfg_images_id_1')); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group',
                    JText::_('COM_RSGALLERY2_IMAGE_MANIPULATION'), 'cfg_images_id_1'); ?>

                <?php
                echo $this->form->renderFieldset('Images_manipulation');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_IMAGE_UPLOAD'), 'cfg_images_id_2'); ?>

                <?php
                echo $this->form->renderFieldset('Images_upload');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_GRAPHICS_LIBRARY'), 'cfg_images_id_3'); ?>

                <?php
                echo $this->form->renderFieldset('Images_graficsLibrary');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_IMAGE_STORAGE'), 'cfg_images_id_4'); ?>

                <?php
                echo $this->form->renderFieldset('Images_storage');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_COMMENTS'), 'cfg_images_id_5'); ?>

                <?php
                echo $this->form->renderFieldset('Images_comments');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_images_group', JText::_('COM_RSGALLERY2_VOTING'), 'cfg_images_id_6'); ?>

                <?php
                echo $this->form->renderFieldset('Images_voting');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.endAccordion'); ?>

                <?php echo JHtml::_('bootstrap.endTab'); ?>

<!--- display --->

				<?php echo JHtml::_('bootstrap.addTab', 'Config', 'Display', JText::_('COM_RSGALLERY2_DISPLAY', false)); ?>

				<strong><?php echo JText::_('COM_RSGALLERY2_CONFIG_MINUS_VIEW_TXT'); ?></strong>

                <?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_display_group', array('active' => 'cfg_display_id_1')); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group',
                    JText::_('COM_RSGALLERY2_FRONT_PAGE'), 'cfg_display_id_1'); ?>

                <?php
                echo $this->form->renderFieldset('Display_frontPage');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_IMAGE_DISPLAY'), 'cfg_display_id_2'); ?>

                <?php
                echo $this->form->renderFieldset('Display_imageDisplay');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_IMAGE_ORDER'), 'cfg_display_id_3'); ?>

                <?php
                echo $this->form->renderFieldset('Display_imageOrder');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_EXIF_SETTINGS'), 'cfg_display_id_4'); ?>

                <?php
                echo $this->form->renderFieldset('Display_exifSettings');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_GALLERY_VIEW'), 'cfg_display_id_5'); ?>

                <?php
                echo $this->form->renderFieldset('Display_galleryView');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_display_group', JText::_('COM_RSGALLERY2_IMAGE_WATERMARK'), 'cfg_display_id_6'); ?>

                <?php
                echo $this->form->renderFieldset('Display_imageWatermark');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.endAccordion'); ?>

                <?php echo JHtml::_('bootstrap.endTab'); ?>

<!--- My galleries --->

                <?php echo JHtml::_('bootstrap.addTab', 'Config', 'Mygalleries', JText::_('COM_RSGALLERY2_MY_GALLERIES', false)); ?>

				<legend><?php echo JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'); ?></legend>

                <?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_my_galleries_group_1', array('active' => 'cfg_my_galleries_id_1')); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_my_galleries_group_1',
                    JText::_('COM_RSGALLERY2_MY_GALLERIES_SETTINGS'), 'cfg_my_galleries_id_1'); ?>


                <?php
                echo $this->form->renderFieldset('Mygalleries_setting');
                ?>
                <?php echo JHtml::_('bootstrap.endSlide'); ?>


                <?php echo JHtml::_('bootstrap.endAccordion'); ?>

                <?php echo JHtml::_('bootstrap.startAccordion', 'slide_cfg_my_galleries_group_2', array('active' => 'cfg_my_galleries_id_2')); ?>

                <?php echo JHtml::_('bootstrap.addSlide', 'slide_cfg_my_galleries_group_2', JText::_('COM_RSGALLERY2_IMAGE_UPLOAD'), 'cfg_my_galleries_id_2'); ?>

                <?php
                echo $this->form->renderFieldset('Mygalleries_upload');
                ?>

                <?php echo JHtml::_('bootstrap.endSlide'); ?>

                <?php echo JHtml::_('bootstrap.endAccordion'); ?>

                <?php echo JHtml::_('bootstrap.endTab'); ?>

<!--- end --->



                <?php echo JHtml::_('bootstrap.endTabSet'); ?>

				<!--input type="hidden" name="option" value="com_rsgallery2" />
				<input type="hidden" name="rsgOption" value="maintenance" />

				<input type="hidden" name="task" value="" /-->
				<?php echo JHtml::_('form.token'); ?>
		</div>
		</form>
	</div>
	<div id="loading"></div>
</div>
</div>
