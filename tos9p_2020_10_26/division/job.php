<?php

include('../attendance/lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();




if(isset($_POST["sei"]) && isset($_POST["mei"]) && (strlen($_POST["sei"]) !== 0 || strlen($_POST["mei"]) !== 0)){

  if(strlen($_POST["sei"]) !== 0 && strlen($_POST["mei"]) !== 0){

    $members = execSQLQuery("SELECT * FROM members WHERE (sei = ? AND mei = ?)", array($_POST["sei"], $_POST["mei"]));
  }else{

    $members = execSQLQuery("SELECT * FROM members WHERE (sei = ? OR mei = ?)", array($_POST["sei"], $_POST["mei"]));
  }

  if(count($members) == 0){

    exit('<div class="container">
      <span style="font-size:25px;"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span></span>&emsp;メンバーが存在しません.</div>');
  }

  if(count($members) > 1){

    exit('<div class="container">
      <span style="font-size:25px;"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span></span>&emsp;同性または同名のメンバーが存在します.
      </div>');
  }

  $member = $members[0];

  execSQLQuery("INSERT INTO replies (job_id, member_id, comment) VALUES (?, ?, ?)", array($_POST["job_id"], $member["member_id"], $_POST["comment"]));
 
}













$job = execSQLQuery("SELECT * FROM jobs WHERE job_id = ?", array($_GET["job_id"]))[0];
echo('<div class="container">');
echo('<h1>'.$job["title"].'</h1>');

$replies = execSQLQuery("SELECT * FROM replies WHERE job_id = ?", array($_GET["job_id"]));

foreach($replies as $reply){

	$member = execSQLQuery('SELECT * FROM members WHERE member_id = ?', array($reply["member_id"]))[0];

echo('<div class="panel panel-default">
  <div class="panel-footer">'.$member["sei"].$member["mei"].'</div>
  <div class="panel-body">'.$reply["comment"].'</div>
</div>');

}







echo('<div class="container">
	<h3>Reply</h3>');

echo('<form action="" method="post">');


echo('<div class="form-group">
    <label for="formGroupExampleInput">姓</label>(姓だけでも可)
    <input name="sei" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">名</label>(名だけでも可)
    <input name="mei" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">本文</label>
    <input name="comment" class="form-control" type="text">
  </div>');

echo('<input type="hidden" name="job_id" value="'.$_GET["job_id"].'" />');

echo('<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button>
	</form></div>');


?>