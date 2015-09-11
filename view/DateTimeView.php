<?php

class DateTimeView {


	public function show() {

		$timeStamp = getDate();
		var_dump($timeStamp);

		$timeString = $timeStamp['weekday'] . ", the " . $timeStamp['mday'] . "th of "
			. $timeStamp['month'] . " " . $timeStamp['year'] . ", The time is "
			. $timeStamp['hours'] . ':' . $timeStamp['minutes'] . ':' . $timeStamp['seconds'];

		return '<p>' . $timeString . '</p>';
	}
}