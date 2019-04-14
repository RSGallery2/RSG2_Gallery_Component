<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

// JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip');
// ToDo: Activate tooltips on every button

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root(true) . '/administrator/components/com_rsgallery2/css/Maintenance.css');

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
			return confirm('" . JText::_('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE') . "'); 
		}); 
	}); 
";
$doc->addScriptDeclaration($script);

/**
 * Used to generate buttons
 *
 * @param string $link  URL for button link
 * @param string $image Image name for button image
 * @param string $title Command title
 * @param string $text  Command explaining text
 * @param string $addClass
 */
function quickiconBar($link, $image, $title, $text = "", $addClass = '')
{
	?>
	<div class="rsg2-icon-bar">
		<a href="<?php echo $link; ?>" class="<?php echo $addClass; ?>">
			<figure class="rsg2-old-icon">
				<?php echo JHtml::image('administrator/components/com_rsgallery2/images/' . $image, $text); ?>
				<figcaption class="rsg2-text">
					<span class="maint-title"><?php echo $title; ?></span>
					<!--br-->
					<span class="maint-text"><?php echo $text; ?></span>
				</figcaption>
			</figure>
		</a>
	</div>
	<?php
}

/**
 * Used to generate buttons with icomoon icon
 *
 * @param string $link       URL for button link
 * @param string $imageClass Image name for button image
 * @param string $title      Command title
 * @param string $text       Command explaining text
 * @param string $addClass
 */
/**
function quickIconMoonBar($link, $imageClass, $title, $text = "", $addClass = '')
{
	?>
	<div class="rsg2-icon-bar button">
		<a href="<?php echo $link; ?>" class="<?php echo $addClass; ?>">
			<figure class="rsg2-icon">
				<span class="<?php echo $imageClass ?>" style="font-size:40px;"></span>
				<figcaption class="rsg2-text">
					<span class="maint-title"><?php echo $title; ?></span>
					<!--br-->
					<span class="maint-text"><?php echo $text; ?></span>
				</figcaption>
			</figure>
		</a>
	</div>
	<?php
}

/**
 * Used to generate buttons with two icomoon icon
 *
 * @param string $link       URL for button link
 * @param string $imageClass1 Image name for button image 1
 * @param string $imageClass2 Image name for button image 2
 * @param string $title      Command title
 * @param string $text       Command explaining text
 * @param string $addClass
 *
 * @since 4.3.0
 */
/**
function quickTwoIconMoonBar($link, $imageClass1, $imageClass2, $title, $text = "", $addClass = '')
{
	?>
	<div class="rsg2-icon-bar">
		<a href="<?php echo $link; ?>" class="<?php echo $addClass; ?>">
			<figure class="rsg2-icon">
				<span class="<?php echo $imageClass1 ?> iconMoon01" style="font-size:30px;"></span>
				<span class="<?php echo $imageClass2 ?> iconMoon02" style="font-size:30px;"></span>
				<figcaption class="rsg2-text">
					<span class="maint-title"><?php echo $title; ?></span>
					<!--br-->
					<span class="maint-text"><?php echo $text; ?></span>
				</figcaption>
			</figure>
		</a>
	</div>
	<?php
}

/**
 * Used to generate buttons with multiple icomoon icons
 *
 * @param string $link       URL for button link
 * @param string $imageClass1 Image name for button image 1
 * @param string $imageClass2 Image name for button image 2
 * @param string $title      Command title
 * @param string $text       Command explaining text
 * @param string $addClass
 *
 * @since 4.3.0
 */
