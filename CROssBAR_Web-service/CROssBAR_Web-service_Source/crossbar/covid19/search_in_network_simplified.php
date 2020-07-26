<?php
$file = 'simplified.json' ;

$data = json_decode(file_get_contents($file));
//print_r($data->nodes);

$ret_arr = array( 'Node_Name' => array() , 'id' => array() );

foreach($data->nodes as $node){
	//$ret_arr[$node->data->id] = $node->data->display_name;

	if (strpos(strtolower($node->data->Node_Name), strtolower($_GET['term'])) !== false)
		$ret_arr['Node_Name'][] = $node->data->Node_Name;
	//$ret_arr['id'][] = $node->data->id;
}

//echo json_encode($ret_arr);
echo json_encode($ret_arr['Node_Name']);

//print_r($_POST);
//echo json_encode($_GET);
/*
$arr = array('aaa','bbb','ccc');
echo json_encode($arr);
*/
?>