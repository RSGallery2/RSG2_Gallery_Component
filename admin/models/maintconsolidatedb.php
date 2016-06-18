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
     * @var ImageReference []
     */
    protected $ImageReferenceList;

    public function GetDisplayImageData () {

        if (empty($this->ImageReferences))
        {
            $this->CreateDisplayImageData ();
        }

        return $this->ImageReferenceList;
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
        $this->ImageReferenceList = $ImageReferences->CollectImageReferences ();

        return $msg;
    }




}

