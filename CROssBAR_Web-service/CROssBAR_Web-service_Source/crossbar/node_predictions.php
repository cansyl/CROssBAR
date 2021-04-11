<?php

# compound predictions
$all_predictions = array();

foreach($accessions as $accession){
	$predictions = $db->tableWhereColumn('predictions_new','compounds',array('accession'=>$accession));
	if(count($predictions))
		foreach(explode(',',$predictions[0]['compounds']) as $prediction)
			$all_predictions[$prediction][] = $accession;
}

$predictions_enrich_scores = array();
$n = count($proteins);
$predictions_M = $db->table('deepscreen_enrichment',PDO::FETCH_KEY_PAIR);

foreach($all_predictions as $key => $acc){
	if(!isset($predictions_M[$key])){
		unset($all_predictions[$key]);
		continue;
	}else{
		$m = count($all_predictions[$key]);
		$pval_tmp_str = 'data = rbind(c(' . (522-$predictions_M[$key]) . ',' . $predictions_M[$key] . '),c('. ($n-$m) .','. $m . '));';
		$predictions_enrich_scores[] = array('id'=>$key,'enrichScore'=>($m*$m/$n) / ($predictions_M[$key] / 522), 'pval'=>$pval_tmp_str);
	}
}

unset($predictions_M);
usort($predictions_enrich_scores, 'sortByEnSc');

$predictions = array();
$total_preds = count($predictions_enrich_scores);
for($i=0,$j=0; $j<$num_of_nodes && $i < $total_preds; $i++, $j++){

	$pred_id = $predictions_enrich_scores[$i]['id']; # predicted compound id
	$score   = $predictions_enrich_scores[$i]['enrichScore'];

	# prediction'dan gelen chembl_id drug olarak network'de varsa => merging edges
	$if_exist_as_drug = isset($drug_chembl_ids[$pred_id]);
	if( $if_exist_as_drug !== false){
		fwrite($report,'Prediction '.$pred_id.' exist in network as drug. Edges merged.'."\n");
		$j--;
		foreach($all_predictions[$pred_id] as $e)
			if(array_search($drug_chembl_ids[$pred_id],$proteins[$e]['drugs']) === false){
				$proteins[$e]['drugs'][] = $drug_chembl_ids[$pred_id];
				$drugs[$drug_chembl_ids[$pred_id]]['edges'][] = $e;
				$edges[] = array('data'=>array('source'=>$e,'target'=>$drug_chembl_ids[$pred_id],'Edge_Type'=>'drugPrediction','label'=>'targets'));
			}
		continue;
	}

	# eger prediction, compound listesinde de varsa,
	# compound'da olmayan edge varsa ekleniyor (kirmizi, prediction edge olarak)
	if (isset($node_compounds[$pred_id])){
		fwrite($report,'Prediction '.$pred_id.' exist in network as chembl compound. Edges merged.'."\n");
		$j--;
		foreach($all_predictions[$pred_id] as $e)
			if(array_search($e,$node_compounds[$pred_id]['edges']) === false){
				$proteins[$e]['compounds'][] = $pred_id;
				$node_compounds[$pred_id]['edges'][] = $e;
				$edges[] = array('data'=>array('source'=>$e,'target'=>$pred_id,'Edge_Type'=>'compoundPrediction','label'=>'targets'));
			}
		continue;
	}

	# cluster'ı ekleniyor ve prediction node'u olarak tanımlanıyor.
	if(clusterCheck($predictions,$pred_id) === false){
		$predictions[$pred_id]['edges'] = $all_predictions[$pred_id];
		$predictions[$pred_id]['enrichScore'] = $score;
		$cluster = $db->tableWhere('chembl_compound_clusters',array('Compound_Id'=>$pred_id));
		if(count($cluster) > 0)
			$predictions[$pred_id]['cluster'] = explode(',',$cluster[0]['Cluster_Members']);
		else
			$predictions[$pred_id]['cluster'] = array();
	}else{
		$j--;
		fwrite($report,'Predicted compound '.$pred_id.' has not been incorporated to the KG since it is in the same compound similarity-based cluster with an incorporated compound.'."\n");
	}
}

#pval calculations
# creating R Script
$pval_file = 'pvals/'.$_POST['params'].'_predictions.R';
$pval_f = fopen( $pval_file , "w" );
$co = 0;
foreach($predictions_enrich_scores as $p){
	if($co>99) break;
	fwrite($pval_f, $p['pval'] . 'test <- fisher.test(data);test$p.value;' );
	$co++;
}
fclose($pval_f);
$pvals = pval_calculate($pval_file);

