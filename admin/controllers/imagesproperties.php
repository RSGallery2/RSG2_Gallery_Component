<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
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
	  * @since 4.3.0
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

	/**
	
	 * @since 4.3.0
     */
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

	    try
	    {
		    // Access check
		    $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		    if (!$canAdmin)
		    {
			    $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			    $msgType = 'warning';
			    // replace newlines with html line breaks.
			    str_replace('\n', '<br>', $msg);
		    }
		    else
		    {
			    $ImagesProperties = ImagesPropertiesFromInput ();

			    $model = $this->getModel('images');
			    $msg   = $model->save_imagesProperties($ImagesProperties);
		    }
	    }
	    catch (RuntimeException $e)
	    {
		    $OutTxt = '';
		    $OutTxt .= 'Error executing apply_imagesProperties: "' . '<br>';
		    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

		    $app = JFactory::getApplication();
		    $app->enqueueMessage($OutTxt, 'error');
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

        try
        {
	        // Access check
	        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
	        if (!$canAdmin)
	        {
		        $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
		        $msgType = 'warning';
		        // replace newlines with html line breaks.
		        str_replace('\n', '<br>', $msg);
	        }
	        else
	        {
		        $ImagesProperties = ImagesPropertiesFromInput ();

		        $model = $this->getModel('images');
		        $msg = $model->save_imagesProperties($ImagesProperties);
	        }
        }
        catch (RuntimeException $e)
        {
	        $OutTxt = '';
	        $OutTxt .= 'Error executing apply_imagesProperties: "' . '<br>';
	        $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

	        $app = JFactory::getApplication();
	        $app->enqueueMessage($OutTxt, 'error');
        }

	    // Create list of CIDS and append to link URL like in PropertiesView above
        // &ID[]=2&ID[]=3&ID[]=4&ID[]=12
        $cids = $this->input->get('cid', 0, 'int');
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

        try
        {
			// selected ids
	        $sids = $this->input->get('sid', 0, 'int');
	        $cids = $this->input->get('cid', 0, 'int');

	        // unset($ids[$i]);

	        // Access check
	        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
	        if (!$canAdmin)
	        {
		        $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
		        $msgType = 'warning';
		        // replace newlines with html line breaks.
		        str_replace('\n', '<br>', $msg);
	        }
	        else
	        {
		        // delete them all
		        $model = $this->getModel('image');
		        $model->delete($sids);

		        // Remove from display list
		        foreach ($sids as $sid)
		        {
			        $key = array_search($sid, $cids);
			        if ($key !== false)
			        {
				        unset($cids[$key]);
			        }

		        }

		        // success
		        $msg = 'Deleted ' . count ($sids) . ' images';
	        }
        }
        catch (RuntimeException $e)
        {
	        $OutTxt = '';
	        $OutTxt .= 'Error executing delete_imagesProperties: "' . '<br>';
	        $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

	        $app = JFactory::getApplication();
	        $app->enqueueMessage($OutTxt, 'error');
        }

	    // $link = 'index.php?option=com_rsgallery2&view=imagesProperties' .....;
	    $link = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids));

	    $this->setRedirect($link, $msg, $msgType);
    }

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function rotate_images_left()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "rotate_left: " . '<br>';

		$direction = 90;
		$this->rotate_images ($direction, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function rotate_images_right()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "rotate_right: " . '<br>';

		$direction = -90;
		$this->rotate_images ($direction, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function rotate_images_180()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "rotate_180: " . '<br>';

		$direction = 180;
		$this->rotate_images ($direction, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function rotate_images($direction = -90, $msg)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msgType = 'notice';

		try
		{
			// Access check
			$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
			if (!$canAdmin)
			{
				$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
				$msgType = 'warning';
				// replace newlines with html line breaks.
				str_replace('\n', '<br>', $msg);
			}
			else
			{
				// selected ids
				$sids = $this->input->get('sid', 0, 'int');

				// toDo: create imageDb model
				$modelImages = $this->getModel('images');
				$fileNames = $modelImages->fileNamesFromIds($sids);

				$galleryId = $modelImages->galleryIdFromId($sids[0]);

				$modelFile = $this->getModel('imageFile');
				$ImgCount = $modelFile->rotate_images($fileNames, $galleryId, $direction);

				$msg = ' Successful rotated ' . $ImgCount . ' images';
				// not all images were rotated
				if ($ImgCount < count ($fileNames))
				{
					$msgType = 'warning';
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing rotate_images: ""' . $direction . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		// Create list of CIDS and append to link URL like in PropertiesView above
		// &ID[]=2&ID[]=3&ID[]=4&ID[]=12
		$cids = $this->input->get('cid', 0, 'int');
		$link = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids));
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function flip_images_horizontal()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "flip_images_horizontal: " . '<br>';

		$flipMode = IMG_FLIP_HORIZONTAL; //  IMG_FLIP_VERTICAL,  IMG_FLIP_BOTH
		$this->flip_images($flipMode, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function flip_images_vertical()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "flip_images_vertical: " . '<br>';

		$flipMode = IMG_FLIP_VERTICAL;
		$this->flip_images($flipMode, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function flip_images_both()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "flip_images_both: " . '<br>';

		$flipMode = IMG_FLIP_BOTH;
		$this->flip_images($flipMode, $msg);
	}

	/**
	 *
	 *
	 *
	 * @since version 4.3
	 */
	public function flip_images($flipMode, $msg)
	{
		$msgType = 'notice';

		try
		{

			// Access check
			$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
			if (!$canAdmin)
			{
				$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
				$msgType = 'warning';
				// replace newlines with html line breaks.
				str_replace('\n', '<br>', $msg);
			}
			else
			{
				// selected ids
				$sids = $this->input->get('sid', 0, 'int');

				// toDo: create imageDb model
				$modelImages = $this->getModel('images');
				$fileNames = $modelImages->fileNamesFromIds($sids);

				$galleryId = $modelImages->galleryIdFromId($sids[0]);

				$modelFile = $this->getModel('imageFile');
				$ImgCount = $modelFile->flip_images($fileNames, $galleryId, $flipMode);

				$msg = ' Successful flipped ' . $ImgCount . ' images';
				// not all images were flipped
				if ($ImgCount < count ($fileNames))
				{
					$msgType = 'warning';
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing flip_images: ""' . $flipMode . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		// Create list of CIDS and append to link URL like in PropertiesView above
		// &ID[]=2&ID[]=3&ID[]=4&ID[]=12
		$cids = $this->input->get('cid', 0, 'int');
		$link = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids));
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Collects from user input the parameter of each image into one object per image
	 *
	 * @return array of images with input properties each
	 *
	 * @since 4.3.2
	 */
	public function ImagesPropertiesFromInput()
	{
		$ImagesProperties = array();

		try
		{
			$input = JFactory::getApplication()->input;

			$cids         = $input->get('cid', 0, 'int');
			$titles       = $input->get('title', 0, 'string');
			$descriptions = $input->get('description', 0, 'string');

			$idx = 0;
			foreach ($cids as $Idx => $cid)
			{
				$ImagesProperty = new stdClass();

				$ImagesProperty->cid = $cids [$Idx];
				// ToDo: Check for not HTML input
				$ImagesProperty->title       = $titles [$Idx];
				$ImagesProperty->description = $descriptions [$Idx];

				$ImagesProperties [] = $ImagesProperty;
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing ImagesPropertiesFromInput: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $ImagesProperties;
	}

}

