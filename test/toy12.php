<?php


function is_old_enough($year, $month, $day) {

	/**
	 * Check age and other contraints.
	 *
	 */
	 
	$unix_bday = mktime(0, 0, 0, $month, $day, $year);
	$now = time();
	$diff = $now-$unix_bday;
	
	//print "Bdate";
	//print date("M-d-Y", $unix_bday);
	//print "nowdate";
	//print date("M-d-Y", $now);
	
	echo date("M-d-Y", mktime(0, 0, 0, $month, $day, $year));
	
	/*
		hour = 3600
		day = 86400
		year = 31536000
	
	The difference between today and the birth date must be at least eighteen years.

If you age has more seconds than 18 years of seconds?
	
	*/
	$one_unix_year = 31536000;
	$age_threshold = 18;
	$age_unix_threshold = $one_unix_year * $age_threshold;
	
	print "<p>Unix Birth Date: $unix_bday</p>";

	print "<p>Unix Now Date: $now</p>";
	print "<p>Difference: $diff</p>";
	print "<p>Threshold: $age_unix_threshold</p>";
	
	$eighteen_years_of_seconds = $one_unix_year * 18;
	$number_of_seconds_alive = $now-$unix_bday;
	if( $number_of_seconds_alive >= $eighteen_years_of_seconds ) {
		print "You have been alive for eighteen years of seconds!";
	}
	
	if( ($now - $unix_bday) >= $age_unix_threshold ) {
		return 1;
		} else {
		return 0;
		}

}

/*
In php4, if you give 07 leading zero, it screws up the mktime, you must not have any leading zeros even when passing it as a value.

is_old_enough( 1988, 07, 08 )


gives wrong mktime

*/

if( is_old_enough( 1988, 7, 14 ) ) {
	print "Old enough!";
} else {
	print "Too young!";
}


?>