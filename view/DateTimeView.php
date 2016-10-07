<?php

namespace view;

class DateTimeView {


	public function show() {

		$timeString = $this->getTimeString();
		return '<p>' . $timeString . '</p>';
	}


	/**
	 * Creates string representing Date and time
	 * @return string
	 */
	private function getTimeString() {
	  	date_default_timezone_set('Europe/Stockholm');
		$timeString = '';
		$weekDay = date('l');
		$month = date('F');
		$_date = date('jS');
		$time = date('H:i:s');
		$year = date('Y');

		$timeString .= $weekDay . ', ' . 'the ' . $_date . ' of ' . $month . ' ' . $year . ', The time is ' . $time;

		return $timeString;
	}
}
