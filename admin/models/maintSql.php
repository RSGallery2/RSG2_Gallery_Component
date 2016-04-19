<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
/**
 * 
 */
class Rsgallery2ModelMaintSql extends  JModelList
{
//    protected $text_prefix = 'COM_RSG2';

	function createGalleryAccessField()
	{
		$msg = "createGalleryAccessField: ";
		$msgType = 'notice';

		/*
			1054 Unknown column 'access' in 'field list' SQL=INSERT INTO `afs_rsgallery2_galleries`
		         (`id`,`parent`,`name`,`alias`,`description`,`published`,`checked_out`,
		          `checked_out_time`,`ordering`,`hits`,`date`,`params`,`user`,`uid`,`allowed`,
		          `thumb_id`,`access`) VALUES ('','0','fdtgsdg','fdtgsdg','','1','','','0','',
		          '2015-10-01 16:18:23','','','45','','','1')

			After that i simply go through to afs_rsgallery2_galleries table and create a new field
		    called "access" and then set then value "1" for access via update query.
		    And my gallery images make a path or url...
		*/


		/*
				$model = $this->getModel('Config');
				$item=$model->save($key);
		*/


// ToDO: move to model !!!

		$table  = '#__rsgallery2_galleries';

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query = 'SHOW COLUMNS FROM ' . $table;
		$db->setQuery($query);
		$ColumnExist = $db->loadObject();

		/*
                $result = mysql_query("SHOW COLUMNS FROM `table` LIKE 'fieldname'");
                $exists = (mysql_num_rows($result))?TRUE:FALSE;

				$result = 'SHOW COLUMNS IN ' . $wordArray[2] . ' WHERE field = ' . $this->fixQuote($wordArray[5]);
				$this->queryType = 'ADD_COLUMN';
				$this->msgElements = array($this->fixQuote($wordArray[2]), $this->fixQuote($wordArray[5]));

		*/




		/*
		   `access` int(10) unsigned DEFAULT NULL,

			ALTER TABLE yourtable ADD q6 VARCHAR( 255 ) after q5

		 	$table  = 'your table name';
 			$column = 'q6'
 			$add = mysql_query("ALTER TABLE $table ADD $column VARCHAR( 255 ) NOT NULL");
        */

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}




}