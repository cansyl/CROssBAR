<?php
$compounds_str = implode(',',$compounds);
#fwrite($report, "\nChEMBL Compounds to be searched: $compounds_str\n");
fwrite($report, "\nQuery terms: $compounds_str (compound)\n");
#$acc_of_chembl = chembl_id_to_acc($compounds_str,$num_of_nodes);

$acc_of_chembl = array();
foreach($compounds as $com){
	$chembl_id_to_acc = chembl_id_to_acc($com,$num_of_nodes);

	if($chembl_id_to_acc === false)
		$chembl_id_to_acc = chembl_id_to_acc($com,$num_of_nodes,5);
	if($chembl_id_to_acc === false)
		$chembl_id_to_acc = chembl_id_to_acc($com,$num_of_nodes,4);

	if($chembl_id_to_acc !== false){
		$acc_of_chembl = array_merge($acc_of_chembl,$chembl_id_to_acc['proteins']);
		$starter_searchs['compounds'][$com] = array('pchembl_value'=>$chembl_id_to_acc['pchembl_value'],'edges'=>$chembl_id_to_acc['proteins']);
	}else{
		$check_if_compound_exist = fetch_data('/molecules?moleculeChemblId='.$com);
		if($check_if_compound_exist !== false)
			$starter_searchs['compounds'][$com] = array('pchembl_value'=>0,'edges'=>array());
	}
	#print_r($chembl_id_to_acc);
}

#print_r($starter_searchs['compounds']);

$acc_of_chembl = array_unique($acc_of_chembl);
$acc_of_chembl_str = implode(',',$acc_of_chembl);
# CROssBAR protein collection to be processed.
#if( ($prots = fetch_data('/proteins?limit='.count($acc_of_chembl).'&accession='.$acc_of_chembl_str)) !== false){

if(count($acc_of_chembl)){
	fwrite($report,'Accessions collected from ChEMBL Compound Search: '."$acc_of_chembl_str\n");
	if( ($prots = fetch_data('/proteins?limit=100&accession='.$acc_of_chembl_str)) !== false){
		$prots = (array)$prots;
		if(isset($prots['proteins'])){
			$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
		}else{
			fwrite($report, "\n".'Error occured while fetching proteins with accessions: '.$acc_of_chembl_str."\n".'/proteins?limit='.count($acc_of_chembl).'&accession='.$acc_of_chembl_str."\n\n");
			die('Protein Fetch Error');
		}
	}else{
		fwrite($report, "\n".'Error occured while fetching proteins with accessions: '.$acc_of_chembl_str."\n".'/proteins?limit='.count($acc_of_chembl).'&accession='.$acc_of_chembl_str."\n\n");
		die('Protein Fetch Error');
	}
}
?>