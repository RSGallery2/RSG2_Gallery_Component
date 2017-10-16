<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLibAbstract.php';

/**
 * Single image model
 * Db functions
 *
 * @since 4.3.0
 */
class Rsgallery2ImageFile
{
	/**
	 * @var  externalImageLib contains external image library handler
	 */
	public $ImageLib = null;

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @since 4.3.0
	 */
	public function __construct($NewImageLib=null)
	{
		global $rsgConfig;

		// Image library is given
		if( ! empty ($NewImageLib))
		{
			$this->ImageLib = $NewImageLib;
		}
		else
		{
			// Use rsgConfig to determine which image library to load
			$graphicsLib    = $rsgConfig->get('graphicsLib');
			switch ($graphicsLib)
			{
				case 'gd2':
					// return GD2::resizeImage($source, $target, $targetWidth);
					require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_GD.php';
					$this->ImageLib = new external_GD2;
					break;
				case 'imagemagick':
					//return ImageMagick::resizeImage($source, $target, $targetWidth);
					require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_imagemagick.php';
					$this->ImageLib = new external_imagemagick;
					break;
				case 'netpbm':
					//return Netpbm::resizeImage($source, $target, $targetWidth);
					require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_netpbm.php';
					$this->ImageLib = new external_netpbm;
					break;
				default:
					require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_Empty.php';
					$this->ImageLib = new external_empty;
					//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_INVALID_GRAPHICS_LIBRARY') . $rsgConfig->get( 'graphicsLib' ));
					JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_INVALID_GRAPHICS_LIBRARY') . $rsgConfig->get('graphicsLib'), 'error');

					return false;
			}

		}





	}


}
