<?php

include('./lib.php');

header("Content-Type: text/csv;charset=UTF-8");

$event_id = $_GET["event_id"];
$event = execSQLQuery('SELECT * FROM events WHERE event_id = ?', array($event_id))[0];

header("Content-Disposition: attachment; filename=".$event["title"]."_筑波大.csv");


$parts = execSQLQuery('SELECT * FROM parts WHERE event_id = ? AND (state = 1) ORDER BY section ASC', array($event_id));


$sections = array();

//ユニークにセクションを集計
foreach($parts as $part){

	$sections[$part["section"]] = array();
}

//セクション毎にメンバーを分離
foreach($parts as $part){

	$member = execSQLQuery("SELECT * FROM members WHERE (member_id = ?)", array($part["member_id"]))[0];
	array_push($sections[$part["section"]], $member["sei"].' '.$member["mei"]);
}

//最大セクション人数(CSVの行数)を求める
$max = 0;
foreach($sections as $section){

	if(count($section) > $max){
		$max = count($section);
	}
}

//縦方向にパディング
foreach($sections as $section => $members){

	for($i=count($members);$i<$max;$i++){
		array_push($sections[$section], '');
	}
}


//CSVを出力
for($i=-1;$i<$max;$i++){

	$flg = 0;

	foreach($sections as $section => $members){

		if($flg != 0){
			echo(',');
		}

		$flg = 1;

		if($i == -1){

			if($section == ''){
				echo('配置未定');
			}else{
				echo($section);
			}
		}else{

			echo($members[$i]);

		}

	}


	echo("\r\n");
}


?>