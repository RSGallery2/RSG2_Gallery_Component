<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017-2019 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */
?>
<?php defined('_JEXEC') or die();


// ToDo: include used parts from display.class and restructure



// Show slideshow link when viewing individual display items
$slideshow = $rsgConfig->get('displaySlideshowImageDisplay', 0);
if ($slideshow)
{
	?>
	<a href='<?php echo JRoute::_("index.php?option=com_rsgallery2&page=slideshow&gid=" . $this->gallery->id); ?>'>
		<?php echo JText::_('COM_RSGALLERY2_SLIDESHOW'); ?></a>
	<br />
	<?php
}

// Display none:0, Display both:1, Display top:2, Display bottom:3
const BAR_MODE_NONE   = '0';
const BAR_MODE_BOTH   = '1';
const BAR_MODE_TOP    = '2';
const BAR_MODE_BOTTOM = '3';

$display_navigation_bar_mode = $rsgConfig->get('display_navigation_bar_mode', 1);
?>

<div class="rsg_sem_inl">
	<?php //if (nav_both_top_and_bottom or nav_only_top){ //MK// [todo] [make config var for location navigation]?>
	<div class="rsg_sem_inl_Nav">
		<?php
		if($display_navigation_bar_mode == BAR_MODE_BOTH && $display_navigation_bar_mode == BAR_MODE_TOP)
		{
			$this->showDisplayPageNav();
		}
		?>
	</div>
	<?php //}  ?>
	<div class="rsg_sem_inl_dispImg">
		<?php

		$this->showItem();

		?>
	</div>
	<?php //if (nav_both_top_and_bottom or nav_only_bottom){ //MK// [todo] [make config var for location navigation] ?>
	<div class="rsg_sem_inl_Nav">
		<?php
		if($display_navigation_bar_mode == BAR_MODE_BOTH || $display_navigation_bar_mode == BAR_MODE_BOTTOM)
		{
			$this->showDisplayPageNav();
		}
		?>
	</div>
	<?php //}?>
	<div class="rsg_sem_inl_ImgDetails">
		<?php

			$this->showDisplayImageDetails();

		?>
	</div>
	<div class="rsg_sem_inl_footer">
		<?php

		$this->showRsgFooter();

		?>
	</div>
</div>

<?php
