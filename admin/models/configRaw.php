<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('joomla.application.component.helper');
/**
 * 
 */
//class Rsgallery2ModelConfigRaw extends JModelLegacy  // JModelForm // JModelAdmin // JModelList // JModelItem
class Rsgallery2ModelConfigRaw extends JModelList
{
	public function getTable($type = 'Config', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	// save raw ...
	public function save() {
		$msg = "Rsgallery2ModelConfigRaw: ";

		$input =JFactory::getApplication()->input;
		//$jform = $input->get( 'jform', array(), 'ARRAY');
		$data  = $input->post->get('jform', array(), 'array');

//		echo json_encode ($jform);
/*
		// Complete data array if needed
		$oldData = $model->getData();
		$data = array_replace($oldData, $data);
*/
		
// ToDo: Remove bad injected code		

		$row = $this->getTable ();
		foreach ($data as $key => $value)
		{
/*
fill an array, bind and check and store ?
 */
			$row->id = null;
			$row->name = $key;
			$row->value = $value;
			$row->id = null;

//			$msg .= '    name = ' . $key . ' value = ' . $value . '<br>';

			$row->check ();
			$row->store ();

		}

		return $msg;
	}




}
