<?php
include('database.php');
include('functions.php');
$db = new database();



#$i = 1;
for($i = 0; $i<28; $i++){
	#$file = json_decode(file_get_contents('https://wwwdev.ebi.ac.uk/crossbar/efo?limit=1000&page=0'));
	$file = fetch_data('/efo?limit=1000&page='.$i);
	//$flag = 0;
	foreach($file->diseases as $disease){
		#var_dump($disease->omim);
		/*
		var_dump(substr($disease->obo_id,0,5));
		$i++;
		if($i > 50) break;
		continue;
		*/
		
		if($disease->omim !== null and count($disease->omim) and substr($disease->obo_id,0,5) != 'CHEBI'){
			#echo $i++ . ') ' . $disease->obo_id . ' : ' . $disease->label .'<br>';
			/*
			if($disease->label == 'prostate cancer'){
				var_dump($disease);
				var_dump($disease->omim);
				var_dump(count($disease->omim));
				$flag = 1;
				break;
			}
			*/
			$db->insert('diseases',array('obo_id'=>$disease->obo_id, 'disease'=>$disease->label));
		}
		
		/*
		if($disease->omim !== null)
			echo $i++ . ') ' . $disease->obo_id . ' : ' . $disease->label .'<br>';
		*/
	}
	//if($flag) break;
}

		
		
?>