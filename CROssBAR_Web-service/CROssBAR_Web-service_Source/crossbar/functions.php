<?php

function get_http_response_code($url) {
	$headers = get_headers($url);
	return substr($headers[0], 9, 3);
}

function sortByPchemblVal($a, $b) {
	 return $b['pchembl_value'] > $a['pchembl_value'] ? 1 : -1;
}
function sortByEnSc($a, $b) {
	 return $b['enrichScore'] > $a['enrichScore'] ? 1 : -1;
}

function pval_calculate($pval_file){
	$pvals = array();
	$pvals_tmp = explode(']',shell_exec('Rscript ' . $pval_file));
	$total_pvals = count($pvals_tmp);
	for($i=1; $i<$total_pvals; $i++){
		$tmp = explode('[',$pvals_tmp[$i]);
		$pvals[] = trim($tmp[0]);
	}
	return $pvals;
}

function chembl_id_to_acc($moleculeChemblIds,$num_of_nodes,$pchemblValue=6){
	$targetChemblIds = array();

	if( ($activities = fetch_data('/activities?limit=1000&pchemblValue='.$pchemblValue.'&moleculeChemblId='.$moleculeChemblIds)) !== false)
	{
		$total_pages = $activities->pageMeta->totalPages;
		foreach($activities->activities as $activity)
			$targetChemblIds[] = array('id'=>$activity->target_chembl_id,'pchembl_value'=>$activity->pchembl_value,'assay_chembl_id'=>$activity->assay_chembl_id);
		for($i = 1; $i < $total_pages; $i++)
			if( ($activities = fetch_data('/activities?limit=1000&page='.$i.'&pchemblValue='.$pchemblValue.'&moleculeChemblId='.$moleculeChemblIds)) !== false)
				foreach($activities->activities as $activity)
					$targetChemblIds[] = array('id'=>$activity->target_chembl_id,'pchembl_value'=>$activity->pchembl_value,'assay_chembl_id'=>$activity->assay_chembl_id);
		unset($activities); # bellegi bosalt...
	}else
		return false;

	usort($targetChemblIds, 'sortByPchemblVal');
	$max_pchembl = $targetChemblIds[0]['pchembl_value'];
	$targetChemblIds_sorted = array();

	# first n node will be taken...
	$num_of_nodes *= 2;
	$total_targetChemblIds = count($targetChemblIds);
	for($i=0,$j=0; $j<$num_of_nodes and $i<$total_targetChemblIds; $i++,$j++){
		# avoiding from duplicate values even they have different pchemblValue
		if(array_search($targetChemblIds[$i]['id'],$targetChemblIds_sorted) === false){
			$targetChemblIds_sorted[] = $targetChemblIds[$i]['id'];
			
		}else
			$j--;
	}

	$pchemblValues_of_targets = array();
	$assayIds_of_targets = array();
	#print_r($targetChemblIds);
	foreach($targetChemblIds as $trgt){
		$pchemblValues_of_targets[$trgt['id']] = $trgt['pchembl_value'];
		$assayIds_of_targets[$trgt['id']] = $trgt['assay_chembl_id'];
	}
	#print_r($pchemblValues_of_targets);
	#$all_compounds[$cid]['pchembl_valOf_edges'][$e];
	$pchemblValues_of_relations = array();
	$assayIds_of_relations = array();
	$acc_of_chembl 	= array();
	if(count($targetChemblIds_sorted)){
		$targets_str 	= implode('|',$targetChemblIds_sorted);
		if( ($targets = fetch_data('/targets?limit=1000&targetIds='.$targets_str)) !== false ){
			$totalPages = $targets->pageMeta->totalPages;
			foreach($targets->targets as $target){
				if($target->accession != NULL){
					$acc_of_chembl[] = $target->accession;
					$pchemblValues_of_relations[$target->accession] = $pchemblValues_of_targets[$target->target_chembl_id];
					$assayIds_of_relations[$target->accession] = $assayIds_of_targets[$target->target_chembl_id];
				}
			}
			for($i=1; $i<$totalPages; $i++){
				if ( ($targets = fetch_data($url.'/targets?page='.$i.'&limit=1000&targetIds='.$targets_str)) !== false)
					foreach($targets->targets as $target){
						if($target->accession != NULL){
							$acc_of_chembl[] = $target->accession;
							$pchemblValues_of_relations[$target->accession] = $pchemblValues_of_targets[$target->target_chembl_id];
							$assayIds_of_relations[$target->accession] = $assayIds_of_targets[$target->target_chembl_id];
						}
					}
			}
			unset($targets);
		}
	}
	#print_r($pchemblValues_of_relations);
	$ret_arr['pchemblValues_of_relations'] = $pchemblValues_of_relations;
	$ret_arr['assayIds_of_relations'] = $assayIds_of_relations;
	$ret_arr['pchembl_value'] = $max_pchembl;
	$ret_arr['proteins']	  = array_values(array_filter($acc_of_chembl));
	return $ret_arr;
	#return array_values(array_filter($acc_of_chembl));
}

