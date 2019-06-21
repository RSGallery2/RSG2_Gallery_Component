<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2019 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');

/**
 * Supports upload of images with access to last galleries and ...
 *
 * @since 4.3.0
 */
class rsgallery2ModelUpload extends JModelLegacy  // JModelForm
{
    protected $text_prefix = 'COM_RSGallery2';


}
