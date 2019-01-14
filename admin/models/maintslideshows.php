<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
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

	// collect slideshow names from existing folder
	public function collectSlideshowsNames()
	{
		$slideshowNames = [];

		try
		{
			//--- search templateDetails.xml files ------------------

			$fileBasePath      = JPATH_COMPONENT_SITE . '/templates';

			// each folder may be a slideshow or a "semantic" image display

			$folders = JFolder::folders($fileBasePath);

			foreach ($folders as $folder)
			{
				// folder shall contain word slideshow
				if (strpos($folder, 'slideshow') !== false)
				{
					$slideshowNames [] = $folder;
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing collectSlideshowsNames: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		$slideshowNames = [];
	}
	/**/


	/***
		templateDetails.xml and params.ini file
	 */
	public function collectSlideshowsConfigData($slideshowName)
	{
		$configData = new stdClass();

		$configData->name = $slideshowName;

		try
		{
			$fieldsFileName    = 'templateDetails.xml';
			$parameterFileName = 'params.ini';
			$fileBasePath = JPATH_COMPONENT_SITE . '/templates/' . $slideshowName;

			$templatePath = $fileBasePath . '/' . $fieldsFileName;
			$paramsPath = $fileBasePath . '/' . $parameterFileName;

			//--- templateDetails.xml -----------------------------------

			// config file exist
			if (!empty($templatePath))
			{
				// extract form fields
				$formFields = $this->formFieldsFromTemplateFile($templatePath);

				// ignore found name when templateDetails.xml does not contain xml->config->fields part
				$configData->formFields = false;
				if (!empty ($formFields))
				{
					$configData->formFields = $formFields;
				}
			}

			//--- params.ini -----------------------------------

			// config file exist
			if (!empty($paramsPath))
			{
				$params = $this->SettingsFromParamsFile($paramsPath);

				$configData->parameterValues = false;

				if (!empty ($params))
				{
					$configData->parameterValues = $params;
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing collectSlideshowsConfigData: "' . $slideshowName . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $configData;
	}

	// may not be needed
	public function collectSlideshowsConfigFiles()
	{
		$configFiles = [];

		try
		{
			//--- search templateDetails.xml files ------------------

			$fieldsFileName    = 'templateDetails.xml';
			$parameterFileName = 'params.ini';
			$fileBasePath      = JPATH_COMPONENT_SITE . '/templates';

			// each folder may be a slideshow or a "semantic" image display

			$folders = JFolder::folders($fileBasePath);

			foreach ($folders as $folder)
			{
				$fileSlidePath = $fileBasePath . '/' . $folder;

				// check if joomla config file exist
				$cfgFile = JFolder::files($fileSlidePath, $fieldsFileName);
				if (!empty($cfgFile))
				{
					$configFiles [] = $fileSlidePath;
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing collectSlideshowsConfigFiles: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		$configFiles = [];
	}
	/**/

		/**
	 * Searches for xml files which defines installed slideshows
	 * Therefore checks for existing 'templateDetails.xml' file which will
	 * contain the parameters to be displayed
	 * Attention the found folders do contain templates for gallery view
	 * and meta data which has to be separated later on
	 *
	 * @return array
	 *
	 * @since version
	 */

	// ToDo: rename to slidesConfigData and make two functions out of it

	public function collectSlideshowsConfigFilesAll()
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

				// extract form fields
				$formFields = $this->formFieldsFromTemplateFile($foundSlideshow->cfgFieldsFileName);

				// ignore found name when templateDetails.xml does not contain xml->config->fields part
				if (!empty ($formFields))
				{
					$foundSlideshow->formFields = $formFields;

					/**
					$formFieldsReg = new JRegistry;
					$formFieldsReg->loadFile($foundSlideshow->cfgFieldsFileName, 'XML');
					$foundSlideshow->formFieldsReg = $formFieldsReg;
					/**/

					// extract settings from params.ini file
					$foundSlideshow->cfgParameterFileName = $fileSlidePath . '/' . $parameterFileName;
					$foundSlideshow->parameterValues = $this->SettingsFromParamsFile($foundSlideshow->cfgParameterFileName);

					// save found values
					$configFiles [] = $foundSlideshow;
				}

				//echo json_encode($foundSlideshow) ;
				//echo '<br>';
			}
		}

		return $configFiles;
	}


	/**
	 * @param $xmlFile
	 *
	 * @return SimpleXMLElement|stdClass
	 *
	 * @since version
	 * @throws Exception
	 */
	public function formFieldsFromTemplateFile($xmlFile)
	{
		$parameter = [];
		//$parameter = new stdClass();

		try
		{
			/**
			$xmlFile                    = $xmlFileInfo->cfgFieldsFileName;
			$formFields = JForm::getInstance($xmlFileInfo->name, $xmlFile);
			/**/

			$xml = simplexml_load_file($xmlFile);
			if (!empty($xml))
			{
				//$citizens = $xml->germany->citizens;
				//echo json_encode($xml);
				//echo '<br>';

				/**/
				//$params = $xml->install->params;
				$config = $xml->config->fields;
				if (!empty($config))
				{
					$parameter = $xml;

				}


				/**/
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing formFieldsFromTemplateFile: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}


		return $parameter;
	}

	/**
	 * @param $paramsFile  params.ini
	 *
	 *
	 * @since version
	 *
	public function addSettingsFromParamsFile($slidesConfigFiles)
	{
		try
		{
			foreach ($slidesConfigFiles as $xmlFileInfo)
			{
				$xmlFileInfo->parameterValues = '';
				//
				$slidesParamsFile = $xmlFileInfo->cfgParameterFileName;

				if (JFile::exists($slidesParamsFile))
				{
					$content = JFile::read($slidesParamsFile);
					if ( ! empty ($content ))
					{
						$xmlFileInfo->parameterValues = $content;

					}
				}
			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing addSettingsFromParamsFile: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return;
	}
	/**/


	/**
	 * setting from text lines of "params.ini" file
	 * @param $slidesParamsFile
	 *
	 * @return JRegistry
	 *
	 * @since version
	 * @throws Exception
	 */
	private function SettingsFromParamsFile($slidesParamsFile)
	{
		$params = new JRegistry;

		try
		{
			if (JFile::exists($slidesParamsFile))
			{
				$params->loadFile($slidesParamsFile, 'INI');
			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing SettingsFromParamsFile: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $params;
	}

}


