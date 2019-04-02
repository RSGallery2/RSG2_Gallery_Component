<?php
/**
 * This file handles the HTML processing for the Admin section of RSGallery.
 *
 * @version       $Id: admin.rsgallery2.html.php 1090 2012-07-09 18:52:20Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

// ToDo: Move file to include folder (clean start directory)

/**
  * The HTML_RSGALLERY class is used to encapsulate the HTML processing for RSGallery.
  *
  * @package RSGallery2
  * @todo    Move this class to a separate class file and add loose functions to it
  * @since 4.3.0
**/
class HTML_RSGALLERY
{
/**
 * use to print a message between HTML_RSGallery::RSGalleryHeader(); and a normal feature.
 * use for things like deleting an image, where a success message should be displayed and viewImages() called afterwards.
 * two css classes are used: rsg-admin-msg, rsg-admin-msg-important
 * this function replaces newlines with <br> for convenience.
 *
 * @todo implement css classes in css file
 *
 * @param string $msg       message to print
 * @param bool   $important optionally display the message as important, possibly changing the text to red or bold, etc.  as a general rule, expected results should be normal, unexpected results should be marked important.
  * @since 4.3.0
*/
static function printAdminMsg($msg, $important = false)
{
	// replace newlines with html line breaks.
	str_replace('\n', '<br>', $msg);

	if ($important)
	{
		echo "<p class='rsg-admin-msg'>$msg</p>";
	}
	else
	{
		echo "<p class='rsg-admin-msg-important message'>$msg</p>";
	}
}

/**
 * Used by showCP to generate buttons
 *
 * @param string $link  URL for button link
 * @param string $image Image name for button image
 * @param string $text  Text to show in button
 * @since 4.3.0
 */
static function quickIconButton($link, $image, $text)
{
	?>
	<div style="float:left;">
		<div class="icon">
			<a href="<?php echo $link; ?>">
				<div class="iconimage">
					<?php echo JHtml::image('administrator/components/com_rsgallery2/images/' . $image, $text); ?>
				</div>
				<?php echo $text; ?>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Used by showCP to generate buttons.
 * Uses icomoon font to display the main icon of the button
 *
 * @param string $link       URL for button link
 * @param string $imageClass Class name for icomoon image
 * @param string $text       Text to show in button
 * @since 4.3.0
 */
static function quickIconMoonButton($link, $imageClass, $text)
{
	?>
	<div style="float:left;">
		<div class="iconMoon">
			<a href="<?php echo $link; ?>">
				<div class="iconMoonImage">

					<!--span class="<?php echo $imageClass ?>" style="font-size:40px;"> </span-->
					<span class="<?php echo $imageClass ?>" style="font-size:40px;"></span>
					<!-- i class="icon-search"  style="font-size:24px;></i -->

				</div>
				<?php echo $text; ?>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Used by showCP to generate buttons
 *
 * @param        $Id
 * @param string $link  URL for button link
 * @param string $image Image name for button image
 * @param string $text  Text to show in button
 * @since 4.3.0
 */
static function quickIconDebugButton($Id, $link, $image, $text)
{
	?>
	<div style="float:left;">
		<div class="debugicon">
			<a href="<?php echo $link; ?>" class="<?php echo $Id; ?>">
				<div class="iconimage">
					<?php echo JHtml::image('administrator/components/com_rsgallery2/images/' . $image, $text); ?>
				</div>
				<?php echo $text; ?>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Shows the RSGallery control panel in backend.
 * @since 4.3.0
 */
static function showCP()
{
	// ToDo: throw away when not used any more (all output in views)

	// Redirect to base site instead of creating the page
	$app = JFactory::getApplication();
	$url = JRoute::_('index.php?option=com_rsgallery2', false);
	$app->redirect($url);
}

/**
 * @param string $message
 * @param string $title
 * @param string $url
 * @since 4.3.0
 */
function showInstallMessage($message, $title, $url)
{
	// global $PHP_SELF;
	?>
	<table class="adminheading">
		<tr>
			<th class="install">
				<?php echo $title; ?>
			</th>
		</tr>
	</table>

	<table class="adminform">
		<tr>
			<td align="left">
				<strong><?php echo $message; ?></strong>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				[&nbsp;<a href="<?php echo $url; ?>" style="font-size: 16px; font-weight: bold;"><?php echo JText::_('COM_RSGALLERY2_CONTINUE') ?></a>&nbsp;]
			</td>
		</tr>
	</table>
	<?php
}


/**
 * If there are no categories and a user has requested an action that
 * requires a category, this is the error message to display
 * @since 4.3.0
 */
static function requestCatCreation()
{
	?>
	<script>
		Joomla.submitbutton = function (pressbutton) {
			if (pressbutton != 'cancel') {
				Joomla.submitform(pressbutton);
				// return;
			} else {
				window.history.go(-1);
				// return;
			}
		}
	</script>

	<table width="100%">
		<tr>
			<td width="40%">&nbsp;</td>
			<td align="center">
				<table width="100%">
					<tr>
						<td><h3><?php echo JText::_('COM_RSGALLERY2_CREATE_A_CATEGORY_FIRST'); ?></h3></td>
					<tr>
						<td>
							<div id='cpanel'>
								<?php
								$link = 'index.php?option=com_rsgallery2&rsgOption=galleries';
								HTML_RSGALLERY::quickIconButton($link, 'categories.png', JText::_('COM_RSGALLERY2_MANAGE_GALLERIES'));
								?>
							</div>
						</td>
					</tr>
				</table>
			</td>
			<td width="40%">&nbsp;</td>
		</tr>
	</table>
	<div class='rsg2-clr'>&nbsp;</div>
	<?php
}

/**
 * Inserts the HTML placed at the top of all RSGallery Admin pages.
 *
 * @param string $type
 * @param string $text
  * @since 4.3.0
*/
static function RSGalleryHeader($type = '', $text = '')
{
	?>
	<table class="adminheading">
		<tr>
			<td><!--<img src="<?php echo JURI_SITE ?>administrator/components/com_rsgallery2/images/rsg2-logo.png" border=0 />--></td>
			<th class='<?php echo $type; ?>'>RSGallery2 <?php echo $text; ?></th>
		</tr>
	</table>
	<?php
}

/**
 * Inserts the HTML placed at the bottom of all RSGallery Admin pages.
 * @since 4.3.0
 */
static function RSGalleryFooter()
{
	global $rsgVersion;
	?>
	<div class="rsg2-footer" align="center"><br /><br /><?php echo $rsgVersion->getShortVersion(); ?></div>
	<div class='rsg2-clr'>&nbsp;</div>
	<?php
}

/**
 * Inserts the HTML placed at the bottom of all RSGallery Admin pages.
 * @since 4.3.0
 */
static function RSGallerySidebar()
{
if (count(JHtmlSidebar::getEntries()) > 0) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo JHtmlSidebar::render(); ?>
</div>
<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
    <?php endif;?>
		<div class="clearfix"></div>
		<?php
		}

		/**
		 *
		 * @since 4.3.0
		*/
		static function showUploadStep1()
		{
			?>
			<script type="text/javascript">
				Joomla.submitbutton = function (pressbutton) {
					var form = document.form;
					if (pressbutton == 'controlPanel') {
						location = "index.php?option=com_rsgallery2";
						return;
					}

					if (pressbutton == 'upload') {
						// do field validation
						if (form.catid.value == "0")
							alert("<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_SELECT_A_GALLERY'); ?>");
						else
							form.submit();
					}
				}
			</script>

			<table width="100%">
				<tr>
					<td width="300">&nbsp;</td>
					<td>
						<form name="form" action="index.php?option=com_rsgallery2&task=upload" method="post">
							<input type="hidden" name="uploadStep" value="2" />
							<table class="adminform">
								<tr>
									<th colspan="2">
										<font size="4"><?php echo JText::_('COM_RSGALLERY2_STEP_1'); ?></font></th>
								</tr>
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td width="200">

										<?php echo JText::_('COM_RSGALLERY2_PICK_A_GALLERY'); ?>
									</td>
									<td>
										<?php echo galleryUtils::galleriesSelectList(null, 'catid', false) ?>
									</td>
								</tr>
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr class="row1">
									<td colspan="2">
										<div style=text-align:center;></div>
									</td>
								</tr>
							</table>
						</form>
					</td>
					<td width="300">&nbsp;</td>
				</tr>
			</table>
			<?php
		}

		/**
		 * asks user to choose how many files to upload
		 * @since 4.3.0
		 */
		static function showUploadStep2()
		{
			$input = JFactory::getApplication()->input;
			$catid = $input->get('catid', null, 'INT');
			?>
			<table width="100%">
				<tr>
					<td width="300">&nbsp;</td>
					<td>
						<form name="form" action="index.php?option=com_rsgallery2&task=upload" method="post">
							<input type="hidden" name="uploadStep" value="3" />
							<input type="hidden" name="catid" value="<?php echo $catid; ?>" />
							<table class="adminform">
								<tr>
									<th colspan="2">
										<font size="4"><?php echo JText::_('COM_RSGALLERY2_STEP_2'); ?></font></th>
								</tr>
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td width="200">
										<?php echo JText::_('COM_RSGALLERY2_NUMBER_OF_UPLOADS'); ?>
									</td>
									<td>
										<?php echo JHtml::_("select.integerlist", 1, 25, 1, 'numberOfUploads', 'onChange="form.submit()"', 1); ?>
									</td>
								</tr>
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr class="row1">
									<td colspan="2">
										<div style=text-align:center;>
											<input type="submit" value="<?php echo JText::_('COM_RSGALLERY2_NEXT') ?>" />
										</div>
									</td>
								</tr>
							</table>
						</form>
					</td>
					<td width="300">&nbsp;</td>
				</tr>
			</table>
			<?php
		}

		/**
		 * asks user to choose what files to upload
		 * @since 4.3.0
		*/
		static function showUploadStep3()
		{
			$input = JFactory::getApplication()->input;
			$catid = $input->get('catid', null, 'INT');
			$uploadstep = $input->get('uploadstep', null, 'INT');
			$numberOfUploads = $input->get('numberOfUploads', null, 'INT');

			?>
			<script language="javascript" type="text/javascript">
				Joomla.submitbutton = function (pressbutton) {
					var form = document.form3;
					form.submit();
				}
			</script>
			<form name="form3" action="index.php?option=com_rsgallery2&task=upload" method="post" enctype="multipart/form-data">
				<input type="hidden" name="uploadStep" value="4" />
				<input type="hidden" name="catid" value="<?php echo $catid; ?>" />
				<input type="hidden" name="numberOfUploads" value="<?php echo $numberOfUploads; ?>" />
				<table width="100%">
					<tr>
						<td width="300">&nbsp;</td>
						<td>
							<table class="adminform">
								<tr>
									<th colspan="2">
										<font size="4"><?php echo JText::_('COM_RSGALLERY2_STEP_3'); ?></font></th>
								</tr>
								<?php for ($t = 1; $t < ($numberOfUploads + 1); $t++): ?>
									<tr>
										<td colspan="2">
											<table width="100%" cellpadding="1" cellspacing="1">
												<tr>
													<td colspan="2">
														<strong><?php echo JText::_('COM_RSGALLERY2_IMAGE'); ?><?php echo "&nbsp;" . $t; ?></strong>
													</td>
												</tr>
												<tr>
													<td><?php echo JText::_('COM_RSGALLERY2_GALLERY_NAME'); ?>:</td>
													<td>
														<strong><?php echo galleryUtils::getCatnameFromId($catid); ?></strong>
													</td>
												</tr>
												<tr>
													<td valign="top" width="100"><?php echo JText::_('COM_RSGALLERY2_TITLE') . " " . $t; ?>:</td>
													<td>
														<input name="imgTitle[]" type="text" class="inputbox" size="40" />
													</td>
												</tr>
												<tr>
													<td valign="top"><?php echo JText::_('COM_RSGALLERY2_FILE') . " " . $t; ?>:</td>
													<td>
														<input class="inputbox" name="images[]" type="file" size="30" />
													</td>
												</tr>
												<tr>
													<td valign="top"><?php echo JText::_('COM_RSGALLERY2_DESCRIPTION') . " " . $t; ?></td>
													<td>
														<textarea class="inputbox" cols="35" rows="3" name="descr[]"></textarea>
													</td>
												</tr>
												<tr class="row1">
													<th colspan="2">&nbsp;</th>
												</tr>
											</table>
										</td>
									</tr>
								<?php endfor; ?>
							</table>
						</td>
						<td width="300">&nbsp;</td>
					</tr>
				</table>
			</form>
			<?php
		}
		}//end class
		?>
