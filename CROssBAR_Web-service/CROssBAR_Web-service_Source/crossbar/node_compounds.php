<?php
$proteins_to_compound = array();
$tmpAccession = '';
$node_compounds = array(); # network'e eklenecek compoundlarin tutuldugu array...

if(($chembl_targets = fetch_data('/targets?limit=1000&accession='.$accessions_str)) === false){
	fwrite($report, 'Could not found any targets with accesions: '.$accessions_str."\n");
	//die('target');
}else{
	foreach($chembl_targets->targets as $target){
		# temporary accession degiskeni ile bir proteine ait sadece ilk kayittaki target_chembl_id degerini aliyoruz.
		# cunku ilki haric digerleri single protein degil, protein family.
		if($target->accession == $tmpAccession)
			continue;
		else{
			$tmpAccession = $target->accession;
			$proteins_to_compound[$target->accession] = $target->target_chembl_id;
		}
	}
	$page_number_of_targets = $chembl_targets->pageMeta->totalPages;
	for($i = 1; $i < $page_number_of_targets; $i++){
		if(($chembl_targets = fetch_data('/targets?limit=1000&page='.$i.'&accession='.$accessions_str)) !== false)
			foreach($chembl_targets->targets as $target){
				if($target->accession == $tmpAccession)
					continue;
				else{
					$tmpAccession = $target->accession;
					$proteins_to_compound[$target->accession] = $target->target_chembl_id;
				}
			}
	}
}
#var_dump($proteins_to_compound); die();

$target_chembl_ids_str = implode(',',$proteins_to_compound);
$all_compounds = array(); # bu array de elemanlar protein -> compound seklinde tutuluyor.

if(count($proteins_to_compound)){
	$pchemblValue = 6;
	$iteration_number = 0;
	#while($pchemblValue>4 and $pchemblValue<11){
	while($iteration_number < 10){
		$iteration_number++;
		$chembl_activities = fetch_data('/activities?limit=1&pchemblValue='.$pchemblValue.'&targetChemblId='.$target_chembl_ids_str);

		if($chembl_activities === false){
			$pchemblValue--;
			continue;
		}
		if($chembl_activities->pageMeta->totalElements>1500){
			if($pchemblValue < 8)
				$pchemblValue += 0.5;
			else
				$pchemblValue += 0.2;
			continue;
		}
		if($chembl_activities->pageMeta->totalElements<750){
			if($pchemblValue > 8)
				$pchemblValue -= 0.5;
			else
				$pchemblValue -= 0.1;
			continue;
		}
		break;
	}

	if(isset($chembl_activities->pageMeta->totalElements))
		$num_of_fetched_compound = $chembl_activities->pageMeta->totalElements;
	else
		$num_of_fetched_compound = 0;
	fwrite($report,'Fetched '.$num_of_fetched_compound.' compounds with pchemblValue '.$pchemblValue."\n");
}

