<?php

include('./lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar("events");

$events = execSQLQuery("SELECT * FROM events ORDER BY date DESC", array());

echo('<div class="container">
  <a href="./admin/set_event.php" class="btn btn-default btn-warning"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
</div>');

echo('<div class="container"><table class="table">
  <thead>
    <tr>
      <th>日付</th>
      <th>イベント名</th>
    </tr>
  </thead>
  <tbody>');


foreach($events as $event){
    echo('<tr><td>');
    echo($event["date"].'</td><td><a href="attendance.php?event_id='.$event["event_id"].'">'.$event["title"].'</a></td>
      </tr>');
}


echo('</tbody></table>
  </div>');

?>
