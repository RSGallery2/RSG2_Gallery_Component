<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2020 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

// No direct access to this file
defined('_JEXEC') or die;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Support to delete data or remove database tables for maintenance
 *
 * @since 4.3.0
 */

// ToDo: Return true or false instead of messages
// ToDo: removeDataInTables single calls '$this->PurgeTable(...' should be done in controller

class rsgallery2ModelMaintSlideshows extends JModelList
{

	/**
	 * Searches for xml files which defines installalled slideshows
	 * Therefore checks for existing 'templateDetails.xml' file which will
	 * contain the parameters to be displayed
	 * Attention the found folders do contain templates for gallery view
	 * and meta data which has to be seperated later on
	 *
	 *
	 * @return array
	 *
	 * @since version
	 */
	public function collectSlideshowsConfigFiles()
	{
		$configFiles = [];

		//--- search templateDetails.xml files ------------------

		$fieldsFileName    = 'templateDetails.xml';
		$parameterFileName = 'params.ini';
		$fileBasePath = JPATH_COMPONENT_SITE . '/templates';

		// each folder may be a slideshow or a "semantic" image display

		$folders = JFolder::folders($fileBasePath);

		foreach ($folders as $folder)
		{
			$fileSlidePath = $fileBasePath . '/' . $folder;

			// check if joomla config file exist
			$cfgFile = JFolder::files($fileSlidePath, $fieldsFileName);
			if (!empty($cfgFile))
			{
				$foundSlideshow = new stdClass();

				$foundSlideshow->name        = $folder;
				$foundSlideshow->cfgFieldsFileName = $fileSlidePath . '/' . $fieldsFileName; // $cfgFile [0];
				$foundSlideshow->cfgParameterFileName = $fileSlidePath . '/' . $parameterFileName;

				$configFiles [] = $foundSlideshow;

				//echo json_encode($foundSlideshow) ;
				//echo '<br>';
			}
		}

		return $configFiles;
	}

	/**/
	public function parameterFromConfigFiles($slidesConfigFiles)
	{
		$parameterSets = [];

		try
		{
			foreach ($slidesConfigFiles as $xmlFileInfo)
			{
				// extract parameter
				$xmlFile      = $xmlFileInfo->cfgFieldsFileName;
				$parameterSet = $this->parameterFromXmlFile($xmlFile);
				if (!empty ($parameterSet))
				{
					$parameterSets [] = $parameterSet;

				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing parameterFromConfigFiles: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}


		return $parameterSets;
	}

	/**/
	public function parameterFromXmlFile($xmlFile)
	{
		$parameter = [];

		try
		{
			/**
			 * $xml = JFactory::getXMLParser('Simple');
			 * $xml->loadFile($xmlFile);
			 * /**/

			$xml = simplexml_load_file($xmlFile);
			if (!empty($xml))
			{
				//$citizens = $xml->germany->citizens;
				//echo json_encode($xml);
				//echo '<br>';

				//$params = $xml->install->params;
				$params = $xml->params;
				if (!empty($params))
				{
					echo '<br>';
					echo $xmlFile;
					echo '<br>';
					echo json_encode($params);
					echo '<br>';
					echo '<hr>';
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing parameterFromConfigFiles: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}


		return $parameter;
	}

	public function parameterValuesFromFile($paramsFile)
	{

		if (JFile::exists($paramsFile))
		{
			$cont = JFile::read($paramsFile);
		}
	}

}

