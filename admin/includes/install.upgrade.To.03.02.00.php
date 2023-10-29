<?php
/**
 * This file contains the upgrade routines for RSGallery2
 *
 * @version       $Id: install.upgrade.php
 * @package       RSGallery2
 * @copyright (C) 2008-2023 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

// no direct access
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');

//require_once( $rsgClasses_path . 'file.utils.php' );

global $rsgConfig;
global $rsgVersion;

/**
 * upgrade rsgallery data content
 * It cares only for data itself not for the table structure
 *
 * @package RSGallery2
 */
class upgrade_com_rsgallery2_03_02_00
{ // extends GenericMigrator { // old: migrate_com_rsgallery
	/* public functions */

	/*
	* upgrade from old versions 
	* ToDo: Check if SQL is done before or after 
	* (Attention the SQL part may or may not be handled before)
	* It cares only for data itself not for the table structure
	* @return true on failure
	 * @since 4.3.0
    */
	function upgrade($oldRelease)
	{

		$failed = false;

		// Compare version numbers. Each update is applied successively until finished.
		// This is caused by no break statements

		switch (true)
		{
			//case version_compare (oldRelease, '1.11.0', 'lt'):
			//case version_compare (oldRelease, '1.11.1', 'lt'):
			//case version_compare (oldRelease, '1.11.8', 'lt'):
			//case version_compare (oldRelease, '1.11.11', 'lt'):
			//case version_compare (oldRelease, '1.12.0', 'lt'):
			case version_compare($oldRelease, '1.12.2', 'lt'):
				$failed |= $this->upgradeTo_1_12_2();

			//case version_compare (oldRelease, '1.13.2', 'lt'):
			//case version_compare (oldRelease, '1.14.0', 'lt'):
			//case version_compare (oldRelease, '1.14.1', 'lt'):
			case version_compare($oldRelease, '2.2.1', 'lt'):
				$failed |= $this->upgradeTo_2_2_1();

			//case version_compare (oldRelease, '3.0.0', 'lt'):
			case version_compare($oldRelease, '3.0.2', 'lt'):
				rsgInstall::writeInstallMsg(JText::_('COM_RSGALLERY2_UPDATEINFO_302'), 'ok');
			//case version_compare (oldRelease, '3.1.1', 'lt'):
			case version_compare($oldRelease, '3.2.0', 'lt'):
				$failed |= $this->upgradeTo_3_2_0();

			default:
		}

		return $failed;
	}

	/*-------------------------------------------------------------------
	Special upgrade handling for various versions is below 
	-------------------------------------------------------------------*/

	/**
	 * Removes hard coded table jos_rsgallery2_acl if jos is not the prefix
	 * in some version prior to 1.12.2 #__rsgallery2_acl was hard coded with the prefix jos.
	 * if Joomla! was installed using a different prefix then #__rsgallery2_acl will be missing.
	 *
	 * @todo this needs to be tested
	 * @since 4.3.0
     */
	function upgradeTo_1_12_2()
	{
		$failed = false;

		try
		{
			JLog::add("upgradeTo_1_12_2", JLog::DEBUG);

			$app    = JFactory::getApplication();
			$prefix = $app->get('dbprefix');

			// if prefix is jos, then it doesn't matter.
			if ($prefix == 'jos_')
			{
				return $failed;
			}

			// Is the proper table missing ?  
			// Attention if the sql upgrade is already done then it may exist anyhow 
			$database = JFactory::getDBO();
			if (in_array($prefix . 'rsgallery2_acl', $database->getTableList()) === false)
			{
				// #__rsgallery2_acl does not exist

				// first we create the table
				// $this->handleSqlFile( 'upgrade_1.12.1_to_1.12.2.sql' );

				// now remove jos_rsgallery2_acl if it does not belong
				// we only want to do this if it is empty and there is no other joomla installed using jos_
				$database->setQuery("SHOW TABLES LIKE 'jos_content'");
				$database->execute();
				if ($database->getNumRows() == 1)
				{
					return $failed; // joomla using jos_ exists
				}

				// check if table has data
				$database->setQuery("SELECT * FROM `jos_rsgallery2_acl`");
				$database->execute();
				if ($database->getNumRows() > 0)
				{
					return $failed; // table not empty, leave it alone
				}

				$database->setQuery("DROP TABLE `jos_rsgallery2_acl`");
				$database->execute();
			}
		}
		catch (Exception $e)
		{
			$ErrText = 'Exception in upgradeTo_1_12_2: ' . $e->getMessage();
			echo $ErrText . "\n";
			JLog::add($ErrText, JLog::DEBUG);
			$failed = true;
		}

		return $failed;
	}

