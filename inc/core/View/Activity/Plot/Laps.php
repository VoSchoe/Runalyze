<?php
/**
 * This file contains class::Laps
 * @package Runalyze\View\Activity\Plot
 */

namespace Runalyze\View\Activity\Plot;

use Runalyze\View\Activity;
use Helper;

/**
 * Plot for: Laps
 * 
 * @author Hannes Christiansen
 * @package Runalyze\View\Activity\Plot
 */
abstract class Laps extends ActivityPlot {
	/**
	 * @var array
	 */
	protected $Labels = array();

	/**
	 * @var boolean
	 */
	protected $SplitsAreNotComplete;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * Load data
	 * @param \Runalyze\View\Activity\Context $context
	 */
	abstract protected function loadData(Activity\Context $context);

	/**
	 * Init data
	 * @param \Runalyze\View\Activity\Context $context
	 */
	protected function initData(Activity\Context $context) {
		$this->SplitsAreNotComplete = $this->splitsAreNotComplete($context);
		$this->loadData($context);

		if (!empty($this->Data)) {
			$min = Helper::floorFor(min($this->Data), 30000);
			$max = Helper::ceilFor(max($this->Data), 30000);
			$this->Plot->setYLimits(1, $min, $max, false);
		}

		$this->Plot->setYAxisTimeFormat('%M:%S');
		$this->Plot->setXLabels($this->Labels);
		$this->Plot->showBars(true);

		$this->Plot->setYTicks(1, null);
		$this->Plot->Options['xaxis']['show'] = true; // force to show xaxis-labels, even if no time or distance array is given

		$this->addAnnotations();
	}

	/**
	 * Add annotations
	 */
	protected function addAnnotations() {
		// Can be overwritten in subclass
	}

	/**
	 * Splits are not complete
	 * "Complete" means: all laps are active and fill total distance
	 * 
	 * @param \Runalyze\View\Activity\Context $context
	 * @return boolean
	 */
	protected function splitsAreNotComplete(Activity\Context $context) {
		if ($context->activity()->splits()->isEmpty() || $context->activity()->splits()->totalDistance() <= 0) {
			return false;
		}

		if ($context->activity()->splits()->hasActiveAndInactiveLaps()) {
			return true;
		}

		if (abs($context->activity()->splits()->totalDistance() - $context->activity()->distance()) > 0.02 * $context->activity()->distance()) {
			return true;
		}

		return false;
	}
}