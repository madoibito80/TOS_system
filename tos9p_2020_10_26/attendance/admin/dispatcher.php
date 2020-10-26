<?php

include('../lib.php');
header("Content-Type: text/html;charset=UTF-8");
libraries();
menubar();

$parts = execSQLQuery('SELECT * FROM parts WHERE event_id = ? AND state = 1', array($_GET["event_id"]));

$self = '';
$nonself = '';
$sections = array();

foreach($parts as $part){

	$member = execSQLQuery('SELECT * FROM members WHERE member_id = ?', array($part["member_id"]))[0];
	$members = execSQLQuery("SELECT * FROM members WHERE sei = ?", array($member["sei"]));

	if(count($members) > 1){
		$name = $member["sei"].$member["mei"];
	}else{
		$name = $member["sei"];
	}

	if($part["car"] == 0){
		$nonself .= $name.' ';
	}else{
		$self .= $name.' ';
	}

	if(!array_key_exists($part["hope_section"], $sections)){
		$sections[$part["hope_section"]] = '';
	}

	$sections[$part["hope_section"]] .= $name.' ';

}

	echo('<div class="container"><pre>[自走]'."\r\n".$self."\r\n\r\n".'[配車]'."\r\n".$nonself.'</pre></div>');
	echo('<div class="container"><pre>');
	foreach($sections as $section => $names){
		echo('['.$section.']'."\r\n");
		echo($names);
		echo("\r\n");
	}

	echo('</pre></div>');

?>