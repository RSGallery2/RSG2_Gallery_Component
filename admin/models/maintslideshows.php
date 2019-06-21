<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2019 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

// No direct access to this file
defined('_JEXEC') or die;

// import Joomla model form library
jimport('joomla.application.component.modeladmin');

/**
 * support of maintenance slideshows handling
 *
 * @since 4.4.2
 */


/**
 * @package     ${NAMESPACE}
 *
 * @since       version
 */
class rsgallery2ModelMaintSlideshows extends JModelList
{
	/**
	 * collectSlideshowNames
	 * Collects slideshow names from folder site:templates folder
	 * In the folder each sub folder is checked for folders
	 * containing name "slideshow"
	 *
	 * @return array slideshow names
	 *
	 * @since 4.4.2
	 * @throws Exception
	 */
	public function collectSlideshowNames()
	{
		$slideshowNames = [];

		try
		{
			//--- base folder ---------------------------------------

			$fileBasePath = JPATH_COMPONENT_SITE . '/templates';

			//--- all folders within ------------------

			$folders = JFolder::folders($fileBasePath);

			foreach ($folders as $folder)
			{
				// collect if name contains word slideshow
				if (strpos($folder, 'slideshow') !== false)
				{
					$slideshowNames [] = $folder;
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing collectSlideshowNames: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $slideshowNames;
	}
	/**/

	/**
	 * collectSlideshowFilesData
	 * Collects form field definitions from file templateDetails.xml
	 * Collects param and values from file params.ini
	 * Both files live in folder site: /template/<slideshowName>
	 *
	 * @param string $slideshowName
	 *
	 * @return stdClass
	 *
	 * @since 4.4.2
	 * @throws Exception
	 */
	public function collectSlideshowFilesData($slideshowName)
	{
		$configData = new stdClass();

		// Init return result
		$configData->name            = $slideshowName;
		$configData->parameterValues = new JRegistry;;

		try
		{
			//--- folder and file names preparation ------------------------

			$fieldsFileName    = 'templateDetails.xml';
			$parameterFileName = 'params.ini';
			$userCssFileName   = 'user.css';

			$fileBasePath      = JPATH_COMPONENT_SITE . '/templates/' . $slideshowName;

			$templatePathFile = $fileBasePath . '/' . $fieldsFileName;
			$paramsPathFile   = $fileBasePath . '/' . $parameterFileName;
			$userCssPathFile   = $fileBasePath . '/css/' . $userCssFileName;

			//--- templateDetails.xml -----------------------------------

			// config file exist
			if (!empty($templatePathFile))
			{
				$configData->cfgFieldsFileName = $templatePathFile;

				// extract form fields
				$formFields = $this->formFieldsFromTemplateFile($templatePathFile);

				// ignore found name when templateDetails.xml does not contain xml->config->fields part
				$configData->formFields = false;
				if (!empty ($formFields))
				{
					$configData->formFields = $formFields;
				}
			}

			//--- params.ini -----------------------------------

			// Does config file exist
			if (!empty($paramsPathFile))
			{
				$configData->parameterValues = $this->SettingsFromParamsFile($paramsPathFile);
			}

			//--- user css -----------------------------------

			$configData->userCssText = '';
			if (file_exists ($userCssPathFile))
			{
				$configData->userCssText = file_get_contents ($userCssPathFile);
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing collectSlideshowFilesData: "' . $slideshowName . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $configData;
	}

	/**
	 * formFieldsFromTemplateFile
	 * Extracts form field definitions from file templateDetails.xml
	 * Return form fields if section .../config/fields
	 * exist in XML of file
	 *
	 * @param $xmlFile
	 *
	 * @return SimpleXMLElement|stdClass form fields usable in render field set
	 *
	 * @since 4.4.2
	 * @throws Exception
	 */
	public function formFieldsFromTemplateFile($xmlFile)
	{
		$formfields = [];
		//$parameter = new stdClass();

		try
		{
			//--- read XML of file ---------------------------------------

			$xml = simplexml_load_file($xmlFile);
			if (!empty($xml))
			{
				// return if section .../config/fields exist
				$config = $xml->config->fields;
				if (!empty($config))
				{
					$formfields = $xml;
				}
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


		return $formfields;
	}

	/**
	 * SettingsFromParamsFile
	 * returns variable=value registry from given file
	 * Expected is a "params.ini" file containing lines
	 * in the form automated_slideshow="1"
	 *
	 * The form needs this data to be in section 'params'
	 * Therefore
	 *
	 * @param string $slidesParamsFile
	 *
	 * @return JRegistry with parameter of slideshow
	 *
	 * @since 4.4.2
	 * @throws Exception
	 */
	private function SettingsFromParamsFile($slidesParamsFile='')
	{
		$params = new JRegistry;

		try
		{
			//--- Read file content into registry object ------------------
			if (JFile::exists($slidesParamsFile))
			{
				// Extract data lines from file
				$paramLines = new JRegistry;
				$paramLines->loadFile($slidesParamsFile, 'INI');

				// order data into section params
				$paramsArray = $paramLines->toArray();
				$paramsFields['params'] = $paramsArray;
				$params->loadArray($paramsFields);
			}
			else
			{
				// throw file does not exist
				//throw new \RuntimeException('File not found or not readable');
				$OutTxt = '';
				$OutTxt .= 'Attention: Parameter file does not exist"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'notice');
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


