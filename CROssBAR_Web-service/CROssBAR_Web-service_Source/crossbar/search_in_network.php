<?php
$file = 'datas/' . $_GET['file'] . '.json' ;
if(!file_exists($file)){
	echo json_encode(array());
	die();
}

$data = json_decode(file_get_contents($file));
//print_r($data->nodes);

$ret_arr = array( 'display_name' => array() , 'id' => array() );

foreach($data->nodes as $node){
	//$ret_arr[$node->data->id] = $node->data->display_name;

	if (strpos(strtolower($node->data->display_name), strtolower($_GET['term'])) !== false)
		$ret_arr['display_name'][] = $node->data->display_name;
	//$ret_arr['id'][] = $node->data->id;
}

//echo json_encode($ret_arr);
echo json_encode($ret_arr['display_name']);

//print_r($_POST);
//echo json_encode($_GET);
/*
$arr = array('aaa','bbb','ccc');
echo json_encode($arr);
*/
?>