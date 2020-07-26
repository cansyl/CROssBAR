<?php

$name = $_GET['name'];
$file = 'covid19.json' ;

$data = json_decode(file_get_contents($file));

foreach($data->nodes as $node){
	if($node->data->Node_Name == $name){
		echo $node->data->id;
		die();
	}
}

//print_r($_GET);

?>