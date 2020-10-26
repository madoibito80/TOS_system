<?php

include('../attendance/lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();



if(isset($_POST["sei"]) && isset($_POST["mei"]) && isset($_POST["title"]) && (strlen($_POST["sei"]) !== 0 || strlen($_POST["mei"]) !== 0)){

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

  execSQLQuery("INSERT INTO jobs (title, member_id) VALUES (?, ?)", array($_POST["title"], $member["member_id"]));
 
}














  //jQuery
  //echo('<script type="text/javascript" src="/jquery-3.1.1.min.js"></script>');








echo('<div class="container">
	<h1>ジョブ追加</h1>');

echo('<form action="" method="post">');

echo('<div class="form-group">
    <label for="formGroupExampleInput">ジョブ名</label>
    <input name="title" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">説明</label>
    <textarea class="form-control" rows="6"></textarea>
  </div>');

echo('<h3>担当者</h3>');

echo('<div class="form-group">
    <label for="formGroupExampleInput">姓</label>(姓だけでも可)
    <input name="sei" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">名</label>(名だけでも可)
    <input name="mei" class="form-control" type="text">
  </div>');


echo('<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button>');

echo('</form>
	</div>');

?>