<?php // no direct access
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2021 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();


JHtml::_('bootstrap.tooltip');

global $Rsg2DebugActive;

global $rsgConfig;
$this->configVars = $rsgConfig;

/* Sort config variables */
$configVars = array();
foreach ($this->configVars as $name => $value)
{
	$configVars [$name] = $value;
}
ksort($configVars);

/**
 * Echos an input field for config variables
 *
 * @param string $name  name of config variable
 * @param string $value of config variable
 * @since 4.3.0
	 */
function configInputField($name = 'unknown', $value = '')
{
    try
    {
	    if (! is_string ($name))
	    {
		    $name = 'configInputField: Name is not a string';
	    }

	    if (! is_string ($value))
	    {
	        if (gettype($value) == 'array')
            {
	            $value = implode (',' , $value);
            }
            else
            {
	            $value = 'Value type is ' . gettype($value) . ' and not a string';
            }
	    }

	?>

	<div class="control-group">
		<div class="control-label">
			<label id="jform_<?php echo $name ?>-lbl" class="jform_control-label"
					for="jform_<?php echo $name ?>"><?php echo $name ?>:</label>
		</div>
		<div class="controls">
			<input id="jform_<?php echo $name ?>" class="input-xxlarge input_box" type="text"
					value="<?php echo $value ?>" size="70" name="jform[<?php echo $name ?>] aria-invalid=" false">
		</div>
	</div>

	<?php
	/*
	<div class="control-group">
		<label class="control-label" for="<?php echo $name?>"><?php echo $name?>:</label>
		<div class="controls">
			<input id="<?php echo $name?>" class="input-xxlarge input_box" type="text"
				value="<?php echo $value?>" size="70" name="<?php echo $name?>">
		</div>
	</div>

	<td>version</td>
	<td>
		<input type="text" value="4.1.0" name="version">
	</td>
	*/
}
    catch (RuntimeException $e)
    {
	    $OutTxt = '';
	    $OutTxt .= 'Error rawEdit view: "' . 'configInputField' . '"<br>';
	    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

	    $app = JFactory::getApplication();
	    $app->enqueueMessage($OutTxt, 'error');
    }

}

?>

<div id="installer-install" class="clearfix">
	<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=config&amp;layout=RawEdit'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

				<legend><?php echo JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT'); ?></legend>
                <div><strong><?php echo JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT'); ?></strong><br></div>

				<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'ConfigRawView')); ?>

				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'ConfigRawView', JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT', true)); ?>

				<?php
				try
				{

				/**/
				foreach ($configVars as $name => $value)
				{
					configInputField($name, $value);
				}
				}
                catch (RuntimeException $e)
                {
                    $OutTxt = '';
                    $OutTxt .= 'Error rawEdit view: "' . 'configInputField' . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = JFactory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

				?>

				<?php echo JHtml::_('bootstrap.endTab'); ?>

				<?php echo JHtml::_('bootstrap.endTabSet'); ?>

				<!--input type="hidden" name="option" value="com_rsgallery2" />
				<input type="hidden" name="rsgOption" value="maintenance" /-->

				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
		<div id="loading"></div>
	</div>
</div>
