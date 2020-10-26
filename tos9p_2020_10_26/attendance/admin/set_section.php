<?php

include('../lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();

if(isset($_GET["event_id"])){

	$event_id = $_GET["event_id"];

}else{

	exit();
}


if(!empty($_POST)){

	foreach($_POST as $part_id => $section){

		if(strpos($part_id, 'section_') !== false){
			$part_id = str_replace('section_','',$part_id);
			execSQLQuery('UPDATE parts SET section = ? WHERE part_id = ?', array($section, $part_id));
		}

	}

}


$event = execSQLQuery('SELECT * FROM events WHERE event_id = ?', array($event_id))[0];


echo('<div class="container"><h1>配置設定</h1>
	<h3>'.$event["title"]);
echo('<br />');
echo($event["date"].'(');

$weekday = array( "日", "月", "火", "水", "木", "金", "土" );
echo($weekday[date("w", strtotime($event["date"]))]);

echo(')<br /></h3></div>');



$parts = execSQLQuery('SELECT * FROM parts WHERE event_id = ? AND (state = 0 OR state = 1) ORDER BY state DESC, section ASC', array($event_id));

echo('<div class="container">
	<form action="" method="post">

	<table class="table">
	<thead>
	<tr><th>お名前</th><th></th><th>コメント</th><th>希望配置</th><th>配置</th></tr>
	</thead>
	<tbody>');

foreach($parts as $part){

	echo('<tr>');
	$member = execSQLQuery('SELECT * FROM members WHERE member_id = ?', array($part["member_id"]))[0];

	echo('<td><span class="sei">'.$member["sei"].'</span><span class="mei">'.$member["mei"].'</span></td>');
	
	if($part["state"] == 1){
		echo('<td><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:#0F0;"></span></td>');
	}else{
		echo('<td><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:#F00;"></span></td>');
	}
	echo('<td style="max-width:200px;word-wrap:break-word;">'.$part["comment"].'</td>');
	echo('<td>'.$part["hope_section"].'</td>');
	echo('<td><input type="text" class="form-control" name="section_'.$part["part_id"].'" value="'.$part["section"].'" /></td>');
	echo('</tr>');

}

echo('</tbody></table>');


echo('<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button>');
echo('</form></div>');


?>