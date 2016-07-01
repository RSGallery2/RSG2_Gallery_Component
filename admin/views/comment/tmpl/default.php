<?php // no direct access
defined( '_JEXEC' ) or die();

JHtml::_('behavior.tooltip');

global $Rsg2DebugActive;

// public static $extension = 'COM_RSG2';

//$doc = JFactory::getDocument();
//$doc->addStyleSheet (JURI::root(true)."/administrator/components/com_rsgallery2/css/Maintenance.css");


?>

<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=gallery'); ?>"
      method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

		<div>
			<h1> standard config edit not ready</h1>
        </div>

        <div>
			<!--input type="hidden" name="option" value="com_rsgallery2" />
			<input type="hidden" name="rsgOption" value="maintenance" />

            <input type="hidden" name="task" value="" /-->
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>

