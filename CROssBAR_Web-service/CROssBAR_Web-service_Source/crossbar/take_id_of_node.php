<?php
$name = $_GET['name'];
$type = $_GET['type'];
$file = 'datas/' . $_GET['file'] . '.json' ;
if(!file_exists($file)){
	echo json_encode(array());
	die();
}

$data = json_decode(file_get_contents($file));

foreach($data->nodes as $node){
	if($node->data->display_name == $name){
		if($type == 'Disease')
			if($node->data->Node_Type != 'Disease')
				continue;
		if($type == 'KEGG Disease')
			if($node->data->Node_Type != 'kegg_Disease')
				continue;
		if($type == 'HPO')
			if($node->data->Node_Type != 'HPO')
				continue;
		echo $node->data->id;
		die();
	}
}

//print_r($_GET);

?>