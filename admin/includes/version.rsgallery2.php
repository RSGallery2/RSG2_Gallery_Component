<?php
/**
 * This class handles version management for RSGallery2
 *
 * @version       $Id: version.rsgallery2.php 1099 2012-10-08 11:37:43Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

// no direct access
defined('_JEXEC') or die();

/**
 * Version information class. Lives from the manifest file which it loads
 * (formely based on the Joomla version class)
 *
 * @package RSGallery2
 */
class rsgalleryVersion
{
    // ToDO: Create singleton

    //Note: also set version number in config.class.php function rsgConfig
    /** @var string Product */
    var $PRODUCT = 'RSGallery2';
    /** @var int Main Release Level */
    var $RELEASE = '4.0.999';                //Main Release Level: x.y.z
    /** @var string Development Status */
//    var $DEV_STATUS = 'dev';
    /** @var int build Number */
    // var $BUILD      = 'SVN 1098';
//    var $BUILD      = 'GitHub';
    /** @var string Codename */
//    var $CODENAME   = '';
    /** @var string Date */
    var $RELDATE = '28 Feb. 2016';
    /** @var string Time */
//    var $RELTIME    = '14:00';
    /** @var string Timezone */
//    var $RELTZ      = 'UTC';
    /** @var string Copyright Text */
    var $COPYRIGHT = '&copy; 2005 - 2018';
    /** @var string URL */
    var $URL = '<strong><a class="rsg2-footer" href="http://www.rsgallery2.org">RSGallery2</a></strong>';
    /** @var string Whether site is a production = 1 or demo site = 0: 1 is default */
//    var $SITE       = 1;
    /** @var string Whether site has restricted functionality mostly used for demo sites: 0 is default */
//    var $RESTRICT   = 0;

    function __construct()
    {
        //--- collect data from manifest -----------------
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('manifest_cache');
        $query->from($db->quoteName('#__extensions'));
        //$query->where('name = "com_rsgallery2"');
        $query->where('element = "com_rsgallery2"');
        $db->setQuery($query);

        // manifest Array (
        //	[name] => com_rsg2
        //	[type] => component
        //	[creationDate] => July 2014
        //	[author] => RSGallery2 Team
        //	[copyright] => (c) 2014 RSGallery2 Team
        //	[authorEmail] => team2@rsgallery2.org
        //	[authorUrl] => http://www.rsgallery2.org
        //	[version] => 1.0.2
        //	[description] => COM_RSGALLERY2_XML_DESCRIPTION
        //	[group] =>
        //	[filename] => rsg2
        //)

        $manifest = json_decode($db->loadResult(), true);

        //--- assign data from manifest -----------------

        //	[name] => com_rsg2
        $this->PRODUCT = $manifest['name'];
        //	[creationDate] => July 2014
        $this->RELDATE = $manifest['creationDate'];
        //	[author] => RSGallery2 Team
        $this->AUTHOR = $manifest['author'];
        //	[copyright] => (c) 2014 RSGallery2 Team
        $this->COPYRIGHT = $manifest['copyright'];
        //	[authorEmail] => team@rsgallery2.org
        $this->EMAIL = $manifest['authorEmail'];
        //	[authorUrl] => http://www.rsgallery2.org
        // Old: = '<strong><a class="rsg2-footer" href="http://www.rsgallery2.org">RSGallery2</a></strong>';
        $this->URL = $manifest['authorUrl'];
        //	[version] => 1.0.2
        $this->RELEASE = $manifest['version'];
        //	[description] => COM_RSGALLERY2_XML_DESCRIPTION
        $this->DESCRIPTION = $manifest['description'];
    }

    /**
     * @return string Long format version
     */
    function getLongVersion()
    {
        return $this->PRODUCT . '     '
            . ' [' . $this->RELEASE . '] '
//            . $this->DEV_STATUS . ' '
//            . $this->AUTHOR . ' '
//            . ' [ '.$this->CODENAME .' ] ' . ' '
            . '(' . $this->RELDATE . ')' . ' '//            . $this->RELTIME .' '. $this->RELTZ
            ;
    }

    /**
     * @return string Short version format
     */
    function getShortVersion()
    {
        return $this->PRODUCT . ' ' . $this->RELEASE . '<br />' . $this->COPYRIGHT;
    }

    /**
     * @return string Short version format
     */
    function getCopyrightVersion()
    {
        return $this->PRODUCT . ' ' . $this->RELEASE . '<br />'
            . $this->COPYRIGHT . ' <strong><a class="rsg2-footer" href="http://www.rsgallery2.org">RSGallery2</a></strong>. All rights reserved.';
    }

    /**
     * @return string PHP standardized version format
     */
    function getVersionOnly()
    {
        return $this->RELEASE;
    }

    /**
     * checks if checked version is lower, equal or higher that the current version
     *
     * @param $version
     *
     * @return int -1 (lower), 0 (equal) or 1 (higher)
     */
    function checkVersion($version)
    {
        $check = version_compare($version, $this->RELEASE);

        return $check;
    }

}

?>
