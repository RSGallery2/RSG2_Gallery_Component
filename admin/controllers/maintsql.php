<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2019 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.maintSql.php ');
}


jimport('joomla.application.component.controlleradmin');

/**
 * Functions for changes of database
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerMaintSql extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
     * @since 4.3.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

    /**
     * Does call the standard sql optimize function for tables
     *
     * @since 4.3.0
     */
	public function optimizeDB()
	{
		$msg     = '<strong>' . JText::_('COM_RSGALLERY2_MAINT_OPTDB') . ':</strong><br>';
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintSql');
			$msg .= $model->optimizeDB();
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	/**
	 * Proxy for getModel
	 * @param string $name
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
	 * @since 4.3.0
	 */
	public function getModel($name = 'maintSql', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	
	 * @since 4.3.0
    
    public function createMissingSqlFields()
    {
        $msg = '<strong>' . JText::_('COM_RSGALLERY2_MAINT_OPTDB') . ':</strong><br>';
        $msg = "Ctrl:compareDb2SqlFile: ";
        $msgType = 'notice';

        // Access check
        $canAdmin	= JFactory::getUser()->authorise('core.manage',	'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            $msg = nl2br ($msg);
        } else {

            // Model tells if successful
            $model = $this->getModel('maintSql');
            $msg .= $model->createMissingSqlFields();
        }

        $msg .= '!!! Not implemented yet !!!';

        $this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
    }
	*/

    /**
     * Creates table column access in table galleries and sets all values to '1'
     *
     * @since 4.3.0
     */
	public function createGalleryAccessField()
	{
		$msg     = '<strong>' . JText::_('COM_RSGALLERY2_CREATE_GALLERY_ACCESS_FIELD') . ':</strong><br>';
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintSql');
			$msg .= $model->createGalleryAccessField();
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

    /**
     * The function may be called from Maintenance Database if a mismatch between database and
     * component sql file is found.
     * It will check and repair following issues
     *    * Missing tables -> create
     *    * Missing columns -> create
     *    * Superfluous tables -> delete
     *    * Superfluous columns -> delete
     *    * ToDO: Wrong column types -> !!! not fixed
     *
     * @since 4.3.0
     */
	public function repairSqlTables()
	{
		$msg     = '<strong>' . JText::_('COM_RSGALLERY2_DATABASE_REPAIR_DESC') . ':</strong><br>';
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintSql');
			$msg .= $model->repairSqlTables();
		}

		// Back to check of database
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintDatabase', $msg, $msgType);
	}

	// ToDO: ? Move to comments ?
    /**
     * Check if database number of comments per image is wrong and fix it if neccesary
     *
     * @since 4.3.0
     */
	public function updateCommentsVoting()
	{
		$msg     = '<strong>Ctrl:' . JText::_('updateCommentsVoting') . ':</strong><br>';
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{

			// Model tells if successful
			$model = $this->getModel('maintSql');
			$msg .= $model->updateComments();
		}

		// Back to check of database
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

}

