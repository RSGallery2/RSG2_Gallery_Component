<?php
/**
* Version ID like 4.0.9 will be kept in parts so it can be compared with other ID of same class
* @package RSGallery2
* @copyright (C) 2016 - 2016 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
**/

// no direct access
//defined('_JEXEC') or die;

class VersionId {
	public $IdParts = array ();
	
	public function  __construct ($strId) {
		$this->AssignId ($strId);
	}
	
	public function AssignId ($strId){
		$this->IdParts = array ();
		if(!empty($strId)) {
			$this->IdParts = explode ('.', $strId);
		}
	}
	
	
	public function Count () {
		return count ($this->IdParts);
	}
	
	public function Compare ($otherId)
	{
		$LengthId = count($this->IdParts);
		$LengthOther = $otherId->Count();
			
		$Length = $LengthId;
		if ($Length > $LengthOther) {
			$Length = $LengthOther;
		}
		
		// Compare array elements 
		for ($Idx = 0; $Idx < $Length; $Idx++) {
			if($this->IdParts[$Idx] != $otherId->IdParts[$Idx]) {
				return $this->IdParts[$Idx] > $otherId->IdParts[$Idx];
			}
		}
		
		// compare length 
		return $LengthId > $LengthOther;		
	}	
		
		
	

}

