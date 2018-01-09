<?php
/**
 * Version IDs like 4.0.9 will be kept in parts so it can be compared with other ID of same class
 *
 * @package       RSGallery2
 * @copyright (C) 2016 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 **/

// no direct access
//defined('_JEXEC') or die;

interface Comparable
{
	/**
	 * @param object $object
	 *
	 * @return int negative value if $this < $object, positive if $this > $object, 0 otherwise (if objects are considered equal)
	 * @throws ComparatorException if objects are not comparable to each other
	 */
	public function compareTo($object);
}

interface Comparator
{
	/**
	 * @param object $object1
	 * @param object $object2
	 *
	 * @return int Negative value if $object1 < $object2, positive if $object1 > $object2, 0 otherwise
	 * @throws ComparatorException if objects are not comparable to each other
	 */
	public static function compare($object1, $object2);
}

class VersionId implements Comparator
{
	protected $IdParts = array();

	/**
	 * VersionId constructor.
	 *
	 * @param string $strId Version id like "1.4.0.2"
	 */
	public function __construct($strId)
	{
		$this->AssignId($strId);
	}

	/**
	 * @param string $strId Version id like "1.4.0.2"
	 */
	public function AssignId($strId)
	{
		$this->IdParts = array();
		if (!empty($strId))
		{
			$this->IdParts = explode('.', $strId);
		}
	}

	/**
	 * @return int depth of version id '1' -> 1, '1.1' -> 2,  '1.1.1' -> 3
	 */
	public function Count()
	{
		return count($this->IdParts);
	}

	/**
	 * General comparison of version strings
	 *
	 * @param VersionId $firstId
	 * @param VersionId $secondId
	 *
	 * @return int returns -1, 0, or 1
	 */
	public static function Compare($firstId, $secondId)
	{
		$LengthFirst  = count($firstId->IdParts);
		$LengthSecond = $secondId->Count();

		$Length = $LengthFirst;
		if ($Length > $LengthSecond)
		{
			$Length = $LengthSecond;
		}

		// Compare array elements 
		for ($Idx = 0; $Idx < $Length; $Idx++)
		{
			if ($firstId->IdParts[$Idx] != $secondId->IdParts[$Idx])
			{
				$a = $firstId->IdParts[$Idx];
				$b = $secondId->IdParts[$Idx];

				return ($a < $b) ? -1 : (($a > $b) ? 1 : 0);
			}
		}

		// Compare length
		$a = $LengthFirst;
		$b = $LengthSecond;

		return ($a < $b) ? -1 : (($a > $b) ? 1 : 0);
	}

	/**
	 * @param VersionId $secondId
	 *
	 * @return bool
	 */
	public function IsBiggerThen($secondId)
	{
		return (VersionId::Compare($this, $secondId) > 0);
	}

	/**
	 * @param VersionId $secondId
	 *
	 * @return bool
	 */
	public function IsBiggerOrEqualThen($secondId)
	{
		return (VersionId::Compare($this, $secondId) >= 0);
	}

	/**
	 * @param VersionId $secondId
	 *
	 * @return bool
	 */
	public function IsSmallerThen($secondId)
	{
		return (VersionId::Compare($this, $secondId) < 0);
	}

	/**
	 * @param $secondId
	 *
	 * @return bool
	 */
	public function IsSmallerOrEqualThen($secondId)
	{
		return (VersionId::Compare($this, $secondId) <= 0);
	}

	/**
	 * @param VersionId $secondId
	 *
	 * @return bool
	 */
	public function IsEqualTo($secondId)
	{
		return (VersionId::Compare($this, $secondId) == 0);
	}

}

