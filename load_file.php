<?php

/*========================================================================
  This file should be executed on the website server arround
  every minute. This is so that e-mails can be sent when needed and
  repeating tasks can be deleted and added.
/*=======================================================================*/

include("classes/database.class.php");
$db = database::connect();
// Delete expired repeating tasks, then re-add them.
$date_now = date('Y-m-d');
$sql = "SELECT * FROM tasks WHERE end_time < '$date_now' AND repeat_id IS NOT NULL";
$result = mysqli_query($db, $sql)->fetch_all();
echo '<pre>';
var_dump($result);
echo '</pre>';
?>