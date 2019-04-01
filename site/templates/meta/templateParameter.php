<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

/**
 * @package     ${NAMESPACE}
 *
 * Handles the files templateDetails.xml with param.ini
 * @since       4.4.3
 */
class Rsg2TemplateParameter
{
	protected $templatePath;

	protected $fieldsFileName    = 'templateDetails.xml';
	protected $parameterFileName = 'params.ini';

	//protected $templatePathFile;
	//protected $paramsPathFile;

	protected $paramNames = [];

	public $params; // JRegistry
	public $formFields = [];

	/**
	 * Rsg2TemplateParameter constructor.
	 * Loads parameter from files and URL
	 * 1) 'templateDetails.xml'defines parameter and standard value
	 * 2) param.ini values overwrites standard values
	 * 3) reading the params from URL overwrites parameters
	 *
	 * @param $templatePath path to files templateDetails.xml and params.ini'
	 */
	function __construct($templatePath)
	{
		// id, name, value
		$this->templatePath = $templatePath;
		$this->params = new JRegistry;

		$templatePathFile = $templatePath . '/' . $this->fieldsFileName;
		$paramsPathFile   = $templatePath . '/' . $this->parameterFileName;

		//--- templateDetails.xml -----------------------------------

		// config file exist
		if (!empty($templatePathFile))
		{
			//--- form fields and default parameter -------------------

			// extract form fields
			$formFields = $this->formFieldsFromTemplateFile($templatePathFile);

			// ignore found name when templateDetails.xml does not contain xml->config->fields part
			$this->formFields = [];
			if (!empty ($formFields))
			{
				$this->formFields = $formFields;
			}

			// Extract Form field parameters with default values
			// is Jquery
			$this->params = $this->extractFormFieldParameters ($formFields);

			// parameter names
			foreach ($this->params as $key => $value)
			{
				$this->paramNames [] = $key;
			}

			//--- overwrite with user parameter -------------------

			// extract parameter from params.ini file
			$userParams = $this->SettingsFromParamsFile ($paramsPathFile);
			$this->params->merge ($userParams);

			/**
			foreach ($userParams as $key => $value)
			{
				$this->params[$key] = value;
			}
			/**/

			//--- overwrite from URL -------------------

			$input = JFactory::getApplication()->input;
			foreach ($this->paramNames as $paramName)
			{
				// ToDo: check ?
				$value = $input->get($paramName, $this->params[$paramName], 'STRING');
				$this->params[$paramName] = $value;
			}

		}
	}

//			<fieldset name="advanced">
//				<field
//						id="isAutoStart"
//						name="isAutoStart"
//						type="radio"
//						default="1"
//						label="COM_RSGALLERY2_SLIDESHOW_AUTOSTART"
//						description="COM_RSGALLERY2_SLIDESHOW_AUTOSTART_DESC"
//						class="btn-group btn-group-yesno">
//					<option value="1">JYES</option>
//					<option value="0">JNO</option>
//				</field>


	/**
	 * @param $formFields
	 *   Extract Form field parameters with default values
	 *
	 * @return mixed
	 *
	 * @since version
	 * @throws Exception
	 */
	function extractFormFieldParameters ($formFields)
	{
		$params = new JRegistry;
		$paramsArray = [];

		try
		{
			if (!empty($formFields->config->fields->fieldset->field))
			{
				$domFields = dom_import_simplexml($formFields->config->fields->fieldset->field);
				$fields    = $formFields->config->fields->fieldset->field;
				foreach ($fields as $formField)
				{
					$this->RecurseXML($formField, $recurseXML11);
					$dom = dom_import_simplexml($formField);

					$test = $formField['name'];
					//echo $xml->book[0]['category'] . "<br>";
					//echo $xml->book[1]->title['lang'];
					$key   = (string) $formField ['name'];
					$value = (string) $formField ['default'];

					$paramsArray [$key] = $value;
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing extractFormFieldParameters: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		$params->loadArray($paramsArray);

		return $params;
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
		$formFields = [];
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
					$formFields = $xml;
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

		return $formFields;
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
				//$paramsArray = $paramLines->toArray();
				//$paramsFields['params'] = $paramsArray;
				//$params->loadArray($paramsFields);
				$params = $paramLines;
			}
			else
			{
				// throw file does not exist
				// throw new \RuntimeException('File not found or not readable');
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

	function debugsxml($sxml) {
		$dom = dom_import_simplexml($sxml);
		printArray($dom);
	}
	function printArray($array){
		echo '';
		print_r($array);
		echo '';
	}


	function RecurseXML($xml,&$vals,$parent="") {

		$childs=0;
		$child_count=-1; # Not realy needed.
		$arr=array();
		foreach ($xml->children() as $key=>$value) {
			if (in_array($key,$arr)) {
				$child_count++;
			} else {
				$child_count=0;
			}
			$arr[]=$key;
			$k=($parent == "") ? "$key.$child_count" : "$parent.$key.$child_count";
			$childs = $this->RecurseXML($value,$vals,$k);
			if ($childs==0) {
				$vals[$k]= (string)$value;
			}
		}

		return $childs;
	}



}