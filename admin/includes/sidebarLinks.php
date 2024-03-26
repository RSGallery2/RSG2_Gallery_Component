<?php
/**
 * This file handles general definitions for RSGallery2
 *
 * @version
 * @package       RSGallery2
 * @copyright (C) 2005-2023 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery2 is Free Software
 * @Since         4.3.2
 */

defined('_JEXEC') or die();


// This is used 2018.05.10


class RSG2_SidebarLinks {

    /**
     * @param string $task
     * @param string $view
     * @param string $layout
     *
     *
     * @since 4.3.0
     */
    static function addItems($view = '', $layout = '', $task = '')
    {

        $view = strtolower ($view);

        // sanitize null on $layout
        if (empty($layout)) {
            $layout = '';
        } else {
            $layout = strtolower($layout);
        }

        /**
         echo 'sidebarLinks:Additems'. '<br>';
         echo '$task = "'      . $task      . '"<br>';
         echo '$view = "'      . $view      . '"<br>';
         echo '$layout = "'    . $layout    . '"<br>';
         echo '<br><br>';
         // echo '<br><br><br><br><br>';
        /**/

        // 'Active' as last parameter in HtmlSidebar::addEntry means
        // to highlight on true and tells the active view then


        //--- Add control panel view link ------------------------------------

        $link = 'index.php?option=com_rsgallery2';
        JHtmlSidebar::addEntry(
            '<span class="icon-home-2" >  </span>' .
            JText::_('COM_RSGALLERY2_SUBMENU_CONTROL_PANEL'),
            $link,
            $view == 'rsgallery2' || $view == '');

        //--- Add galleries view link ------------------------------------

        $link = 'index.php?option=com_rsgallery2&view=galleries';
        JHtmlSidebar::addEntry(
            '<span class="icon-images" >  </span>' .
            JText::_('COM_RSGALLERY2_SUBMENU_GALLERIES'),
            $link,
            $view == 'galleries');

        //--- Add upload view link ------------------------------------

        $link = 'index.php?option=com_rsgallery2&view=upload';
        JHtmlSidebar::addEntry(
            '<span class="icon-upload" > </span>' .
            JText::_('COM_RSGALLERY2_SUBMENU_UPLOAD'),
            $link,
            $view == 'upload');

        //--- Add images view link ------------------------------------

        $link = 'index.php?option=com_rsgallery2&view=images';
        JHtmlSidebar::addEntry(
            '<span class="icon-image" >  </span>' .
            JText::_('COM_RSGALLERY2_SUBMENU_IMAGES'),
            // 'index.php?option=com_rsgallery2&rsgOption=images',
            $link,
            ($view == 'images'));

        //--- Add maintenance view link ------------------------------------

        if ($view == 'config' || $view == '') {
            $link = 'index.php?option=com_rsgallery2&view=maintenance';
            // In config add maintenance
            JHtmlSidebar::addEntry(
                '<span class="icon-screwdriver" >  </span>' .
                JText::_('COM_RSGALLERY2_MAINTENANCE'),
                $link,
                false);
        }

        if (substr($view, 0, 5) == 'devel') {
            $link = 'index.php?option=com_rsgallery2&view=maintenance';
            // In config add maintenance
            JHtmlSidebar::addEntry(
                '<span class="icon-screwdriver" >  </span>' .
                JText::_('COM_RSGALLERY2_MAINTENANCE'),
                $link,
                false);
        }

        // gallery_raw, image_raw, ...
        if (substr($layout, -4) == '_raw') {
            $link = 'index.php?option=com_rsgallery2&view=maintenance';
            // In config add maintenance
            JHtmlSidebar::addEntry(
                '<span class="icon-screwdriver" >  </span>' .
                JText::_('COM_RSGALLERY2_MAINTENANCE'),
                $link,
                false);
        }


        //--- Add config view link ------------------------------------

        // inside maintenance ....
        if (substr($view, 0, 5) == 'maint') {
            $link = 'index.php?option=com_rsgallery2&view=config&task=config.edit';
            // In maintenance add config
            JHtmlSidebar::addEntry(
                '<span class="icon-equalizer" >  </span>' .
                JText::_('COM_RSGALLERY2_CONFIGURATION'),
                $link,
                false);
            if ($view != 'maintenance') {
                $link = 'index.php?option=com_rsgallery2&view=maintenance';
                // In config add maintenance
                JHtmlSidebar::addEntry(
                    '<span class="icon-screwdriver" >  </span>' .
                    JText::_('COM_RSGALLERY2_MAINTENANCE'),
                    $link,
                    false);
            }
        }


        /**/


    }

} // class

