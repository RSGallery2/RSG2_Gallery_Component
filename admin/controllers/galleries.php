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

/**/
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.galleries.php ');
}
/**/

// ToDo: // Sanitize the input

jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerGalleries extends JControllerAdmin
{
    /**
     * Proxy for getModel.
     */
    public function getModel($name = 'Gallery', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return  parent::getModel($name, $prefix, $config);
    }

    /**
	 * Saves changed manual ordering of galleries
	 * !!! Not implemented yet !!!
     *
	 * @throws Exception
     *
     * @since version 4.3
	 */
	public function saveOrdering()
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        //$msg     = "Control:saveOrdering: ";
        $msg     = "";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
		    try
			{
				// Model tells if successful
				$model = $this->getModel('galleries');
                $IsSaved = $model->saveOrdering();
                if ($IsSaved) {
                    $msg .= JText::_('COM_RSGALLERY2_NEW_ORDERING_SAVED');
                }
                else
                {
                    $msg .= JText::_('Save new ordering failed');
                    $msgType = 'error';
                }
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing saveOrdering: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=galleries', $msg, $msgType);
	}

	/**
	 * function saveOrder( &$cid ) {
	 * $mainframe =& JFactory::getApplication();
	 * $database = JFactory::getDBO();
	 *
	 * $total        = count( $cid );
	 * // $order        = JRequest::getVar( 'order', array(0), 'post', 'array' );
	 * $input = JFactory::getApplication()->input;
	 * $order = $input->post->get( 'order', array(), 'ARRAY');
	 * //  JArrayHelper::toInteger($order, array(0));
	 * ArrayHelper::toInteger($order, array(0));
	 * $row        = new rsgGalleriesItem( $database );
	 *
	 * $conditions = array();
	 *
	 * // update ordering values
	 * for ( $i=0; $i < $total; $i++ ) {
	 * $row->load( (int) $cid[$i] );
	 * $groupings[] = $row->parent;
	 * if ($row->ordering != $order[$i]) {
	 * $row->ordering = $order[$i];
	 * if (!$row->store()) {
	 * JError::raiseError(500, $mainframe->getErrorMsg());
	 * } // if
	 * } // if
	 * } // for
	 *
	 * // reorder each group
	 * $groupings = array_unique( $groupings );
	 * foreach ( $groupings as $group ) {
	 * $row->reorder('parent = '.$database->Quote($group));
	 * } // foreach
	 *
	 * // clean any existing cache files
	 * $cache =& JFactory::getCache('com_rsgallery2');
	 * $cache->clean( 'com_rsgallery2' );
	 *
	 * $msg    = JText::_( 'COM_RSGALLERY2_NEW_ORDERING_SAVED' );
	 * $mainframe->enqueueMessage( $msg );
	 * $mainframe->redirect( 'index.php?option=com_rsgallery2&rsgOption=galleries');
	 * } // saveOrder
	 * /**/

}

