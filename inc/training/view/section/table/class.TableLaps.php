<?php
/**
 * This file contains class::TableLaps
 * @package Runalyze\DataObjects\Training\View\Section
 */

use Runalyze\View\Splits;
use Runalyze\Model\Trackdata;
use Runalyze\Configuration;
use Runalyze\Util\StringReader;

/**
 * Table: laps
 * 
 * @author Hannes Christiansen
 * @package Runalyze\DataObjects\Training\View\Section
 */
class TableLaps extends TableLapsAbstract {
	/**
	 * Set code
	 */
	protected function setCode() {
		$Reader = new StringReader($this->Context->activity()->comment());

		$Splits = $this->Context->activity()->splits();
		$SplitsView = new Splits\Table($Splits);
		$SplitsView->setDemandedPace($Reader->findDemandedPace());

		if ($this->Context->trackdata()->has(Trackdata\Object::DISTANCE)
			&& $this->Context->activity()->typeid() == Configuration::General()->competitionType()
		) {
			$SplitsView->setHalfsOfCompetition($this->computeHalfs());
		}

		$this->Code = $SplitsView->code();
	}

	/**
	 * @return array
	 */
	protected function computeHalfs() {
		$Halfs = array();

		$Loop = new Trackdata\Loop($this->Context->trackdata());
		$Loop->moveToDistance( $this->Context->activity()->distance()/2 );
		$Halfs[] = $this->halfFromLoop($Loop);

		$Loop->moveToDistance( $this->Context->activity()->distance() );
		$Halfs[] = $this->halfFromLoop($Loop);

		return $Halfs;
	}

	/**
	 * @param \Runalyze\Model\Trackdata\Loop $Loop
	 * @return array
	 */
	protected function halfFromLoop(Trackdata\Loop $Loop) {
		return array(
			's' => $Loop->difference(Trackdata\Object::TIME),
			'km' => $Loop->difference(Trackdata\Object::DISTANCE)
		);
	}
}