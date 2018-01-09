<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2018 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Returns HTML to dislpay the link to the RSGALLERY2 configuration
 *
 *
 * @since 4.3.0
 */
class JFormFieldRsg2Link extends JFormField
{
    /**
     * The field type.
     *
     * @var string
     *
     * @since 4.3.0
     */
	protected $type = 'Rsg2Link';

	/**
	 * Creates html for buton with link to Returns HTML to dislpay the link to the RSGALLERY2 configuration
	 *
	 * @access protected
	 * @return    string    The field input markup.
     *
     * @since 4.3.0
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		$link = 'index.php?option=com_rsgallery2&view=config&amp;task=config.edit';

		$linktext = $this->element['linktext'];
		if (empty ($linktext))
		{
			$linktext = '"linktext" not defined in field element xml';
		}

		/**/
		$html[] = '<ul class="nav nav-pills">';
		$html[] = '    <li class="active">';
		$html[] = '        <a href="' . $link . '" >';
		$html[] = '            ' . JText::_($linktext);
		$html[] = '        </a>';
		$html[] = '    </li>';
		$html[] = '</ul>';
		/**/

		return implode($html);
	}
}

