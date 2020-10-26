<?php

include('../attendance/lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar("points");


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
	<h1>TOS CHAMPIONSHIP STANDINGS&emsp;<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span></h1>

	<table class="table"><thead><th>Pos</th><th>Rider/Driver</th><th>ATTENDANCE POINTS</th></thead><tbody>');


$pos = 1;
$bef = -1;
$tie = 0;

foreach($counts as $count){
	if($count["count"] == $bef){
		$pos -= 1;
		$tie += 1;
	}elseif($tie != 0){
		$pos += $tie;
		$tie = 0;
	}
	$bef = $count["count"];
	echo("<tr><td>$pos</td><td>".$count["name"]."</td><td>".$count["count"]."</td></tr>");
	$pos += 1;
}
echo('</tbody></table></div>');


?>
