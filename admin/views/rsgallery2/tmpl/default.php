<?php // no direct access
defined( '_JEXEC' ) or die();

JHtml::_('behavior.tooltip');

global $Rsg2DebugActive;

/**
  * Used to generate buttons. Uses iconmoon font to display the main icon of the button
  * @param string $link URL for button link
  * @param string $imageClass Class name for icomoon image
  * @param string $text Text to show in button
  * @param string $addClass Additional class to style the element
  */
function RsgIconMoonButton( $link, $imageClass, $text, $addClass='') {
	?>
	<!--div class="span2 "-->
    <div style="float:left;">
		<div class="iconMoon">
			<a href="<?php echo $link; ?>">
				<div class="iconMoonImage<?php echo ' '.$addClass; ?>">

					<!--span class="<?php echo $imageClass ?>" style="font-size:40px;"> </span-->
					<span class="<?php echo $imageClass ?>" style="font-size:40px;"></span>
					<!-- i class="icon-search"  style="font-size:24px;></i -->
					
				</div>
				<?php echo $text; ?>
			</a>
		</div>
	</div>
    <?php
}

/**
 * @param $infoGalleries
 */
function DisplayInfoGalleries ($infoGalleries) {

    // exit if no data given
    if (count($infoGalleries) == 0)
    {
        echo JText::_('COM_RSGALLERY2_NO_NEW_GALLERIES');
        return;
    }

    // Header ----------------------------------

    echo '<table class="table table-striped table-condensed">';
    echo '    <caption>'.JText::_('COM_RSGALLERY2_MOST_RECENTLY_ADDED_GALLERIES').'</caption>';
    echo '    <thead>';
    echo '        <tr>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_GALLERY').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_USER').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_ID').'</th>';
    echo '        </tr>';
    echo '    </thead>';

        //--- data ----------------------------------

    echo '    <tbody>';

    foreach ($infoGalleries as $GalleryInfo) {

        echo '        <tr>';
        echo '            <td>' . $GalleryInfo['name'] . '</td>';
        echo '            <td>' . $GalleryInfo['user'] . '</td>';
        echo '            <td>' . $GalleryInfo['id'] . '</td>';
        echo '        </tr>';
    }
    echo '    </tbody>';

    //--- footer ----------------------------------
    echo '</table>';

    return;
}

function DisplayInfoImages ($infoImages) {

    // exit if no data given
    if (count($infoImages) == 0)
    {
        echo JText::_('COM_RSGALLERY2_NO_NEW_IMAGES');
        return;
    }

    // Header ----------------------------------

    echo '<table class="table table-striped table-condensed">';
    echo '    <caption>'.JText::_('COM_RSGALLERY2_MOST_RECENTLY_ADDED_ITEMS').'</caption>';
    echo '    <thead>';
    echo '        <tr>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_FILENAME').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_GALLERY').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_DATE').'</th>';
    echo '            <th>'.JText::_('COM_RSGALLERY2_USER').'</th>';
    echo '        </tr>';
    echo '    </thead>';

    //--- data ----------------------------------

    echo '    <tbody>';

	foreach ($infoImages as $ImgInfo) {

        echo '        <tr>';
        echo '            <td>' . $ImgInfo['name'] . '</td>';
        echo '            <td>' . $ImgInfo['gallery'] . '</td>';
        echo '            <td>' . $ImgInfo['date'] . '</td>';
        echo '            <td>' . $ImgInfo['user'] . '</td>';
        echo '        </tr>';
    }
    echo '    </tbody>';

    //--- footer ----------------------------------
    echo '</table>';

	return;
}


function DisplayInfoRsgallery2 ($Rsg2Version)
{
    /*
        // Rsgallery Info area
        echo '<div class="row">';
    */
    // Logo

    echo '<row>';
    echo '<div class="rsg2logo-container">';
    echo '<div class="rsg2logo">';
    echo '  <img src="'.JUri::root(true).'/administrator/components/com_rsgallery2/images/rsg2-logo.png" align="middle" alt="RSGallery2 logo" />';
    echo '</div>';
    /**/
//    echo '<table class="table table-striped">';
    echo '<table class="table table-striped table-condensed">';
//    echo '<table class="table">';
//    echo '<table>';
//    echo '<table>';
    echo '    <tbody>';
    /**/
    /**/
    echo '        <tr>';
    echo '            <td>' . JText::_('COM_RSGALLERY2_INSTALLED_VERSION') . ': ' . '</td>';
    echo '            <td>';
    echo '                <a href="" target="_blank" title="' . JText::_('COM_RSGALLERY2_VIEW_CHANGE_LOG') . '": >' . $Rsg2Version . '</a>';
    echo '            </td>';
    echo '        </tr>';
    /**/
    // License
    echo '        <tr>';
    echo '            <td>' . JText::_('COM_RSGALLERY2_LICENSE') . ': ' . '</td>';
    echo '            <td>';
    echo '               <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank" title="'.JText::_('COM_RSGALLERY2_JUMP_TO_GNU_ORG').'" >GNU GPL</a>';
    echo '            </td>';
    echo '        </tr>';
    /**/
    /**/
    // Forum
    echo '        <tr>';
    echo '            <td>' . JText::_('COM_RSGALLERY2_FORUM') . '</td>';
    echo '            <td>';
    echo '                <a href="http://www.rsgallery2.nl/" target="_blank" '.' title="'.JText::_('COM_RSGALLERY2_JUMP_TO_FORUM').'" >www.rsgallery2.nl</a>';
    echo '            </td>';
    echo '        </tr>';
    /**/
    // Documentation
    echo '        <tr>';
    echo '            <td>' . JText::_('COM_RSGALLERY2_DOCUMENTATION') . '</td>';
    echo '            <td>';
    echo '                <a href="http://joomlacode.org/gf/project/rsgallery2/frs/?action=FrsReleaseBrowse&frs_package_id=6273" target="_blank"';
    echo '                    title="'.JText::_('COM_RSGALLERY2_JUMP_TO_DOCUMENTATION').'" >'.JText::_('COM_RSGALLERY2_DOCUMENTATION').'</a>';
    echo '            </td>';
    echo '        </tr>';

    /**/
    echo '    </tbody>';
    echo '</table>';
    /**/
    echo '</div>';

    echo '</row>';

    echo '<br>';
    return;
}

