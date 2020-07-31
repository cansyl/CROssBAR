<?php
$acc_of_drugs = array();

#fwrite($report, "\nDRUGs to be searched: $drugs_str\n");
#fwrite($report, "\nQuery terms: $drugs_str (drug)\n");

$acc_of_drugs	 		= array(); # drug aramasindan gelen tum proteinlerin toplandigi array
$acc_of_drugbank		= array(); # drugbank'ten gelen proteinlerin toplandigi array
$acc_of_chembl			= array(); # ChEMBL'dan gelen proteinlerin toplandigi array
$chemblIDs 		 		= array(); # ChEMBL'da arama yapmak icin chembl_id'lerin toplandigi array

if( ($drug_entities = (fetch_data('/drugs?limit=10&name='.urlencode($drugs_str)))) !== false){

	foreach($drug_entities->drugs as $drug){
		$accs_of_this_drug = array();
		if(array_search($drug->name,$drugs) !== false){
			# arama adiyla bulunan drug starter olarak kaydediliyor.
			# bu node drug eklenirken kontrol edilecek, network'e girmediyse force edilip eklenecek.
			# enrichment score'u maksimize edilebilir network'de bir yerlerde mevcutsa
			$starter_searchs['drugs'][$drug->identifier]['display_name'] = $drug->name;
			$starter_searchs['drugs'][$drug->identifier]['chembl_id'] = $drug->chembl_id;
			$starter_searchs['drugs'][$drug->identifier]['edges'] = array();
		}

		foreach($drug->targets as $target)
			foreach($target->accessions as $accession){
				#$acc_of_drugbank[] = $accession;
				$accs_of_this_drug[] = $accession;
				$starter_searchs['drugs'][$drug->identifier]['edges'][] = $accession;
			}
		
		# ChEMBL'DAN PROTEIN (ACCESSION) TOPLAMA ISLEMI...
		if($chembl){
			#if(count($chemblIDs)){
			if($drug->chembl_id !== null){
				#$moleculeChemblIds 	= implode(',',$chemblIDs);
				#$acc_of_chembl 		= chembl_id_to_acc($moleculeChemblIds,$num_of_nodes);
				$tmp_acc = chembl_id_to_acc($drug->chembl_id,$num_of_nodes);
				if($tmp_acc !== false){
					$chembl_accs_of_drug =  array_diff( $tmp_acc['proteins'] , $accs_of_this_drug);
					$acc_of_chembl = array_merge($acc_of_chembl,$chembl_accs_of_drug);
					$starter_searchs['drugs'][$drug->identifier]['chembl_edges'] = $chembl_accs_of_drug;
					fwrite($report,'Accessions collected from ChEMBL via drug ['.$drug->name .']   '.implode(',',$chembl_accs_of_drug)."\n");
				}else
					fwrite($report,'Could not found any accession with chembl: '. $drug->chembl_id ."\n");
			}else
				fwrite($report,'There is no chembl_ids related to '.$drug->name ."\n");
		}
		# ChEMBL'DAN PROTEIN (ACCESSION) TOPLAMA ISLEMI BITTI.
		$acc_of_drugbank = array_merge($acc_of_drugbank,$accs_of_this_drug);
	}
}
unset($drug_entities);

fwrite($report,'Accessions collected from Drugbank: '.implode(',',$acc_of_drugbank)."\n");

$acc_of_drugs = array_unique(array_merge($acc_of_drugbank,$acc_of_chembl));
# Drugbank'ten toplanan accession'ları aramayı hızlandırmak için temizliyoruz.
# bu kontrol ayrıca crossbar protein collection'dan çekilen veride de yapılıyor.
if($search_parameters['options']['reviewed_filter'] == 1){
	foreach($acc_of_drugs as $i => $acc){
		#var_dump(reviewed_check($acc,$revieweds,'drug accessions',$report));
		if(! reviewed_check($acc,$revieweds,'drug accessions',$report)){
			unset($acc_of_drugs[$i]);
		}
	}
}
#$acc_of_drugs_str = implode(',',$acc_of_drugs);
# CROssBAR protein collection to be processed.
#$total_acc_of_drugs = count($acc_of_drugs);
$acc_of_drugs = array_unique($acc_of_drugs);
$divided_accs_list = array_chunk($acc_of_drugs,100);
foreach($divided_accs_list as $acc_of_drugs_divided){
	$acc_of_drugs_str = implode(',', $acc_of_drugs_divided);
	if( ($prots = fetch_data('/proteins?limit=100&accession='.$acc_of_drugs_str)) !== false){
		$prots = (array)$prots;
		if(isset($prots['proteins'])){
			$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
		}else{
			fwrite($report, "\n".'Error occured while fetching proteins with accessions: '.$acc_of_drugs_str."\n".'/proteins?limit=100&accession='.$acc_of_drugs_str."\n\n");
			#die('Protein Fetch Error');
		}
	}else{
		fwrite($report, "\n".'Error occured while fetching proteins with accessions: '.$acc_of_drugs_str."\n".'/proteins?limit=100&accession='.$acc_of_drugs_str."\n\n");
		#die('Protein Fetch Error');
	}
}
#var_dump($crossbar_proteins);

#$accessions = tax_ids_filter($url, $accessions, $tax_ids);
#fwrite($report,'Accessions after tax_ids filtering: '.implode(',',$accessions)."\n");
?>