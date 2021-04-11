<?php
$file = 'data/' . $_GET['file'] . '.json' ;
if(!file_exists($file)){
	echo json_encode(array());
	die();
}

$data = json_decode(file_get_contents($file));

$ret_arr = array( 'display_name' => array() , 'id' => array() );

foreach($data->nodes as $node){
	if (strpos(strtolower($node->data->display_name), strtolower($_GET['term'])) !== false)
		$ret_arr['display_name'][] = $node->data->display_name;
}

echo json_encode($ret_arr['display_name']);

?>