<?php
defined('_JEXEC') or die;

class rsg2Common
{
    /**
     * This function will retrieve the user name based on the user id
     * @param int $uid user id
     * @return string the username
     * @todo isn't there a joomla function for this?
     */
    static function getUsernameFromId($uid) {  // ToDO: Move to somewhere else
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //$query = 'SELECT `username` FROM `#__users` WHERE `id` = '. (int) $uid;

        // Query for user with $uid.
        $query
            ->select('username')
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('id') .' = ' . (int)$uid);

        $db->setQuery($query);
        $name = $db->loadResult();

        return $name;
    }

	
    static function getRsg2ComponentVersion ()
	{
		$Version = '';
		
		/* 
		$xml = JFactory::getXML(JPATH_ADMINISTRATOR.'/components/com_rsgallery2/rsgallery2.xml');
		$version = (string)$xml->version;
			SimpleXML: $xml = simplexml_load_string($file);
			$array = (array)$xml;
		*/
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
	
		$query->select('manifest_cache');
		$query->from($db->quoteName('#__extensions'));
		//$query->where('name = "com_rsgallery2"');
		$query->where('element = "com_rsgallery2"');
		$db->setQuery($query);

		$manifest = json_decode($db->loadResult(), true);
		$Version = $manifest['version'];	
		
		return $Version;
    }

	
	
	
	
}