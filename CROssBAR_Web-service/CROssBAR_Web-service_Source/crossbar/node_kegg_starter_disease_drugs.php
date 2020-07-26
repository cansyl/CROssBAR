<?php
$kegg_drugs		 		 = array();
$kegg_accessions 		 = array();

foreach($kegg_starter_diseases as $id => $name){

	# KEGG diseaseid'ler ile druglar toplanıp edge yapılıyor.
	$res = $db->tableWhere('kegg_disease_drug',array('kegg_diseaseid'=>$id));
	foreach($res as $r){
		$kegg_drugs[$r['drugbankid']] = $r['drugname'];
		#$kegg_starter_diseases[$id]['drug_edges'][] = $r['drugbankid'];
	}

	# KEGG disease'lerden accessions (uniprotids) toplanıyor.
	$kegg_acc = $db->tableWhere('kegg_disease_protein',array('kegg_diseaseid'=>$id));
	foreach($kegg_acc as $acc){
		$kegg_accessions[] = $acc['uniprotid'];
		#$kegg_starter_diseases[$id]['acc_edges'][] = $acc['uniprotid'];
	}
}

# KEGG'den toplanan accession'ları aramayı hızlandırmak için temizliyoruz.
# bu kontrol ayrıca crossbar protein collection'dan çekilen veride de yapılıyor.
if($search_parameters['options']['reviewed_filter'] == 1){
	foreach($kegg_accessions as $i => $acc){
		if(! reviewed_check($acc,$revieweds,'main proteins list',$report))
			unset($kegg_accessions[$i]);
	}
}

if(count($kegg_accessions)){
	fwrite($report, "\nCollected accessions from KEGG disease search: \n".implode(',',$kegg_accessions)."\n");
	$kegg_accessions_str = implode(',',$kegg_accessions);
	#$prots = fetch_data('/proteins?limit=1000&accession='.$kegg_accessions_str);
	$prots = fetch_data('/proteins?limit=100&accession='.$kegg_accessions_str);
	$total_proteins_page = $prots->pageMeta->totalPages;
	$prots = (array)$prots;
	if(isset($prots['proteins'])){
		$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
		for($i=1; $i<$total_proteins_page; $i++){
			$prots = fetch_data('/proteins?limit=100&page='.$i.'&accession='.$kegg_accessions_str);
			$prots = (array)$prots;
			if(isset($prots['proteins']))
				$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
		}
	}else
		fwrite($report, "\nError occured while fetching proteins with KEGG accessions : $kegg_accessions_str\n".'/proteins?limit=1000&accession='.$kegg_accessions_str."\n\n");
}else
	fwrite($report, "\nCould not found any accession in KEGG database related to disease\n");

if(count($kegg_drugs)){
	$search_parameters['tonext']['kegg_drugs'] 	= $kegg_drugs;
	fwrite($report, "\nCollected drugs from KEGG disease search:\n".implode("\n",$kegg_drugs)."\n");
}else
	fwrite($report, "\nCould not found any drug in KEGG database related to disease\n");

?>