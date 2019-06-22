<?php
/**
 * @version        $Id: controller.php 1011 2011-01-26 15:36:02Z mirjam $
 * @package        Joomla
 * @subpackage     Installer
 * @copyright      (C) 2005-2019 RSGallery2 Team
 * @license        GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
jimport('joomla.client.helper');

/**
 * Installer Controller
 *
 * @package        Joomla
 * @subpackage     Installer
 * @since          1.5
 */
class InstallerController extends JControllerLegacy
{
	/**
	 * Display the extension installer form
	 *
	 * @access    public
	 * @return    void
	 * @since     1.5
	 * @throws Exception
	 */
	function installform()
	{
		$model = $this->getModel('Install');
		$view  = $this->getView('Install', '', '', array('base_path' => rsgOptions_installer_path));

		$ftp       = JClientHelper::setCredentialsFromRequest('ftp');
		$view->ftp = $ftp;

		$view->setModel($model, true);
		$view->display();
	}

	/**
	 * Install an extension
	 *
	 * @access    public
	 * @return    void
	 * @since     1.5
	 */
	function doInstall()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('Install');
		$view  = $this->getView('Install', '', '', array('base_path' => rsgOptions_installer_path));

		$ftp       = JClientHelper::setCredentialsFromRequest('ftp');
		$view->ftp = $ftp;

		if ($model->install())
		{
			$cache = JFactory::getCache('mod_menu');
			$cache->clean();
		}

