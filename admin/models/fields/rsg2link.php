<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Gallery Form Field class to create contents of dropdown box for 
 * gallery selection in RSGallery2.
 */
class JFormFieldRsg2Link extends JFormField {
	/**
	 * The field type.
	 *
	 * @var         string
	 */
    protected $type = 'Rsg2Link';

	/**
	 *
	 */

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.6
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'linktext':
				return $this->$name;
		}

		return parent::__get($name);
	}



	/**
	 * Method to get the field input markup.
	 * @access protected
	 * @return    string    The field input markup.
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		$link = 'index.php?option=com_rsgallery2&view=config&amp;task=config.edit';

		//Load user example. REPLACE WITH YOUR CODE
		// $html[] = '<input type="text" name="totalprice" value="' . $your_data->value . '" />';

		/**
		 * $html[] = '<a href="' . $link . '">';
		 * $html[] =     'Test' . $this->linktext;
		 * $html[] = '</a">';
		 * /**/

		/**
		 * $html[] = '    <a href="' . $link . '" >';
		 * $html[] = '        Test' . $this->linktext;
		 * $html[] = '    </a">';
		 * /**/

		/**
		 * $html[] = '<div class="hero-unit">';
		 * $html[] = '    <a href="' . $link . '" >';
		 * $html[] = '        Test' . $this->linktext;
		 * $html[] = '    </a">';
		 * $html[] = '</div>';
		 * /**/


		//echo "<br>" . "element: " . json_encode ($this) . "<br>" . "<br>" . "<br>" ;
		echo "<br>" . "element: " . json_encode ($this->element) . "<br>" . "<br>" ;
		echo "<br>" . "element ['@attributes']: " . json_encode ($this->element["@attributes"]) . "<br>" . "<br>" ;
		//echo "<br>" . "element: " . json_encode ($this->element["@attributes"].linktext) . "<br>" . "<br>" ;
		//echo "<br>" . "element: " . json_encode ($this->element["@attributes"]->linktext) . "<br>" . "<br>" ;
		echo "<br>" . "attributes: " . json_encode ($this->element->attributes) . "<br>" . "<br>" . "<br>" ;
		echo "<br>" . "attributes: " . json_encode ($this->element->attributes) . "<br>" . "<br>" . "<br>" ;




		$linktext = $this->element->attributes['linktext'];
		if (empty ($linktext))
		{
			$linktext = '"linktext" not defined in config xml';
		}

		/**/
		$html[] = '<ul class="nav nav-pills">';
		$html[] = '    <li class="active">';
		$html[] = '        <a href="' . $link . '" >';
		$html[] = '            ' . $linktext;
		$html[] = '        </a>';
		$html[] = '    </li>';
		$html[] = '</ul>';
		/**/

		return implode($html);
	}
}

