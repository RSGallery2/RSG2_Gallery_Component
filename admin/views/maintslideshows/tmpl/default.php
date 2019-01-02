<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

// JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip');

global $Rsg2DebugActive;

JHtml::_('formbehavior.chosen', 'select');


function tabHeader ($sliderName)
{
    // ? Sanitize name ?


    echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName); //, true);
	//echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
    //echo " // start tab " . $sliderName;
}

function tabContent ($xmlFileInfo)
{
	echo '<div class="well">';
	// $xmlFileInfo->name;
    //<h3><?php echo $this->item->title;</h3>

    echo '<h3>content: ' . $xmlFileInfo->name . '<h3>';
	// echo json_encode($xmlFileInfo) ;
	//echo $this->form->renderFieldset('regenerateGallerySelection');
    echo '</div>'; // well
}

function tabFooter ($sliderName)
{
	echo JHtml::_('bootstrap.endTab');
	//echo " //end tab " . $sliderName;
}



?>

<div id="slidshow-edit" class="clearfix">
	<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=maintSlideshows'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

                <legend><?php echo JText::_('COM_RSGALLERY2_SLIDESHOWS_CONFIGURATION'); ?></legend>
                <div><?php echo JText::_('COM_RSGALLERY2_SLIDESHOWS_CONFIGURATION_INFO'); ?></div>
                <br>

				<?php
                /**
                echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'name'));
                echo '//start tab set<br>';

				echo JHtml::_('bootstrap.addTab', 'myTab', 'name', JText::_('COM_EXAMPLE_NAME'));
                echo '//start tab pane 1';
                echo '<h3>' . 'title' . '</h3>';
                echo '<p>Author: ' . 'author' . '</p>';
				echo JHtml::_('bootstrap.endTab');
                echo '//end tab pane 1<br>';

				echo JHtml::_('bootstrap.addTab', 'myTab', 'desc', JText::_('COM_EXAMPLE_DESCRIPTION'));
                echo '//start tab pane 2';
				echo '<h3>' . 'description' . '</h3>';
				echo JHtml::_('bootstrap.endTab');
                echo '//end tab pane 2<br>';

				echo JHtml::_('bootstrap.addTab', 'myTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
                echo '//start tab pane 3';
				echo '<h3>' . 'price' . '</h3>';
				echo JHtml::_('bootstrap.endTab');
                echo '//end tab pane 3<br>';

				echo JHtml::_('bootstrap.endTabSet');
                echo '//end tab set';

                echo '<hr>';
                /**/
                ?>

                <?php
                /**
                echo '<br>';
                echo '<br>';
                echo json_encode($this->slidesConfigFiles);
                echo '<br>';
                echo '<br>';

                /**/
                if ( ! empty ($this->slidesConfigFiles))
                {
                    // activate first (?last ?) element
                    // toDo: Last used ....
                    $slidesCount = count ($this->slidesConfigFiles);
                    //$xmlFileInfo = $this->slidesConfigFiles [$slidesCount-1];
                    //$xmlFileInfo = $this->slidesConfigFiles [$slidesCount-2];
                    $xmlFileInfo = $this->slidesConfigFiles [0];
                    $activeName = $xmlFileInfo->name;

	                /**
	                $xmlFileInfo = $this->slidesConfigFiles [0];
	                $sliderName = $xmlFileInfo->name;

	                echo JHtml::_('bootstrap.startTabSet', 'slidersTab', array('active' => $sliderName));
	                echo '//start tab set<br>';

	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, JText::_('COM_EXAMPLE_NAME'));
	                echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                echo '//start tab pane 1';
	                echo '<h3>' . $sliderName . '</h3>';
	                echo '<p>Author: ' . 'author' . '</p>';
	                echo JHtml::_('bootstrap.endTab');
	                echo '//end tab pane 1<br>';

	                $xmlFileInfo = $this->slidesConfigFiles [1];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'desc', JText::_('COM_EXAMPLE_DESCRIPTION'));
	                echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                echo '//start tab pane 2';
	                echo '<h3>' . $sliderName . '</h3>';
	                echo JHtml::_('bootstrap.endTab');
	                echo '//end tab pane 2<br>';

	                $xmlFileInfo = $this->slidesConfigFiles [2];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
	                echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                echo '//start tab pane 3';
	                echo '<h3>' . $sliderName . '</h3>';
	                echo JHtml::_('bootstrap.endTab');
	                echo '//end tab pane 3<br>';

	                $xmlFileInfo = $this->slidesConfigFiles [3];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
	                echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                echo '//start tab pane 4';
	                echo '<h3>' . $sliderName . '</h3>';
	                echo JHtml::_('bootstrap.endTab');
	                echo '//end tab pane 4<br>';

	                $xmlFileInfo = $this->slidesConfigFiles [4];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
	                echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                echo '//start tab pane 5';
	                echo '<h3>' . $sliderName . '</h3>';
	                echo JHtml::_('bootstrap.endTab');
	                echo '//end tab pane 5<br>';

	                $xmlFileInfo = $this->slidesConfigFiles [5];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
	                echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                echo '//start tab pane 6';
	                echo '<h3>' . $sliderName . '</h3>';
	                echo JHtml::_('bootstrap.endTab');
	                echo '//end tab pane 6<br>';

	                echo JHtml::_('bootstrap.endTabSet');
	                echo '//end tab set';
	                /**/

	                /**
	                $xmlFileInfo = $this->slidesConfigFiles [0];
	                $sliderName = $xmlFileInfo->name;

	                echo JHtml::_('bootstrap.startTabSet', 'slidersTab', array('active' => $sliderName));
	                echo '//start tab set<br>';

	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, JText::_('COM_EXAMPLE_NAME'));
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                //echo '//start tab pane 1';
	                tabHeader ($sliderName);
	                //echo '<h3>' . $sliderName . '</h3>';
	                //echo '<p>Author: ' . 'author' . '</p>';
	                tabContent ($xmlFileInfo);
	                //echo JHtml::_('bootstrap.endTab');
	                //echo '//end tab pane 1<br>';
	                tabFooter ($sliderName);

	                $xmlFileInfo = $this->slidesConfigFiles [1];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'desc', JText::_('COM_EXAMPLE_DESCRIPTION'));
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                //echo '//start tab pane 2';
	                tabHeader ($sliderName);
	                //echo '<h3>' . $sliderName . '</h3>';
	                tabContent ($xmlFileInfo);
	                //echo JHtml::_('bootstrap.endTab');
	                //echo '//end tab pane 2<br>';
	                tabFooter ($sliderName);

	                $xmlFileInfo = $this->slidesConfigFiles [2];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                //echo '//start tab pane 3';
	                tabHeader ($sliderName);
	                //echo '<h3>' . $sliderName . '</h3>';
	                tabContent ($xmlFileInfo);
	                //echo JHtml::_('bootstrap.endTab');
	                //echo '//end tab pane 3<br>';
	                tabFooter ($sliderName);

	                $xmlFileInfo = $this->slidesConfigFiles [3];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                //echo '//start tab pane 4';
	                tabHeader ($sliderName);
	                //echo '<h3>' . $sliderName . '</h3>';
	                tabContent ($xmlFileInfo);
	                //echo JHtml::_('bootstrap.endTab');
	                //echo '//end tab pane 4<br>';
	                tabFooter ($sliderName);

	                $xmlFileInfo = $this->slidesConfigFiles [4];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                //echo '//start tab pane 5';
	                tabHeader ($sliderName);
	                //echo '<h3>' . $sliderName . '</h3>';
	                tabContent ($xmlFileInfo);
	                //echo JHtml::_('bootstrap.endTab');
	                //echo '//end tab pane 5<br>';
	                tabFooter ($sliderName);

	                $xmlFileInfo = $this->slidesConfigFiles [5];
	                $sliderName = $xmlFileInfo->name;
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
	                //echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
	                //echo '//start tab pane 6';
	                tabHeader ($sliderName);
	                //echo '<h3>' . $sliderName . '</h3>';
	                tabContent ($xmlFileInfo);
	                //echo JHtml::_('bootstrap.endTab');
	                //echo '//end tab pane 6<br>';
	                tabFooter ($sliderName);

	                echo JHtml::_('bootstrap.endTabSet');
	                echo '//end tab set';
	                /**/

                    /**/
	                $xmlFileInfo = $this->slidesConfigFiles [0];
	                $sliderName = $xmlFileInfo->name;

	                echo JHtml::_('bootstrap.startTabSet', 'slidersTab', array('active' => $sliderName));
	                //echo '//start tab set<br>';

	                foreach ($this->slidesConfigFiles as $xmlFileInfo)
	                {
		                $sliderName = $xmlFileInfo->name;
                        // extract parameter
                        tabHeader($sliderName);

                        tabContent($xmlFileInfo);

                        tabFooter ($sliderName);
	                }
	                echo JHtml::_('bootstrap.endTabSet');
	                //echo '//end tab set';
	                /**/
				?>

                <input type="hidden" value="" name="task">

				<?php

                    echo JHtml::_('form.token');

				} //    empty ($this->slidesConfigFiles))

                ?>

			</form>
		</div>
		<div id="loading"></div>
	</div>



</div>
