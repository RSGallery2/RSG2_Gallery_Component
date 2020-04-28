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

	    JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	    // Access check
        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
			$msg = nl2br ($msg);
        } else {
            $model = $this->getModel('ConfigRaw');
            $msg .= $model->save();

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

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
			$msg = nl2br ($msg);
        } else {
            $model = $this->getModel('ConfigRaw');
            $msg .= $model->save();
        }

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
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
	 * Save changes in raw edit view value by value
	 *
	 * @since version 4.3
	 */
	public function reset2default()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "reset2default: " . '<br>';
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		} else {
			$model = $this->getModel('ConfigRaw');

			$isSaved = $model->reset2default();

			$msg  = "Reset configuration to default values ";
			if ($isSaved)
			{
				$msg .= "successful";
			}
			else
			{
				$msg .= "failed !!!";
			}
		}

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
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

