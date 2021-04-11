<?php

	# hpo big M values...
	$hpo_M = $db->table('hpo_enrichment',PDO::FETCH_KEY_PAIR);
	// m^2/n  /  M/N
	$n = count($proteins);
	# $bN = 4531; # big N value for HPO terms
	$hpo_enrich_scores = array();
	foreach($hpo_nodes as $key => $arr){
		if(isset($hpo_M[$key]) and $hpo_M[$key] != 0){
			$m = count($arr['edges']);
			$enrichScore = round((($m*$m/$n) / ($hpo_M[$key] / 4531)),3);
			$pval_tmp_str = 'data = rbind(c(' . (4531-$hpo_M[$key]) . ',' . $hpo_M[$key] . '),c('. ($n-$m) .','. $m . '));';
		}else{
			$enrichScore = -1;
			$pval_tmp_str ='data = rbind(c(0,0),c(0,0));';
		}
		$hpo_enrich_scores[] = array('id'=>$key,'enrichScore'=>$enrichScore, 'pval'=>$pval_tmp_str);
		$hpo_nodes[$key]['enrichScore'] = round($enrichScore,4);
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
				$hpo_enrich_scores[] = array('id'=>$id,'enrichScore'=>9999999, 'pval'=>'data = rbind(c(0,0),c(0,0));');
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

	#pval calculations
	# creating R Script
	$pval_file = 'pvals/'.$_POST['params'].'_hpos.R';
	$pval_f = fopen( $pval_file , "w" );
	$co = 0;
	foreach($hpo_enrich_scores as $p){
		if($co>99) break;
		fwrite($pval_f, $p['pval'] . 'test <- fisher.test(data);test$p.value;' );
		$co++;
	}
	fclose($pval_f);
	$pvals = pval_calculate($pval_file);
	
	# creating temporary HPO terms log file
	$report_hpos = fopen('datas/'.$_POST['params'].'_hpos', "w");
	fwrite($report_hpos, 'Total number of collected HPO terms: '.count($hpo_enrich_scores)."\n\n");
	fwrite($report_hpos, 'Phenotype_id.'."\t".'Phenotype_name'."\t".'Enrichment_score'."\t".'Significance_(p-value)'."\n");
	$co = 0;
	foreach($hpo_enrich_scores as $index => $score){
		if($co>99) break;
		if($co===$num_of_phenotypes_ver2)
			fwrite($report_hpos,'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\n");
		fwrite($report_hpos,$score['id']."\t".$hpo_nodes[$score['id']]['display_name']."\t".$hpo_nodes[$score['id']]['enrichScore']."\t".$pvals[$co]."\n");
		$co++;
	}
	fclose($report_hpos);

	# verilen limitin ustu siliniyor
	$total_hpo = count($hpo_nodes);
	$hpo_tmp = array();
	#for($i=0; $i<$total_hpo and $i<$num_of_nodes; $i++){
	for($i=0; $i<$total_hpo and $i<$num_of_phenotypes_ver2; $i++){
		$key = $hpo_enrich_scores[$i]['id'];
		$hpo_tmp[$key] = $hpo_nodes[$key];
	}
	unset($hpo_enrich_scores);
	unset($hpo_nodes);
	$hpo_nodes = $hpo_tmp;
	unset($hpo_tmp);

	# reactome enrichment M values
	$pathway_M = $db->selectByColumn('reactome_enrichment',array('id','M'),PDO::FETCH_KEY_PAIR);
	// m^2/n  /  M/N
	# $n = count($proteins); => yukarida hpo icin bu degeri hesapladik zaten
	# $bN = 26442; # big N value for pathways
	$pathway_enrich_scores = array();
	foreach($pathway_nodes as $key => $arr){
		$m = count($arr['edges']);
		if(isset($pathway_M[$key]) and $pathway_M[$key] != 0){
			$enrichScore = ($m*$m/$n) / ($pathway_M[$key] / 26442);
			$pval_tmp_str = 'data = rbind(c(' . (26442-$pathway_M[$key]) . ',' . $pathway_M[$key] . '),c('. ($n-$m) .','. $m . '));';
		}else{
			$enrichScore = -1;
			$pval_tmp_str ='data = rbind(c(0,0),c(0,0));';
		}
		
		$pathway_enrich_scores[] = array('id'=>$key,'enrichScore'=>$enrichScore, 'pval'=>$pval_tmp_str);
		$pathway_nodes[$key]['enrichScore'] = round($enrichScore,4);
	}

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

	#pval calculations
	# creating R Script
	$pval_file = 'pvals/'.$_POST['params'].'_pathways.R';
	$pval_f = fopen( $pval_file , "w" );
	$co = 0;
	foreach($pathway_enrich_scores as $p){
		if($co>99) break;
		fwrite($pval_f, $p['pval'] . 'test <- fisher.test(data);test$p.value;' );
		$co++;
	}
	fclose($pval_f);
	$pvals = pval_calculate($pval_file);

	# creating temporary pathways log file
	$report_pathways = fopen('datas/'.$_POST['params'].'_pathways', "w");
	fwrite($report_pathways, 'Total number of collected Reactome pathways: '.count($pathway_enrich_scores)."\n\n");
	fwrite($report_pathways, 'Pathway_id.'."\t".'Pathway_name'."\t".'Enrichment_score'."\t".'Significance_(p-value)'."\n");
	$co = 0;
	foreach($pathway_enrich_scores as $index => $score){
		if($co>99) break;
		if($co===$num_of_pathways_ver2)
			fwrite($report_pathways,'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\n");
			#fwrite($report_pathways,'-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-'."\n");
		fwrite($report_pathways,$score['id']."\t".$pathway_nodes[$score['id']]['display_name']."\t".$pathway_nodes[$score['id']]['enrichScore']."\t".$pvals[$co]."\n");
		$co++;
	}
	fclose($report_pathways);

	# verilen limitin ustu siliniyor
	$total_pathway = count($pathway_nodes);
	$pathway_tmp = array();
	#for($i=0; $i<$total_pathway and $i<$num_of_nodes; $i++){
	for($i=0; $i<$total_pathway and $i<$num_of_pathways_ver2; $i++){
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

	# disease enrichment M values
	$disease_M = $db->selectByColumn('diseaseterms_enrichment',array('obo_id','M'),PDO::FETCH_KEY_PAIR);
	# 3969 => big N value for diseases
	# $n = count($proteins); => yukarda hpo icin bu degeri hesapladik zaten
	$disease_enrich_scores = array();
	foreach($disease_nodes as $key => $arr){
		$m = count($arr['edges']);
		if(isset($disease_M[$key]) and $disease_M[$key] != 0){
			$enrichScore = ($m*$m/$n) / ($disease_M[$key] / 3969);
			$pval_tmp_str = 'data = rbind(c(' . (3969-$disease_M[$key]) . ',' . $disease_M[$key] . '),c('. ($n-$m) .','. $m . '));';
		}else{
			$enrichScore = -1;
			$pval_tmp_str ='data = rbind(c(0,0),c(0,0));';
		}
		$disease_enrich_scores[] 			= array('id'=>$key,'enrichScore'=>$enrichScore, 'pval'=>$pval_tmp_str);
		$disease_nodes[$key]['enrichScore'] = round($enrichScore,4);
	}
	unset($disease_M);

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
				$disease_enrich_scores[] = array('id'=>$id,'enrichScore'=>9999999, 'pval'=>'data = rbind(c(0,0),c(0,0));');
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

	#pval calculations
	# creating R Script
	$pval_file = 'pvals/'.$_POST['params'].'_diseases.R';
	$pval_f = fopen( $pval_file , "w" );
	$co = 0;
	foreach($disease_enrich_scores as $p){
		if($co>99) break;
		fwrite($pval_f, $p['pval'] . 'test <- fisher.test(data);test$p.value;' );
		$co++;
	}
	fclose($pval_f);
	$pvals = pval_calculate($pval_file);

	# creating temporary diseases log file
	$report_diseases = fopen('datas/'.$_POST['params'].'_diseases', "w");
	fwrite($report_diseases, 'Total number of collected EFO diseases: '.count($disease_enrich_scores)."\n\n");
	fwrite($report_diseases, 'Disease_id.'."\t".'Disease_name'."\t".'Enrichment_score'."\t".'Significance_(p-value)'."\n");
	$co = 0;
	foreach($disease_enrich_scores as $index => $score){
		if($co>99) break;
		if($co===$num_of_diseases_ver2)
			fwrite($report_diseases,'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\n");
			#fwrite($report_diseases,'-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-'."\n");
		fwrite($report_diseases,$score['id']."\t".$disease_nodes[$score['id']]['display_name']."\t".$disease_nodes[$score['id']]['enrichScore']."\t".$pvals[$co]."\n");
		$co++;
	}
	fclose($report_diseases);

	# verilen limitin ustu siliniyor
	$total_disease = count($disease_nodes);
	$tmp = array();
	for($i=0; $i<$total_disease and $i<$num_of_diseases_ver2; $i++){
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

	# drug enrichment M values
	$drugs_M = $db->selectByColumn('drugbank_enrichment',array('identifier','M'),PDO::FETCH_KEY_PAIR);
	# 4405 => big N value for diseases
	# $n = count($proteins); => yukarda hpo icin bu degeri hesapladik zaten
	$drug_enrich_scores = array();
	foreach($drug_nodes as $key => $arr){
		$m = count($arr['edges']);
		if(isset($drugs_M[$key]) and $drugs_M[$key] != 0){
			$enrichScore = ($m*$m/$n) / ($drugs_M[$key] / 4405);
			$pval_tmp_str = 'data = rbind(c(' . (4405-$drugs_M[$key]) . ',' . $drugs_M[$key] . '),c('. ($n-$m) .','. $m . '));';
		}else{
			$enrichScore = -1;
			$pval_tmp_str ='data = rbind(c(0,0),c(0,0));';
		}
		$drug_enrich_scores[] = array('id'=>$key,'enrichScore'=>$enrichScore, 'pval'=>$pval_tmp_str);
		$drug_nodes[$key]['enrichScore'] = round($enrichScore,4);
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
				$drug_enrich_scores[] = array('id'=>$id,'enrichScore'=>9999999, 'pval'=>'data = rbind(c(0,0),c(0,0));');
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
			if(isset($arr['chembl_edges'])){
				foreach($arr['chembl_edges'] as $ee => $pchmbl){
					if(isset($proteins[$ee])){
						$drug_nodes[$id]['chembl_edges'][$ee] = $pchmbl;
						$drug_nodes[$id]['chembl_assays'][$ee] = $arr['chembl_assays'][$ee];
					}
				}
			}
		}
	}
	usort($drug_enrich_scores, 'sortByEnSc');
	unset($drugs_M);

	#pval calculations
	# creating R Script
	$pval_file = 'pvals/'.$_POST['params'].'_drugs.R';
	$pval_f = fopen( $pval_file , "w" );
	$co = 0;
	foreach($drug_enrich_scores as $p){
		if($co>99) break;
		fwrite($pval_f, $p['pval'] . 'test <- fisher.test(data);test$p.value;' );
		$co++;
	}
	fclose($pval_f);
	$pvals = pval_calculate($pval_file);

	# kegg'den drug geldiyse, network'e eklenecek drug sayısı değişiyor.
	# $number_of_kegg_drugs
	$number_of_drugs_tobe_added = $num_of_drugs_ver2 - $number_of_kegg_drugs;
	if( ($num_of_drugs_ver2/2) > $number_of_drugs_tobe_added )
		$number_of_drugs_tobe_added = (int)ceil($num_of_drugs_ver2/2);

	# creating temporary drugs log file
	$report_drugs = fopen('datas/'.$_POST['params'].'_drugs', "w");
	fwrite($report_drugs, 'Total number of collected drugs: '.count($drug_enrich_scores)."\n\n");
	fwrite($report_drugs, 'Drug_id.'."\t".'Drug_name'."\t".'Enrichment_score'."\t".'Significance_(p-value)'."\n");
	$co = 0;
	foreach($drug_enrich_scores as $index => $score){
		if($co>99) break;
		if($co===$number_of_drugs_tobe_added)
			fwrite($report_drugs,'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\t".'*-*-*'."\n");
			#fwrite($report_drugs,'-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-'."\n");
		fwrite($report_drugs,$score['id']."\t".$drug_nodes[$score['id']]['display_name']."\t".$drug_nodes[$score['id']]['enrichScore']."\t".$pvals[$co]."\n");
		$co++;
	}
	fclose($report_drugs);

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

	# adding hpo-disease edges
	foreach($hpo_nodes as $hid => $hpo){
		$hpo_res = $db->tableWhere('hpo_term_names',array('hpo_id'=>$hid));
		if(count($hpo_res)){
			$omimsofhpo = explode('|',$hpo_res[0]['refs']);
			foreach($omimsofhpo as $omimofhpo){
				$orpha_relation = false;
				$omim_relation = false;
				$related_disease = '';
				if(substr($omimofhpo,0,5) == 'ORPHA'){
					$tmp_orpha = explode(':',$omimofhpo);
					if(isset($disease_nodes['Orphanet:'.$tmp_orpha[1]])){
						$orpha_relation = true;
						$related_disease = 'Orphanet:'.$tmp_orpha[1];
					}
				}else{
					foreach($disease_nodes as $did => $dis){
						$omim_relation = array_search($omimofhpo, $dis['omims']);
						if($omim_relation !== false){
							$related_disease = $did;
							break;
						}
					}
				}
				if($orpha_relation !== false or $omim_relation !== false ){
					$file['edges'][] = array('data'=>array('source'=>$hid,'target'=>$related_disease,'Edge_Type'=>'hpodis','label'=>'is associated w/'));
				}

			}
		}
	}

?>
