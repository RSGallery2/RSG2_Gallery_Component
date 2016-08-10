<?php
defined('_JEXEC') or die;

/*
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.image.php ');
}
/**/

jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerImages extends JControllerAdmin
{

	public function getModel($name = 'Image', 
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

				$query->update($db->quoteName('#__rsgallery2_files'))
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


}

