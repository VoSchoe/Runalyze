<?php
/**
 * This file contains class::Time
 * @package Runalyze\Calculations
 */
/**
 * Class for standard operations for timestamps
 * @author Hannes Christiansen
 * @package Runalyze\Calculations
 */
class Time {
	/**
	 * Absolute difference in days between two timestamps
	 * @param int $time_1
	 * @param int $time_2 optional
	 * @return int
	 */
	static public function diffInDays($time_1, $time_2 = 0) {
		if ($time_2 == 0)
			$time_2 = time();

		return floor(abs(($time_1 - $time_2)/(3600*24)));
	}

	/**
	 * Calculates the difference in days of two dates (YYYY-mm-dd)
	 * @param string $date1
	 * @param string $date2
	 * @return int
	 */
	static public function diffOfDates($date1, $date2) {
		if (function_exists('date_diff')) // needs PHP >5.3.0
			return (int)date_diff(date_create($date1), date_create($date2))->format('%a');

		// TODO: Problem because of summer/winter-time
		return floor(abs(strtotime($date1) - strtotime($date2)) / (3600 * 24));
	}

	/**
	 * Is given timestamp from today?
	 * @param int $timestamp
	 * @return boolean
	 */
	static public function isToday($timestamp) {
		return date('d.m.Y') == date('d.m.Y', $timestamp);
	}

	/**
	 * Get the timestamp of the start of the week
	 * @param int $time
	 */
	static public function Weekstart($time) {
		$w = date("w", $time);
		if ($w == 0)
			$w = 7;
		$w -= 1;
		return mktime(0, 0, 0, date("m",$time), date("d",$time)-$w, date("Y",$time));
	}

	/**
	 * Get the timestamp of the end of the week
	 * @param int $time
	 */
	static public function Weekend($time) {
		$start = self::Weekstart($time);
		return mktime(23, 59, 50, date("m",$start), date("d",$start)+6, date("Y",$start));
	}

	/**
	 * Get the name of a day
	 * @param string $w     date('w');
	 * @param bool $short   short version, default: false
	 * @codeCoverageIgnore
	 */
	static public function Weekday($w, $short = false) {
		switch ($w%7) {
			case 0: return $short ? __('Sun') : __('Sunday');
			case 1: return $short ? __('Mon') : __('Monday');
			case 2: return $short ? __('Tue') : __('Tuesday');
			case 3: return $short ? __('Wed') : __('Wednesday');
			case 4: return $short ? __('Thu') : __('Thursday');
			case 5: return $short ? __('Fri') : __('Friday');
			case 6:
			default: return $short ? __('Sat') : __('Saturday');
		}
	}

	/**
	 * Get the name of the month
	 * @param string $m     date('m');
	 * @param bool $short   short version, default: false
	 * @codeCoverageIgnore
	 */
	static public function Month($m, $short = false) {
		switch ($m) {
			case 1: return $short ? __('Jan') : __('January');
			case 2: return $short ? __('Feb') : __('February');
			case 3: return $short ? __('Mar') : __('March');
			case 4: return $short ? __('Apr') : __('April');
			case 5: return $short ? __('May') : __('May');
			case 6: return $short ? __('Jun') : __('June');
			case 7: return $short ? __('Jul') : __('July');
			case 8: return $short ? __('Aug') : __('August');
			case 9: return $short ? __('Sep') : __('September');
			case 10: return $short ? __('Oct') : __('October');
			case 11: return $short ? __('Nov') : __('November');
			case 12:
			default: return $short ? __('Dec') : __('December');
		}
	}
}