	/**
	 * Create not existing aliases
	 * @since 4.3.0
     */
	function upgradeTo_2_2_1()
	{
		$failed = false;

		try
		{
			// There is a new field 'alias in tables #__rsgallery2_galleries and 
			// #__rsgallery2_files and it needs to be filled as our SEF router uses it
			JLog::add("upgradeTo_2_2_1", JLog::DEBUG);

			$db = JFactory::getDBO();

			//Get id, name for the galleries
			$query = 'SELECT id, name FROM #__rsgallery2_galleries';
			$db->setQuery($query);
			$result = $db->loadAssocList();

			//...and make alias from name
			foreach ($result as $key => $value)
			{
				jimport('joomla.filter.filteroutput');
				$result[$key][alias] = JFilterOutput::stringURLSafe($value[name]);
			}

			//save the alias
			foreach ($result as $key => $value)
			{
				$query = 'UPDATE #__rsgallery2_galleries '
					. ' SET `alias` = ' . $db->quote($value[alias])
					. ' WHERE `id` = ' . (int) $value[id];
				$db->setQuery($query);
				$result = $db->execute();
				if (!$result)
				{
					$msg = JText::_('COM_RSGALLERY2_MIGRATE_ERROR_FILLING_ALIAS_GALLERY', $value[id], $value[name]);
					JFactory::getApplication()->enqueueMessage($msg, 'error');
					$failed = true;
				}
			}

			//Get id, title for the items
			$query = 'SELECT id, title FROM #__rsgallery2_files';
			$db->setQuery($query);
			$result = $db->loadAssocList();

			//...and make alias from title
			foreach ($result as $key => $value)
			{
				jimport('joomla.filter.filteroutput');
				$result[$key][alias] = JFilterOutput::stringURLSafe($value[title]);
			}

			//save the alias
			foreach ($result as $key => $value)
			{
				$query = 'UPDATE #__rsgallery2_files '
					. ' SET `alias` = ' . $db->quote($value[alias])
					. ' WHERE `id` = ' . (int) $value[id];
				$db->setQuery($query);
				$result = $db->execute();
				if (!$result)
				{
					$msg = JText::_('COM_RSGALLERY2_MIGRATE_ERROR_FILLING_ALIAS_ITEM', $value[id], $value[title]);
					JFactory::getApplication()->enqueueMessage($msg, 'error');
					$failed = true;
				}
			}

			if ($failed)
			{
				rsgInstall::writeInstallMsg(JText::_('COM_RSGALLERY2_FINISHED_CREATING_ALIASES'), 'error');
			}
			else
			{
				rsgInstall::writeInstallMsg(JText::_('COM_RSGALLERY2_FINISHED_CREATING_ALIASES'), 'ok');
			}
		}
		catch (Exception $e)
		{
			$ErrText = 'Exception in upgradeTo_2_2_1: ' . $e->getMessage();
			echo $ErrText . "\n";
			JLog::add($ErrText, JLog::DEBUG);
			$failed = true;
		}

		return $failed;
	}

