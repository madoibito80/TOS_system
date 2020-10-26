<?php

header('Content-Type: text/plane;charset=UTF-8');

if(preg_match("/^[0-9]+$/",$_GET["num"])){
	echo(file_get_contents('./log/'.$_GET["num"].'.log'));
}

?>