		$view->setModel($model, true);
		$view->display();
	}

	/**
	 * List all templates
	 *
	 * @access    public
	 * @return    void
	 * @since     1.5
	 */
	function manage()
	{
		$model = $this->getModel('templates');
		$view  = $this->getView('templates', '', '', array('base_path' => rsgOptions_installer_path));

		$ftp       = JClientHelper::setCredentialsFromRequest('ftp');
		$view->ftp = $ftp;

		$view->setModel($model, true);
		$view->display();
	}

	/**
	 * Set template as default
	 *
	 * @access    public
	 * @return    void
	 * @since     1.5
	 */
	function setDefault()
	{

		global $rsgConfig;
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$rsgConfig->set('template', $template);
		$rsgConfig->saveConfig();
		$this->manage();

	}

	/**
	 * Remove an extension (Uninstall)
	 *
	 * @access    public
	 * @return    void
	 * @since     1.5
	 */
	function remove()
	{
		global $rsgConfig;

		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		if ($rsgConfig->template == $template)
		{
			JFactory::getApplication()->enqueueMessage('Can not delete default template. Select an other template and then delete this one.', 'warning');
		}
		else
		{
			JFolder::delete(JPATH_RSGALLERY2_SITE. '/' ."templates". '/' .$template);
		}

		$this->manage();
	}

	/**
	
	 * @since 4.3.0
    */
	function template()
	{
		switch ($this->get('task_type', 'templateGeneral'))
		{

			case "templateCSS":
				$this->selectCSS();
				break;
			case "templateHTML":
				$this->selectHTML();
				break;
			case "templateGeneral":
			case "templates":
				$this->editTemplate();
				break;
		}

	}

	/**
	 * edit the base data of a template
	 *
	 * @access    public
	 * @return    void
	 * @since     RSG 1.5
	 * @author    John Caprez (john@porelaire.com)
	 */
	function editTemplate()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('template');
		$view  = $this->getView('template', '', '', array('base_path' => rsgOptions_installer_path));

		$ftp       = JClientHelper::setCredentialsFromRequest('ftp');
		$view->ftp = $ftp;

		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$model->template = $template;

		$view->setModel($model, true);
		$view->display();

	}

	/**
	 * apply chnages to template
	 *
	 * @access    public
	 * @return    void
	 * @since     RSG 1.5
	 * @author    John Caprez (john@porelaire.com)
	 */
	function applyTemplate()
	{

		$model = $this->getModel('template');
		$view  = $this->getView('template', '', '', array('base_path' => rsgOptions_installer_path));

		$ftp       = JClientHelper::setCredentialsFromRequest('ftp');
		$view->ftp = $ftp;

		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$params = $input->post->get('params', array(), 'ARRAY');

		$model->set('template', $template);
		$model->set('params', $params);
		$model->update();

		$view->setModel($model, true);
		$view->display();

	}

	/**
	 * save chenges to template
	 *
	 * @access    public
	 * @return    void
	 * @since     RSG 1.5
	 * @author    John Caprez (john@porelaire.com)
	 */
	function saveTemplate()
	{

		$model = $this->getModel('template');

		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$params = $input->post->get('params', array(), 'ARRAY');

		$model->set('template', $template);
		$model->set('params', $params);

		$model->update();

		$this->manage();
	}

	/**
	 * select witch css file has to be edited
	 *
	 * @access    public
	 * @return    void
	 * @since     RSG 1.5
	 * @author    John Caprez (john@porelaire.com)
	 */
	function selectCss()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('selectCss');
		$view  = $this->getView('selectCss', '', '', array('base_path' => rsgOptions_installer_path));

		$ftp       = JClientHelper::setCredentialsFromRequest('ftp');
		$view->ftp = $ftp;

		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$model->template = $template;

		$view->setModel($model, true);
		$view->display();
	}

	/**
	 * edit a CSS file
	 *
	 * @access    public
	 * @return    void
	 * @since     RSG 1.5
	 * @author    John Caprez (john@porelaire.com)
	 */
	function editCSS()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('editCss');
		$view  = $this->getView('editCss', '', '', array('base_path' => rsgOptions_installer_path));

		$ftp       = JClientHelper::setCredentialsFromRequest('ftp');
		$view->ftp = $ftp;

		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$model->template = $template;
		$model->filename = $input->get('filename');

		$view->setModel($model, true);
		$view->display();
	}

	/**
	
	@since 4.3.0
	*/
	function saveCSS()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('editCss');
		$input           = JFactory::getApplication()->input;
		$model->filename = $input->get('filename');

		$model->content = $input->post->get('csscontent', 'RAW');
		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$model->save();

		$this->selectCss();
	}
	
	/**
	
	@since 4.3.0
	*/
	function applyCSS()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('editCss');
		$input           = JFactory::getApplication()->input;
		$model->filename = $input->get('filename');

		$model->content = $input->post->get('csscontent', 'RAW');
		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$model->save();

		$this->editCSS();
	}

	/**
	
	@since 4.3.0
	*/
	function cancelCSS()
	{
		$this->selectCss();
	}

	/**
	 * select witch html file has to be edited
	 *
	 * @access    public
	 * @return    void
	 * @since     RSG 1.5
	 * @author    John Caprez (john@porelaire.com)
	 */
	function selectHTML()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('selectHtml');
		$view  = $this->getView('selectHtml', '', '', array('base_path' => rsgOptions_installer_path));

		$ftp       = JClientHelper::setCredentialsFromRequest('ftp');
		$view->ftp = $ftp;

		$input           = JFactory::getApplication()->input;
		$template        = $input->get('template');
		$model->template = $template;

		$view->setModel($model, true);
		$view->display();
	}

	/**
	 * edit a HTML file
	 *
	 * @access    public
	 * @return    void
	 * @since     RSG 1.5
	 * @author    John Caprez (john@porelaire.com)
	 */
	function editHTML()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('editHtml');
		$view  = $this->getView('editHtml', '', '', array('base_path' => rsgOptions_installer_path));

		$ftp       = JClientHelper::setCredentialsFromRequest('ftp');
		$view->ftp = $ftp;

		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$model->template = $template;
		$model->filename = $input->get('filename');

		$view->setModel($model, true);
		$view->display();
	}

	/**
	
	
	porelaire.com*/
	function saveHTML()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('editHtml');
		$input           = JFactory::getApplication()->input;
		$model->filename = $input->get('filename');

		$model->content = $input->post->get('htmlcontent', 'RAW');
		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$model->save();

		$this->selectHTML();
	}

	/**
	
	porelaire.com*/
	function applyHTML()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$model = $this->getModel('editHtml');
		$input           = JFactory::getApplication()->input;
		$model->filename = $input->get('filename');

		$model->content = $input->post->get('htmlcontent', 'RAW');
		$input    = JFactory::getApplication()->input;
		$template = $input->get('template');

		$model->save();

		$this->editHTML();
	}

	/**
	
	porelaire.com*/
	function cancelHTML()
	{
		$this->selectHTML();
	}
}




