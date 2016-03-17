<?php // no direct access
defined( '_JEXEC' ) or die();

JHtml::_('behavior.tooltip');
// ToDo: Activate tooltips on every button

global $Rsg2DebugActive;

// public static $extension = 'COM_RSG2';

$doc = JFactory::getDocument();
$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/Maintenance.css");

// Purge / delete of database variables should be confirmed 
$script = " 
	jQuery(document).ready(function($){ 
		$('.consolidateDB').on('click', function () { 
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE') . "'); 
		}); 

		$('.regenerateThumbs').on('click', function () { 
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE') . "'); 
		}); 

		$('.optimizeDB').on('click', function () { 
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE') . "'); 
		}); 

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
  * Used by showCP to generate buttons
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
				<figure class="rsg2-icon">
					<?php echo JHtml::image('administrator/components/com_rsgallery2/images/'.$image, $text); ?>
					<figcaption class="rsg2-text">
						<span class="maint-title"><?php echo $title;?></span>
						<br>
						<span class="maint-text"><?php echo $text;?></span>
					</figcaption>
				</figure>
			</a>
		</div>
<?php
}

 /**
  * Used by showCP to generate buttons
  * @param string $link URL for button link
  * @param string $imageClass Image name for button image
  * @param string $title Command title
  * @param string $text Command explaining text
  * @param string $addClass
  */
