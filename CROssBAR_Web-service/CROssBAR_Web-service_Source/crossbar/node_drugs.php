<?php

# Collecting drugs related to generated protein set accesions as target
$drug_nodes = array();

if( ($drugEntities = fetch_data('/drugs?limit=1000&accession='.$accessions_str)) !== false)
	foreach($drugEntities->drugs as $drug){

		# creating node with drug name
		if (!isset($drug_nodes[$drug->identifier])){
			$drug_nodes[$drug->identifier]['display_name'] = $drug->name;
			# we are saving chembl_id(s) of drugs
			# since we will check the drug versions of the compounds which will be added next steps
			$drug_nodes[$drug->identifier]['chembl_id'] = $drug->chembl_id;
			$drug_nodes[$drug->identifier]['edges'] = array();
		}

		# creating edge between drug-protein
		foreach($drug->targets as $target)
			foreach($target->accessions as $acc){
				//$key = array_search($acc,$accessions);
				//if( $key !== false){
				if( isset($proteins[$acc]) ){
					# at this point we found accession
					# olusturdugumuz protein setini drugs->targets->accesions yolunda arıyoruz. 
					# buldugumuz eslesme EDGE oluyor.
					$drug_nodes[$drug->identifier]['edges'][] = $acc;
					#echo $acc . ' to ' . $drug->identifier . '<br>';
				}
			}
	}

# kegg'den gelen drug var ise onlari da ekleyelim...
if(isset($file['tonext']['kegg_drugs'])){
	# burada kegg druglar ayri bir array'e alinip direkt network'e ekleniyor
	$kegg_drug_nodes = array();
	$kegg_drugs = $file['tonext']['kegg_drugs'];
	$drugbankids = array();
	foreach($kegg_drugs as $id => $name){

		if(isset($starter_searchs['drugs']))
			if(isset($starter_searchs['drugs'][$id]))
				continue;

		if(isset($drug_nodes[$id])){
			# eger kegg'den cektimiz drug network'de bulunduysa kegg_drugs'a aktarip silelim,
			# kegg'den gelen druglar her turlu network'e eklenecek
			# drug hakkindaki gerekli tum bilgiler kegg'de olmadigi icin drugbank'tan cekilmeli
			# boylece ayni drug verisi alindiysa tekrar sorgu yapilmasi engelleniyor
			$kegg_drug_nodes[$id] = $drug_nodes[$id];
			$kegg_drug_nodes[$id]['enrichScore'] = 9999999;
			unset($drug_nodes[$id]);
			continue;
		}else
			$drugbankids[] = $id;
	}

	if(count($drugbankids)){
		# eger drugbank'ten cekilmemis drug var ise...
		$identifiers = implode('|',$drugbankids);
		if( ($drugEntities = fetch_data('/drugs?limit=1000&identifier='.$identifiers)) !== false ){
			foreach($drugEntities->drugs as $drug){
				$kegg_drug_nodes[$drug->identifier]['display_name'] = $drug->name;
				# daha sonra bulunan compoundlarin drug hali var mi diye kontrol edebilmek icin
				# chembl_id bilgisini sakliyoruz...
				$kegg_drug_nodes[$drug->identifier]['chembl_id']	= $drug->chembl_id;
				$kegg_drug_nodes[$drug->identifier]['edges'] 		= array();
				$kegg_drug_nodes[$drug->identifier]['enrichScore']  = 9999999;

				# DRUG ILE PROTEIN ARASINDA EDGE OLUSTURUYORUZ...
				foreach($drug->targets as $target)
					foreach($target->accessions as $acc){
						if( isset($proteins[$acc]) ){
							# bu nokta accession buldugumuz nokta. 
							# olusturdugumuz protein setinde
							# drugs->targets->accesions protein'ini arıyoruz. 
							# buldugumuz eslesme EDGE oluyor.
							$kegg_drug_nodes[$drug->identifier]['edges'][] = $acc;
						}
					}
			}
		}
	}
	$number_of_kegg_drugs = count($kegg_drug_nodes);
}else
	$number_of_kegg_drugs = 0;
?>