<?php

ini_set('display_errors', 1);

include(dirname(__FILE__).'/conf.php');


function execSQLQuery($query, $parms){

	$pdo = new PDO(
	    'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8',
	    DB_USER,
	    DB_PASSWORD
	);


	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$stm = $pdo->prepare($query);

	foreach($parms as $in => $val){
    $parms[$in] = h(u($parms[$in]));
		$stm->bindParam($in+1, $parms[$in], PDO::PARAM_STR);
	}

	$stm->execute();
	$rec = $stm->fetchAll(PDO::FETCH_ASSOC);
	return $rec;
}

//UTF-8エンコーディング
function u($str){

	return mb_convert_encoding($str, 'utf-8');
}


//XSS対策
function h($str){

	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


//メニューバーの表示
function menubar($active_li="") {
echo('<nav class="navbar navbar-default navbar-inverse">
    <div class="container-fluid">
		<div class = "navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menubar">
                <span class="sr-only"> Toggle navigation </span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./');
if(strpos(getcwd(),'/admin') !== false){
      echo('../');
}
echo('events.php">
                    TOS9p
            </a>
		</div>
		<div class ="collapse navbar-collapse" id="menubar">
			<ul class="nav navbar-nav">
                <li class="nav-item');
if($active_li == "events"){
    echo(' active');
}
echo ('">
					<a class="nav-link" href="./');
if(strpos(getcwd(),'/admin') !== false){
      echo('../');
}
echo('events.php">イベント一覧</a></li>
		  <li class="nav-item"><a class="nav-link" href="../');
if(strpos(getcwd(),'/admin') !== false){
    echo('../');
}
echo('wiki/">Wiki</a></li>');

echo('<li class="nav-item');
if($active_li == "points"){
    echo(' active');
}
echo ('">
<a class="nav-link" href="./');
if(strpos(getcwd(),'/admin') !== false){
echo('../');
}
echo('points.php">Omake</a></li>');

echo('
		  </ul>
		  </li>
</ul>
</div>
</nav>');

}



//ライブラリの読み込み
function libraries(){

	//bootstrap
	echo('<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
 		<link href="/attendance/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

    	<!--[if lt IE 9]>
    	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
   		<![endif]-->

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    	<!-- Include all compiled plugins (below), or include individual files as needed -->
    	<script src="/attendance/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>');

	//jQuery
	echo('<script type="text/javascript" src="/jquery-3.1.1.min.js"></script>');
}


?>
