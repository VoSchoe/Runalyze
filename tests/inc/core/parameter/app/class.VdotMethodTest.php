<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2014-09-19 at 17:39:48.
 */
class VdotMethodTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var VdotMethod
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new VdotMethod;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		
	}

	/**
	 * @covers VdotMethod::usesLogarithmic
	 * @covers VdotMethod::usesLinear
	 */
	public function testMethods() {
		$this->object->set( VdotMethod::LOGARITHMIC );
		$this->assertTrue( $this->object->usesLogarithmic() );
		$this->assertFalse( $this->object->usesLinear() );

		$this->object->set( VdotMethod::LINEAR );
		$this->assertFalse( $this->object->usesLogarithmic() );
		$this->assertTrue( $this->object->usesLinear() );
	}

}