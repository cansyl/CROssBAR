<?php

$file_name = 'tmp'. time();
$data = fopen("tmps/".$file_name.'.txt', "w");
fwrite($data, $_POST['query']);
fclose($data);
echo $file_name;

?>