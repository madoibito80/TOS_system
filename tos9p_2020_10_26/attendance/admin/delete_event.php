<?php

include('../lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();


echo('<div class="container">
	<h1>イベント削除</h1>');


if(isset($_POST["event_id"])){

	execSQLQuery("DELETE FROM events WHERE event_id = ?", array($_POST["event_id"]));
	echo('イベントを削除しました.<br />');

}else{
	
	echo('<form action="" method="post">');
	echo('イベントを削除します.よろしいですか?<br />');

	echo('<input type="hidden" name="event_id" value="');
	echo($_GET["event_id"]);
	echo('" />');
	echo('<button type="submit" class="btn btn-primary">削除</button>');
	echo('</form>');
}

echo('</div>');

?>