	/**
	 * Change comments in table from BB Code to HTML
	 * @since 4.3.0
     */
	function upgradeTo_3_2_0()
	{
		$failed = false;

		try
		{
			JLog::add("upgradeTo_3_2_0", JLog::DEBUG);

			// Change comments in table from BB Code to HTML
			$database = JFactory::getDBO();

			$query = 'SELECT id, comment FROM #__rsgallery2_comments';
			$database->setQuery($query);
			$comments = $database->loadAssocList();

			$rsgComment = new rsgCommentsOld();

			foreach ($comments as $comment)
			{
				//Parse BBCode comment to HTML comment
				$comment['comment'] = $rsgComment->parse($comment['comment']);
				//Strip HTML tags with the exception of these allowed tags: line break, paragraph, bold, italic, underline, link image (for smileys) and allowed attribute: link, src; then clean comment.
				$allowedTags    = array('strong', 'em', 'a', 'img', 'b', 'i', 'u');
				$allowedAttribs = array('href', 'src');
				$filter         = &JFilterInput::getInstance($allowedTags, $allowedAttribs);
				@$comment['comment'] = $filter->clean($comment['comment']);
				// Update comment in table
				$query = 'UPDATE #__rsgallery2_comments SET comment  = ' . $database->Quote($comment['comment']) . ' where id =' . (int) $comment['id'];
				$database->setQuery($query);
				$result = $database->execute();
			}
		}
		catch (Exception $e)
		{
			$ErrText = 'Exception in upgradeTo_3_2_0: ' . $e->getMessage();
			echo $ErrText . "\n";
			JLog::add($ErrText, JLog::DEBUG);
			$failed = true;
		}

		return $failed;
	} // end of function upgradeTo_3_2_0
}    //end class upgrade_com_rsgallery2_03_02_00 old: migrate_com_rsgallery

/**
 * (Stripped) Class for the comments plugin - only here for converting comments from 2.2.1 to 2.3.0
 *
 * @author Ronald Smit <ronald.smit@rsdev.nl>
 */
class rsgCommentsOld
{
	var $_buttons;
	var $_emoticons;

	/**
	 * Constructor
	 * @since 4.3.0
     */
	function __construct()
	{
		global $mainframe;
		$this->_buttons        = array(
			"b"     => "ubb_bold.gif",
			"i"     => "ubb_italicize.gif",
			"u"     => "ubb_underline.gif",
			"url"   => "ubb_url.gif",
			"quote" => "ubb_quote.gif",
			"code"  => "ubb_code.gif",
			"img"   => "ubb_image.gif"
		);
		$this->_emoticons      = array(
			":D"         => "icon_biggrin.gif",
			":)"         => "icon_smile.gif",
			":("         => "icon_sad.gif",
			":O"         => "icon_surprised.gif",
			":shock:"    => "icon_eek.gif",
			":confused:" => "icon_confused.gif",
			"8)"         => "icon_cool.gif",
			":lol:"      => "icon_lol.gif",
			":x"         => "icon_mad.gif",
			":P"         => "icon_razz.gif",
			":oops:"     => "icon_redface.gif",
			":cry:"      => "icon_cry.gif",
			":evil:"     => "icon_evil.gif",
			":twisted:"  => "icon_twisted.gif",
			":roll:"     => "icon_rolleyes.gif",
			":wink:"     => "icon_wink.gif",
			":!:"        => "icon_exclaim.gif",
			":?:"        => "icon_question.gif",
			":idea:"     => "icon_idea.gif",
			":arrow:"    => "icon_arrow.gif"
		);
		$this->_emoticons_path = JURI_SITE . "/components/com_rsgallery2/lib/rsgcomments/emoticons/default/";
	}

	/**
	 * Retrieves raw text and converts bbcode to HTML
	 *
	 * @param string $html
	 * @param int    $hide
	 *
	 * @return mixed
	 * @since 4.3.0
     */
	static function parseUBB($html, $hide = 0)
	{    //needed
		$html         = str_replace(']www.', ']http://www.', $html);
		$html         = str_replace('=www.', '=http://www.', $html);
		$patterns     = array('/\[b\](.*?)\[\/b\]/i',
			'/\[u\](.*?)\[\/u\]/i',
			'/\[i\](.*?)\[\/i\]/i',
			'/\[url=(.*?)\](.*?)\[\/url\]/i',
			'/\[url\](.*?)\[\/url\]/i',
			'#\[email\]([a-z0-9\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#',
			'#\[email=([a-z0-9\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\](.*?)\[/email\]#',
			'/\[font=(.*?)\](.*?)\[\/font\]/i',
			'/\[size=(.*?)\](.*?)\[\/size\]/i',
			'/\[color=(.*?)\](.*?)\[\/color\]/i');
		$replacements = array('<b>\\1</b>',
			'<u>\\1</u>',
			'<i>\\1</i>',
			'<a href=\'\\1\' title=\'Visit \\1\'>\\2</a>',
			'<a href=\'\\1\' title=\'Visit \\1\'>\\1</a>',
			'<a href=\'mailto:\\1\'>\\1</a>',
			'<a href=\'mailto:\\1\'>\\3</a>',
			'<span style=\'font-family: \\1\'>\\2</span>',
			'<span style=\'font-size: \\1\'>\\2</span>');
		if ($hide)
		{
			$replacements[] = '\\2';
		}
		else
		{
			$replacements[] = '<span style=\'color: \\1\'>\\2</span>';
		}
		$html = preg_replace($patterns, $replacements, $html);

		return $html;
	}