function quickIconsBar($link, $imageClasses=array(), $title, $text = "", $addClass = '')
{
	?>
    <div class="rsg2-icon-bar">
        <a href="<?php echo $link; ?>" class="<?php echo $addClass; ?>">
            <figure class="rsg2-icon">
                <?php
                foreach ($imageClasses as $Idx => $imageClass )
                {
	            ?>
                    <span class="<?php echo $imageClass ?> iconMoon0<?php echo $Idx+1?>" style="font-size:30px;"></span>
	            <?php
                }
	            ?>
                <figcaption class="rsg2-text">
                    <span class="maint-title"><?php echo $title; ?></span>
                    <!--br-->
                    <span class="maint-text"><?php echo $text; ?></span>
                </figcaption>
            </figure>
        </a>
    </div>
	<?php
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=maintenance'); ?>"
		method="post" name="adminForm" id="adminForm">

	<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
    <?php else : ?>
	<div id="j-main-container">
	<?php endif; ?>

        <div class="row-fluid grey-background">
            <div class="container-fluid grey-background">

                <div class="row span4 rsg2_container_icon_set">
                    <div class="icons-panel rsg2">
                        <div class="row-fluid">
                            <div class="icons-panel-title rsg2Zone">
                                <h3>
                                    <?php echo JText::_('COM_RSGALLERY2_RSGALLERY2_ZONE'); ?>
                                </h3>
                            </div>

                            <div class='icons-panel-info'>
                                <strong>
                                    <?php echo JText::_('COM_RSGALLERY2_RSGALLERY2_ZONE_DESC'); ?>
                                </strong>
                            </div>

                            <?php
                            $link = 'index.php?option=com_rsgallery2&amp;view=maintslideshows';
                            quickIconsBar($link, array ('icon-equalizer', 'icon-play'),
                                JText::_('COM_RSGALLERY2_SLIDESHOW_CONFIGURATION'),
                                JText::_('COM_RSGALLERY2_SLIDESHOWS_CONFIGURATION_DESC') . '                        ',
                                'viewConfigSlideshow');
                            ?>

                            <?php
                            $link = 'index.php?option=com_rsgallery2&amp;view=maintTemplates';
                            quickIconsBar($link, array ('icon-equalizer', 'icon-out-3'), //paragraph-justify
                                JText::_('COM_RSGALLERY2_TEMPLATE_CONFIGURATION'),
                                JText::_('COM_RSGALLERY2_TEMPLATES_CONFIGURATION_DESC') . '                        ',
                                'viewConfigTemplate');
                            ?>

                            <?php
                            $link = 'index.php?option=com_rsgallery2&amp;view=comments';
                            quickIconsBar($link, array('icon-comment', 'icon-list-2'),
                                JText::_('COM_RSGALLERY2_COMMENTS_LIST'),
                                JText::_('COM_RSGALLERY2_COMMENTS_TXT'),
                                'consolidateDB');
                            ?>


                            <?php
                            $link = 'index.php?option=com_rsgallery2&rsgOption=installer';

                            quickIconsBar($link, array('icon-scissors clsTemplate'),
                                JText::_('COM_RSGALLERY2_TEMPLATE_MANAGER'),
                                '<del>' . JText::_('COM_RSGALLERY2_MAINT_TEMPLATE_DESC') . '</del>',
                                'templateManager');
                            ?>

                        </div>
                    </div>

                    <?php
                    if ($this->rawDbActive)
                    {
                        ?>
                        <div class="icons-panel rawDb">
                            <div class="row-fluid">
                                <div class="icons-panel-title rawDbZone">
                                    <h3>
                                        <?php echo JText::_('COM_RSGALLERY2_RAW_DB_ZONE'); ?>
                                    </h3>
                                </div>

                                <div class='icons-panel-info'>
                                    <strong>
                                        <?php echo JText::_('COM_RSGALLERY2_RAW_DB_ZONE_DESCRIPTION'); ?>
                                    </strong>
                                </div>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=config&amp;layout=RawView';
                                quickIconsBar($link, array('icon-equalizer', 'icon-eye'),
                                    JText::_('COM_RSGALLERY2_CONFIGURATION_VARIABLES'),
                                    JText::_('COM_RSGALLERY2_CONFIG_MINUS_VIEW_TXT') . '                        ',
                                    'viewConfigRaw');
                                ?>

                                <?php
                                //$link = 'index.php?option=com_rsgallery2&amp;view=images';
                                $link = 'index.php?option=com_rsgallery2&amp;view=images&amp;layout=images_raw';
                                quickIconsBar($link, array('icon-image', 'icon-list-2'),
                                    JText::_('COM_RSGALLERY2_IMAGES_LIST'),
                                    JText::_('COM_RSGALLERY2_RAW_IMAGES_TXT'),
                                    'consolidateDB');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=galleries&amp;layout=galleries_raw';
                                quickIconsBar($link, array('icon-images', 'icon-list-2'),
                                    JText::_('COM_RSGALLERY2_GALLERIES_LIST'),
                                    JText::_('COM_RSGALLERY2_RAW_GALLERIES_TXT'),
                                    'consolidateDB');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=comments&amp;layout=comments_raw';
                                quickIconsBar($link, array('icon-comment', 'icon-list-2'),
                                    JText::_('COM_RSGALLERY2_COMMENTS_LIST'),
                                    JText::_('COM_RSGALLERY2_RAW_COMMENTS_TXT'),
                                    'consolidateDB');
                                ?>

                                <?php
                                /**
                            $link = 'index.php?option=com_rsgallery2&amp;view=acl_items&amp;layout=acls_raw';
                            quickIconsBar ($link, array('icon-eye-close', 'icon-list-2'),
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
                                    <?php echo JText::_('COM_RSGALLERY2_OUTDATED_ZONE'); ?>
                                </h3>
                            </div>

                            <div class='icons-panel-info'>
                                <strong>
                                    <?php echo JText::_('COM_RSGALLERY2_OUTDATED_ZONE_DESC'); ?>
                                </strong>
                            </div>

                            <?php
                            $link = 'index.php?option=com_rsgallery2&amp;rsgOption=config&amp;task=showConfig';
                            quickiconBar($link, 'config.png',
                                JText::_('COM_RSGALLERY2_CONFIGURATION'),
                                JText::_('        '), // COM_RSGALLERY2_CONFIG_MINUS_VIEW
                                'test');
                            ?>

                            <?php
                            $link = 'index.php?option=com_rsgallery2&amp;rsgOption=galleries';
                            quickiconBar($link, 'categories.png',
                                JText::_('COM_RSGALLERY2_MANAGE_GALLERIES'),
                                JText::_('        '),
                                'test');
                            ?>


                            <?php
                            $link = 'index.php?option=com_rsgallery2&amp;rsgOption=images&task=view_images';
                            quickiconBar($link, 'mediamanager.png',
                                JText::_('COM_RSGALLERY2_MANAGE_IMAGES'),
                                JText::_('        '),
                                'test');
                            ?>


                            <?php
                            $link = 'index.php?option=com_rsgallery2&rsgOption=images&task=upload';
                            quickiconBar($link, 'upload.png',
                                JText::_('COM_RSGALLERY2_UPLOAD_SINGLE_IMAGES'),
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
                            if ($this->UserIsRoot)
                            {
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
                                    <?php echo JText::_('COM_RSGALLERY2_REPAIR_ZONE'); ?>
                                </h3>
                            </div>
                            <div class='icons-panel-info'>
                                <strong>
                                    <?php echo JText::_('COM_RSGALLERY2_FUNCTIONS_MAY_CHANGE_DATA'); ?>
                                </strong>
                            </div>

                            <?php
                            if ($this->UserIsRoot)
                            {
                                ?>

                                <?php
                                // $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.consolidateDB';
                                $link = 'index.php?option=com_rsgallery2&amp;view=maintConsolidateDB';
                                quickIconsBar($link, array('icon-database', 'icon-checkbox-checked'),
                                    JText::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE'),
                                    JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT'),
                                    'consolidateDB');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=config&amp;layout=RawEdit';
                                quickIconsBar($link, array('icon-equalizer', 'icon-edit'),
                                    JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'),
                                    JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT'),
                                    'editConfigRaw');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;task=config.reset2default';
                                quickIconsBar($link, array('icon-equalizer', 'icon-redo'),
                                    JText::_('COM_RSGALLERY2_CONFIG_RESET_TO_DEFAULT'),
                                    JText::_('COM_RSGALLERY2_CONFIG_RESET_TO_DEFAULT_DESC'),
                                    'editConfigRaw');
                                ?>

                                <?php
                                /**
                                $link = 'index.php?option=com_rsgallery2&amp;view=maintUploadLeftOverImages';
                                quickIconsBar($link, array('icon-image', 'icon-upload'),
                                    JText::_('COM_RSGALLERY2_MAINT_UPLOAD_LEFT_OVER_IMAGES'),
                                    JText::_('COM_RSGALLERY2_MAINT_UPLOAD_LEFT_OVER_IMAGES_DESC') . '                        ',
                                    'uploadLeftOverImages');
                                /**/
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=maintRegenerateImages';
                                quickIconsBar($link, array('icon-image', 'icon-wand'),
                                    JText::_('COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY'),
                                    JText::_('COM_RSGALLERY2_MAINT_REGEN_TXT') . '                        ',
                                    'regenerateThumbs');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;task=maintSql.optimizeDB';
                                quickIconsBar($link, array('icon-database', 'icon-clock'), // 'icon-checkbox-checked'
                                    JText::_('COM_RSGALLERY2_MAINT_OPTDB'),
                                    JText::_('COM_RSGALLERY2_MAINT_OPTDB_TXT'),
                                    'optimizeDB');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.repairImagePermissions';
                                quickIconsBar($link, array('icon-image', 'icon-unlock'), // 'icon-'
                                    JText::_('COM_RSGALLERY2_MAINT_REPAIR_IMAGE_PERMISSION'),
                                    JText::_('COM_RSGALLERY2_MAINT_REPAIR_IMAGE_PERMISSION_DESC'),
                                    'optimizeDB');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;task=MaintRemoveLogFiles.DeleteLogFiles';
                                quickIconsBar($link, array('icon-file-check', 'icon-file-remove'),
                                JText::_('COM_RSGALLERY2_REMOVE_LOG_FILES'),
                                JText::_('COM_RSGALLERY2_REMOVE_LOG_FILES_TXT'),
                                'consolidateDB');
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
                                    <?php echo JText::_('COM_RSGALLERY2_DANGER_ZONE'); ?>
                                </h3>
                            </div>
                            <?php
                            if ($this->dangerActive)
                            {
                                ?>
                                <div class='icons-panel-info'>
                                    <strong>
                                        <?php echo JText::_('COM_RSGALLERY2_ONLY_WHEN_YOU_KNOW_WHAT_YOU_ARE_DOING'); ?>
                                    </strong>
                                </div>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;task=MaintCleanUp.purgeImagesAndData';
                                //$link = 'index.php?option=com_rsgallery2&task=purgeEverything';
                                quickIconsBar($link, array('icon-database ', 'icon-purge'),
                                    JText::_('COM_RSGALLERY2_PURGEDELETE_EVERYTHING'),
                                    JText::_('COM_RSGALLERY2_PURGEDELETE_EVERYTHING_TXT'),
                                    'purgeImagesAndData');
                                ?>
                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;task=MaintCleanUp.removeImagesAndData';
                                //$link = 'index.php?option=com_rsgallery2&task=reallyUninstall';
                                quickIconsBar($link, array('icon-database ', 'icon-delete'),
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
                if ($this->upgradeActive)
                {
                    ?>
                    <div class="row span4 rsg2_container_icon_set">
                        <div class="icons-panel upgrade">
                            <div class="row-fluid">
                                <div class="icons-panel-title upgradeZone">
                                    <h3>
                                        <?php echo JText::_('COM_RSGALLERY2_UPGRADE_ZONE'); ?>
                                    </h3>
                                </div>

                                <div class='icons-panel-info'>
                                    <strong>
                                        <?php echo JText::_('COM_RSGALLERY2_UPGRADE_ZONE_DESCRIPTION'); ?>
                                    </strong>
                                </div>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=maintDatabase';
                                quickIconsBar($link, array('icon-database', 'icon-book'),
                                    JText::_('JLIB_FORM_VALUE_SESSION_DATABASE'),
                                    JText::_('COM_RSGALLERY2_DATABASE_REPAIR_DESC'),
                                    'compareDb2SqlFile');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;task=maintSql.createGalleryAccessField';
                                quickIconsBar($link, array('icon-database', 'icon-wrench'),
                                    JText::_('COM_RSGALLERY2_CREATE_GALLERY_ACCESS_FIELD'),
                                    JText::_('COM_RSGALLERY2_CREATE_GALLERY_ACCESS_FIELD_DESCRIPTION'),
                                    'createGalleryAccessField');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;task=maintenance.delete_1_5_LangFiles';
                                quickIconsBar($link, array('icon-delete', 'icon-flag'),
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


                <?php
                if ($this->testActive)
                {
                    ?>
                    <div class="row span4 rsg2_container_icon_set">
                        <div class="icons-panel test">
                            <div class="row-fluid">
                                <div class="icons-panel-title testZone">
                                    <h3>
                                        <?php echo JText::_('COM_RSGALLERY2_TEST_ZONE'); ?>
                                    </h3>
                                </div>

                                <div class='icons-panel-info'>
                                    <strong>
                                        <?php echo JText::_('COM_RSGALLERY2_TEST_ZONE_DESCRIPTION'); ?>
                                    </strong>
                                </div>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=comments';
                                quickIconsBar($link, array('icon-comment', 'icon-list-2'),
                                    JText::_('COM_RSGALLERY2_COMMENTS_LIST'),
                                    JText::_('COM_RSGALLERY2_COMMENTS_TXT'),
                                    'consolidateDB');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=acl_items';
                                quickIconsBar($link, array('icon-eye-close', 'icon-list-2'),
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
                if ($this->developActive)
                {
                    ?>
                    <div class="row span4 rsg2_container_icon_set">
                        <div class="icons-panel developer">
                            <div class="row-fluid">
                                <div class="icons-panel-title developerZone">
                                    <h3>
                                        <?php echo JText::_('COM_RSGALLERY2_DEVELOPER_ZONE'); ?>
                                    </h3>
                                </div>
                                <div class='icons-panel-info'>
                                    <strong>
                                        <?php echo JText::_('COM_RSGALLERY2_ONLY_WHEN_YOU_KNOW_WHAT_YOU_ARE_DOING'); ?>
                                    </strong>
                                </div>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=maintRemoveInstallLeftOvers';
                                quickIconsBar($link, array('icon-upload', 'icon-file-remove'),
                                    JText::_('COM_RSGALLERY2_REMOVE_INSTALLATION_LEFT_OVERS'),
                                    JText::_('COM_RSGALLERY2_REMOVE_INSTALLATION_LEFT_OVERS_DESC'),
                                    'consolidateDB');
                                ?>

                                <?php
                                // ToDo: ? Move to comments ?
                                $link = 'index.php?option=com_rsgallery2&amp;task=maintSql.updateCommentsVoting';
                                quickIconsBar($link, array('icon-comment', 'icon-wand'),
                                    JText::_('COM_RSGALLERY2_UPDATE_COMMENTS_AND_VOTING'),
                                    JText::_('COM_RSGALLERY2_UPDATE_COMMENTS_AND_VOTING_TXT'),
                                    'consolidateDB');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=develop&amp;layout=InitUpgradeMessage';
                                quickIconsBar($link, array('icon-eye-open', 'icon-expand'),
                                    JText::_('Test Install/Update message'),
                                    JText::_('Check the output result of the install finish and upgrade finish result view part'),
                                    'consolidateDB');
                                ?>

                                <?php
                                $link = 'index.php?option=com_rsgallery2&amp;view=develop&amp;layout=DebugGalleryOrder';
                                quickIconsBar($link, array('icon-expand-2', 'icon-contract-2'),
                                    JText::_('COM_RSGALLERY2_DEBUG_GALLERY_ORDER'),
                                    JText::_('COM_RSGALLERY2_DEBUG_GALLERY_ORDER_DESC'),
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

