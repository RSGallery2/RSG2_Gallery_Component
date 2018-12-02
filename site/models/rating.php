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
class RSGallery2ModelRating extends JModelLegacy
{
    protected $text_prefix = 'COM_RSGALLERY2';


    public function getRatingSumAndVotes($id)
    {
        $db = JFactory::getDBO();

        // $rating = 0; // avarage
        // $votes = 0;

        $query = $db->getQuery(true);
        $query
            ->select($db->quoteName(array('rating', 'votes')))
            ->from($db->quoteName('#__rsgallery2_files'))
            ->where('id=' . (int) $id);
        $db->setQuery($query);

        $results = $db->loadObject();

        // $rating = $results->rating;
        // $votes  = $results->votes;
        // return array($rating, $votes);
        // list($rating, $votes) = getRatingAndVotes($id);

        return $results;
    }

    public function calculateAverage($ratingSum, $votes)
    {
        $average = 0;

        if ($votes > 0)
        {
            $averageDivision = $ratingSum / $votes;
            // two digits behind point
            $average = round(($averageDivision * 2), 0) / 2;
        }

        return $average;
    }

    /**
     * Checks if it is allowed to vote in this gallery
     *
     * @return True or False
     */
    public function isRatingAllowed($gid)
    {
        $user = JFactory::getUser();

        $voteAllowed = $user->authorise('rsgallery2.vote', 'com_rsgallery2.gallery.' . $gid);

        return $voteAllowed;
    }

    public function doRate ($id, $rating)
    {
        $isOk = false;

        $imgVal = getRatingSumAndVotes($id);
        $ratingSum = $imgVal->rating + $rating;
        $votes = $imgVal->voting +1;

        // Save new ordering
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->update($db->quoteName('#__rsgallery2_files'))
            ->set($db->quoteName('rating') . '=' . $db->quote((int) $ratingSum))
            ->set($db->quoteName('votes') . '=' . $db->quote((int) $votes))
            ->where(array($db->quoteName('id') . '=' . $db->quote((int) $id)));
        $db->setQuery($query);

        $result = $db->execute();
        if ( ! empty($result))
        {
            $isOk = true;
        }

        return $isOk;
    }

    public function UserHasRated ($id)
    {
        global $rsgConfig;

        // save needed
        if ($rsgConfig->get('voting_once') != 0)
        {
            $cookie_name = $rsgConfig->get('cookie_prefix') . $id;

            // setcookie($rsgConfig->get('cookie_prefix') . $id, $my->id, time() + 60 * 60 * 24 * 365, "/");
            $time = time() + 60 * 60 * 24 * 365; // one year

            // Set cookie data
            $cookies  = JFactory::getApplication()->input->cookie;
            $cookies->set($name = $cookie_name, $value = '1', $expire = $time);
        }
    }

    /**
     * Check if the user already voted for this item
     *
     * @param int ID of item to vote on
     *
     * @return True or False
     */
    public function isUserHasRated($id)
    {
        global $rsgConfig;

        $isHasRated = false;

        // check needed
        if ($rsgConfig->get('voting_once') != 0) {

            // Check if cookie rsgvoting was set for this image!
            $cookie_name = $rsgConfig->get('cookie_prefix') . $id;
            /**
            if (isset($_COOKIE[$cookie_name])) {
                return true;
            } else {
                return false;
            }
            /**/

            // Get the cookie
            $cookies  = JFactory::getApplication()->input->cookie;
            $value = $cookies->get($cookie_name, null);
            if(!empty($value))
            {
                $isHasRated = true;
            }
        }

        return $isHasRated;
    }

}
