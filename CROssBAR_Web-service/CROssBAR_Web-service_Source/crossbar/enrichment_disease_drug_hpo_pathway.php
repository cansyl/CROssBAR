<?php
	# $num_of_nodes => network'e girecek node sayisini veren degisken

	# hpo big M values...
	$hpo_M = $db->table('hpo_enrichment',PDO::FETCH_KEY_PAIR);
	// m^2/n  /  M/N
	$n = count($proteins);
	# $bN = 4231; # big N value for HPO terms
	$hpo_enrich_scores = array();
	foreach($hpo_nodes as $key => $arr){
		$m = count($arr['edges']);
		$enrichScore = round((($m*$m/$n) / ($hpo_M[$key] / 4231)),3);
		$hpo_enrich_scores[] = array('id'=>$key,'enrichScore'=>$enrichScore);
		$hpo_nodes[$key]['enrichScore'] = $enrichScore;
	}

	# forcing starter hpo node to be in network
	if(isset($starter_searchs['hpos'])){
		foreach($starter_searchs['hpos'] as $id => $arr){
			if(isset($hpo_nodes[$id])){
				# searched HPO already exist in network
				# maximize the enrichScore
				foreach($hpo_enrich_scores as $index => $diss)
					if($diss['id']==$id){
						$hpo_nodes[$id]['enrichScore'] 			  = 9999999;
						$hpo_enrich_scores[$index]['enrichScore'] = 9999999;
						break;
					}
			}else{
				$hpo_nodes[$id]['display_name'] = $arr['display_name'];
				$hpo_nodes[$id]['enrichScore']  = 9999999;
				$hpo_nodes[$id]['edges'] = array();
				$hpo_enrich_scores[] = array('id'=>$id,'enrichScore'=>9999999);
				foreach($arr['edges'] as $ee){
					foreach($proteins as $unip => $p){
						if($p['display_name'] === $ee){
							$hpo_nodes[$id]['edges'][] = $unip;
							break;
						}
					}
				}
			}
		}
	}

	usort($hpo_enrich_scores, 'sortByEnSc');
	unset($hpo_M);

	write_enrichScores_extended($report,$hpo_nodes,$hpo_enrich_scores,'HPO');
	# verilen limitin ustu siliniyor
	$total_hpo = count($hpo_nodes);
	$hpo_tmp = array();
	for($i=0; $i<$total_hpo and $i<$num_of_nodes; $i++){
		$key = $hpo_enrich_scores[$i]['id'];
		$hpo_tmp[$key] = $hpo_nodes[$key];
	}
	unset($hpo_enrich_scores);
	unset($hpo_nodes);
	$hpo_nodes = $hpo_tmp;
	unset($hpo_tmp);

	//write_enrichScores($report,$hpo_nodes,'HPO');

	# reactome enrichment M values
	$pathway_M = $db->selectByColumn('reactome_enrichment',array('id','M'),PDO::FETCH_KEY_PAIR);
	// m^2/n  /  M/N
	# $n = count($proteins); => yukarda hpo icin bu degeri hesapladik zaten
	# $bN = 64275; # big N value for pathways
	$pathway_enrich_scores = array();
	foreach($pathway_nodes as $key => $arr){
		$m = count($arr['edges']);
		if(isset($pathway_M[$key]))
			$enrichScore = round((($m*$m/$n) / ($pathway_M[$key] / 64275)),3);
		else
			$enrichScore = 0;
		$pathway_enrich_scores[] = array('id'=>$key,'enrichScore'=>$enrichScore);
		$pathway_nodes[$key]['enrichScore'] = $enrichScore;
	}
	# print_r($pathway_enrich_scores); die();
	if(isset($starter_searchs['pathways'])){
		foreach($starter_searchs['pathways'] as $id => $arr){
			$found_id = '';
			foreach($pathway_nodes as $id => $pat)
				if($pat['display_name']==$arr['display_name']){
					$pathway_nodes[$id]['enrichScore'] = 9999999;
					$found_id = $id;
					break;
				}
			if($found_id !== '')
				foreach($pathway_enrich_scores as $index => $pt)
					if($found_id === $pt['id']){
						$pathway_enrich_scores[$index]['enrichScore'] = 9999999;
						break;
					}
		}
	}

	usort($pathway_enrich_scores, 'sortByEnSc');
	unset($pathway_M);

	write_enrichScores_extended($report,$pathway_nodes,$pathway_enrich_scores,'Pathway');
	# verilen limitin ustu siliniyor
	$total_pathway = count($pathway_nodes);
	$pathway_tmp = array();
	for($i=0; $i<$total_pathway and $i<$num_of_nodes; $i++){
		$key = $pathway_enrich_scores[$i]['id'];
		$pathway_tmp[$key] = $pathway_nodes[$key];
		$pathway_tmp[$key]['enrichScore'] = $pathway_nodes[$key]['enrichScore'];
		# pathway edgeleri protein array'ina ekleniyor
		/*
		foreach($pathway_nodes[$key]['edges'] as $p){
			$proteins[$p]['pathways'][] = $key;
		}
		*/
	}
	unset($pathway_enrich_scores);
	unset($pathway_nodes);
	$pathway_nodes = $pathway_tmp;
	unset($pathway_tmp);

	//write_enrichScores($report,$pathway_nodes,'Pathway');

	# disease enrichment M values
	$disease_M = $db->selectByColumn('diseaseterms_enrichment',array('obo_id','M'),PDO::FETCH_KEY_PAIR);
	# 3601 => big N value for diseases
	# $n = count($proteins); => yukarda hpo icin bu degeri hesapladik zaten
	$disease_enrich_scores = array();
	foreach($disease_nodes as $key => $arr){
		$m = count($arr['edges']);
		if(isset($disease_M[$key]) and $disease_M[$key] != 0)
			$enrichScore = round((($m*$m/$n) / ($disease_M[$key] / 3601)),3);
		else
			$enrichScore = 0;
		$disease_enrich_scores[] 			= array('id'=>$key,'enrichScore'=>$enrichScore);
		$disease_nodes[$key]['enrichScore'] = $enrichScore;
	}
	unset($disease_M);
	
	#var_dump($disease_nodes);
	
	# arama yapilan disease node'u varsa disease'ler enrichScore'a gore siralanmadan force ediyoruz...
	if(isset($starter_searchs['diseases'])){
		foreach($starter_searchs['diseases'] as $id => $arr){
			if(isset($disease_nodes[$id])){
				# arama yapilan disease network'e girmis.
				# enrichScore'unu maximize ederek elenmemesini garantiliyoruz
				foreach($disease_enrich_scores as $index => $diss)
					if($diss['id']==$id){
						#$disease_nodes[$id]['edges'] = array_merge($disease_nodes[$id]['edges'],$arr['edges']);
						foreach($arr['proteins'] as $se)
							if(isset($proteins[$se]))
								$disease_nodes[$id]['edges'][] = $se;
							
						$disease_nodes[$id]['enrichScore'] 			  = 9999999;
						$disease_enrich_scores[$index]['enrichScore'] = 9999999;
						
					}
			}else{
				# arama yapilan disease network'de yok. ekliyoruz.
				# bu kismi deneyemedim henuz.
				# aramada adi girilen fakat network'e hic girmeyen disease case'i bilmiyorum.
				# boyle bi case icin disease'in CROssBAR'da olmamasi,
				# kegg'den gelen proteinlerinde reviewed filtresinde elenmesi lazim sanirim.
				# bu durumla hic karsilasilmayabilir...
				$disease_nodes[$id]['display_name'] = $arr['display_name'];
				$disease_nodes[$id]['source']		= 'EFO';
				$disease_nodes[$id]['enrichScore']  = 9999999;
				$disease_nodes[$id]['edges'] = array();
				$disease_nodes[$id]['omims'] = array();
				$disease_enrich_scores[] = array('id'=>$id,'enrichScore'=>9999999);
				foreach($arr['omim'] as $om){
					$disease_nodes[$id]['omims'][] = $om;
					foreach($proteinToOmim as $protein => $omims)
						foreach($omims as $omim)
							if($om === $omim)
								$disease_nodes[$id]['edges'][] = $protein;
				}
				foreach($arr['proteins'] as $se)
					if(isset($proteins[$se]))
						$disease_nodes[$id]['edges'][] = $se;
			}
		}
	}
	usort($disease_enrich_scores, 'sortByEnSc');

	write_enrichScores_extended($report,$disease_nodes,$disease_enrich_scores,'Disease');
	# verilen limitin ustu siliniyor
	$total_disease = count($disease_nodes);
	$tmp = array();
	for($i=0; $i<$total_disease and $i<$num_of_nodes; $i++){
		$key = $disease_enrich_scores[$i]['id'];
		$tmp[$key] = $disease_nodes[$key];
		# disease edgeleri protein array'ina ekleniyor
		/*
		foreach($disease_nodes[$key]['edges'] as $p){
			$proteins[$p]['diseases'][] = $key;
		}
		*/
	}
	unset($disease_enrich_scores);
	unset($disease_nodes);
	$disease_nodes = $tmp;
	unset($tmp);

	//write_enrichScores($report,$disease_nodes,'Disease');
	#var_dump($disease_nodes); die();

	# drug enrichment M values
	$drugs_M = $db->selectByColumn('drugbank_enrichment',array('identifier','M'),PDO::FETCH_KEY_PAIR);
	# 4769 => big N value for diseases
	# $n = count($proteins); => yukarda hpo icin bu degeri hesapladik zaten
	$drug_enrich_scores = array();
	foreach($drug_nodes as $key => $arr){
		$m = count($arr['edges']);
		if(isset($drugs_M[$key]) and $drugs_M[$key] != 0)
			$enrichScore = round( ( ($m*$m/$n) / ($drugs_M[$key] / 4769) ),3);
		else
			$enrichScore = 0;
		$drug_enrich_scores[] = array('id'=>$key,'enrichScore'=>$enrichScore);
		$drug_nodes[$key]['enrichScore'] = $enrichScore;
	}

	if(isset($starter_searchs['drugs'])){

		foreach($starter_searchs['drugs'] as $id => $arr){
			if(isset($drug_nodes[$id])){
				# arama yapilan drug network'e girmis.
				# enrichScore'unu maximize ederek elenmemesini garantiliyoruz
				foreach($drug_enrich_scores as $index => $drg)
					if($drg['id']==$id){
						$drug_nodes[$id]['enrichScore'] 		   = 9999999;
						$drug_enrich_scores[$index]['enrichScore'] = 9999999;
						break;
					}
			}else{
				# arama yapilan drug network'de yok. ekliyoruz.
				# bu kismi deneyemedim henuz.
				# aramada adi girilen fakat network'e hic girmeyen drug case'i bilmiyorum.
				# bu durumla hic karsilasilmayabilir...
				$drug_nodes[$id]['display_name'] = $arr['display_name'];
				$drug_nodes[$id]['chembl_id'] 	 = $arr['chembl_id'];
				$drug_nodes[$id]['enrichScore']  = 9999999;
				$drug_nodes[$id]['edges'] = array();
				$drug_enrich_scores[] = array('id'=>$id,'enrichScore'=>9999999);
				foreach($arr['edges'] as $ee)
					if(isset($proteins[$ee]))
						$drug_nodes[$id]['edges'][] = $ee;
				/*
				# if ChEMBL included and found main ChEMBL proteins...
				if(isset($arr['chembl_edges']))
					foreach($arr['chembl_edges'] as $ee)
						if(isset($proteins[$ee]))
							$drug_nodes[$id]['chembl_edges'][] = $ee;
					#$drug_nodes[$id]['chembl_edges'] = $arr['chembl_edges'];
				*/
			}
			# if ChEMBL included and found main ChEMBL proteins...
			if(isset($arr['chembl_edges']))
				foreach($arr['chembl_edges'] as $ee)
					if(isset($proteins[$ee]))
						$drug_nodes[$id]['chembl_edges'][] = $ee;
		}

	}	
	usort($drug_enrich_scores, 'sortByEnSc');
	unset($drugs_M);

	# kegg'den drug geldiyse, network'e eklenecek drug sayısı değişiyor.
	# $number_of_kegg_drugs
	$number_of_drugs_tobe_added = $num_of_nodes - $number_of_kegg_drugs;
	if( ($num_of_nodes/2) > $number_of_drugs_tobe_added )
		$number_of_drugs_tobe_added = (int)ceil($num_of_nodes/2);

	write_enrichScores_extended($report,$drug_nodes,$drug_enrich_scores,'Drug');
	# verilen limitin ustu siliniyor
	$total_drug = count($drug_nodes);
	$tmp = array();
	for($i=0; $i<$total_drug and $i<$number_of_drugs_tobe_added; $i++){
		$key = $drug_enrich_scores[$i]['id'];
		$tmp[$key] = $drug_nodes[$key];
	}
	unset($drug_enrich_scores);
	unset($drug_nodes);
	$drug_nodes = $tmp;
	unset($tmp);

	if(isset($kegg_drug_nodes))
		$drug_nodes = array_merge($drug_nodes,$kegg_drug_nodes);

	//write_enrichScores($report,$drug_nodes,'Drug');

	# adding hpo-disease nodes
	foreach($hpo_nodes as $hid => $hpo){
		$hpo_res = $db->tableWhere('hpo_term_names',array('hpo_id'=>$hid));
		if(count($hpo_res)){
			$omimsofhpo = explode('|',$hpo_res[0]['refs']);
			foreach($omimsofhpo as $omimofhpo){
				foreach($disease_nodes as $did => $dis){
					$orpha_relation = false;
					$omim_relation = false;
					if(substr($omimofhpo,0,5) == 'ORPHA'){
						$tmp_orpha = explode(':',$omimofhpo);
						if(isset($disease_nodes['Orphanet:'.$tmp_orpha[1]]))
							$orpha_relation = true;
					}else{
						$omim_relation = array_search($omimofhpo, $dis['omims']);
					}
					if($orpha_relation !== false or $omim_relation !== false ){
						$file['edges'][] = array('data'=>array('source'=>$hid,'target'=>$did,'Edge_Type'=>'hpodis','label'=>'associated w/'));
					}
				}
			}
		}
	}

?>