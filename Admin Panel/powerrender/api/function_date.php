<?php
error_reporting(0);
function DatePost($date){
	$dateCustom = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 
	$years = substr($date, 0, 4);
	$month = substr($date, 5, 2);
	$date   = substr($date, 8, 2);
 
	$result = $date . "-" . $dateCustom[(int)$month-1] . "-". $years;		
	return($result);
}
?>