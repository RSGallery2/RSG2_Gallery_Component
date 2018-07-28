<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
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

class Rsgallery2ControllerConfig extends JControllerForm
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


    // ToDo: use JRoute::_(..., false)  for all redirects  -> $link = JRoute::_('index.php?option=com_foo&ctrl=bar',false);

    /**
     * On cancel raw view goto maintenance
     *
     * @param null $key (not used)
     *
     * @return bool
     *
     * @since version 4.3
     */
	public function cancel_rawView($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link);

		return true;
	}

    /**
     * Save changes in raw edit view value by value
     *
     * @since version 4.3
     */
	public function apply_rawEdit()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $msg = "apply_rawEdit: " . '<br>';
        $msgType = 'notice';

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            $model = $this->getModel('ConfigRaw');
	        $isSaved = $model->save();

        }
        $link = 'index.php?option=com_rsgallery2&view=config&layout=RawEdit';
        $this->setRedirect($link, $msg, $msgType);
    }
    /**
     * Save changes in raw edit view value by value
     *
     * @since version 4.3
     */
	public function save_rawEdit()
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
            $model = $this->getModel('ConfigRaw');
	        $isSaved = $model->save();
        }

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Save changes in raw edit view value by value
	 *
	 * @since version 4.3
	 */
	public function apply_rawEditOld()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "apply_rawEdit: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRawOld');
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
			$model = $this->getModel('ConfigRawOld');
			$isSaved = $model->save();
		}

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}

	/*-------------------------------------------------------------------------------------*/
	/**
	 * removes all entries fromm old
	 *
	 * @since version 4.3
	 */
	public function remove_OldConfigData()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "remove_OldConfigData: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else
		{
			// $model = $this->getModel('ConfigRawOld');
			$model     = $this->getModel('ConfigRaw');
			$isRemoved = $model->removeOldConfigData();

			if ($isRemoved)
			{
				$msg .= 'Successfully removed J2.5 configuration data';
			}
			else
			{
				$msg .= '!!! Failed at removing J2.5 configuration data !!! ';
				$msgType = 'error';
			}
		}

		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEditOld';
		// $link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
     * On cancel raw exit goto maintenance
     * @param null $key
     *
     * @return bool
     *
     * @since version 4.3
     */
	public function cancel_rawEdit($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link);

		return true;
	}

	/**
	 * Save changes in raw edit view value by value and copy items to the
	 * old configuration  data
	 *
	 * @since version 4.3
	 */
	public function save_rawEditAndCopyOld()
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
			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->save();
			$isSaved = $model->copyNew2Old();
		}

		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEditOld';
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
			$model = $this->getModel('ConfigRawOld');
			$isSaved = $model->saveOld();
			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->copyOld2New();
		}

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}


	/**
	 * Save changes in raw edit view value by value and copy items to the
	 * old configuration  data
	 *
	 * @since version 4.3
	 */
	public function copy_rawEditFromOld()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "copy_rawEditFromOld: " . '<br>';
		$msgType = 'notice';
		$isSaved = false;

			// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg .= JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->copyOld2New();
		}

		if ($isSaved)
		{
			$msg .= " done";
		}
		else
		{
			$msg .= " !!! not done !!!";
		}

		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEdit';
		$this->setRedirect($link, $msg, $msgType);
	}

/** yyyy */

	/**
	 * Save changes in raw edit view value by value and copy items to the
	 * old configuration  data
	 *
	 * @since version 4.3
	 */
	public function save_rawEdit2Text()
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

			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->save();
			$isSaved = $model->createConfigTextFile($ConfigParameter);
		}

		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEdit';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Read text file and copy items to the configuration  data
	 *
	 * @since version 4.3
	 */
	public function read_rawEdit2Text()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "write_rawEdit2Text: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->readConfigTextFile();
		}

		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEdit';
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

			$model = $this->getModel('ConfigRawOld');
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
			$model = $this->getModel('ConfigRawOld');
			$isSaved = $model->readConfigTextFileOld();
		}

		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEditOld';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Standard cancel (may not be used)
     *
	 * @param null $key
	 *
	 * @return bool
     *
     * @since version 4.3
	 */
	public function cancel($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		parent::cancel($key);

		$link = 'index.php?option=com_rsgallery2';
		$this->setRedirect($link);

		return true;
	}

    /**
     * Standard save of configuration
     * @param null $key
     * @param null $urlVar
     *
     * @since version 4.3
     */
	function save($key = null, $urlVar = null)
	{
		parent::save($key, $urlVar);

		$inTask = $this->getTask();

		if ($inTask != "apply")
		{
			// Don't go to default ...
			$this->setredirect('index.php?option=com_rsgallery2');
		}
	}

}

