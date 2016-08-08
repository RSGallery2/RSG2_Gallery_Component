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

			$OutTxt = '';

			/*
			$msg .= "<br>" . "isset(orders): " . isset($orders);
			$msg .= "<br>" . "is_array(orders): " . is_array($orders);

			$msg .= "<br>" . "isset(ids): " . isset($ids);
			$msg .= "<br>" . "is_array(ids): " . is_array($ids);
			/**/

			$CountOrders = count($ids);
			//$msg .= "<br>" . "$CountOrders: " . $CountOrders;
			$CountIds = count($ids);
			//$msg .= "<br>" . "$CountIds: " . $CountIds;

			/*
			$OutTxt = '';
			for ($idx = 0; $idx < $CountIds; $idx++) {
				$msg .= '<br>' . 'ID: ' . $ids[$idx] . ' ' . 'Order: ' . $orders[$idx];
			}
			$msg .= "<br>";
			/**/


			$db = JFactory::getDbo();
			$query = $db->getQuery(true);


			for ($idx = 0; $idx < $CountIds; $idx++) {
				$id = $ids[$idx];
				$orderIdx = $orders[$idx];

				$query->clear();

				$query->update($db->quoteName('#__rsgallery2_files'))
					->set(array($db->quoteName('ordering') . '=' . $orderIdx))
					->where(array($db->quoteName('id') . '='. $id));

				$db->execute($query);
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

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=galleries', $msg, $msgType);
	}
}



