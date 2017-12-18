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

    /**
     * Save changes from imagesPropertiesView
     *
     * @since version 4.3
     */
    public function save_imagesProperties()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $msg     = "save_imagesProperties: " . '<br>';
        $msgType = 'notice';

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            $model = $this->getModel('images');
            $msg .= $model->save_imagesProperties();
        }

        $link = 'index.php?option=com_rsgallery2&view=images';
        $this->setRedirect($link, $msg, $msgType);
    }


    /**
     * Apply changes from imagesPropertiesView
     * Is like save_imagesProperties but redirects to calling view
     *
     * @since version 4.3
     */
    public function apply_imagesProperties()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $msg     = "apply_imagesProperties: " . '<br>';
        $msgType = 'notice';

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            $model = $this->getModel('images');
            $msg .= $model->save_imagesProperties();
        }

        // ToDo: Create list of CIDS and append to link URL like in PropertiesView above

        // &ID[]=2&ID[]=3&ID[]=4&ID[]=12
        //127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=imagesProperties&cid[]=1&cid[]=2&cid[]=3&cid[]=4
        $cids = $this->input->get('cid', 0, 'int');
        // $link = 'index.php?option=com_rsgallery2&view=imagesProperties' .....;
        $link = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids));

        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Save changes from imagesPropertiesView
     *
     * @since version 4.3
     */
    public function cancel_imagesProperties()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $link = 'index.php?option=com_rsgallery2&view=images';
        $this->setRedirect($link);
    }


    /**
     * Save changes from imagesPropertiesView
     *
     * @since version 4.3
     */
    public function delete_imagesProperties()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $msg     = "delete_imagesProperties: " . '<br>';
        $msgType = 'notice';

        $msg     = "delete_imagesProperties: Not implemented yet" . '<br>';
        $msgType = 'notice';

	    $cids = $this->input->get('cid', 0, 'int');
	    $iids = $this->input->get('iid', 0, 'int');

	    // unset($ids[$i]);

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
	        // &ID[]=2&ID[]=3&ID[]=4&ID[]=12
	        //127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=imagesProperties&cid[]=1&cid[]=2&cid[]=3&cid[]=4

	        foreach ($cids as $cid)
	        {
	        	echo 'Delete Cid: ' . $cid . '<br>';


		        $key = array_search($cid, $iids);
		        if($key!==false){
			        unset($iids[$key]);
		        }

		        //$msg .= $model->delete_imagesProperties();

	        }

	        $model = $this->getModel('image');
	        $model->delete ($cids);
        }

	    // $link = 'index.php?option=com_rsgallery2&view=imagesProperties' .....;
	    $link = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $iids));

	    $this->setRedirect($link, $msg, $msgType);
    }


}

