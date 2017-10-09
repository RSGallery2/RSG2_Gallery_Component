<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

/**
 * Encapsule calls of image functions to use different libraries
 * @package     ${NAMESPACE}
 *
 * @since 4.3.2
 */
abstract class externalImageLib {

    public $XXXLastName;
    public $XXXFirstName;
    public $XXXBirthDate;

    /**
     * image resize function
     *
     * @param string $source      full path of source image
     * @param string $target      full path of target image
     * @param int    $targetWidth width of target
     *
     * @return bool true if successfull, false if error
     */
    abstract static function resizeImage($source, $target, $targetWidth);

    /**
     * Creates a square thumbnail by first resizing and then cutting out the thumb
     *
     * @param string $source Full path of source image
     * @param string $target Full path of target image
     * @param int    $width  width of target
     *
     * @return bool true if successfull, false if error
     */
    abstract static function createSquareThumb($source, $target, $width);


    /**
     * detects if image library is available
     *
     * @return string user friendly string of library name and version if detected
     *                 empty if not detected,
     */
    abstract static function detect();







}

global $rsgconfig;