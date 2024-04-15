<?php
/**
 * @version        $Id: admin.installer.php 1011 2011-01-26 15:36:02Z mirjam $
 * @package        Joomla
 * @subpackage     Installer
 * @copyright      (C) 2005-2024 RSGallery2 Team rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die();

require_once(rsgOptions_installer_path . '/helpers/template.php');
require_once(rsgOptions_installer_path . '/controller.php');

$controller = new InstallerController(array(
	'default_task' => 'installform',
	'model_path'   => rsgOptions_installer_path . '/models',
	'view_path'    => rsgOptions_installer_path . '/views'
));

$input = JFactory::getApplication()->input;

$type = $input->get('type', '', 'CMD');
$controller->set('task_type', $type);

$task = $input->get('task', '', 'CMD');
$controller->execute($task);

$controller->redirect();
