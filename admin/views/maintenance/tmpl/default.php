<?php 
/**
 * @package RSGallery2
 * @copyright (C) 2003 - 2017 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined( '_JEXEC' ) or die();

// JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip'); 
// ToDo: Activate tooltips on every button


$doc = JFactory::getDocument();
$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/Maintenance.css");

// Purge / delete of database variables should be confirmed 
$script = "
	jQuery(document).ready(function($){ 
/*		$('.consolidateDB').on('click', function () {
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE') . "'); 
		}); 
*/
/*
		$('.regenerateThumbs').on('click', function () { 
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE') . "'); 
		}); 
*/
/*		$('.optimizeDB').on('click', function () { 
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE') . "'); 
		}); 
*/
/*		$('.editConfigRaw').on('click', function () {
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE') . "'); 
		}); 
*/
		$('.purgeImagesAndData').on('click', function () {
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE') . "'); 
		}); 

		$('.uninstallDataTables').on('click', function () {
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE')  . "'); 
		}); 
	}); 
"; 
$doc->addScriptDeclaration($script); 

 /**
  * Used to generate buttons
  * @param string $link URL for button link
  * @param string $image Image name for button image
  * @param string $title Command title
  * @param string $text Command explaining text
  * @param string $addClass
  */
function quickiconBar( $link, $image, $title, $text = "", $addClass = '' ) {
    ?>
		<div class="rsg2-icon-bar">
			<a href="<?php echo $link; ?>" class="<?php echo $addClass; ?>" >
				<figure class="rsg2-old-icon">
					<?php echo JHtml::image('administrator/components/com_rsgallery2/images/'.$image, $text); ?>
                    <figcaption class="rsg2-text">
                        <span class="maint-title"><?php echo $title;?></span>
                        <!--br-->
                        <span class="maint-text"><?php echo $text;?></span>
                    </figcaption>
				</figure>
			</a>
		</div>
<?php
}

/**
 * Used to generate buttons with icomoon icon
 * @param string $link URL for button link
 * @param string $imageClass Image name for button image
 * @param string $title Command title
 * @param string $text Command explaining text
 * @param string $addClass
 */
function quickIconMoonBar( $link, $imageClass, $title, $text = "", $addClass = '' ) {
	?>
	<div class="rsg2-icon-bar button">
		<a href="<?php echo $link; ?>" class="<?php echo $addClass; ?>" >
			<figure class="rsg2-icon">
				<span class="<?php echo $imageClass ?>" style="font-size:40px;"></span>
				<figcaption class="rsg2-text">
					<span class="maint-title"><?php echo $title;?></span>
					<!--br-->
					<span class="maint-text"><?php echo $text;?></span>
				</figcaption>
			</figure>
		</a>
	</div>
	<?php
}

/**
 * Used to generate buttons with two icomoon icon
 * @param string $link URL for button link
 * @param string $imageClass Image name for button image
 * @param string $title Command title
 * @param string $text Command explaining text
 * @param string $addClass
 */
