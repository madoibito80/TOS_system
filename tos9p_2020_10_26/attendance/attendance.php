<?php

include('./lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar("events");


if(isset($_GET["event_id"])){

	$event_id = $_GET["event_id"];

}else{

	exit();
}


$event = execSQLQuery('SELECT * FROM events WHERE event_id = ?', array($event_id))[0];

if($event["deadline"] < date("Y-m-d")){
	print('<script>alert("このイベントは出欠締切を過ぎています.このイベントの出欠を変更した場合, イベントの管理者に通知メールが送信されます.");</script>');
}

if(empty($event)){
	exit('<div class="container">
		<span style="font-size:25px;"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span></span>&emsp;イベントが存在しません.
		</div>');
}


if(isset($_POST["sei"]) && isset($_POST["mei"]) && isset($_POST["state"]) && isset($_POST["comment"])
	&& (strlen($_POST["sei"]) !== 0 || strlen($_POST["mei"]) !== 0)){

	if(strlen($_POST["sei"]) !== 0 && strlen($_POST["mei"]) !== 0){

		$members = execSQLQuery("SELECT * FROM members WHERE (sei = ? AND mei = ?)", array($_POST["sei"], $_POST["mei"]));
	}else{

		$members = execSQLQuery("SELECT * FROM members WHERE (sei = ? OR mei = ?)", array($_POST["sei"], $_POST["mei"]));
	}

	if(count($members) == 0){

		exit('<div class="container">
			<span style="font-size:25px;"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span></span>&emsp;出欠登録には事前に<a href="./register_member.php">メンバー登録</a>が必要です.</div>');
	}

	if(count($members) > 1){

		exit('<div class="container">
			<span style="font-size:25px;"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span></span>&emsp;同性または同名のメンバーが存在します.
			</div>');
	}

	$member = $members[0];


	$part = execSQLQuery('SELECT * FROM parts WHERE event_id = ? AND member_id = ?', array($event_id, $member["member_id"]))[0];
	$comment = substr($_POST["comment"], 0, 256);

	if(empty($part) || $part["state"] != $_POST["state"]){

		if($_POST["state"] == 1){
			$state_word = '参加に変更しました';
		}else{
			$state_word = '不参加に変更しました';
		}

		file_put_contents('./log/'.$event_id.'.log', '['.date('Y/m/d H:i').'] '.$member["sei"].$member["mei"].'が'.$state_word."\r\n",FILE_APPEND);

		if($event["deadline"] < date("Y-m-d")){
			mb_language("Japanese");
			mb_internal_encoding("UTF-8");
			$message = $event["title"].'は既に締切を過ぎていますが, '.$member["sei"].$member["mei"].'が'.$state_word;
			$message .= "\r\n";
			$message .= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

			$headers = "From: ".POSTMASTER_EMAIL."\r\n";
			mb_send_mail($event["email"], "締切後の出欠変更がありました", $message, $headers);
		}
	}


	if(isset($_POST["hope_section"])){
		$hope_section = $_POST["hope_section"];
	}else{
		$hope_section = '';
	}


	if(empty($part)){
		execSQLQuery("INSERT INTO parts (event_id, member_id, car, state, hope_section, comment) VALUES (?, ?, ?, ?, ?, ?)", array($event_id, $member["member_id"], $_POST["car"], $_POST["state"], $hope_section, $comment));
	}else{
		execSQLQuery('UPDATE parts SET car = ?, state = ?, hope_section = ?, comment = ? WHERE event_id = ? AND member_id = ?', array($_POST["car"], $_POST["state"], $hope_section, $comment, $event_id, $member["member_id"]));
	}

}



echo('<script type="text/javascript">

$(function(){
	$(".edit").on("click", function(e){

		button = jQuery(e.target);
		if(button.html() == ""){
			button = button.parent("button");
		}

		sei = button.parent("td").prev("td").prev("td").prev("td").prev("td").prev("td").find(".sei").text();
		mei = button.parent("td").prev("td").prev("td").prev("td").prev("td").prev("td").find(".mei").text();
		car = button.parent("td").prev("td").prev("td").prev("td").text();
		state = button.parent("td").prev("td").prev("td").prev("td").prev("td").find("span").attr("class");
		hope_section = button.parent("td").prev("td").text();
		comment = button.parent("td").prev("td").prev("td").text();

		$("input[name=comment]").val(comment);
		$("input[name=sei]").val(sei);
		$("input[name=mei]").val(mei);
		$("select[name=car]").val(car);
		$("select[name=state]").val(state);
		$("select[name=hope_section]").val(hope_section);

		prop = "border-color";
		color = "red";

		$("input[name=comment]").css(prop,color);
		$("input[name=sei]").css(prop,color);
		$("input[name=mei]").css(prop,color);
		$("select[name=car]").css(prop,color);
		$("select[name=state]").css(prop,color);
		$("select[name=hope_section]").css(prop,color);

		pos = $(".inputs").offset().top;
        $("html,body").animate({scrollTop: pos}, "fast");

	});
});

</script>');



echo('<div class="container"><h1>'.$event["title"]);
echo('<br />');
echo($event["date"].'(');

$weekday = array( "日", "月", "火", "水", "木", "金", "土" );
echo($weekday[date("w", strtotime($event["date"]))]);

echo(')</h1><h3>締切 : '.$event["deadline"].'</h3></div>');

echo('<div class="container inputs">
	<form action="" method="post">');
echo('初回は<a href="./register_member.php">メンバー登録</a>が必要です');

echo('<div class="form-group">
    <label for="formGroupExampleInput">姓</label>(姓だけでも可)
    <input name="sei" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">名</label>(名だけでも可)
    <input name="mei" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">出欠</label>
   	<select name="state" class="form-control">
		<option value="1">参加</option>
		<option value="0">不参加</option>
	</select>
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">配車</label>
   	<select name="car" class="form-control">
		<option value="0">自走不可</option>
		<option value="1" selected>自走</option>
		<option value="2">自分を含めて2人</option>
		<option value="3">自分を含めて3人</option>
		<option value="4">自分を含めて4人</option>
		<option value="5">自分を含めて5人</option>
	</select>
  </div>');


if(strlen($event["hope_sections"]) !== 0){
	$hope_sections = explode(",",$event["hope_sections"]);
	echo('<div class="form-group">
	    <label for="formGroupExampleInput">希望配置</label>
	   	<select name="hope_section" class="form-control">');
	foreach($hope_sections as $hope_section){
		echo('<option value="'.$hope_section.'">'.$hope_section.'</option>');
	}
	echo('</select>
	  </div>');
}

echo('<div class="form-group">
    <label for="formGroupExampleInput">コメント</label>
    <input name="comment" class="form-control" type="text">
  </div>');



echo('<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button>
	</form></div>');



$parts = execSQLQuery('SELECT * FROM parts WHERE event_id = ? AND (state = 0 OR state = 1) ORDER BY state DESC, part_id ASC', array($event_id));


echo('<div class="container"><hr /><table class="table">
  <thead>
    <tr>
      <th>お名前</th>
      <th></th>
      <th>配車</th>
      <th>コメント</th>
      <th>希望配置</th>
      <th></th>
    </tr>
  </thead>
  <tbody>');


foreach($parts as $part){

	echo('<tr>');
	$member = execSQLQuery('SELECT * FROM members WHERE member_id = ?', array($part["member_id"]))[0];

	echo('<td><span class="sei">'.$member["sei"].'</span><span class="mei">'.$member["mei"].'</span></td>');

	if($part["state"] == 1){
		echo('<td><span class="1"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:#0F0;"></span></span></td>');
	}else{
		echo('<td><span class="0"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:#F00;"></span></span></td>');
	}

	echo('<td>'.$part["car"].'</td>');

	echo('<td style="max-width:200px;word-wrap:break-word;">'.$part["comment"].'</td>');
	echo('<td>'.$part["hope_section"].'</td>');


	echo('<td><button class="btn btn-primary edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>');
	echo('</tr>');

}
echo('</tbody></table></div>');




$parts = execSQLQuery('SELECT * FROM parts WHERE event_id = ? AND (state = 1)', array($event_id));

echo('<div class="container">
<hr />
	<table class="table">
  <thead>
    <tr>
      <th>配置</th>
      <th>人数</th>
    </tr>
  </thead>
  <tbody>');

$sections = array();
foreach($parts as $part){

	if(!empty($part["section"])){
		if(array_key_exists($part["section"], $sections)){
			$sections[$part["section"]] += 1;
		}else{
			$sections[$part["section"]] = 1;
		}
	}
}

foreach($sections as $section => $num){

	echo('<tr><td>');
	if($section == ''){
		echo('配置未定');
	}else{
		echo($section);
	}
	echo('</td><td>'.$num.'</td></tr>');
}



echo('<tr><td>合計人数</td><td>');

echo(count($parts));

echo('</td></tr>
	</tbody></table></div>');


echo('<div class="container">
	<a href="./gen_csv.php?event_id='.$event_id.'" class="btn btn-default btn-primary"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></a>
	<a href="./display_log.php?num='.$event_id.'" class="btn btn-default btn-primary"><span class="glyphicon glyphicon-time" aria-hidden="true"></span></a>

	<a href="./admin/set_section.php?event_id='.$event_id.'" class="btn btn-default btn-warning"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
	<a href="./admin/reminder.php?event_id='.$event_id.'" class="btn btn-default btn-warning"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a>
	<a href="./admin/dispatcher.php?event_id='.$event_id.'" class="btn btn-default btn-warning"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></a>
	<a href="./admin/delete_event.php?event_id='.$event_id.'" class="btn btn-default btn-warning"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
	<br /><br /><br /></div>');


?>
