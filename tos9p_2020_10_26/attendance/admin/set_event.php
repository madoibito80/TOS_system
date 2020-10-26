<?php

include('../lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();

if(isset($_POST["title"]) && isset($_POST["year"]) && isset($_POST["month"]) && isset($_POST["day"]) && isset($_POST["hope_sections"]) &&
  isset($_POST["deadline_year"]) && isset($_POST["deadline_month"]) && isset($_POST["deadline_day"]) && isset($_POST["email"]) &&
  preg_match("/^[0-9]{4}+$/", $_POST["year"]) &&
  preg_match("/^[0-9]{1,2}+$/", $_POST["month"]) &&
  preg_match("/^[0-9]{1,2}+$/", $_POST["day"]) && 
  preg_match("/^[0-9]{4}+$/", $_POST["deadline_year"]) &&
  preg_match("/^[0-9]{1,2}+$/", $_POST["deadline_month"]) &&
  preg_match("/^[0-9]{1,2}+$/", $_POST["deadline_day"])
  ){

  execSQLQuery('INSERT INTO events (title, date, hope_sections, deadline, email) VALUES (?, ?, ?, ?, ?)', array($_POST["title"], $_POST["year"].'-'.$_POST["month"].'-'.$_POST["day"], $_POST["hope_sections"], $_POST["deadline_year"].'-'.$_POST["deadline_month"].'-'.$_POST["deadline_day"], $_POST["email"]));
}


echo('<div class="container">
	<h1>イベント追加</h1>');

echo('<form action="" method="post">');

echo('<div class="form-group">
    <label for="formGroupExampleInput">イベント名</label>
    <input name="title" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">開催年</label>(半角,YYYY)
    <input name="year" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">開催月</label>(半角,MM)
    <input name="month" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">開催日</label>(半角,DD)
    <input name="day" class="form-control" type="text">
  </div>');

echo('<div class="form-group">
    <label for="formGroupExampleInput">締切年</label>(半角,YYYY)
    <input name="deadline_year" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">締切月</label>(半角,MM)
    <input name="deadline_month" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">締切日</label>(半角,DD)
    <input name="deadline_day" class="form-control" type="text">
  </div>');

echo('<div class="form-group">
    <label for="formGroupExampleInput">希望配置</label>(カンマ区切り)
    <input name="hope_sections" class="form-control" type="text" placeholder="おまかせ,ゲート,ポスト,進行,計時">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">管理者メールアドレス</label>
    <input name="email" class="form-control" type="text">
  </div>');
echo('<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button>');

echo('</form>
	</div>');

?>