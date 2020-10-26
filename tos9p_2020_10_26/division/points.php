<?php

include('../attendance/lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();


$members = execSQLQuery("SELECT * FROM members WHERE is_student = 1", array());
$counts = array();


foreach($members as $member){
	
	$c = execSQLQuery('SELECT COUNT(*) FROM parts WHERE member_id = ? AND state = 1', array($member["member_id"]))[0]["COUNT(*)"];
	array_push($counts, array("name"=>$member["sei"].$member["mei"], "count"=>$c));

}


foreach($counts as $key => $value){
    $sort[$key] = $value["count"];
}

array_multisort($sort, SORT_DESC, $counts);



echo('<div class="container">
	<h1>CHAMPIONSHIP</h1>

	<table class="table"><thead><th>NAME</th><th>ATTENDS</th><th>JOBS</th></thead><tbody>');
foreach($counts as $count){
	echo("<tr><td>".$count["name"]."</td><td>".$count["count"]."</td><td></td></tr>");
}
echo('</tbody></table></div>');


?>