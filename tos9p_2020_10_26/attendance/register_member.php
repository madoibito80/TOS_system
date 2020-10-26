<?php

include('./lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();


echo('<div class="container">
	<h1>メンバー登録・変更</h1>');

echo('<form action="" method="post">');

echo('<div class="form-group">
    <label for="formGroupExampleInput">姓</label>
    <input name="sei" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">名</label>
    <input name="mei" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">Email</label>
    <input name="email" class="form-control" type="text">
  </div>');
echo('<div class="form-group">
    <label for="formGroupExampleInput">Email(確認)</label>
    <input name="email2" class="form-control" type="text">
  </div>');


echo('<div class="form-group">
    <label for="formGroupExampleInput">身分</label>
   	<select name="is_student" class="form-control">
		<option value="1">学生</option>
		<option value="0">社会人</option>
	</select>
  </div>');

echo('<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>同姓同名を入力して再度このページからメンバー登録を行うことで, 登録内容の変更(上書き)を行うことができます.<br />
	<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>身分を社会人に設定すると, 出欠の未入力時に代表からの催促メールを受け取ることができなくなります.<br />');


echo('<input type="hidden" name="referer" value="');

if(isset($_POST["referer"])){

	echo($_POST["referer"]);
}else{
	echo($_SERVER["HTTP_REFERER"]);
}

echo('" />');

echo('<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button>');

echo('</form>');






if(isset($_POST["sei"]) && isset($_POST["mei"]) && isset($_POST["email"]) && isset($_POST["email2"]) && isset($_POST["is_student"])){

	if(strlen($_POST["sei"]) !== 0 && strlen($_POST["mei"]) !== 0 && strlen($_POST["email"]) !== 0 && $_POST["email"] == $_POST["email2"]){

		$member = execSQLQuery("SELECT * FROM members WHERE (sei = ? AND mei = ?)", array($_POST["sei"], $_POST["mei"]))[0];

		if(empty($member)){

			execSQLQuery('INSERT INTO members (sei, mei, email, is_student) VALUES (?, ?, ?, ?)', array($_POST["sei"], $_POST["mei"], $_POST["email"], $_POST["is_student"]));

			echo('メンバー登録が完了しました.<br />');

		}else{

			execSQLQuery('UPDATE members SET email = ?, is_student = ? WHERE sei = ? AND mei = ?', array($_POST["email"], $_POST["is_student"], $_POST["sei"], $_POST["mei"]));
			echo('既にメンバー登録されています.Emailと身分を更新しました.<br />');
		}

		echo('<a href="'.$_POST["referer"].'">戻る</a>');

	}else{

		echo('<span style="font-size:25px;"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span></span>&emsp;登録に失敗しました.項目を確認してください.');
	}

}

echo('</div>');

?>