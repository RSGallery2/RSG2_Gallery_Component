<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

// Joel Lipman Jdatabase


/**
 * 
 */
class Rsgallery2ModelMaintSql extends  JModelList
{
//    protected $text_prefix = 'COM_RSG2';

	function createGalleryAccessField()
	{
		$msg = "Model: createGalleryAccessField: ";
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

		$table  = '#__rsgallery2_galleries';
		$field = 'access';

		$db = JFactory::getDBO();
		// $query = $db->getQuery(true);

		/*
		$result = mysql_query("SHOW COLUMNS FROM `table` LIKE 'fieldname'");
		$exists = (mysql_num_rows($result))?TRUE:FALSE;
		*/
		$query = 'SHOW COLUMNS FROM ' . $table . ' LIKE ' . $db->quote($field) ;
		$msg .= '<br>' . '$query: ' . json_encode ($query);
		$db->setQuery($query);
		$AccessField = $db->loadObject();
		$ColumnExist = isset($AccessField);
		$msg .= '<br>' . '$ColumnExist: ' . json_encode ($ColumnExist);

		// test code
		if ($ColumnExist) {
			// ALTER TABLE t2 DROP COLUMN c, DROP COLUMN d;
			$query = 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $field ;
			$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$result = $db->execute();
			$msg .= '<br>' . '$result (drop): ' . json_encode ($result);

			$ColumnExist = false;
		}


		// Create table column
		if (!$ColumnExist)
		{
			/*
               `access` int(10) unsigned DEFAULT NULL,

                ALTER TABLE yourtable ADD q6 VARCHAR( 255 ) after q5

                 $table  = 'your table name';
                 $column = 'q6'
                 $add = mysql_query("ALTER TABLE $table ADD $column VARCHAR( 255 ) NOT NULL");

				$db = JFactory::getDBO();
				$sql = "ALTER TABLE #__shoutbox ADD COLUMN user_id int(11) NOT NULL DEFAULT '0'";
				$db->setQuery($sql);
				$result = $db->query()

				 $db = JFactory::getDbo();
				$query='ALTER TABLE `#__virtuemart_categories_en_gb` ADD `short_desc` varchar(1200)';
				$db->setQuery($query);
				$result = $db->query();
            */

			//   `access` int(10) unsigned DEFAULT NULL
			$query = 'ALTER TABLE ' . $table . ' ADD ' . $field . ' INT  (10) UNSIGNED DEFAULT NULL';
			$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$ColumnExist = $db->execute();
			$msg .= '<br>' . '$result (Add): ' . json_encode ($result);
		}

		// Set all access values to '1'
		if ($ColumnExist)
		{
			// update your_table set likes = null
			$query = 'UPDATE ' . $table . ' SET ' . $field . '=1';
			$msg .= '<br>' . '$query: ' . json_encode ($query);
			$db->setQuery($query);
			$result = $db->execute();
			$msg .= '<br>' . '$result (update): ' . json_encode ($result);



		}
		return $msg;
	}




}