if(count($proteins_to_compound) and ($chembl_activities = fetch_data('/activities?limit=1000&pchemblValue='.$pchemblValue.'&targetChemblId='.$target_chembl_ids_str)) !== false ){

	$total_activity_page = $chembl_activities->pageMeta->totalPages;

	foreach($chembl_activities->activities as $activity){
		if (!isset($all_compounds[$activity->molecule_chembl_id])){
			$all_compounds[$activity->molecule_chembl_id]['m'] = 0;
			$all_compounds[$activity->molecule_chembl_id]['pchembl_value'] = $activity->pchembl_value;
			#$all_compounds[$activity->molecule_chembl_id]['display_name'] = $activity->molecule_chembl_id;
		}
		$all_compounds[$activity->molecule_chembl_id]['m']++;
		# burda accesion degerleriyle compounds arasinda edge olusturulacak.
		$all_compounds[$activity->molecule_chembl_id]['edges'][] = array_search($activity->target_chembl_id,$proteins_to_compound);
	}

	for($i = 1; $i < $total_activity_page; $i++){
		if( ($chembl_activities = fetch_data('/activities?limit=1000&pchemblValue='.$pchemblValue.'&page='.$i.'&targetChemblId='.$target_chembl_ids_str)) !== false ){
			//$chembl_activities = json_decode(file_get_contents());
			foreach($chembl_activities->activities as $activity){
				if (!array_key_exists($activity->molecule_chembl_id,$all_compounds)){
					$all_compounds[$activity->molecule_chembl_id]['m'] = 0;
					$all_compounds[$activity->molecule_chembl_id]['pchembl_value'] = $activity->pchembl_value;
				}
				$all_compounds[$activity->molecule_chembl_id]['m']++;
				# burda accesion degerleriyle compounds arasinda edge olusturulacak.
				$all_compounds[$activity->molecule_chembl_id]['edges'][] = array_search($activity->target_chembl_id,$proteins_to_compound);
			}
		}
	}
	unset($chembl_activities);

	fwrite($report,"\n".'Number of the unique Compounds: '.count($all_compounds)."\n");

	/* compounds enrichment N and M values */
	$compounds_NM = $db->table('chembl_compound_enrichment',PDO::FETCH_GROUP|PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC);

	# 4559 => N_universe
	$n = count($proteins);
	$compounds_enrich_scores = array();
	foreach($all_compounds as $key => $arr){
		#$small_m_of_compound = count(array_unique($all_compounds[$key]['edges']));
		$all_compounds[$key]['m'] = count(array_unique($all_compounds[$key]['edges']));
		# big M value 0 olan compoundlar en sona aliniyor...
		if(isset($compounds_NM[$key]) and $compounds_NM[$key]['M'])
			$score = ($all_compounds[$key]['m']*$all_compounds[$key]['m']/$n) / ($compounds_NM[$key]['M'] / $compounds_NM[$key]['N']);
		else
			$score = -1;
			//unset($all_compounds[$key]);
		$compounds_enrich_scores[] = array('id'=>$key,'enrichScore'=>$score);
		$all_compounds[$key]['enrichScore'] = round($score,3);
	}

	# force starter compound
	if(isset($starter_searchs['compounds']))
	{
		foreach($starter_searchs['compounds'] as $id => $arr)
		{
			if(isset($all_compounds[$id])){
				$all_compounds[$id]['enrichScore'] = 9999999;
				$total_enriched_coms = count($compounds_enrich_scores);
				for($i=0; $i<$total_enriched_coms; $i++){
					if($compounds_enrich_scores[$i]['id'] == $id){
						$compounds_enrich_scores[$i]['enrichScore'] = 9999999;
						break;
					}
				}
			}else{
				$compounds_enrich_scores[] = array('id'=>$id,'enrichScore'=>9999999);
				$all_compounds[$id] = array('pchembl_value'=>$arr['pchembl_value'],'edges'=>array(),'enrichScore'=>9999999);
				//print_r($arr); die();
				foreach($arr['edges'] as $e){
					if(isset($proteins[$e])){
						$all_compounds[$id]['edges'][] = $e;
					}
				}
			}
		}
	}

	usort($compounds_enrich_scores, 'sortByEnSc');
	unset($compounds_NM);

	$total_compound = count($compounds_enrich_scores);
	$compounds_to_be_processed =  $num_of_nodes*2;
	for($i=0,$j=0; $j<$compounds_to_be_processed && $i < $total_compound; $i++, $j++){
		$cid = $compounds_enrich_scores[$i]['id']; # compound id
		$if_exist_as_drug = isset($drug_chembl_ids[$cid]);
		if( $if_exist_as_drug !== false){
			# so, drug version of compound exist in network
			# adding edge information to the drug which is exist already in network
			# this interaction must be blue color since it is coming from activities collection
			# 'Edge_Type'=>'Chembl' defined as blue in css/css.json file
			fwrite($report,'Compound '.$cid.' exist in network as drug. Edges merged.'."\n");
			$j--; # j degerini azaltalim ki almadigimiz compound'un yerine baska gelsin.
			$edges_tmp = array_unique($all_compounds[$cid]['edges']);
			foreach($edges_tmp as $e){
				//if(array_search($e,$drugs[$drug_chembl_ids[$compounds_enrich_scores[$i]['id']]]['edges']) === false)
				# ustteki if statement'inda $e degiskenini (uniprot id) related drug'in edge'leri icinde aradik,
				# bunun yerine protein array'imizde related drug var mi diye bakmayi tercih ettim.
				# boylece gelen protein'in network'de olmasini garantiliyoruz.
				# muhtemelen bu aramada network'de olmayan protein zaten gelmiyordu. i am not sure.
				if(!isset($proteins[$e]['drugs']))
					$proteins[$e]['drugs'] = array();
				if(array_search($drug_chembl_ids[$cid],$proteins[$e]['drugs']) === false){
					$proteins[$e]['drugs'][] = $drug_chembl_ids[$cid];
					$drugs[$drug_chembl_ids[$cid]]['edges'][] = $e;
					$edges[] = array('data'=>array('source'=>$e,'target'=>$drug_chembl_ids[$cid],'Edge_Type'=>'drugChembl','label'=>'targets'));
				}
			}
			#var_dump($all_compounds[$compounds_enrich_scores[$i]['id']]['edges']);
			continue;
		}
		# bu if statement'i ile, oncelikle bulunan compound'un ilac hali networkde var mi bakiliyor
		# eger network'de yoksa, daha once eklenen compoundlarin cluster'inda var mi bakiliyor
		# ikisinden de false deger alinirsa compound network'e ekleniyor.
		if(clusterCheck($node_compounds,$compounds_enrich_scores[$i]['id']) === false ){
			
			$node_compounds[$cid]['edges'] 			= $all_compounds[$cid]['edges'];
			$node_compounds[$cid]['pchembl_value'] 	= $all_compounds[$cid]['pchembl_value'];
			$node_compounds[$cid]['enrichScore'] 	= $all_compounds[$cid]['enrichScore'];
			$cluster = $db->tableWhere('chembl_compound_clusters',array('Compound_Id'=>$cid));
			if(count($cluster) > 0)
				$node_compounds[$cid]['cluster'] = explode(',',$cluster[0]['Cluster_Members']);
			else
				$node_compounds[$cid]['cluster'] = array();
		}else{
			$j--;
			fwrite($report,'Compound '.$cid.' not added. It is in the same cluster with previous ones.'."\n");
		}
	}

	write_enrichScores_extended($report,$all_compounds,$compounds_enrich_scores,'Bioactive compounds');

	unset($all_compounds);
	unset($compounds_enrich_scores);

	$total_compounds 	= count($node_compounds);
	$all_chembl_ids 	= array_keys($node_compounds);
	$all_chembl_ids_str = implode(',',$all_chembl_ids);
	
	if( ($drug_of_compounds = fetch_data('/drugs?chemblId='.$all_chembl_ids_str.'&limit='.$total_compounds) ) !== false ){
		# buraya girdigine gore bazi compoundlarin CROssBAR'da drug karsiligi var.
		# bunlari drug'a cevirelim fakat edgeleri mavi kalsin.
		
		foreach($drug_of_compounds->drugs as $drug){
			
			fwrite($report,'Compound '.$drug->chembl_id .' converted to drug '.$drug->name ."\n");
			
			$drugs[$drug->identifier]['display_name'] 	= $drug->name;
			$drugs[$drug->identifier]['chembl_id'] 		= $drug->chembl_id;
			$drugs[$drug->identifier]['enrichScore'] 	= 1000000;
			//$drugs[$drug->identifier]['edges'] 		= array();
			$tmp_edges = array_unique($node_compounds[$drug->chembl_id]['edges']);
			$drugs[$drug->identifier]['edges'] = $tmp_edges;
			$nodes[] = array('data'=>array('id'=>$drug->identifier,'display_name'=>$drug->name,'Node_Type'=>'Drug','enrichScore'=>1000000));
			# drug'a cevirdigimiz compound'un, drugbank'ten gelen edge'leri yesil olmali
			foreach($drug->targets as $target)
				foreach($target->accessions as $acc){
					if( isset($proteins[$acc]) ){
						$edges[] = array('data'=>array('source'=>$acc,'target'=>$drug->identifier,'Edge_Type'=>'Drug','label'=>'targets'));
						$proteins[$acc]['drugs'][] = $drug->identifier;
						$chck = array_search($acc,$tmp_edges);
						if($chck !== false)
							unset($tmp_edges[$chck]);
					}
				}
			foreach($tmp_edges as $e){
				$edges[] = array('data'=>array('source'=>$e,'target'=>$drug->identifier,'Edge_Type'=>'drugChembl','label'=>'targets'));
				$proteins[$e]['drugs'][] = $drug->identifier;
			}
			unset($node_compounds[$drug->chembl_id]);
		}
		unset($drug_of_compounds);
	}

	//var_dump($all_chembl_ids);
	# chembl compoundlar network'e ekleniyor
	$tmp_counter = 0;
	foreach($node_compounds as $id => $c){
		if($tmp_counter >= $num_of_nodes) break;

		unset($node_compounds[$id]['cluster']);
		$tmp_edges = array_unique($node_compounds[$id]['edges']);
		$node_compounds[$id]['edges'] = $tmp_edges;
		$nodes[] = array('data'=>array('id'=>$id,'display_name'=>$id,'Node_Type'=>'Compound','enrichScore'=>$c['enrichScore'],'pchembl_value'=>$c['pchembl_value']));
		foreach($tmp_edges as $e){
			$edges[] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'Chembl','label'=>'targets'));
			$proteins[$e]['compounds'][] = $id;
		}
		$tmp_counter++;
	}

	fwrite($report,'Number of Compounds after enrichment, drug check and clustering respectively: '.$tmp_counter."\n");
}else
	fwrite($report,"\nNot found any compounds.\n");
?>