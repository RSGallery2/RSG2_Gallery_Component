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
     * retrieves state if debug is activated on user config
     * @return bool
     */
    /*
	public static function getIsDebugActive()
	{
		if (!empty($this->IsDebugActive)) {
			$db =  JFactory::getDbo();
			$query = $db->getQuery (true)
				->select ($db->quoteName('value'))
				->from($db->quoteName('#__rsgallery2_config'))
				->where($db->quoteName('name')." = ".$db->quote('debug'));
			$db->setQuery($query);
			$this->IsDebugActive  = $db->loadResult();
		}

		return $this->IsDebugActive;
	}
	*/


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
                $OutTxt .= 'Error executing query: "' . $query . '" in IsWatermarkActive' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        return $this->IsWatermarkActive;
    }



}

