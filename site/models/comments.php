<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die;

/**
 * Foo model.
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
class RSGallery2ModelComments extends JModelList
{
    /**
     * @var object
     *
     * @since ?
     */
    private $item;

	/**
	 * @param $imageId
	 * @param $comment
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since version
	 */
	public function addComment($imageId, $comment)
	{
		$isCommented = false;

		try
		{
			$db = JFactory::getDBO();
			//$query = $db->getQuery(true);

			// Insert the object into the user profile table.
			$isCommented = $db->insertObject('#__rsgallery2_comments', $comment);
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= ': Error executing insertObject in addComment' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isCommented;
	}

	/**
	 * @param $imageId
	 * @param $comment
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public function saveComment($imageId, $comment)
	{
		$isCommented = false;

		/**
		$comment [commentUserName] = $commentUserName;
		$comment [commentTitle] = $commentTitle;
		$comment [commentText] = $commentText;
		/**/



		/**
		  `id` int(11) NOT NULL auto_increment,
		  `user_id` int(11) NOT NULL,
		  `user_name` varchar(100) NOT NULL,
		  `user_ip` varchar(50) NOT NULL default '0.0.0.0',
		  `parent_id` int(11) NOT NULL default '0',
		  `item_id` int(11) NOT NULL,
		  `item_table` varchar(50) default NULL,
		  `datetime` datetime NOT NULL,
		  `subject` varchar(100) default NULL,
		  `comment` text NOT NULL,
		  `published` tinyint(1) NOT NULL default '1',
		--- ToDo: `checked_out` int(11) default NULL,
		  `checked_out` int(11) NOT NULL default '0',
		--- ToDo: `checked_out_time` datetime default NULL,
		  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
		  `ordering` int(11) NOT NULL,
		  `params` text,
		  `hits` int(11) NOT NULL,
		/**/

		/**
		$imgVal = $this->getRatingSumAndVotes($imageId);
		$ratingSum = $imgVal->rating + $userRating;
		$votes = $imgVal->votes +1;

		// Save new ordering
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// ->set($db->quoteName($columns) = $db->quote($values))
		// $query->set('h.name = "apple", h.description= "orange", h.url = "bannana"');
		//$query->update($db->quoteName('#__rsgallery2_files'))
		//    ->set($db->quoteName('rating') . '=' . $db->quote((int) $ratingSum),
		//        $db->quoteName('votes') . '=' . $db->quote((int) $votes))
		//    ->where(array($db->quoteName('id') . '=' . $db->quote((int) $imageId)));
		$query->update($db->quoteName('#__rsgallery2_files'))
			->set(array($db->quoteName('rating') . '=' . $db->quote((int) $ratingSum),
				$db->quoteName('votes') . '=' . $db->quote((int) $votes)))
			->where(array($db->quoteName('id') . '=' . $db->quote((int) $imageId)));
		$db->setQuery($query);

		$result = $db->execute();
		if ( ! empty($result))
		{
			$isCommented = true;
		}
		/**/

		return $isCommented;
	}

	/**
	 * @param $imageId
	 *
	 * @return array|mixed
	 *
	 * @throws Exception
	 * @since version
	 */
	public function getImageComments ($imageId)
	{
		$imageComments = [];

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		try
		{
			// ToDo: select only needed data
			$query
				->select('*')
				->from($db->quoteName('#__rsgallery2_comments'))
				->where('item_id=' . (int) $imageId);
			$db->setQuery($query);

			$comments = $db->loadObjectList();
			if (!empty ($comments))
			{
				$imageComments = $comments;
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= ': Error executing query in getItems' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $imageComments;
	}


	/**
	 * @param $imageId
	 *
	 *
	 * @throws Exception
	 * @since version
	 */
	public function SetUserHasCommented ($imageId)
	{
		global $rsgConfig;

		// save needed
		//if ($rsgConfig->get('commenting_once') != 0)
		{
			$cookie_name = 'rsgcommenting_' . $rsgConfig->get('cookie_prefix') . $imageId;

			// setcookie($rsgConfig->get('cookie_prefix') . $id, $my->id, time() + 60 * 60 * 24 * 365, "/");
			$time = time() + 60 * 60 * 24 * 365; // one year

			// Set cookie data
			$cookies  = JFactory::getApplication()->input->cookie;
			$cookies->set($name = $cookie_name, $value = 1, $expire = $time);
		}
	}

	/**
	 * Check if the user already voted for this item
	 *
	 * @param int $imageId ID of item to vote on
	 *
	 * @throws Exception
	 * @since version
	 * @return int 0 or user rating
	 */
	public function isUserHasCommented($imageId)
	{
		global $rsgConfig;

		$userCommented = (int) 0;

		// check needed
		//if ($rsgConfig->get('voting_once') != 0)
		{

			// Check if cookie rsgvoting was set for this image!
			$cookie_name = 'rsgcommenting_' . $rsgConfig->get('cookie_prefix') . $imageId;

			// Get the cookie
			$cookies  = JFactory::getApplication()->input->cookie;
			$value = $cookies->get($cookie_name, null);
			if(!empty($value))
			{
				$userCommented = (int) $value;
			}
		}

		return $userCommented;
	}

}
