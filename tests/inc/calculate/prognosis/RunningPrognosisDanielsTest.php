<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-10-26 at 15:33:59.
 */
class RunningPrognosisDanielsTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var RunningPrognosisDaniels
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new RunningPrognosisDaniels;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		
	}

	/**
	 * @covers RunningPrognosisDaniels::setupFromDatabase
	 * @todo   Implement testSetupFromDatabase().
	 */
	public function testSetupFromDatabase() {
		// TODO: uses VDOT_FORM / BasicEndurance::getConst()
	}

	/**
	 * @covers RunningPrognosisDaniels::inSeconds
	 * @todo   Implement testInSeconds().
	 */
	public function testInSeconds() {
		$this->object->adjustVDOT( false );

		$Distances    = array(5, 10, 21.0975, 42.195);
		$Requirements = array(
			30 => array(array(0,30,40), array(1,03,46), array(2,21,04), array(4,49,17)),
			35 => array(array(0,27,00), array(0,56,03), array(2,04,13), array(4,16,13)),
			40 => array(array(0,24,08), array(0,50,03), array(1,50,59), array(3,49,45)),
			45 => array(array(0,21,50), array(0,45,16), array(1,40,20), array(3,28,26)),
			50 => array(array(0,19,57), array(0,41,21), array(1,31,35), array(3,10,49)),
			55 => array(array(0,18,22), array(0,38,06), array(1,24,18), array(2,56,01)),
			60 => array(array(0,17,03), array(0,35,22), array(1,18,09), array(2,43,25)),
			65 => array(array(0,15,54), array(0,33,01), array(1,12,53), array(2,32,35)),
			70 => array(array(0,14,55), array(0,31,00), array(1, 8,21), array(2,23,10)),
			75 => array(array(0,14,03), array(0,29,14), array(1, 4,23), array(2,14,55))
		);

		foreach ($Requirements as $vdot => $times) {
			$this->object->setVDOT( $vdot );
			foreach ($times as $i => $time) {
				$this->assertEquals(
					$time[0]*60*60 + $time[1]*60 + $time[2],
					$this->object->inSeconds($Distances[$i]),
					'Failure for VDOT = '.$vdot.' at '.$Distances[$i].' km ',
					$Distances[$i]*1.5
				);
			}
		}
	}

	/**
	 * @covers RunningPrognosisDaniels::getAdjustedVDOTforDistanceIfWanted
	 * @covers RunningPrognosisDaniels::setVDOT
	 * @covers RunningPrognosisDaniels::setBasicEnduranceForAdjustment
	 * @covers RunningPrognosisDaniels::adjustVDOT
	 */
	public function testGetAdjustedVDOTforDistanceIfWanted() {
		$this->object->setVDOT( 30 );
		$this->object->adjustVDOT( true );
		$this->object->setBasicEnduranceForAdjustment(0);
		$this->assertEquals( 30*1.0, $this->object->getAdjustedVDOTforDistanceIfWanted(0) );
		$this->assertEquals( 30*0.6, $this->object->getAdjustedVDOTforDistanceIfWanted(50) );

		$this->object->adjustVDOT( false );
		$this->assertEquals( 30, $this->object->getAdjustedVDOTforDistanceIfWanted(0) );
		$this->assertEquals( 30, $this->object->getAdjustedVDOTforDistanceIfWanted(50) );
	}

	/**
	 * @covers RunningPrognosisDaniels::getAdjustedVDOTforDistance
	 */
	public function testGetAdjustedVDOTforDistance() {
		$this->object->setVDOT( 30 );
		$this->object->setBasicEnduranceForAdjustment(0);
		$this->assertEquals( 30*1.0, $this->object->getAdjustedVDOTforDistance(0) );
		$this->assertEquals( 30*0.6, $this->object->getAdjustedVDOTforDistance(50) );

		$this->object->setBasicEnduranceForAdjustment(100);
		$this->assertEquals( 30*1.0, $this->object->getAdjustedVDOTforDistance(40) );
		$this->assertEquals( 30*0.91, $this->object->getAdjustedVDOTforDistance(50), '', 0.2 );

		$this->object->setVDOT( 60 );
		$this->object->setBasicEnduranceForAdjustment(0);
		$this->assertEquals( 60*1.0, $this->object->getAdjustedVDOTforDistance(0) );
		$this->assertEquals( 60*0.6, $this->object->getAdjustedVDOTforDistance(50) );

		$this->object->setBasicEnduranceForAdjustment(100);
		$this->assertEquals( 60*1.0, $this->object->getAdjustedVDOTforDistance(40) );
		$this->assertEquals( 60*0.91, $this->object->getAdjustedVDOTforDistance(50), '', 0.2 );
	}

	/**
	 * @covers RunningPrognosisDaniels::getAdjustmentFactor
	 */
	public function testGetAdjustmentFactor() {
		$this->object->setBasicEnduranceForAdjustment(0);
		$this->assertEquals( 1.00, $this->object->getAdjustmentFactor(0), '', 0 );
		$this->assertEquals( 0.93, $this->object->getAdjustmentFactor(10), '', 0.01 );
		$this->assertEquals( 0.84, $this->object->getAdjustmentFactor(20), '', 0.01 );
		$this->assertEquals( 0.625, $this->object->getAdjustmentFactor(40), '', 0.01 );
		$this->assertEquals( 0.60, $this->object->getAdjustmentFactor(50), '', 0.01 );
		$this->assertEquals( 0.60, $this->object->getAdjustmentFactor(100), '', 0.01 );

		$this->object->setBasicEnduranceForAdjustment(50);
		$this->assertEquals( 1.00, $this->object->getAdjustmentFactor(0), '', 0 );
		$this->assertEquals( 1.00, $this->object->getAdjustmentFactor(10), '', 0.01 );
		$this->assertEquals( 1.00, $this->object->getAdjustmentFactor(20), '', 0.01 );
		$this->assertEquals( 0.825, $this->object->getAdjustmentFactor(40), '', 0.01 );
		$this->assertEquals( 0.71, $this->object->getAdjustmentFactor(50), '', 0.01 );
		$this->assertEquals( 0.60, $this->object->getAdjustmentFactor(100), '', 0.01 );

		$this->object->setBasicEnduranceForAdjustment(100);
		$this->assertEquals( 1.00, $this->object->getAdjustmentFactor(40), '', 0.01 );
		$this->assertEquals( 0.91, $this->object->getAdjustmentFactor(50), '', 0.01 );
		$this->assertEquals( 0.60, $this->object->getAdjustmentFactor(100), '', 0.01 );

		$this->object->setBasicEnduranceForAdjustment(200);
		$this->assertEquals( 1.00, $this->object->getAdjustmentFactor(50), '', 0.01 );
		$this->assertEquals( 0.65, $this->object->getAdjustmentFactor(100), '', 0.01 );

		$this->object->setBasicEnduranceForAdjustment(300);
		$this->assertEquals( 1.00, $this->object->getAdjustmentFactor(100), '', 0.01 );
	}

}
