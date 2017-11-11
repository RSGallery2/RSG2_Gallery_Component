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
	JLog::add('==> ctrl.imageProperties.php ');
}
/**/

class Rsgallery2ControllerImagesProperties extends JControllerForm
{
	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A named array of configuration variables.
	 *
	 * @since   1.6
	 *
	public function __construct($config = array())
	{
		parent::__construct($config);

		// An article edit form can come from the articles or featured view.
		// Adjust the redirect view on the value of 'return' in the request.
		if ($this->input->get('return') == 'featured')
		{
			$this->view_list = 'featured';
			$this->view_item = 'article&return=featured';
		}
	}
	/**/

	public function PropertiesView ()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// &ID[]=2&ID[]=3&ID[]=4&ID[]=12
		//127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=imagesProperties&cid[]=1&cid[]=2&cid[]=3&cid[]=4
		$cids = $this->input->get('cid', 0, 'int');
		$this->setRedirect('index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids)));

		parent::display();
	}
}

