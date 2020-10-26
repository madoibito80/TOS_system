<?php

include('../attendance/lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();

$jobs = execSQLQuery("SELECT * FROM jobs", array());

echo('<div class="container">
  <a href="./admin/set_event.php" class="btn btn-default btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
</div>');

echo('<div class="container"><table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>ジョブ名</th>
      <th>担当者</th>
    </tr>
  </thead>
  <tbody>');


foreach($jobs as $job){
    echo('<tr><td>#'.$job["job_id"].'</td><td><a href="job.php?job_id='.$job["job_id"].'">'.$job["title"].'</a></td><td></td></tr>');
}


echo('</tbody></table>


<ul class="list-group">
  <li class="list-group-item list-group-item-success">list-group-item-success</li>
  <li class="list-group-item list-group-item-info">list-group-item-info</li>
  <li class="list-group-item list-group-item-warning">list-group-item-warning</li>
  <li class="list-group-item list-group-item-danger">list-group-item-danger</li>
</ul>





  </div>');

?>