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
     * Runs optimization for each table
     *
     * @return string operation messages
     */
    public function ()
    {
        $msg = ''; //  ": " . '<br>';

        if (empty($this->sqlFile))
        {
            $this->sqlFile = new SqlInstallFile ();
        }


        return $msg;
    }




}

