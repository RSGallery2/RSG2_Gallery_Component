<?php
defined('_JEXEC') or die;

/*
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.galleries.php ');
}
/**/

jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerGalleries extends JControllerAdmin
{

	public function getModel($name = 'Gallery', 
 							 $prefix = 'Rsgallery2Model',
  							 $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}


	public function saveOrdering ()
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		$msg = "saveOrder: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));


		try {

			$input = JFactory::getApplication()->input;
			$orders = $input->post->get( 'order', array(), 'ARRAY');
			$ids = $input->post->get( 'ids', array(), 'ARRAY');

			// $CountOrders = count($ids);
			$CountIds = count($ids);

			$db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($query);

			for ($idx = 0; $idx < $CountIds; $idx++) {
				$id = $ids[$idx];
				$orderIdx = $orders[$idx];
                // $msg .= "<br>" . '$id: ' . $id . '$orderIdx: ' . $orderIdx;

				$query->clear();

				$query->update($db->quoteName('#__rsgallery2_galleries'))
					->set(array($db->quoteName('ordering') . '=' . $orderIdx))
					->where(array($db->quoteName('id') . '='. $id));

				$result = $db->execute($query);
                //$msg .= "<br>" . "Query : " . $query->__toString();
                //$msg .= "<br>" . 'Query  $result: : ' . json_encode($result);
			}
            // $msg .= "<br>";





            $msg .= JText::_( 'COM_RSGALLERY2_NEW_ORDERING_SAVED' );
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing saveOrdering: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=galleries', $msg, $msgType);
	}

/**
	function saveOrder( &$cid ) {
		$mainframe =& JFactory::getApplication();
		$database = JFactory::getDBO();

		$total		= count( $cid );
		// $order 		= JRequest::getVar( 'order', array(0), 'post', 'array' );
		$input = JFactory::getApplication()->input;
		$order = $input->post->get( 'order', array(), 'ARRAY');
		//  JArrayHelper::toInteger($order, array(0));
		ArrayHelper::toInteger($order, array(0));
		$row 		= new rsgGalleriesItem( $database );

		$conditions = array();

		// update ordering values
		for ( $i=0; $i < $total; $i++ ) {
			$row->load( (int) $cid[$i] );
			$groupings[] = $row->parent;
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $mainframe->getErrorMsg());
				} // if
			} // if
		} // for

		// reorder each group
		$groupings = array_unique( $groupings );
		foreach ( $groupings as $group ) {
			$row->reorder('parent = '.$database->Quote($group));
		} // foreach

		// clean any existing cache files
		$cache =& JFactory::getCache('com_rsgallery2');
		$cache->clean( 'com_rsgallery2' );

		$msg 	= JText::_( 'COM_RSGALLERY2_NEW_ORDERING_SAVED' );
		$mainframe->enqueueMessage( $msg );
		$mainframe->redirect( 'index.php?option=com_rsgallery2&rsgOption=galleries');
	} // saveOrder
/**/


}