function quickTwoIconMoonBar( $link, $imageClass1, $imageClass2, $title, $text = "", $addClass = '' ) {
	?>
	<div class="rsg2-icon-bar">
		<a href="<?php echo $link; ?>" class="<?php echo $addClass; ?>" >
			<figure class="rsg2-icon">
				<span class="<?php echo $imageClass1 ?> iconMoon01" style="font-size:30px;"></span>
				<span class="<?php echo $imageClass2 ?> iconMoon02" style="font-size:30px;"></span>
				<figcaption class="rsg2-text">
					<span class="maint-title"><?php echo $title;?></span>
					<!--br-->
					<span class="maint-text"><?php echo $text;?></span>
				</figcaption>
			</figure>
		</a>
	</div>
	<?php
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=maintenance'); ?>"
      method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

        <div class="row-fluid grey-background">
            <div class="container-fluid grey-background">

				<div class="row span4 rsg2_container_icon_set">
					<div class="icons-panel rsg2">
						<div class="row-fluid">
							<div class="icons-panel-title rsg2Zone">
								<h3>
									<?php echo JText::_('COM_RSGALLERY2_RSGALLERY2_ZONE');?>
								</h3>
							</div>

							<div class='icons-panel-info'>
								<strong>
									<?php echo JText::_('COM_RSGALLERY2_RSGALLERY2_ZONE_DESC');?>
								</strong>
							</div>

							<?php
							$link = 'index.php?option=com_rsgallery2&amp;view=comments';
							quickTwoIconMoonBar ($link, 'icon-comment', 'icon-list-2',
								JText::_('COM_RSGALLERY2_COMMENTS_LIST'),
								JText::_('COM_RSGALLERY2_COMMENTS_TXT'),
								'consolidateDB');
							?>


							<?php
							$link = 'index.php?option=com_rsgallery2&rsgOption=installer';

							quickIconMoonBar( $link, 'icon-scissors clsTemplate',
								JText::_('COM_RSGALLERY2_TEMPLATE_MANAGER'),
								JText::_('COM_RSGALLERY2_TEMPLATE_EXPLANATION'),
								'templateManager');
							?>

						</div>
					</div>

	            	<?php
	            	if( $this->rawDbActive ) {
		            ?>
			            <div class="icons-panel rawDb">
				            <div class="row-fluid">
					            <div class="icons-panel-title rawDbZone">
						            <h3>
							            <?php echo JText::_('COM_RSGALLERY2_RAW_DB_ZONE');?>
						            </h3>
					            </div>

					            <div class='icons-panel-info'>
						            <strong>
							            <?php echo JText::_('COM_RSGALLERY2_RAW_DB_ZONE_DESCRIPTION');?>
						            </strong>
					            </div>

					            <?php
					            $link = 'index.php?option=com_rsgallery2&amp;view=config&amp;layout=RawView';
					            quickTwoIconMoonBar ($link, 'icon-equalizer', 'icon-eye',
						            JText::_('COM_RSGALLERY2_CONFIGURATION_VARIABLES'),
						            JText::_('COM_RSGALLERY2_CONFIG_MINUS_VIEW_TXT').'                        ',
						            'viewConfigRaw');
					            ?>

					            <?php
					            //$link = 'index.php?option=com_rsgallery2&amp;view=images';
					            $link = 'index.php?option=com_rsgallery2&amp;view=images&amp;layout=images_raw';
					            quickTwoIconMoonBar ($link, 'icon-image', 'icon-list-2',
						            JText::_('COM_RSGALLERY2_IMAGES_LIST'),
						            JText::_('COM_RSGALLERY2_RAW_IMAGES_TXT'),
						            'consolidateDB');
					            ?>

					            <?php
					            $link = 'index.php?option=com_rsgallery2&amp;view=galleries&amp;layout=galleries_raw';
					            quickTwoIconMoonBar ($link, 'icon-images', 'icon-list-2',
						            JText::_('COM_RSGALLERY2_GALLERIES_LIST'),
						            JText::_('COM_RSGALLERY2_RAW_GALLERIES_TXT'),
						            'consolidateDB');
					            ?>

					            <?php
					            $link = 'index.php?option=com_rsgallery2&amp;view=comments&amp;layout=comments_raw';
					            quickTwoIconMoonBar ($link, 'icon-comment', 'icon-list-2',
						            JText::_('COM_RSGALLERY2_COMMENTS_LIST'),
						            JText::_('COM_RSGALLERY2_RAW_COMMENTS_TXT'),
						            'consolidateDB');
					            ?>

					            <?php
								/**
					            $link = 'index.php?option=com_rsgallery2&amp;view=acl_items&amp;layout=acls_raw';
					            quickTwoIconMoonBar ($link, 'icon-eye-close', 'icon-list-2',
						            JText::_('COM_RSGALLERY2_ACLS_LIST'),
						            JText::_('COM_RSGALLERY2_RAW_ACLS_TXT'),
						            'consolidateDB');
								/**/
					            ?>

				            </div>
			            </div>
					<?php
					}
					?>

					<div class="icons-panel Outdated">
						<div class="row-fluid">
							<div class="icons-panel-title OutdatedZone">
								<h3>
									<?php echo JText::_('COM_RSGALLERY2_OUTDATED_ZONE');?>
								</h3>
							</div>

							<div class='icons-panel-info'>
								<strong>
									<?php echo JText::_('COM_RSGALLERY2_OUTDATED_ZONE_DESC');?>
								</strong>
							</div>

							<?php
							$link = 'index.php?option=com_rsgallery2&amp;rsgOption=config&amp;task=showConfig';
							quickiconBar ($link, 'config.png',
								JText::_('COM_RSGALLERY2_CONFIGURATION'),
								JText::_('        '), // COM_RSGALLERY2_CONFIG_MINUS_VIEW
								'test');
							?>

							<?php
							$link = 'index.php?option=com_rsgallery2&amp;rsgOption=galleries';
							quickiconBar ($link, 'categories.png',
								JText::_('COM_RSGALLERY2_MANAGE_GALLERIES'),
								JText::_('        '),
								'test');
							?>


							<?php
							$link = 'index.php?option=com_rsgallery2&amp;rsgOption=images&task=view_images';
							quickiconBar ($link, 'mediamanager.png',
								JText::_('COM_RSGALLERY2_MANAGE_IMAGES'),
								JText::_('        '),
								'test');
							?>

                            <?php
                            /**
                            $link = 'index.php?option=com_rsgallery2&task=config_rawEdit';
                            quickiconBar($link, 'menu.png',
                            JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT'),
                            JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT'),
                            'editConfigRaw');
                            /**/
                            ?>

                            <?php
                            if($this->UserIsRoot ) {
                            ?>
                                <?php
                                //$link = 'index.php?option=com_rsgallery2&amp;task=maintenance.consolidateDB';
                                $link = 'index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=consolidateDB';
                                quickiconBar($link, 'blockdevice.png',
                                    JText::_('COM_RSGALLERY2_MAINT_CONSOLDB'),
                                    JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT'),
                                    'consolidateDB');
                                ?>
                            <?php
                            }
                            ?>

                        </div>
					</div>
				</div>

				<div class="row span4 rsg2-container-icon-set">
					<div class="icons-panel repair">
						<div class="row-fluid">
							<div class="icons-panel-title repairZone">
								<h3>
									<?php echo JText::_('COM_RSGALLERY2_REPAIR_ZONE');?>
								</h3>
							</div>
							<div class='icons-panel-info'>
								<strong>
									<?php echo JText::_('COM_RSGALLERY2_FUNCTIONS_MAY_CHANGE_DATA');?>
								</strong>
							</div>

							<?php
							$link = 'index.php?option=com_rsgallery2&amp;view=config&amp;layout=RawEdit';
							quickTwoIconMoonBar ($link, 'icon-equalizer', 'icon-edit',
								JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'),
								JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT'),
								'editConfigRaw');
							?>

							<?php
							if($this->UserIsRoot ) {
							?>

                                <?php
                                // $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.consolidateDB';
                                $link = 'index.php?option=com_rsgallery2&amp;view=maintConsolidateDB';
                                quickTwoIconMoonBar ($link, 'icon-database', 'icon-checkbox-checked',
                                    JText::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE'),
                                    JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT'),
                                    'consolidateDB');
                                ?>

                                <?php
								$link =  'index.php?option=com_rsgallery2&amp;view=maintRegenerateImages';
								quickTwoIconMoonBar ($link, 'icon-image', 'icon-wand',
									JText::_('COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY'),
									JText::_('COM_RSGALLERY2_MAINT_REGEN_TXT').'                        ',
									'regenerateThumbs');
								?>

								<?php
								$link = 'index.php?option=com_rsgallery2&amp;task=maintSql.optimizeDB';
								quickTwoIconMoonBar ($link, 'icon-database', 'icon-clock', // 'icon-checkbox-checked'
									JText::_('COM_RSGALLERY2_MAINT_OPTDB'),
									JText::_('COM_RSGALLERY2_MAINT_OPTDB_TXT'),
									'optimizeDB');
								?>

                            <?php
							}
							?>

						</div>
					</div>
				</div>
									
				<div class="row span4 rsg2_container_icon_set">
					<div class="icons-panel danger">
						<div class="row-fluid">
							<div class="icons-panel-title dangerZone">
								<h3>
									<?php echo JText::_('COM_RSGALLERY2_DANGER_ZONE');?>
								</h3>
							</div>
							<?php
								if( $this->dangerActive ) {
							?>
									<div class='icons-panel-info'>
										<strong>
											<?php echo JText::_('COM_RSGALLERY2_ONLY_WHEN_YOU_KNOW_WHAT_YOU_ARE_DOING'); ?>
										</strong>
									</div>

									<?php
									$link = 'index.php?option=com_rsgallery2&amp;task=MaintCleanUp.purgeImagesAndData';
									//$link = 'index.php?option=com_rsgallery2&task=purgeEverything';
									quickTwoIconMoonBar ($link, 'icon-database ', 'icon-purge',
										JText::_('COM_RSGALLERY2_PURGEDELETE_EVERYTHING'),
										JText::_('COM_RSGALLERY2_PURGEDELETE_EVERYTHING_TXT'),
										'purgeImagesAndData');
									?>
									<?php
									$link = 'index.php?option=com_rsgallery2&amp;task=MaintCleanUp.removeImagesAndData';
									//$link = 'index.php?option=com_rsgallery2&task=reallyUninstall';
									quickTwoIconMoonBar ($link, 'icon-database ', 'icon-delete',
										JText::_('COM_RSGALLERY2_C_REALLY_UNINSTALL'),
										'<del>' . JText::_('COM_RSGALLERY2_C_REALLY_UNINSTALL_TXT') . '</del><br>'
										. JText::_('COM_RSGALLERY2_C_TODO_UNINSTALL_TXT'),
										'uninstallDataTables');
									?>
									<?php
										//} else {
										//	echo JText::_('COM_RSGALLERY2_MORE_FUNCTIONS_WITH_DEBUG_ON');
										//}
									?>
							<?php
								}
							?>

						</div>
					</div>
                </div>
		<!--
			</div>
		</div>

		<div class="row-fluid grey-background">
			<div class="container-fluid grey-background">
		-->
				<?php
				if( $this->upgradeActive ) {
				?>
				<div class="row span4 rsg2_container_icon_set">
					<div class="icons-panel upgrade">
						<div class="row-fluid">
							<div class="icons-panel-title upgradeZone">
								<h3>
									<?php echo JText::_('COM_RSGALLERY2_UPGRADE_ZONE');?>
								</h3>
							</div>

							<div class='icons-panel-info'>
								<strong>
									<?php echo JText::_('COM_RSGALLERY2_UPGRADE_ZONE_DESCRIPTION');?>
								</strong>
							</div>

							<?php
							$link = 'index.php?option=com_rsgallery2&amp;view=maintDatabase';
							quickTwoIconMoonBar ($link, 'icon-database', 'icon-book',
								JText::_('JLIB_FORM_VALUE_SESSION_DATABASE'),
								JText::_('COM_RSGALLERY2_DATABASE_REPAIR_DESC'),
								'compareDb2SqlFile');
							?>

							<?php
							$link = 'index.php?option=com_rsgallery2&amp;task=maintSql.createGalleryAccessField';
							quickTwoIconMoonBar ($link, 'icon-database', 'icon-wrench',
								JText::_('COM_RSGALLERY2_CREATE_GALLERY_ACCESS_FIELD'),
								JText::_('COM_RSGALLERY2_CREATE_GALLERY_ACCESS_FIELD_DESCRIPTION'),
								'createGalleryAccessField');
							?>
							
						</div>
					</div>
				</div>
				<?php
					}
				?>


				<?php
				if( $this->testActive ) {
				?>
				<div class="row span4 rsg2_container_icon_set">
					<div class="icons-panel test">
						<div class="row-fluid">
							<div class="icons-panel-title testZone">
								<h3>
									<?php echo JText::_('COM_RSGALLERY2_TEST_ZONE');?>
								</h3>
							</div>

							<div class='icons-panel-info'>
								<strong>
									<?php echo JText::_('COM_RSGALLERY2_TEST_ZONE_DESCRIPTION');?>
								</strong>
							</div>

                            <?php
                            $link = 'index.php?option=com_rsgallery2&amp;view=comments';
                            quickTwoIconMoonBar ($link, 'icon-comment', 'icon-list-2',
                                JText::_('COM_RSGALLERY2_COMMENTS_LIST'),
                                JText::_('COM_RSGALLERY2_COMMENTS_TXT'),
                                'consolidateDB');
                            ?>

                            <?php
							$link = 'index.php?option=com_rsgallery2&amp;view=acl_items';
							quickTwoIconMoonBar ($link, 'icon-eye-close', 'icon-list-2',
								JText::_('COM_RSGALLERY2_ACLS_LIST'),
								JText::_('List of ACL: niot ready'),
								'consolidateDB');
							?>

						</div>
					</div>
				</div>
				<?php
					}
				?>

				<!--
			</div>
		</div>

		<div class="row-fluid grey-background">
			<div class="container-fluid grey-background">
				-->
				<?php
					if( $this->developActive ) {
				?>
				<div class="row span4 rsg2_container_icon_set">
						<div class="icons-panel developer">
							<div class="row-fluid">
								<div class="icons-panel-title developerZone">
									<h3>
										<?php echo JText::_('COM_RSGALLERY2_DEVELOPER_ZONE');?>
									</h3>
								</div>
								<div class='icons-panel-info'>
									<strong>
										<?php echo JText::_('COM_RSGALLERY2_ONLY_WHEN_YOU_KNOW_WHAT_YOU_ARE_DOING'); ?>
									</strong>
								</div>

								<?php
								// $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.consolidateDB';
								$link = 'index.php?option=com_rsgallery2&amp;view=maintRemoveLogFiles';
								quickTwoIconMoonBar ($link, 'icon-file-check', 'icon-file-remove',
									JText::_('COM_RSGALLERY2_REMOVE_LOG_FILES'),
									JText::_('COM_RSGALLERY2_REMOVE_LOG_FILES_TXT'),
									'consolidateDB');
								?>

								<?php
								// $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.consolidateDB';
								$link = 'index.php?option=com_rsgallery2&amp;view=maintRemoveInstallLeftovers';
								quickTwoIconMoonBar ($link, 'icon-upload', 'icon-file-remove',
									JText::_('COM_RSGALLERY2_REMOVE_INSTALLATION_LEFTOVERS'),
									JText::_('COM_RSGALLERY2_REMOVE_INSTALLATION_LEFTOVERS_TXT'),
									'consolidateDB');
								?>

                                <?php
                                // $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.consolidateDB';
                                $link = 'index.php?option=com_rsgallery2&amp;task=maintSql.updateCommentsVoting';
                                quickTwoIconMoonBar ($link, 'icon-comment', 'icon-wand',
                                    JText::_('COM_RSGALLERY2_UPDATE_COMMENTS_AND_VOTING'),
                                    JText::_('COM_RSGALLERY2_UPDATE_COMMENTS_AND_VOTING_TXT'),
                                    'consolidateDB');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.Delete_1_5_LangFiles';
                                quickTwoIconMoonBar ($link, 'icon-delete', 'icon-wand',
                                    JText::_('COM_RSGALLERY2_DELETE_1_5_LANG_FILES'),
                                    JText::_('COM_RSGALLERY2_DELETE_1_5_LANG_FILES_DESC'),
                                    'consolidateDB');
                                ?>
                            </div>
						</div>
					</div>
				<?php
					}
				?>
			</div>
		</div>

        <div>
			<input type="hidden" name="option" value="com_rsgallery2" />
			<input type="hidden" name="rsgOption" value="maintenance" />

            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>

