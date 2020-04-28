<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2020 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

/**/
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
//	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.config.php ');
}
/**/

jimport('joomla.application.component.controllerform');

class Rsgallery2ControllerConfig_J25 extends JControllerForm
{

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since 4.3.0
     */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Proxy for getModel.
	 * @param string $name
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
	 * @since 4.3.0
	 */
    public function getModel($name = 'Config', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

	/**
	 * Save changes in raw edit view value by value
	 *
	 * @since version 4.3
	 */
	public function apply_rawEditOld()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "apply_rawEdit Old (J2.5): " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRaw_J25');
			$isSaved = $model->saveOld();

		}
		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEdit';
		$this->setRedirect($link, $msg, $msgType);
	}
	/**
	 * Save changes in raw edit view value by value
	 *
	 * @since version 4.3
	 */
	public function save_rawEditOld()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "save_rawEdit Old (J2.5): " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRaw_J25');
			$isSaved = $model->save();
		}

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Save changes in old raw edit view value by value and copy the items
	 * to the new configuration data
	 *
	 * @since version 4.3
	 */
public function save_rawEditOldAndCopy2New()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "save_rawEdit: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRaw_25');
			$isSaved = $model->saveOld();
			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->copyOld2New();
		}

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Save changes in old raw edit view value by value and copy the items
	 * to the new configuration data
	 *
	 * @since version 4.3
	 */
	public function save_rawEditOld2Text()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "save_rawEdit: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$ConfigParameter = JComponentHelper::getParams('com_rsgallery2');
			$ConfigParameter = $ConfigParameter->toArray();

			$model = $this->getModel('ConfigRaw_J25');
			$isSaved = $model->saveOld();
			$isSaved = $model->createConfigTextFileOld($ConfigParameter);
		}

		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEditOld';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Read text file and copy items to the old configuration data
	 *
	 * @since version 4.3
	 */
	public function read_rawEditOld2Text()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "write_rawEditOld2Text: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRaw_J25');
			$isSaved = $model->readConfigTextFileOld();
		}

		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEditOld';
		$this->setRedirect($link, $msg, $msgType);
	}


}

