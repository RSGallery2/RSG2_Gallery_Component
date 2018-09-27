<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

global $rsgConfig;

//JHtml::_('behavior.core');

echo "layout classic: image: <br>";

$image = json_encode($displayData['item']);
echo "Image: " . $gallery;


//$item      = $this->currentItem;

// ToDo: Prepare URL and watermark databefore

// $watermark = $rsgConfig->get('watermark');

/*
$imageOriginalUrl = $watermark ? waterMarker::showMarkedImage($item->name, 'original') :
	//imgUtils::getImgOriginalPath( $item->name );
	imgUtils::getImgOriginal($item->name);
/**/

/*
imageDisplayUrl = $watermark ? waterMarker::showMarkedImage($item->name) :
	//imgUtils::getImgDisplayPath( $item->name );
	imgUtils::getImgDisplay($item->name);
/**/

$imageDisplayUrl = $image->imageDisplayUrl;
$imageOriginalUrl = $image->imageOriginalUrl;     
    
switch ($rsgConfig->get('displayPopup'))
{
	//No popup
	case 0:
	{
		?>
		<img class="rsg2-displayImage" src="<?php echo $imageDisplayUrl; ?>"
             alt="<?php echo $item->name; ?>" title="<?php echo $item->name; ?>" />
		<?php
		break;
	}
	//Normal popup
	case 1:
	{
		?>
		<a href="<?php echo $imageOriginalUrl; ?>" target="_blank">
			<img class="rsg2-displayImage" src="<?php echo imageDisplayUrl; ?>"
                 alt="<?php echo $item->name; ?>" title="<?php echo $item->name; ?>" />
		</a>
		<?php

		break;
	}
	case 2:
	{
		// ToDo: Place it on start ???
		JHTML::_('behavior.modal');
		?>

		<a class="modal" href="<?php echo $imageOriginalUrl; ?>">
			<img class="rsg2-displayImage" src="<?php echo imageDisplayUrl; ?>"
                 alt="<?php echo $item->name; ?>" title="<?php echo $item->name; ?>" />
		</a>

		<?php
		/**
		 * $doc = JFactory::getDocument();
		 * $doc->addScriptDeclaration($jsModal);
		 * /**/
		break;
	}
}

?>