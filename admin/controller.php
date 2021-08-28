<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2021 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

/**
 * @param bool
 */
global $Rsg2DebugActive;

// $Rsg2DebugActive = true; // ToDo: $rsgConfig->get('debug');
if ($Rsg2DebugActive) {
    // Include the JLog class.
    jimport('joomla.log.log');

    // identify active file
//    JLog::add('==> base.controller.php');
}

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * Class Rsgallery2Controller
 */
class Rsgallery2Controller extends JControllerLegacy
{
    protected $default_view = 'rsgallery2';

    /**
     * display task
     *
     * @param bool $cachable
     * @param bool $urlparams
     *
     * @return $this
     * @since 4.3.0
    */
    public function display($cachable = false, $urlparams = false)
    {
        global $Rsg2DebugActive;

        if ($Rsg2DebugActive) {
            JLog::add('==> base.controller display');
        }

//	ToDo: Use 	require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/rsg2.php';
        /*
                $view   = $this->input->get('view', 'rsgallery2');
                JLog::add('  base.controller.view: ', json_encode($view));

                $layout = $this->input->get('layout', 'default');
                JLog::add('  base.controller.layout: ', json_encode($layout));

                $id     = $this->input->getInt('id');
                JLog::add('  base.controller.id: ', json_encode($id));
        */

        /*
                if($Rsg2DebugActive)
                {
                    $task = $this->input->get('task');
                    JLog::add('  base.controller.task: ', json_encode($task));
                }
        */

        /* ToDo: Activate following: book extension entwickeln  page 208
        if ($view == 'rsg2' && $layout == 'edit' && !$this->checkEditId('com_rsgallery2.edit.rsgallery2', $id))
        {
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
            $this->setMessage($this->getError(), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_rsgallery2&view=rsgallery2s', false));

            return false;
        }
        */

        /*----------------------------------------------------
         Prevent editing checked out gallery, image, ... items
        ------------------------------------------------------*/

        $view = $this->input->get('view', 'rsgallery2');
        $layout = $this->input->get('layout', 'rsgallery2');
        $id = $this->input->getInt('id');

        // Check for edit form.
        if ($layout == 'edit') {
            if ($view == 'gallery' && !$this->checkEditId('com_rsgallery2.edit.gallery', $id)) {
                // Somehow the person just went to the form - we don't allow that.
                $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
                $this->setMessage($this->getError(), 'error');
                $this->setRedirect(JRoute::_('index.php?option=com_rsgallery2&view=rsgallery2', false));

                return false;
            }

            if ($view == 'image' && !$this->checkEditId('com_rsgallery2.edit.image', $id)) {
                // Somehow the person just went to the form - we don't allow that.
                $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
                $this->setMessage($this->getError(), 'error');
                $this->setRedirect(JRoute::_('index.php?option=com_rsgallery2&view=rsgallery2', false));

                return false;
            }

            if ($view == 'comment' && !$this->checkEditId('com_rsgallery2.edit.comment', $id)) {
                // Somehow the person just went to the form - we don't allow that.
                $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
                $this->setMessage($this->getError(), 'error');
                $this->setRedirect(JRoute::_('index.php?option=com_rsgallery2&view=rsgallery2', false));

                return false;
            }
            if ($view == 'config') {
                if (!JFactory::getUser()->authorise('core.manage', 'com_rsgallery2')) {
                    JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
                    return false;
                }

                /**
                 * // Somehow the person just went to the form - we don't allow that.
                 * $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
                 * $this->setMessage($this->getError(), 'error');
                 * //$this->setRedirect(JRoute::_('index.php?option=com_rsgallery2&view=rsgallery2', false));
                 * $this->setRedirect(JRoute::_('index.php?option=com_rsgallery2&view=maintenance', false));
                 * /**/

                //return false;
            }
        }

        parent::display($cachable);

        if ($Rsg2DebugActive) {
            JLog::add('<== base.controller display');
        }

        return $this;
    }
}

if ($Rsg2DebugActive) {
//    JLog::add('<== base.controller.php');
}


