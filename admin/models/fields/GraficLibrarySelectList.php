<?php
/*
* @package RSGallery2
* @copyright (C) 2005-2023 RSGallery2 Team
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Detect available graphic libraries on the system create contents of dropdown box
 *
 * ToDo: Improve for more libraries and better detection
 *
 * @since 4.3.0
 */
class JFormFieldGraficLibrarySelectList extends JFormFieldList
{
    /**
     * The field type.
     *
     * @var string
     *
     * @since 4.3.0
     */
	protected $type = 'GraficLibrarySelectList';

	/**
     * Method to get the field options. -> List of existing graphic libraries
	 *
	 * @return  string array  An array of JHtml options.
     *
     * @since   4.3.0
	 */
	protected function getOptions()
	{
		$libraries = array();

		try
		{
			/**
			 * detect available graphics libraries
			 *
			 * @ToDo: call imgUtils graphics lib detection when it is built
			 */
			/*
            $options[] = (object) array(
                'value' => $value,
                'text' => ($value != 0) ? JText::_('J' . $value) : JText::_('JALL'));

            $check = $this->value == $menu->value ? 'edit' : 'create';
			/**/

			//--- GD2 -------------------------
			$VersionId = $this->GD2_detect();
			if (!empty ($VersionId))
			{
				$libraries[] = JHtml::_("select.option", 'gd2', 'gd2: ' . $VersionId);
				//$libraries[] = 'GD2: ' . $VersionId);
				//$libraries[] = (object) array(
				//    'value' => 'gd2',
				//    'text' => 'gd2: ' . $VersionId . ' ::' . $this->value);
			}
			else
			{
				$libraries[] = JHtml::_("select.option", 'gd2', JText::_('COM_RSGALLERY2_GD2_NOT_DETECTED'));
				//$libraries[] = JHtml::_("select.option", 'gd2', JText::_('COM_RSGALLERY2_GD2_NOT_DETECTED') );
			}

			$VersionId = $this->ImageMagick_detect();
			if (!empty ($VersionId))
			{
				$libraries[] = JHtml::_("select.option", 'imagemagick', 'ImageMagick: ' . $VersionId);
				//$libraries[] = 'ImageMagick: ' . $VersionId );
				//$libraries[] = (object) array(
				//        'value' => 'gd2',
				//        'text' => 'gd2: ' .$VersionId . ' ::' . $this->value);
			}
			else
			{
				$libraries[] = JHtml::_("select.option", 'imagemagick', JText::_('COM_RSGALLERY2_IMAGEMAGICK_NOT_DETECTED'));
				//$libraries[] = JHtml::_("select.option", 'imagemagick', JText::_('COM_RSGALLERY2_IMAGEMAGICK_NOT_DETECTED') );
			}

			$VersionId = $this->Netpbm_detect();
			if (!empty ($VersionId))
			{
				$libraries[] = JHtml::_("select.option", 'netpbm', 'Netpbm: ' . $VersionId);
				//$libraries[] = 'Netpbm: ' . $VersionId );
				//$libraries[] = (object) array(
				//        'value' => 'gd2',
				//        'text' => 'gd2: ' .$VersionId . ' ::' . $this->value);
			}
			else
			{
				$libraries[] = JHtml::_("select.option", 'netpbm', JText::_('COM_RSGALLERY2_NETPBM_NOT_DETECTED'));
				//$libraries[] = JHtml::_("select.option", 'netpbm', JText::_('COM_RSGALLERY2_NETPBM_NOT_DETECTED') );
			}

//            $lists['$libraries'] = JHtml::_("select.genericlist",$libraries, 'graphicsLib', '', 'value', 'text', $rsgConfig->graphicsLib );
			/**/

		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		// Put "Select an option" on the top of the list.
		// array_unshift($options, JHtml::_('select.option', '0', JText::_('Select an option')));

        $options = array_merge(parent::getOptions(), $libraries);

        return $options;
	}

    /**
     * Searches for GD2 through check if extension is loaded
     *
     * @return string GD2 Version
     *
     * @since 4.3.0
     */
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
					$VersionId   = $gdInfoArray["GD Version"];
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
            JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		return $VersionId;
	}

    /**
     * Searches for ImageMagic by path. Does call it for version info
     * ToDo::Test on server
     *
     * @return string  ImageMagic Version by call with parameters
     *
     * @since 4.3.0
     */
	private function ImageMagick_detect()
	{
		//global $rsgConfig;

		$VersionId = '';

		try
		{
			$status    = '';

			//$app = JFactory::getApplication();
			//$rsgConfig = $app->getParams();
			$rsgConfig = JComponentHelper::getParams('com_rsgallery2');

			// if path exists add the final /
			$impath = $rsgConfig->get("imageMagick_path");
			$impath = $impath == '' ? '' : $impath . '/';

            $shell_cmd = $impath . 'convert -version';
            $result    = '';
            $status    = '';

            @exec($shell_cmd, $result, $status);
			if (!$status)
			{
				if (preg_match("/imagemagick[ \t]+([0-9\.]+)/i", $result[0], $matches))
				{
					// echo '<br>ImageMagick: ' . $matches[0];
					$VersionId = $matches[0];
				}
			}
		}
		catch (RuntimeException $e)
		{
            JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		return $VersionId;
	}

	/**
	 * detects if image library is available
     * ToDo::Test on server
     *
	 * @param string $output
	 * @param string $status
	 *
	 * @return bool false if not detected, user friendly string of library name and version if detected
     *
     * @since 4.3.0
     *
    static function imgageMagicOld_detect( $output = '', $status = '' ){
        global $rsgConfig;

        // if path exists add the final /
        $impath = $rsgConfig->get( "imageMagick_path" );
        $impath = $impath==''? '' : $impath.'/';

        @exec($impath.'convert -version', $result, $status);
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


    /**
     * Searches for Netpbm by path
     * ToDo::Test on server
     *
     * @return string  Netpbm Version by call with parameters
     *
     * @since 4.3.0
     */
	private function Netpbm_detect()
	{
		$VersionId = '';

		try
		{
            // Any of the netbpm programs may be called with option -version
            // ToDO: Netbpm path ?
			$shell_cmd = 'jpegtopnm -version 2>&1';
			$result    = '';
			$status    = '';

			@exec($shell_cmd, $result, $status);
			if (!$status)
			{
				if (preg_match("/netpbm[ \t]+([0-9\.]+)/i", $result[0], $matches))
				{
					// echo '<br>netpbm: ' + $matches[0];
					$VersionId = $matches[0];
				}
			}
		}
		catch (RuntimeException $e)
		{
            JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		return $VersionId;
	}

	/**
	 * Detects if image library is available
	 *
	 * @param string $shell_cmd
	 * @param string $output
	 * @param string $status
	 *
	 * @return bool false if not detected, user friendly string of library name and version if detected
     *
     * @since 4.3.0
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