	/**
	 * Replaces emoticons code with emoticons
	 * @param $html
	 *
	 * @return mixed
	 *
	 * @since 4.3.0
     */
	function parseEmoticons($html)
	{ //needed
		foreach ($this->_emoticons as $ubb => $icon)
		{
			$html = str_replace($ubb, "<img src='" . $this->_emoticons_path . $icon . "' border='0' alt='' />", $html);
		}

		return $html;
	}

	/**
	 * Parses an image element to HTML
	 *
	 * @param string $html
	 *
	 * @return mixed
	 * @since 4.3.0
     */
	static function parseImgElement($html)
	{    //needed
		return preg_replace('/\[img\](.*?)\[\/img\]/i', '<img src=\'\\1\' alt=\'Posted image\' />', $html);
	}

	/**
	 * Parse a quote element to HTML
	 *
	 * @param string $html
	 *
	 * @return mixed
	 * @since 4.3.0
     */
	static function parseQuoteElement($html)
	{    //needed
		$q1 = substr_count($html, "[/quote]");
		$q2 = substr_count($html, "[quote=");
		if ($q1 > $q2)
		{
			$quotes = $q1;
		}
		else
		{
			$quotes = $q2;
		}
		$patterns     = array("/\[quote\](.+?)\[\/quote\]/is",
			"/\[quote=(.+?)\](.+?)\[\/quote\]/is");
		$replacements = array(
			"<div class='quote'><div class='genmed'><b>" . JText::_('Quote') . "</b></div><div class='quotebody'>\\1</div></div>",
			"<div class='quote'><div class='genmed'><b>\\1" . JText::_('Wrote') . "</b></div><div class='quotebody'>\\2</div></div>"
		);
		while ($quotes > 0)
		{
			$html = preg_replace($patterns, $replacements, $html);
			$quotes--;
		}

		return $html;
	}

	/**
	 * @param $html
	 *
	 * @return mixed
	 * @since 4.3.0
     */
	function parseCodeElement($html)
	{    //needed
		if (preg_match_all('/\[code\](.+?)\[\/code\]/is', $html, $replacementI))
		{
			foreach ($replacementI[0] as $val) $html = str_replace($val, $this->code_unprotect($val), $html);
		}
		$pattern       = array();
		$replacement   = array();
		$pattern[]     = "/\[code\](.+?)\[\/code\]/is";
		$replacement[] = "<div class='code'><div class='genmed'><b>" . JText::_('Code') . "</b></div><div class='codebody'><pre>\\1</pre></div></div>";

		return preg_replace($pattern, $replacement, $html);
	}

	/**
	 * Parse a BB-encoded message to HTML
	 *
	 * @param $html
	 *
	 * @return mixed
	 * @since 4.3.0
     */
	function parse($html)
	{    //needed
		$html = $this->parseEmoticons($html);
		$html = $this->parseImgElement($html);
		$html = $this->parseUBB($html, 0);
		$html = $this->parseCodeElement($html);
		$html = $this->parseQuoteElement($html);
		$html = stripslashes($html);

		return str_replace('&#13;', "\r", nl2br($html));
	}

	/**
	 * @param $val
	 *
	 * @return mixed
	 * @since 4.3.0
     */
	static function code_unprotect($val)
	{    //needed
		$val = str_replace("{ : }", ":", $val);
		$val = str_replace("{ ; }", ";", $val);
		$val = str_replace("{ [ }", "[", $val);
		$val = str_replace("{ ] }", "]", $val);
		$val = str_replace(array("\n\r", "\r\n"), "\r", $val);
		$val = str_replace("\r", '&#13;', $val);

		return $val;
	}
}    //end class rsgCommentsOld
