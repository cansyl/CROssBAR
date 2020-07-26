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
	foreach($kegg_Pathways as $key => $arr)
		if(isset($kegg_pathway_Ms[$key])){
			$score = ($kegg_Pathways[$key]['m']*$kegg_Pathways[$key]['m']/$n) / ($kegg_pathway_Ms[$key] / 6838);
			$kegg_pathway_enrich_scores[] = array('id'=>$key,'enrichScore'=>$score);
			$kegg_Pathways[$key]['enrichScore'] = round($score,3);
		}
	unset($kegg_pathway_Ms);
	usort($kegg_pathway_enrich_scores, 'sortByEnSc');

	/*
	var_dump($kegg_Pathways);
	var_dump($kegg_pathway_enrich_scores);
	die();
	*/

	fwrite($report,"\nKEGG Pathways enrichment scores:\n");
	$co = 0;
	foreach($kegg_Pathways as $p){
		if($co>99) break;
		fwrite($report,"$p[display_name] : $p[enrichScore]\n");
		$co++;
	}

	# verilen limitin ustu siliniyor
	$total_Pathway = count($kegg_Pathways);
	$tmp = array();
	for($i=0; $i<$total_Pathway and $i<$num_of_nodes; $i++){
		$key = $kegg_pathway_enrich_scores[$i]['id'];
		unset($kegg_Pathways[$key]['m']);
		$tmp[$key] = $kegg_Pathways[$key];
		# kegg pathway edgeleri protein array'ina ekleniyor
		/*
		foreach($kegg_Pathways[$key]['edges'] as $p){
			$proteins[$p]['pathways'][] = $key;
		}
		*/
	}
	unset($kegg_pathway_enrich_scores);
	unset($kegg_Pathways);
	$kegg_Pathways = $tmp;
	unset($tmp);

	/*
	var_dump($kegg_Pathways);
	die();
	*/

	$kegg_disease_M = $db->selectByColumn('kegg_diseaseterms_enrichment',array('kegg_diseaseid','M'),PDO::FETCH_KEY_PAIR);
	# $bN = 3667; # big N value for kegg diseases
	$kegg_disease_enrich_scores = array();
	foreach($kegg_Diseases as $key => $arr)
		if(isset($kegg_disease_M[$key])){
			$score = ($kegg_Diseases[$key]['m']*$kegg_Diseases[$key]['m']/$n) / ($kegg_disease_M[$key] / 3667);
			$kegg_disease_enrich_scores[] = array('id'=>$key,'enrichScore'=>$score);
			$kegg_Diseases[$key]['enrichScore'] = round($score,3);
		}
	unset($kegg_disease_M);
	
	if(isset($starter_searchs['kegg_diseases'])){
		# var_dump($starter_searchs['kegg_diseases']);
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
				$kegg_disease_enrich_scores[] = array('id'=>$id,'enrichScore'=>9999999);
				$result = $db->tableWhere('kegg_disease_protein',array('kegg_diseaseid'=>$id));
				#var_dump($result); die();
				foreach($result as $r){
					# echo $r['uniprotid'] . '<br/>';
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
				$kegg_disease_enrich_scores[] = array('id'=>$id,'enrichScore'=>1000000);
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


	fwrite($report,"\nKEGG Diseases enrichment scores:\n");
	$co = 0;
	foreach($kegg_Diseases as $p){
		if($co>99) break;
		fwrite($report,"$p[display_name] : $p[enrichScore]\n");
		$co++;
	}
		#echo "$p[display_name] : $p[enrichScore] <br/>";

	# verilen limitin ustu siliniyor
	$total_Diseases = count($kegg_Diseases);
	$tmp = array();
	for($i=0; $i<$total_Diseases and $i<$num_of_nodes; $i++){
		$key = $kegg_disease_enrich_scores[$i]['id'];
		unset($kegg_Diseases[$key]['m']);
		$tmp[$key] = $kegg_Diseases[$key];
		$tmp[$key]['source'] = 'KEGG';
	}
	unset($kegg_disease_enrich_scores);
	unset($kegg_Diseases);
	$kegg_Diseases = $tmp;
	unset($tmp);

	//var_dump($kegg_Diseases); die();

	#var_dump($kegg_Diseases); die();
	#die();

	# KEGG DISEASE-PATHWAY AND DISEASE-DRUG INTERACTIONS

	#var_dump($kegg_Pathways); die();

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