<?php

$proteins_fn = array();
$main_accessions_str 	= implode('|',$main_accessions);
$fn = array();

foreach($main_accessions as $main_accession){
	$intact_fns = $db->fetch_first_n($main_accession);
	if(count($intact_fns)){
		foreach($intact_fns as $intact_fn){
			$proteins_main[$main_accession]['proteins'][$intact_fn['acc']] = array('conf'=>$intact_fn['conf'], 'link'=>$intact_fn['link']);
			if($intact_fn['acc'] != $main_accession){
				$proteins_fn[$intact_fn['acc']]['intacts'][] = $main_accession;
				$proteins_fn[$intact_fn['acc']]['gene'] = $intact_fn['gene'];
			}
		}
	}
}

if(count($proteins_fn)){
	# apply enrichment to decrease number of first neighbours
	$ppi_M = $db->table('ppi_enrichment',PDO::FETCH_KEY_PAIR);
	$n = count($proteins_main);
	$fn_enrich_scores = array();
	foreach($proteins_fn as $key => $arr){
		if(isset($ppi_M[$key]) and $ppi_M[$key] != 0){
			$m = count(array_unique($arr['intacts']));
			$pval_tmp_str = 'data = rbind(c(' . (38736-$ppi_M[$key]) . ',' . $ppi_M[$key] . '),c('. ($n-$m) .','. $m . '));';
			$fn_enrich_scores[] = array('id'=>$key,'enrichScore'=>($m*$m/$n) / ($ppi_M[$key] / 38736), 'pval'=>$pval_tmp_str);
		}else{
			$fn_enrich_scores[] = array('id'=>$key,'enrichScore'=>-1, 'pval'=>'data = rbind(c(0,0),c(0,0));');
		}
	}
	unset($ppi_M);
	usort($fn_enrich_scores, 'sortByEnSc');

	#pval calculations
	# creating R Script
	$pval_file = 'pvals/'.$_POST['params'].'_fn.R';
	$pval_f = fopen( $pval_file , "w" );
	$co = 0;
	foreach($fn_enrich_scores as $p){
		/*
		if($co===$num_of_fn_nodes)
			break;
		*/
		if($co>99) break;
		fwrite($pval_f, $p['pval'] . 'test <- fisher.test(data);test$p.value;' );
		$co++;
	}
	fclose($pval_f);

	$pvals = pval_calculate($pval_file);

	# creating temporary interacting proteins log file
	$report_neighbours = fopen('data/'.$_POST['params'].'_fns', "w");
	fwrite($report_neighbours, 'Total number of collected interacting proteins: '.count($proteins_fn)."\n\n");
	fwrite($report_neighbours, 'UniProt_protein_acc.'."\t".'Gene_name'."\t".'Enrichment_score'."\t".'Significance_(p-value)'."\n");
	$co = 0;
	foreach($fn_enrich_scores as $p){
		if($co>99) break;
		if($co===$num_of_fn_nodes)
			fwrite($report_neighbours,'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\n");
		fwrite($report_neighbours,$p['id']."\t".$proteins_fn[$p['id']]['gene']."\t".round($p['enrichScore'],4)."\t".$pvals[$co]."\n");
		$co++;
	}
	fclose($report_neighbours);

	# user defined first n protein taking
	for($i=0; $i<$num_of_fn_nodes; $i++){
		# checking if exist protein with this index
		# avoiding if user defined 20 (n) but we have just 10 (n/2)
		if(isset($fn_enrich_scores[$i])){
			$fn[$fn_enrich_scores[$i]['id']] = array('display_name'=>$proteins_fn[$fn_enrich_scores[$i]['id']]['gene'], 'enrichScore'=>round($fn_enrich_scores[$i]['enrichScore'],3), 'Node_Type'=>'Protein_N');
		}else
			break;
	}

	unset($proteins_fn);
	unset($fn_enrich_scores);
}

?>
