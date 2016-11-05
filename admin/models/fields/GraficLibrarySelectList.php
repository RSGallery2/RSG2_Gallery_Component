<?php
/*
* @version $Id: GraficLibrarySelectList.php  $
* @package RSGallery2
* @copyright (C) 2005 - 2016 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Grafic Library Select List Form Field class to create contents of dropdown box for 
 * usable grafic libraries on the system
 */
class JFormFieldGraficLibrarySelectList extends JFormFieldList {
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	protected $type = 'GraficLibrarySelectList';
	
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  An array of JHtml options.
	 */
	protected function getOptions() 
	{	
		$options = array();
		$libraries = array();

		try
		{
            /**
             * detect available graphics libraries
             * @todo call imgUtils graphics lib detection when it is built
            */
/*
            $options[] = (object) array(
                'value' => $value,
                'text' => ($value != 0) ? JText::_('J' . $value) : JText::_('JALL'));

            $check = $this->value == $menu->value ? 'edit' : 'create';
/**/

            //--- GD2 -------------------------
            $VersionId =  $this->GD2_detect();
	        if(!empty ($VersionId))
	        {
                $libraries[] = JHtml::_("select.option", 'gd2', 'gd2: ' .$VersionId);
                //$libraries[] = 'GD2: ' . $VersionId);
                //$libraries[] = (object) array(
                //    'value' => 'gd2',
                //    'text' => 'gd2: ' . $VersionId . ' ::' . $this->value);
            }
	        else
            {
                $libraries[] = JHtml::_("select.option", 'gd2', JText::_('COM_RSGALLERY2_GD2_NOT_DETECTED') );
                //$libraries[] = JHtml::_("select.option", 'gd2', JText::_('COM_RSGALLERY2_GD2_NOT_DETECTED') );
            }

            $VersionId = $this->ImageMagick_detect();
	        if(!empty ($VersionId))
            {
                $libraries[] = JHtml::_("select.option", 'imagemagick', 'ImageMagick: ' . $VersionId );
                //$libraries[] = 'ImageMagick: ' . $VersionId );
                //$libraries[] = (object) array(
                //        'value' => 'gd2',
                //        'text' => 'gd2: ' .$VersionId . ' ::' . $this->value);
            }
            else
            {
                $libraries[] = JHtml::_("select.option", 'imagemagick', JText::_('COM_RSGALLERY2_IMAGEMAGICK_NOT_DETECTED') );
                //$libraries[] = JHtml::_("select.option", 'imagemagick', JText::_('COM_RSGALLERY2_IMAGEMAGICK_NOT_DETECTED') );
            }

            $VersionId = $this->Netpbm_detect();
	        if(!empty ($VersionId))
            {
                $libraries[] = JHtml::_("select.option", 'netpbm', 'Netpbm: ' . $VersionId );
                //$libraries[] = 'Netpbm: ' . $VersionId );
                //$libraries[] = (object) array(
                //        'value' => 'gd2',
                //        'text' => 'gd2: ' .$VersionId . ' ::' . $this->value);
            }
            else
            {
                $libraries[] = JHtml::_("select.option", 'netpbm', JText::_('COM_RSGALLERY2_NETPBM_NOT_DETECTED') );
                //$libraries[] = JHtml::_("select.option", 'netpbm', JText::_('COM_RSGALLERY2_NETPBM_NOT_DETECTED') );
            }

            
//            $lists['$libraries'] = JHtml::_("select.genericlist",$libraries, 'graphicsLib', '', 'value', 'text', $rsgConfig->graphicsLib );
/**/


		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}
		
		
//		// Add select option (no value)
//		$options[] = JHtml::_('select.option', -1, JText::_('COM_RSGALLERY2_SELECT_GALLERY_FROM_LIST'));
//		foreach($galleries as $gallery)
//		{	
//			$options[] = JHtml::_('select.option', $gallery->gid, $gallery->name);
//		}
//		$options = array_merge(parent::getOptions() , $options);
		
		// Merge any additional options in the XML definition.
		// $options[] = JHtml::_('select.option', $key, $value);
		// $options[] = array("value" => 1, "text" => "1");


		$options = $libraries;
		// Put "Select an option" on the top of the list.
		// array_unshift($options, JHtml::_('select.option', '0', JText::_('Select an option')));

		return array_merge(parent::getOptions(), $options);

	}


    private function GD2_detect()
    {
        $VersionId = '';

        try
        {
            if (extension_loaded('gd'))
            {
                if (function_exists('gd_info'))
                {
                    $gdInfoArray = gd_info();
                    $VersionId = $gdInfoArray["GD Version"];
                }
            }
            /*
            if(strlen ($Gd2Version) < 1) {
                // echo "<br>false";
                return false;
            }
            /**/
        }
        catch (RuntimeException $e)
        {
            JError::raiseWarning(500, $e->getMessage());
        }

        return $VersionId;
    }

    private function ImageMagick_detect()
    {
        global $rsgConfig;

        $VersionId = '';

        try
        {
            $shell_cmd = '';
            $result ='';
            $status ='';

            // if path exists add the final /
            $impath = $rsgConfig->get( "imageMagick_path" );
            $impath = $impath==''? '' : $impath.'/';

            @exec($impath.'convert -version',  $output, $status);
            if(!$status) {
                if (preg_match("/imagemagick[ \t]+([0-9\.]+)/i", $output[0], $matches)) {
                    // echo '<br>ImageMagick: ' . $matches[0];
                    $VersionId = $matches[0];
                }
            }

        }
        catch (RuntimeException $e)
        {
            JError::raiseWarning(500, $e->getMessage());
        }

        return $VersionId;
    }

    /**
     * detects if image library is available
     * @param string $output
     * @param string $status
     * @return bool false if not detected, user friendly string of library name and version if detected
     *
    static function imgageMagicOld_detect( $output = '', $status = '' ){
        global $rsgConfig;

        // if path exists add the final /
        $impath = $rsgConfig->get( "imageMagick_path" );
        $impath = $impath==''? '' : $impath.'/';

        @exec($impath.'convert -version',  $output, $status);
        if(!$status){
            if(preg_match("/imagemagick[ \t]+([0-9\.]+)/i",$output[0],$matches)){
                // echo '<br>ImageMagick: ' . $matches[0];
                $VersionId = $matches[0];
            }
                return false;
            }
        }

        return true;
    }
    /**/

    private function Netpbm_detect()
    {
        $VersionId = '';

        try
        {
            $shell_cmd = '';
            $result ='';
            $status ='';
            @exec($shell_cmd. 'jpegtopnm -version 2>&1',  $result, $status);

            if(!$status) {
                if (preg_match("/netpbm[ \t]+([0-9\.]+)/i", $result[0], $matches)) {
                    // echo '<br>netpbm: ' + $matches[0];
                    $VersionId = $matches[0];
                }
            }
        }
        catch (RuntimeException $e)
        {
            JError::raiseWarning(500, $e->getMessage());
        }

        return $VersionId;
    }

    /**
     * detects if image library is available
     * @param string $shell_cmd
     * @param string $output
     * @param string $status
     * @return bool false if not detected, user friendly string of library name and version if detected
     *
    static function netpbmOld_detect($shell_cmd = '', $output = '', $status = ''){
        @exec($shell_cmd. 'jpegtopnm -version 2>&1',  $output, $status);
        if(!$status){
            if(preg_match("/netpbm[ \t]+([0-9\.]+)/i",$output[0],$matches)){
                // echo '<br>netpbm: ' + $matches[0];
                return $matches[0];
            }
            else
                return false;
        }

        return true;
    }
    /**/



}

