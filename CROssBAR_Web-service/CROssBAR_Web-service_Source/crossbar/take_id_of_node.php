<?php

$name = $_GET['name'];
$file = 'datas/' . $_GET['file'] . '.json' ;
if(!file_exists($file)){
	echo json_encode(array());
	die();
}

$data = json_decode(file_get_contents($file));

foreach($data->nodes as $node){
	if($node->data->display_name == $name){
		echo $node->data->id;
		die();
	}
}

//print_r($_GET);

?>