<?php
$file = 'largescale_covid19_network_data.json' ;

$data = json_decode(file_get_contents($file));

$ret_arr = array( 'Node_Name' => array() , 'id' => array() );

foreach($data->nodes as $node){
	if (strpos(strtolower($node->data->Node_Name), strtolower($_GET['term'])) !== false)
		$ret_arr['Node_Name'][] = $node->data->Node_Name;
}

echo json_encode($ret_arr['Node_Name']);

?>