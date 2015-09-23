<?php

namespace view;

/**
 * Class DateTimeView
 */
class DateTimeView {

	/**
	 * Returns a string with the current date and time
	 *
	 * @return string Current date and time
	 */
	public function show() {
		$timeString = date('l') . ", the " . date('jS') . " of " . date('F Y') . ", The time is " . date('H:i:s');

		return '<p>' . $timeString . '</p>';
	}
}