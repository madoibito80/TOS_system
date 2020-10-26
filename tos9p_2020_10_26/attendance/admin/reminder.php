<?php


include('../lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();


function getUninputMembers($event_id){

$members = execSQLQuery("SELECT * FROM members WHERE is_student = 1", array());
$res = array();

foreach($members as $member){
	$part = execSQLQuery("SELECT * FROM parts WHERE event_id = ? AND member_id = ?", array($event_id,$member["member_id"]))[0];
	if(empty($part)){
		array_push($res,$member);
	}
}

	return $res;
}


function getMailArray($members){

$mails = array();

foreach($members as $member){

	$to      = $member["email"];
	$subject = $_POST["subject"];
	$message = $member["sei"]."さん\r\n\r\n";
	$message .= $_POST["message"];
	$message .= "\r\n\r\n";
	$message .= 'http://'.$_SERVER['HTTP_HOST'].explode('/admin/',$_SERVER['REQUEST_URI'])[0].'/attendance.php?event_id='.$_POST["event_id"];
	$headers = "From: ".POSTMASTER_EMAIL."\r\n";

	array_push($mails, array(
				"to"=>$member["email"],
				"subject"=>$_POST["subject"],
				"message"=>$message,
				"headers"=>$headers
			));
}



//to admin

	$message = "管理者さん\r\n\r\n";
	$message .= $_POST["message"];
	$message .= "\r\n\r\n";
	$message .= 'http://'.$_SERVER['HTTP_HOST'].explode('/admin/',$_SERVER['REQUEST_URI'])[0].'/attendance.php?event_id='.$_POST["event_id"];


	$event = execSQLQuery('SELECT * FROM events WHERE event_id = ?', array($_POST["event_id"]))[0];

	array_push($mails, array(
				"to"=>$event["email"],
				"subject"=>$_POST["subject"],
				"message"=>$message,
				"headers"=>$headers
			));

	return $mails;

}





if($_GET["event_id"]){

echo('<div class="container">');
echo('<h1>出欠未入力者</h1>');

$event_id = $_GET["event_id"];

$members = getUninputMembers($event_id);

echo('<table class="table"><thead><tr><th>氏名</th><th>Email</th></tr></thead><tbody>');

foreach($members as $member){
		echo('<tr><td>'.$member["sei"].$member["mei"].'</td>');
		echo('<td>'.$member["email"].'</td></tr>');
}

echo('</tbody></table>
	</div>');


echo('<div class="container">
	<h1>催促メール一斉送信</h1>');

echo('<form action="./reminder.php" method="post">');

echo('<div class="form-group">
    <label for="formGroupExampleInput">件名</label>
    <input name="subject" class="form-control" type="text" size="30">
  	</div>');

echo('<div class="form-group">
    <label for="formGroupExampleInput">本文</label>
    <textarea name="message" class="form-control" type="text" size="30"></textarea>
  	</div>');

echo('<input type="hidden" name="event_id" value="'.$event_id.'" />');


echo('<button type="submit" class="btn btn-primary">確認画面へ</button>
	</form>
	</div>');

}




if(isset($_POST["subject"]) && isset($_POST["message"]) && isset($_POST["event_id"]) && !isset($_POST["confirmed"])){

	$members = getUninputMembers($_POST["event_id"]);

	echo('<div class="container">
	<h1>送信内容確認</h1>');

	$mails = getMailArray($members);


foreach($mails as $mail){

	echo('<pre>'.$mail["headers"]."To: ".$mail["to"]."\r\nSubject: ".$mail["subject"].'</pre>
		<pre>'.$mail["message"].'</pre>
		<hr />');
}


echo('<form action="./reminder.php" method="post">');
echo('<input type="hidden" name="event_id" value="'.$_POST["event_id"].'" />');
echo('<input type="hidden" name="subject" value="'.$_POST["subject"].'" />');
echo('<input type="hidden" name="message" value="'.$_POST["message"].'" />');
echo('<input type="hidden" name="confirmed" value="1" />');
echo('<button type="submit" class="btn btn-primary">送信</button>');
echo('</form>');

echo('</div>');

}



if(isset($_POST["subject"]) && isset($_POST["message"]) && isset($_POST["event_id"]) && isset($_POST["confirmed"])){

	mb_language("Japanese");
	mb_internal_encoding("UTF-8");

	$members = getUninputMembers($_POST["event_id"]);
	$mails = getMailArray($members);

	foreach($mails as $mail){
		mb_send_mail($mail["to"], $mail["subject"], $mail["message"], $mail["headers"]);
	}

		echo('<div class="container"><h3>送信完了</h3></div>');
}




?>