function reviewed_check($acc,$revieweds,$from,$report){
	if(array_search($acc,$revieweds,true) === false){
		fwrite($report, "$acc deleted from $from since it is an unreviewed entry\n");
		return false;
	}
	return true;
}

function protein_to_node($prots){

	$elements['nodes'] = array();
	$elements['edges'] = array();
	foreach($prots as $uniprotid => $protein){
		if(!isset($protein['enrichScore']))
			$elements['nodes'][] = array('data'=>array('id'=>$uniprotid, 'display_name'=>$protein['display_name'], 'Node_Type'=>$protein['Node_Type']));
		else
			$elements['nodes'][] = array('data'=>array('id'=>$uniprotid, 'display_name'=>$protein['display_name'], 'Node_Type'=>$protein['Node_Type'], 'enrichScore'=>$protein['enrichScore']));
		if(isset($protein['proteins']))
			foreach($protein['proteins'] as $p => $p_arr)
				$elements['edges'][] = array('data'=>array('source'=>$uniprotid,'target'=>$p,'Edge_Type'=>'PPI','label'=>'interacts w/', 'conf'=>$p_arr['conf'], 'link'=>$p_arr['link']));
	}
	return $elements;
}

function fetch_data($url){
	#$base = 'https://wwwdev.ebi.ac.uk/crossbar';
	$base = 'https://www.ebi.ac.uk/Tools/crossbar';
	// Create a stream
	$opts = array(
		'http'=>array('timeout' => 5)
	);
	$context = stream_context_create($opts);
	$num_of_tries = 0;
	while($num_of_tries<3){
		# Open the file using the HTTP headers set above
		$file = @file_get_contents($base.$url, false, $context);
		$content = json_decode($file);
		#print_r($content);
		if(!is_object($content)){
			$error = error_get_last();
			$f = fopen('data/crossbar_errors.txt', "a");
			fwrite($f,'Error while fetching data from CROssBAR:'."\n".'Request Url: '.$base.$url."\n".'Time: '.date("Y-m-d H:i:s a")."\nError: ".$error['message']."\n\n");
			$num_of_tries++;
			#fwrite($f,$base.$url."\n");
			# wait for a moment for try again
			# if we enter this block 3 times, fetching this file will be cancelled
			sleep(1); # wait 1 second
			#return false;
		}else
			break;
	}
	if($num_of_tries===3)
		return false;
	return $content;
}

function add_hpo($hpo_res, $acc, $db){
	$hpo_nodes = array();
	foreach($hpo_res as $hpo){
		if (!isset($hpo_nodes[$hpo['id']])){
			$hpo_disp = $db->tableWhere('hpo_term_names',array('hpo_id'=>$hpo['id']));
			if(count($hpo_disp) === 1)
				$hpo_nodes[$hpo['id']]['display_name'] = $hpo_disp[0]['term_name'];
			else
				$hpo_nodes[$hpo['id']]['display_name'] = $hpo['id'];
		}
		$hpo_nodes[$hpo['id']]['edges'][] = $acc;
	}
}

function clusterCheck($predictions,$compound){
	$flag = 0;
	foreach($predictions as $key => $prediction)
		if(array_search($compound,$prediction['cluster']) !== false){
			$flag++;
			break;
		}
	if($flag > 0)
		return true;
	else
		return false;
}

function form_input($input){
	$vals = array_map('trim',explode('|',$input));
	$vals = array_map('htmlspecialchars',$vals);
	return array_filter($vals);
}

?>
