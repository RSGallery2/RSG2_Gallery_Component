<?php
	defined('_JEXEC') or die();

global $rsgConfig;

	$item = $this->currentItem;
	$watermark = $rsgConfig->get('watermark');

	$imageOriginalUrl = $watermark ? waterMarker::showMarkedImage( $item->name, 'original' ) : 
		//imgUtils::getImgOriginalPath( $item->name );
		imgUtils::getImgOriginal( $item->name );
		
	$imageUrl = $watermark ? waterMarker::showMarkedImage( $item->name ) : 
		//imgUtils::getImgDisplayPath( $item->name );
		imgUtils::getImgDisplay( $item->name );

	switch ($rsgConfig->get('displayPopup')) {
		//No popup
		case 0:{
            ?>
            <img class="rsg2-displayImage" src="<?php echo $imageUrl;?>" alt="<?php echo $item->name; ?>" title="<?php echo $item->name; ?>" />
    		<?php
            break;
        }
        //Normal popup
        case 1:{
            ?>
            <a href="<?php echo $imageOriginalUrl; ?>" target="_blank">
                <img class="rsg2-displayImage" src="<?php echo $imageUrl;?>" alt="<?php echo $item->name; ?>" title="<?php echo $item->name; ?>" />
            </a>
			<?php

            break;
		}
		case 2:{
            // ToDo: Place it on start ???
			JHTML::_('behavior.modal');
			?>

            <a class="modal" href="<?php echo $imageOriginalUrl; ?>">
                <img class="rsg2-displayImage" src="<?php echo $imageUrl;?>" alt="<?php echo $item->name; ?>" title="<?php echo $item->name; ?>" />
            </a>

            <?php
            /**
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration($jsModal);
            /**/
			break;
		}
	}

?>