# creating temporary predictions log file
$report_predictions = fopen('data/'.$_POST['params'].'_predictions', "w");
fwrite($report_predictions, 'Total number of collected predicted compounds: '.count($predictions_enrich_scores)."\n\n");
fwrite($report_predictions, 'Compound_id.'."\t".'Enrichment_score'."\t".'Significance_(p-value)'."\n");
$co = 0;
foreach($predictions_enrich_scores as $index => $score){
	if($co>99) break;
		if($co===$num_of_nodes)
			fwrite($report_predictions,'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\n");
			#fwrite($report_predictions,'-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-'."\n");
	fwrite($report_predictions,$score['id']."\t".round($score['enrichScore'],4)."\t".$pvals[$co]."\n");
	$co++;
}
fclose($report_predictions);

unset($all_predictions);
unset($predictions_enrich_scores);

$total_predictions 	= count($predictions);
$all_chembl_ids 	= array_keys($predictions);

$all_chembl_ids_str = implode('|',$all_chembl_ids);

if($total_predictions !== 0){
	if( ($drug_of_predictions = fetch_data('/drugs?chemblId='.$all_chembl_ids_str.'&limit='.$total_predictions) ) !== false ){
		# buraya girdigine gore bazi predictionlarin CROssBAR'da drug karsiligi var.
		# bunlari drug'a cevirelim fakat edgeleri kirmizi kalsin.
		foreach($drug_of_predictions->drugs as $drug){

			fwrite($report,'Prediction '.$drug->chembl_id .' converted to drug '.$drug->name ."\n");

			$drugs[$drug->identifier]['display_name'] 	= $drug->name;
			$drugs[$drug->identifier]['chembl_id'] 		= $drug->chembl_id;
			$drugs[$drug->identifier]['enrichScore'] 	= 1000000;
			$drugs[$drug->identifier]['edges'] = $predictions[$drug->chembl_id]['edges'];
			$nodes[] = array('data'=>array('id'=>$drug->identifier,'display_name'=>$drug->name,'Node_Type'=>'Drug','enrichScore'=>1000000));
			# drug'a cevirdigimiz prediction'in, drugbank'ten gelen edge'leri yesil olmali
			$tmp_edges = $predictions[$drug->chembl_id]['edges'];
			$accs_of_this_drug = array();
			foreach($drug->targets as $target)
				foreach($target->accessions as $acc){
					if( isset($proteins[$acc]) ){
						$edges[] = array('data'=>array('source'=>$acc,'target'=>$drug->identifier,'Edge_Type'=>'Drug','label'=>'targets'));
						$proteins[$acc]['drugs'][] = $drug->identifier;
						$accs_of_this_drug[] = $acc;
						$chck = array_search($acc,$tmp_edges);
						if($chck !== false)
							unset($tmp_edges[$chck]);
					}
				}
			# drug'in chembl id'sinden gelen proteinleri kontrol ediyoruz, bunlar mavi olmali
			if($drug->chembl_id !== null){
				$tmp_res = chembl_id_to_acc($drug->chembl_id,$num_of_nodes,5);
				if($tmp_res !== false){
					$chembl_accs_of_drug =  array_diff( $tmp_res['proteins'] , $accs_of_this_drug);
					foreach($chembl_accs_of_drug as $e){
						if( isset($proteins[$e]) ){
							$edges[] = array('data'=>array('source'=>$e,'target'=>$drug->identifier,'Edge_Type'=>'drugChembl','label'=>'targets'));
							$chck = array_search($e,$tmp_edges);
							if($chck !== false)
								unset($tmp_edges[$chck]);
						}
					}
				}
			}
			foreach($tmp_edges as $e){
				$edges[] = array('data'=>array('source'=>$e,'target'=>$drug->identifier,'Edge_Type'=>'Prediction','label'=>'targets'));
				$proteins[$e]['drugs'][] = $drug->identifier;
			}
			unset($predictions[$drug->chembl_id]);
		}

		unset($drug_of_predictions);
	}
}

# predictions network'e ekleniyor
foreach($predictions as $k => $c){
	unset($predictions[$k]['cluster']);
	$tmp_edges = $c['edges'];

	# check chembl edges for rest of the predictions
	$chembl_accs_of_pred = chembl_id_to_acc($k,$num_of_nodes,5);
	if($chembl_accs_of_pred !== false)
		foreach($chembl_accs_of_pred['proteins'] as $e){
			if( isset($proteins[$e]) ){
				$edges[] = array('data'=>array('source'=>$e,'target'=>$k,'Edge_Type'=>'predictionChembl','label'=>'targets'));
				$chck = array_search($e,$tmp_edges);
				if($chck !== false)
					unset($tmp_edges[$chck]);
			}
		}

	$nodes[] = array('data'=>array('id'=>$k,'display_name'=>$k,'Node_Type'=>'Prediction','enrichScore'=>round($c['enrichScore'],4)));
	foreach($tmp_edges as $e){
		$edges[] = array('data'=>array('source'=>$e,'target'=>$k,'Edge_Type'=>'Prediction','label'=>'targets'));
		$proteins[$e]['predictions'][] = $k;
	}
}

?>
