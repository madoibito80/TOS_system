<?php

$event_id = $_GET["event_id"];

header('Location: ./attendance/events.php?event_id='.$event_id);

?>