<?php
/**
 * @package        RSGallery2
 * @subpackage     TemplateManager
 * @copyright      Copyright (C) 2005 - 2018 RSGallery2
 * @license        GNU/GPL, see LICENSE.php
 */

// Import library dependencies
require_once(dirname(__FILE__) . DS . 'extension.php');
jimport('joomla.filesystem.folder');

/**
 * RSGallery2 Template Manager Template Model
 *
 * @package        RSGallery2
 * @subpackage     TemplateManager
 * @since          1.5
 */
class InstallerModelTemplate extends InstallerModel
{
	/**
	 * Extension Type
	 *
	 * @var    string
	 */
	var $_type = 'template';
	var $template = '';

	/**
	 * Overridden constructor
	 *
	 * @access    protected
	 */
	function __construct()
	{
		$mainframe = JFactory::getApplication();

		// Call the parent constructor
		parent::__construct();

		// Set state variables from the request
		$this->setState('filter.string', $mainframe->getUserStateFromRequest("com_rsgallery2_com_installer.templates.string", 'filter', '', 'string'));
	}

	/**
	 * @return object|stdClass
	 */
	function getItem()
	{
		jimport('joomla.filesystem.path');
		if (!$this->template)
		{
			// return JError::raiseWarning( 500, 'Template not specified' );
			JFactory::getApplication()->enqueueMessage(
				'Template not specified'
				, 'warning');

			return null;
		}

		$tBaseDir = JPath::clean(JPATH_RSGALLERY2_SITE . DS . 'templates');

		if (!is_dir($tBaseDir . DS . $this->template))
		{
			// return JError::raiseWarning( 500, 'Template not found' );
			JFactory::getApplication()->enqueueMessage(
				'Template not found'
				, 'warning');

			return null;
		}
		$lang = JFactory::getLanguage();
		$lang->load('tpl_' . $this->template, JPATH_RSGALLERY2_SITE);

		$ini = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . $this->template . DS . 'params.ini';
		$xml = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . $this->template . DS . 'templateDetails.xml';
		$row = TemplatesHelper::parseXMLTemplateFile($tBaseDir, $this->template);

		jimport('joomla.filesystem.file');
		// Read the ini file
		if (JFile::exists($ini))
		{
			//$content = JFile::read($ini); J3
			$content = JFile::file_get_contents($ini);
		}
		else
		{
			$content = null;
		}

		// $params = new JParameter($content, $xml, 'template');
		$jparams = new JRegistry();
		$params  = $jparams->get($content, $xml);  // Ignore parameter 'template' ?		

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		$ftp = JClientHelper::setCredentialsFromRequest('ftp');

		$item           = new stdClass();
		$item->params   = $params;
		$item->row      = $row;
		$item->type     = $this->_type;
		$item->template = $this->template;

		return $item;

	}

	/**
	 * Updates the template parameter file
	 *
	 * @access protected
	 * @throws Exception
	 */
	function update()
	{

		global $rsgConfig;

		$app = &JFactory::getApplication();

		if (!$this->template)
		{
			JError::raiseError(500, "No template specified");

			return;
		}

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');

		$file = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . $this->template . DS . 'params.ini';

		jimport('joomla.filesystem.file');
		if (JFile::exists($file) && count($this->params))
		{
			$txt = null;
			foreach ($this->params as $k => $v)
			{
				$txt .= "$k=$v\n";
			}

			// Try to make the params file writeable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755'))
			{
				//JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_RSGALLERY2_COULD_NOT_MAKE_THE_TEMPLATE_PARAMETER_FILE_WRITABLE'));
				JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_COULD_NOT_MAKE_THE_TEMPLATE_PARAMETER_FILE_WRITABLE'), 'error');

				return;
			}

			$return = JFile::write($file, $txt);

			// Try to make the params file unwriteable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555'))
			{
				//JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_RSGALLERY2_COULD_NOT_MAKE_THE_TEMPLATE_PARAMETER_FILE_UNWRITABLE'));
				JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_COULD_NOT_MAKE_THE_TEMPLATE_PARAMETER_FILE_UNWRITABLE'), 'error');

				return;
			}

		}

		$app->enqueueMessage('Template saved');

	}
}
