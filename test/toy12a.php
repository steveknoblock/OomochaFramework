<?php

/* manual input */
$then_day = 26;
$then_month = 5;
$then_year = 1964;

function date_to_age($day, $month, $year) {
	/* your timezone, offset from GMT time */
	$offset = -5;

	$now = gmdate("Y m d", time() + (60*60*$offset));

	$now_year = intval(substr($now,0,4));
	$now_month = intval(substr($now,5,2));
	$now_day = intval(substr($now,8,2));

	$age = $now_year - $year;
	if($now_month == $month && $now_day >= $day) $age++;

	return $age;
}

print date_to_age($then_day, $then_month, $then_year)

?>