function quickIconMoonBar( $link, $imageClass, $title, $text = "", $addClass = '' ) {
	?>
	<div class="rsg2-icon-bar">
		<a href="<?php echo $link; ?>" class="<?php echo $addClass; ?>" >
			<figure class="rsg2-icon">
				<span class="<?php echo $imageClass ?>" style="font-size:40px;"></span>
				<figcaption class="rsg2-text">
					<span class="maint-title"><?php echo $title;?></span>
					<br>
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
				<div class="row span6 rsg2-container-icon-set">
					<div class="icons-panel repair">
						<div class="row-fluid">
							<div class="icons-panel-title repairZone">
								<h3>
									<?php echo JText::_('COM_RSGALLERY2_REPAIR');?>
								</h3>
							</div>
							<div class='icons-panel-info'>
								<strong>
									<?php echo JText::_('COM_RSGALLERY2_FUNCTIONS_MAY_CHANGE_DATA');?>
								</strong>
							</div>
							<?php
							$link = 'index.php?option=com_rsgallery2&amp;view=config&amp;layout=RawView';
							quickiconBar( $link, 'menu.png', JText::_('COM_RSGALLERY2_CONFIGURATION_VARIABLES'),
								JText::_('COM_RSGALLERY2_CONFIG_MINUS_VIEW_TXT').'                        ',
								// JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW').'                        ',
								'New: viewConfigPlain');
							?>


							<?php
							if($this->UserIsRoot ) {
							?>

								<?php
								$link = 'index.php?option=com_rsgallery2&amp;view=config';
								quickiconBar( $link, 'menu.png', JText::_('COM_RSGALLERY2_CONFIGURATION'),
									JText::_('COM_RSGALLERY2_CONFIG_MINUS_VIEW_TXT').'                        ',
									'New: Config Standard (Prepared but not Ready)');
								?>

								<?php
								//$link = 'index.php?option=com_rsgallery2&amp;task=maintenance.consolidateDB';
								$link = 'index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=consolidateDB';
								quickiconBar($link, 'blockdevice.png',
									JText::_('COM_RSGALLERY2_MAINT_CONSOLDB'), JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT'),
									'consolidateDB');
								?>
								<?php
								$link = 'index.php?option=com_rsgallery2&amp;task=maintenance.consolidateDB';
								quickiconBar($link, 'blockdevice.png',
									JText::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE'), JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT'),
									'consolidateDB');
								?>
								<?php
								//$link = 'index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=regenerateThumbs';
								//$link =  'index.php?option=com_rsgallery2&amp;task=maintenance.regenerateThumbs';
								$link =  'index.php?option=com_rsgallery2&amp;view=maintRegenerateImages';

								quickIconMoonBar ($link, 'icon-scissors icon-remove',
									JText::_('COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY'), JText::_('COM_RSGALLERY2_MAINT_REGEN_TXT'),
									'regenerateThumbs');
								?>
								<?php
								// $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.optimizeDB';
								$link = 'index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=optimizeDB';
								quickiconBar($link, 'db_optimize.png', JText::_('COM_RSGALLERY2_MAINT_OPTDB'),
									JText::_('COM_RSGALLERY2_MAINT_OPTDB_TXT'),
									'optimizeDB');
								?>
								<?php
								$link = 'index.php?option=com_rsgallery2&amp;task=maintenance.compareDb2SqlFile';
								//$link = 'index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=CompareDb2SqlFile';
								//$link = 'index.php?option=com_rsgallery2&amp;task=compareDb2SqlFile';
								quickiconBar($link, 'db_optimize.png', JText::_('COM_RSGALLERY2_COMPARE_DB_TO_SQL_FILE'),
									JText::_('COM_RSGALLERY2_COMPARE_DB_TO_SQL_DESC'),
									'compareDb2SqlFile');
								?>

							<?php
							}
							?>

							<?php
							$link = 'index.php?option=com_rsgallery2&rsgOption=installer';

							quickIconMoonBar( $link, 'icon-scissors clsTemplate', JText::_('COM_RSGALLERY2_TEMPLATE_MANAGER'),
								JText::_('COM_RSGALLERY2_TEMPLATE_EXPLANATION'),
								'templateManager');
							?>

						</div>
					</div>
				</div>
									
				<div class="row-fluid span6 rsg2_container_icon_set">
					<div class="icons-panel danger">
						<div class="row-fluid">
							<div class="icons-panel-title dangerZone">
								<h3>
									<?php echo JText::_('COM_RSGALLERY2_DANGER_ZONE');?>
								</h3>
							</div>
							<?php
								if( $this->UserIsRoot ) {
							?>
									<div class='icons-panel-info'>
										<strong>
											<?php echo JText::_('COM_RSGALLERY2_ONLY_WHEN_YOU_KNOW_WHAT_YOU_ARE_DOING'); ?>
										</strong>
									</div>

									<?php
									// $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.viewConfigPlain';
									// $link = 'index.php?option=com_rsgallery2&amp;view=configRaw';
									$link = 'index.php?option=com_rsgallery2&amp;view=config&amp;layout=RawEdit';
									quickiconBar( $link, 'menu.png', JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'),
										JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT').'                        ',
										'New: Config Standard (Prepared but not Ready)');
									?>

									<?php
									//$link = 'index.php?option=com_rsgallery2&amp;view=configRawEdit';
									//$link = 'index.php?option=com_rsgallery2&amp;view=configRaw&amp;layout=edit';
									$link = 'index.php?option=com_rsgallery2&task=config_rawEdit';
									quickiconBar($link, 'menu.png', JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT'),
										JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT'),
										'editConfigRaw');
									?>
									<?php
										// if($Rsg2DebugActive){
									?>

											<?php
											//$link = 'index.php?option=com_rsgallery2&amp;task=maintenance.purgeImagesAndData';
											$link = 'index.php?option=com_rsgallery2&task=purgeEverything';
											quickiconBar($link, 'media_DelItems.png', JText::_('COM_RSGALLERY2_PURGEDELETE_EVERYTHING'),
												JText::_('COM_RSGALLERY2_PURGEDELETE_EVERYTHING_TXT'),
												'purgeImagesAndData');
											?>
											<?php
											//$link = 'index.php?option=com_rsgallery2&amp;task=maintenance.removeImagesAndData';
											$link = 'index.php?option=com_rsgallery2&task=reallyUninstall';
											quickiconBar($link, 'db_DelItems.png', JText::_('COM_RSGALLERY2_C_REALLY_UNINSTALL'),
												JText::_('COM_RSGALLERY2_C_REALLY_UNINSTALL_TXT'),
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
            </div>

            <!--div class='rsg2-clr'>&nbsp;</div -->

        </div>

        <div>
			<input type="hidden" name="option" value="com_rsgallery2" />
			<input type="hidden" name="rsgOption" value="maintenance" />

            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>

