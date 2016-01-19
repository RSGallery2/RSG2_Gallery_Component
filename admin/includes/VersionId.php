<?php
/**
* Version ID like 4.0.9 will be kept in parts so it can be compared with other ID of same class
* @package RSGallery2
* @copyright (C) 2016 - 2016 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
**/

// no direct access
defined('_JEXEC') or die;

class VersionId {
	public $IdParts = array ();
	
	void function  __construct ($strId) {
		this->AssignId ($strId);
	}
	
	void function AssignId ($strId){
		$this->IdParts = array ();
		if(!empty($strId)) {
			$this->IdParts = split ('.');
		}
	}
	
	
	function Count () {
		return count ($IdParts);
	}
	
	function compare ($otherId)
	{
		$Idx = 0;
		
		$LengthId = count($IdParts);
		$LengthOther = $otherId.Count();
			
		$Length = $LengthId;
		if ($Length > $LengthOther) {
			$Length = $LengthOther;
		}
		
		// Compare array elements 
		for ($Idx = 0; $Idx < $Length; Idx++) {
			if($IdParts[$Idx] != $otherId.IdParts[$Idx]) {
				return $IdParts[$Idx] > $otherId.IdParts[$Idx];
			}
		}
		
		// compare length 
		return $LengthId > $LengthOther;
		
		
		
		
	}

	test
}

