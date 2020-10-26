<?php

include('../header.php');

echo('<form action="" method="post">
	<table>
	<tr><td>イベント名</td><td><input type="text" name="title" /></td></tr>
	<tr><td>月</td><td><input type="text" name="month" /></td></tr>
	<tr><td>日</td><td><input type="text" name="day" /></td></tr>
	<tr><td><input type="submit" /></td></tr>
	</table>
	</form>');

if(isset($_POST["title"]) && isset($_POST["month"]) && isset($_POST["day"])){

	execSQLQuery('INSERT INTO events (title, date) VALUES (?, ?)', array($_POST["title"], '2017-'.$_POST["month"].'-'.$_POST["day"]));
}


$events = execSQLQuery("SELECT * FROM events", array());

foreach($events as $event){
    echo('<hr />');
    echo('<a href="attendance.php?event_id='.$event["event_id"].'">'.$event["title"].'</a>');
}

?>