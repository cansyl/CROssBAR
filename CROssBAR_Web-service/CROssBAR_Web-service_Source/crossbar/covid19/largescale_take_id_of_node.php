<?php

$name = $_GET['name'];
$file = 'largescale_covid19_network_data.json' ;

$data = json_decode(file_get_contents($file));

foreach($data->nodes as $node){
	if($node->data->Node_Name == $name){
		echo $node->data->id;
		die();
	}
}

?>