<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

/**
 * RSGgallery2 Controller
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
class Rsgallery2Controller extends BaseController 
{

    public function rateSingleImage()
    {
        $msgType = 'notice';
        $msg     = 'Save and goto upload ';

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // http://127.0.0.1/Joomla3x/index.php?option=com_rsgallery2&view=gallery&gid=42&advancedSef=1&startShowSingleImage=1&Itemid=218
        $link = 'index.php?option=com_rsgallery2'; // &startShowSingleImage=1&Itemid=218

        // Access check
        $canVote = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        //$canVote = true;

        if ( ! $canVote)
        {
            $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        }
        else
        {
            try
            {
                echo "<br><br><br>rateSingleImage<br><br><br>";

                $input = JFactory::getApplication()->input;
                $galleryId = $input->get('gid', 0, 'INT');
                $imageId = $input->get('id', 0, 'INT');
                $userRating = $input->get('rating', 0, 'INT');

                $ratingModel = $this->getModel('rating');
	            $isRated = $ratingModel->doRating ($imageId, $userRating);


	            if ()

		            UserHasRated


		            see also isUserHasRated -> update it  

                $link = 'index.php?option=com_rsgallery2&view=gallery&gid=' . $galleryId . '&id=' . $imageId . '&startShowSingleImage=1'; // &startShowSingleImage=1&Itemid=218
            }
            catch (RuntimeException $e)
			{
                $OutTxt = '';
                $OutTxt .= 'Error executing saveOrdering: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
		}

        $this->setRedirect($link, $msg, $msgType);
    }

}
