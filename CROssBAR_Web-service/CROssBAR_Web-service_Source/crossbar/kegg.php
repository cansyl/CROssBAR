<?php
$kegg_Diseases = array();
$kegg_Pathways = array();

foreach($accessions as $acc){
	# kegg_Disease Nodes collecting
	$dis = $db->tableWhere('kegg_disease_protein',array('uniprotid'=>$acc));
	$pat = $db->tableWhere('kegg_pathway_protein',array('uniprotid'=>$acc));

	foreach($dis as $d){
		if (!isset($kegg_Diseases[$d['kegg_diseaseid']]))
			$kegg_Diseases[$d['kegg_diseaseid']] = array('display_name'=>$d['kegg_diseasename'],'m'=>1,'edges'=>array($acc));
		else{
			$kegg_Diseases[$d['kegg_diseaseid']]['m']++;
			$kegg_Diseases[$d['kegg_diseaseid']]['edges'][] = $acc;
		}
	}

	foreach($pat as $p){
		if (!isset($kegg_Pathways[$p['kegg_pathwayid']]))
			$kegg_Pathways[$p['kegg_pathwayid']] = array('display_name'=>$p['kegg_pathwayname'],'m'=>1,'edges'=>array($acc));
		else{
			$kegg_Pathways[$p['kegg_pathwayid']]['m']++;
			$kegg_Pathways[$p['kegg_pathwayid']]['edges'][] = $acc;
		}
	}
}

	$n = count($accessions);

	$kegg_pathway_Ms = $db->selectByColumn('kegg_pathways_enrichment',array('kegg_pathwayid','M'),PDO::FETCH_KEY_PAIR);
	# $bN = 6838; # big N value for kegg pathways
	$kegg_pathway_enrich_scores = array();
	foreach($kegg_Pathways as $key => $arr){
		if(isset($kegg_pathway_Ms[$key])){
			$m = $kegg_Pathways[$key]['m'];
			$enrichScore = ($kegg_Pathways[$key]['m']*$m/$n) / ($kegg_pathway_Ms[$key] / 6838);
			$pval_tmp_str = 'data = rbind(c(' . (6838-$kegg_pathway_Ms[$key]) . ',' . $kegg_pathway_Ms[$key] . '),c('. ($n-$m) .','. $m . '));';
		}else{
			$enrichScore = -1;
			$pval_tmp_str ='data = rbind(c(0,0),c(0,0));';
		}
		$kegg_pathway_enrich_scores[] = array('id'=>$key,'enrichScore'=>$enrichScore, 'pval'=>$pval_tmp_str);
		$kegg_Pathways[$key]['enrichScore'] = round($enrichScore,4);
		
	}
	unset($kegg_pathway_Ms);
	usort($kegg_pathway_enrich_scores, 'sortByEnSc');

	#pval calculations
	# creating R Script
	$pval_file = 'pvals/'.$_POST['params'].'_kpathways.R';
	$pval_f = fopen( $pval_file , "w" );
	$co = 0;
	foreach($kegg_pathway_enrich_scores as $p){
		if($co>99) break;
		fwrite($pval_f, $p['pval'] . 'test <- fisher.test(data);test$p.value;' );
		$co++;
	}
	fclose($pval_f);
	$pvals = pval_calculate($pval_file);

	# creating temporary KEGG Pathways log file
	$report_kpathways = fopen('data/'.$_POST['params'].'_kpathways', "w");
	fwrite($report_kpathways, 'Total number of collected KEGG pathways: '.count($kegg_pathway_enrich_scores)."\n\n");
	fwrite($report_kpathways, 'Pathway_id.'."\t".'Pathway_name'."\t".'Enrichment_score'."\t".'Significance_(p-value)'."\n");
	$co = 0;
	foreach($kegg_pathway_enrich_scores as $index => $score){
		if($co>99) break;
		if($co===$num_of_pathways_ver2)
			fwrite($report_kpathways,'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\n");
		fwrite($report_kpathways,$score['id']."\t".$kegg_Pathways[$score['id']]['display_name']."\t".$kegg_Pathways[$score['id']]['enrichScore']."\t".$pvals[$co]."\n");
		$co++;
	}
	fclose($report_kpathways);


	# verilen limitin ustu siliniyor
	$total_Pathway = count($kegg_Pathways);
	$tmp = array();
	for($i=0; $i<$total_Pathway and $i<$num_of_pathways_ver2; $i++){
		$key = $kegg_pathway_enrich_scores[$i]['id'];
		unset($kegg_Pathways[$key]['m']);
		$tmp[$key] = $kegg_Pathways[$key];
	}
	unset($kegg_pathway_enrich_scores);
	unset($kegg_Pathways);
	$kegg_Pathways = $tmp;
	unset($tmp);

	$kegg_disease_M = $db->selectByColumn('kegg_diseaseterms_enrichment',array('kegg_diseaseid','M'),PDO::FETCH_KEY_PAIR);
	# $bN = 3667; # big N value for kegg diseases
	$kegg_disease_enrich_scores = array();
	foreach($kegg_Diseases as $key => $arr){
		if(isset($kegg_disease_M[$key])){
			$m = $kegg_Diseases[$key]['m'];
			$enrichScore = ($kegg_Diseases[$key]['m']*$m/$n) / ($kegg_disease_M[$key] / 3667);
			$pval_tmp_str = 'data = rbind(c(' . (3667-$kegg_disease_M[$key]) . ',' . $kegg_disease_M[$key] . '),c('. ($n-$m) .','. $m . '));';
		}else{
			$enrichScore = -1;
			$pval_tmp_str ='data = rbind(c(0,0),c(0,0));';
		}
		$kegg_disease_enrich_scores[] = array('id'=>$key,'enrichScore'=>$enrichScore, 'pval'=>$pval_tmp_str);
		$kegg_Diseases[$key]['enrichScore'] = round($enrichScore,4);
	}
	unset($kegg_disease_M);
	
	if(isset($starter_searchs['kegg_diseases'])){
		foreach($starter_searchs['kegg_diseases'] as $id => $name){
			if(isset($kegg_Diseases[$id])){
				$kegg_Diseases[$id]['enrichScore'] = 9999999;
				foreach($kegg_disease_enrich_scores as $index => $diss)
					if($diss['id']==$id){
						$kegg_disease_enrich_scores[$index]['enrichScore'] = 9999999;
					}
			}else{
				# aranilan kegg disease network'e girmemis, force edip ekliyoruz
				$kegg_Diseases[$id]['display_name'] = $name;
				$kegg_Diseases[$id]['enrichScore']  = 9999999;
				$kegg_Diseases[$id]['edges']  		= array();
				$kegg_disease_enrich_scores[] = array('id'=>$id,'enrichScore'=>9999999, 'pval'=>'data = rbind(c(0,0),c(0,0));');
				$result = $db->tableWhere('kegg_disease_protein',array('kegg_diseaseid'=>$id));
				foreach($result as $r){
					if(isset($proteins[$r['uniprotid']])){
						# gelen protein network'de varsa edge olusturuluyor
						$kegg_Diseases[$id]['edges'][] = $r['uniprotid'];
					}
				}
			}
		}
	}

	if(isset($file['tonext']['kegg_diseases'])){
		foreach($file['tonext']['kegg_diseases'] as $id => $name){
			if (isset($kegg_Diseases[$id])){
				$kegg_Diseases[$id]['enrichScore'] = 1000000;
				foreach($kegg_disease_enrich_scores as $index => $diss)
					if($diss['id']==$id){
						$kegg_disease_enrich_scores[$index]['enrichScore'] = 1000000;
					}
			}else{
				# aranilan kegg disease network'e girmemis, force edip ekliyoruz
				$kegg_Diseases[$id]['display_name'] = $name;
				$kegg_Diseases[$id]['enrichScore']  = 1000000;
				$kegg_Diseases[$id]['edges']  		= array();
				$kegg_disease_enrich_scores[] = array('id'=>$id,'enrichScore'=>1000000, 'pval'=>'data = rbind(c(0,0),c(0,0));');
				$result = $db->tableWhere('kegg_disease_protein',array('kegg_diseaseid'=>$id));
				foreach($result as $r){
					if(isset($proteins[$r['uniprotid']])){
						# gelen protein network'de varsa edge olusturuluyor
						$kegg_Diseases[$id]['edges'][] = $r['uniprotid'];
					}
				}
			}
		}
	}

	usort($kegg_disease_enrich_scores, 'sortByEnSc');

	#pval calculations
	# creating R Script
	$pval_file = 'pvals/'.$_POST['params'].'_kdiseases.R';
	$pval_f = fopen( $pval_file , "w" );
	$co = 0;
	foreach($kegg_disease_enrich_scores as $p){
		if($co>99) break;
		fwrite($pval_f, $p['pval'] . 'test <- fisher.test(data);test$p.value;' );
		$co++;
	}
	fclose($pval_f);
	$pvals = pval_calculate($pval_file);

	# creating temporary KEGG Pathways log file
	$report_kdiseases = fopen('data/'.$_POST['params'].'_kdiseases', "w");
	fwrite($report_kdiseases, 'Total number of collected KEGG diseases: '.count($kegg_disease_enrich_scores)."\n\n");
	fwrite($report_kdiseases, 'Disease_id.'."\t".'Disease_name'."\t".'Enrichment_score'."\t".'Significance_(p-value)'."\n");
	$co = 0;
	foreach($kegg_disease_enrich_scores as $index => $score){
		if($co>99) break;
		if($co===$num_of_diseases_ver2)
			fwrite($report_kdiseases,'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\n");
			#fwrite($report_kdiseases,'-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-'."\n");
		fwrite($report_kdiseases,$score['id']."\t".$kegg_Diseases[$score['id']]['display_name']."\t".$kegg_Diseases[$score['id']]['enrichScore']."\t".$pvals[$co]."\n");
		$co++;
	}
	fclose($report_kdiseases);


	# verilen limitin ustu siliniyor
	$total_Diseases = count($kegg_Diseases);
	$tmp = array();
	#for($i=0; $i<$total_Diseases and $i<$num_of_nodes; $i++){
	for($i=0; $i<$total_Diseases and $i<$num_of_diseases_ver2; $i++){
		$key = $kegg_disease_enrich_scores[$i]['id'];
		unset($kegg_Diseases[$key]['m']);
		$tmp[$key] = $kegg_Diseases[$key];
		$tmp[$key]['source'] = 'KEGG';
	}
	unset($kegg_disease_enrich_scores);
	unset($kegg_Diseases);
	$kegg_Diseases = $tmp;
	unset($tmp);

	# KEGG DISEASE-PATHWAY AND DISEASE-DRUG INTERACTIONS
	foreach($kegg_Diseases as $id => $arr){
		$paths = $db->tableWhere('kegg_disease_pathway',array('kegg_diseaseid'=>$id));
		foreach($paths as $pt){
			if(isset($kegg_Pathways[$pt['kegg_pathwayid']]) !== false)
				$edges[] = array('data'=>array('source'=>$id, 'target'=>$pt['kegg_pathwayid'], 'Edge_Type'=>'kegg_dis_path', 'label'=>'modulates'));
		}
		$drgs = $db->tableWhere('kegg_disease_drug',array('kegg_diseaseid'=>$id));
		foreach($drgs as $drg){
			if(isset($drugs[$drg['drugbankid']]) !== false)
				$edges[] = array('data'=>array('source'=>$id, 'target'=>$drg['drugbankid'], 'Edge_Type'=>'kegg_dis_drug', 'label'=>'indicates'));
		}
	}

	#print_r($edges);

?>