// public static $extension = 'COM_RSGallery2';

$doc = JFactory::getDocument();
//$doc->addStyleSheet ("administrator/components/com_rsgallery2/admin.rsgallery2.css");
//$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/admin.rsgallery2.css");
//$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/admin.rsg2.01.old.css");
$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/admin.rsg2.css");


?>

    <form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=rsg2'); ?>" method="post" name="adminForm" id="adminForm">

    <?php if (!empty( $this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
    <?php else : ?>
        <div id="j-main-container">
    <?php endif;?>

        <div class="row greyBackground">
            <div class="span12">
                <div class="row-fluid">
                    <?php
                        if ( $this->UserIsRoot ){
                            //$link = 'index.php?option=com_rsgallery2&amp;view=config';
                            $link = 'index.php?option=com_rsgallery2&rsgOption=config&task=showConfig';
                            //RsgButton( $link, 'config.png',  JText::_('COM_RSGALLERY2_CONFIGURATION') );
                            RsgIconMoonButton( $link, 'icon-equalizer clsProperties',  JText::_('COM_RSGALLERY2_CONFIGURATION') );
                        }

                        //$link = 'index.php?option=com_rsgallery2&amp;view=galleries';
                        $link = 'index.php?option=com_rsgallery2&rsgOption=galleries';
                        //RsgButton( $link, 'categories.png', JText::_('COM_RSGALLERY2_MANAGE_GALLERIES') );
                        RsgIconMoonButton( $link, 'icon-images clsGalleries', JText::_('COM_RSGALLERY2_MANAGE_GALLERIES') );

                        $link = 'index.php?option=com_rsgallery2&view=upload';
                        //RsgButton( $link, 'upload.png', JText::_('COM_RSGALLERY2_UPLOAD') );
                        RsgIconMoonButton( $link, 'icon-upload clsUpload', JText::_('COM_RSGALLERY2_UPLOAD') );
/**/
                        // $link = 'index.php?option=com_rsgallery2&amp;view=images';
                        $link = 'index.php?option=com_rsgallery2&rsgOption=images&task=view_images';
                        //RsgButton( $link, 'mediamanager.png', JText::_('COM_RSGALLERY2_MANAGE_IMAGES') );
                        RsgIconMoonButton( $link, 'icon-image clsImages', JText::_('COM_RSGALLERY2_MANAGE_IMAGES') );
/**/					
                        if ( $this->UserIsRoot ){
                            $link = 'index.php?option=com_rsgallery2&view=maintenance';
							//RsgButton( $link, 'maintenance.png', JText::_('COM_RSGALLERY2_MAINTENANCE'));
							RsgIconMoonButton( $link, 'icon-screwdriver clsMaintenance', JText::_('COM_RSGALLERY2_MAINTENANCE'));
						}
/**/
                    ?>
                </div>
			</div>
		
			<br>
			<br>
			
			<div class="span12">
				<div class="row-fluid">
					<div class="span6">
						<?php
							DisplayInfoRsgallery2 ($this->Rsg2Version);
						?>			
					</div>
				</div>
			</div>
			<div class="span12">
				<div class="row-fluid">
					<div class="span4 clsInfoAccordion">
						 <?php
							echo JHtml::_('bootstrap.startAccordion', 'slide-example', array('active' => 'slide1', 'toggle' => 'false' ));
							echo JHtml::_('bootstrap.addSlide', 'slide-example', JText::_('COM_RSGALLERY2_GALLERIES'), 'slide1');

								// Info about last uploaded galleries
								DisplayInfoGalleries ($this->LastGalleries);

							echo JHtml::_('bootstrap.endSlide');
							echo JHtml::_('bootstrap.endAccordion');
						?>
					</div>
					<div class="span8 clsInfoAccordion">
						 <?php
							echo JHtml::_('bootstrap.startAccordion', 'slide-example2', array('active' => 'slide2'));
							echo JHtml::_('bootstrap.addSlide', 'slide-example2', JText::_('COM_RSGALLERY2_IMAGES'), 'slide2');

								// info about last uploaded images
								DisplayInfoImages ($this->LastImages);

							echo JHtml::_('bootstrap.endSlide');
							echo JHtml::_('bootstrap.endAccordion');							
						?>
					</div>
                </div>
            </div>
            <br>
            <div class="span12">
                <div class="row-fluid">
					<div class="span6 clsInfoAccordion">
						 <?php
							echo JHtml::_('bootstrap.startAccordion', 'slide-example3', array('active' => 'slide1'));
							echo JHtml::_('bootstrap.addSlide', 'slide-example3', JText::_('COM_RSGALLERY2_CREDITS'), 'slide3');
						?>
						<div id='rsg2-credits'>
							<?php			
								echo $this->Credits;
							?>
						</div>							
						<?php
							echo JHtml::_('bootstrap.endSlide');
							echo JHtml::_('bootstrap.endAccordion');
						?>
					</div>
				</div>
			</div>					
		</div>
	</div>
	
    <div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>	
	
	<?php			
		echo $this->FooterText;
	?>
</form>

