<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

// access to the content of the install.mysql.utf8.sql file
require_once (JPATH_COMPONENT_ADMINISTRATOR . '/classes/ImageReferences.php');

/**
 * 
 */
class rsgallery2ModelMaintConsolidateDB extends  JModelList
{

    /**
     * @var ImageReferences
     */
    protected $ImageReferences;

    public function GetImageReferences () {

        if (empty($this->ImageReferences))
        {
            $this->CreateDisplayImageData ();
        }

        return $this->ImageReferences;
    }


    /**
     * Runs optimization for each table
     *
     * @return string operation messages
     */
    public function CreateDisplayImageData ()
    {
        // ToDo: Instead of message return HasError;
        $msg = ''; //  ": " . '<br>';

        //
        $ImageReferences = new ImageReferences ();
        $this->ImageReferences = $ImageReferences;

        // Include watermarked files to search and check for missing 
        $ImageReferences->UseWatermarked = $this->IsWatermarkActive();
        $ImageReferences->UseWatermarked = true; // ToDO: remove
        
        try
        {
            $msg .= $ImageReferences->CollectImageReferences();
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing CollectImageReferences: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }

    /**
     * Tells if watermark is activated on user config
     * @return bool
     */
    public function IsWatermarkActive()
    {
        if (empty($this->IsWatermarkActive))
        {
            $this->IsWatermarkActive = false;

            try
            {
                $db    = JFactory::getDbo();
                $query = $db->getQuery(true)
                    ->select($db->quoteName('value'))
                    ->from($db->quoteName('#__rsgallery2_config'))
                    ->where($db->quoteName('name') . " = " . $db->quote('watermark'));
                $db->setQuery($query);

                $this->IsWatermarkActive = $db->loadResult();
            }
            catch (RuntimeException $e)
            {
                $OutTxt = '';
                $OutTxt .= 'Error executing query: "' . $query . '"' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
                
            }
        }

        return $this->IsWatermarkActive;
    }

    /**
     * SelectedImageReferences
     *
     *
     *
     * throws exception
     *
     * @return array
     *
     * @since version
     */
    public function SelectedImageReferences () {

        $ImageReferences = array ();

        $input = JFactory::getApplication()->input;
        $cid   = $input->get('cid', array(), 'ARRAY');

        if (empty ($cid)){
            $OutTxt = 'No items selected';
            // $OutTxt .= ': "' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'notice');

            return $ImageReferences;
        }

        $cids = implode(',', $cid);

        $ImageReferenceList = $input->getString('ImageReferenceList');
        if (empty ($ImageReferenceList)){
            $OutTxt = 'Retrieved no image reference items from input';
            // $OutTxt .= ': "' . '<br>';

            // $app = JFactory::getApplication();
            //$app->enqueueMessage($OutTxt, 'error');

            // return -1;
            throw new RuntimeException($OutTxt);
        }

        /**
        if (!$ImageReferences instanceof ImageReference [])
        {
        continue;
        }
        /**/

        $ImageReferenceList = html_entity_decode($ImageReferenceList, ENT_QUOTES, 'UTF-8');
        $ImageReferenceList = json_decode ($ImageReferenceList);

        //$UseWatermarked = $ImageReferenceList->UseWatermarked;
        //$ImageReferences = $ImageReferenceList->ImageReferences;
//            if (!is_array ($ImageReferences)) {
        if (!is_array ($ImageReferenceList)) {
            $OutTxt = 'Format of image reference items wrong';
            // $OutTxt .= ': "' . '<br>';

            //$app = JFactory::getApplication();
            //$app->enqueueMessage($OutTxt, 'error');

            $OutTxt .= '->'.$ImageReferenceList;
            // return -1;
            throw new RuntimeException($OutTxt);
        }

        $imgRefCount = count ($ImageReferenceList);

        // each selected image row
        foreach ($cid as $imgIdx)
        {
            // out of range ?
            if ($imgIdx < 0 || $imgRefCount <= $imgIdx)
            {
                $OutTxt = 'Selected index: ' . $imgIdx . ' is out of range';
                // $OutTxt .= ': "' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'notice');

                continue;
            }

            $ImageReferences[] = $ImageReferenceList [$imgIdx];
        }

        return $ImageReferences;
    }


//-----------------------------------------------------------------------------------------

    public function createSelectedMissingImages ()
    {
        // ToDo: Instead of message return HasError;

        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "createSelectedMissingImages: ";
        //       $msgType = 'notice';

        try
        {
            // Collect user selection
            // May throw error
            $ImageReferences =  $this->SelectedImageReferences ();

            foreach ($ImageReferences as $ImageReference) {
                $msg .= $this->createMissingImages ($ImageReference);
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error creating database image object: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }

    public function createMissingImages ($ImageReference)
    {
        // ToDo: Instead of message return HasError;

        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "<br>createMissingImages: ";
        $msg .= $ImageReference->imageName;
        //       $msgType = 'notice';

        try
        {




        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing create missing images: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }



//-----------------------------------------------------------------------------------------

    public function deleteAllImages ()
    {
        // ToDo: Instead of message return HasError;

        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "deleteAllImages: ";
        //       $msgType = 'notice';

        try
        {
            // Collect user selection
            // May throw error
            $ImageReferences =  $this->SelectedImageReferences ();

            foreach ($ImageReferences as $ImageReference) {
                $msg .= $this->deleteImageSet ($ImageReference);
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error creating database image object: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }

    public function deleteImageSet ($ImageReference)
    {
        // ToDo: Instead of message return HasError;

        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "<br>deleteImageSet: ";
        $msg .= $ImageReference->imageName;
        //       $msgType = 'notice';

        try
        {




        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error deleteImageSet: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }



//-----------------------------------------------------------------------------------------

    public function assignGalleries ()
    {
        // ToDo: Instead of message return HasError;

        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "assignGalleries: ";
        //       $msgType = 'notice';

        try
        {
            // Collect user selection
            // May throw error
            $ImageReferences =  $this->SelectedImageReferences ();

            foreach ($ImageReferences as $ImageReference) {
                $msg .= $this->assignGallery ($ImageReference);
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error creating database image object: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }

    public function assignGallery ($ImageReference)
    {
        // ToDo: Instead of message return HasError;

        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "<br>assignGallery: ";
        $msg .= $ImageReference->imageName;
        //       $msgType = 'notice';

        try
        {




        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing moveTo: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }



//-----------------------------------------------------------------------------------------

    public function deleteReferences ()
    {
        // ToDo: Instead of message return HasError;

        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "deleteReferences: ";
        //       $msgType = 'notice';

        try
        {
            // Collect user selection
            // May throw error
            $ImageReferences =  $this->SelectedImageReferences ();

            foreach ($ImageReferences as $ImageReference) {
                $msg .= $this->deleteReference ($ImageReference);
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error creating database image object: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }

    public function deleteReference ($ImageReference)
    {
        // ToDo: Instead of message return HasError;

        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "<br>deleteReference: ";
        $msg .= $ImageReference->imageName;
        //       $msgType = 'notice';

        try
        {




        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing moveTo: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }




}

