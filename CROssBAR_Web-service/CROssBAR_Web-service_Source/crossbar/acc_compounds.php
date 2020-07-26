<?php
$compounds_str = implode(',',$compounds);
fwrite($report, "\nChEMBL Compounds to be searched: $compounds_str\n");
$acc_of_chembl = chembl_id_to_acc($compounds_str,$num_of_nodes);
fwrite($report,'Accessions collected from ChEMBL Compound Search: '.implode(',',$acc_of_chembl)."\n");

//$accessions = array_merge($accessions,$acc_of_chembl);
//$accessions = tax_ids_filter($url, $accessions, $tax_ids);
//fwrite($report,'Accessions after tax_ids filtering: '.implode(',',$accessions)."\n");

$acc_of_chembl_str = implode(',',$acc_of_chembl);
# CROssBAR protein collection to be processed.
if( ($prots = fetch_data('/proteins?limit='.count($acc_of_chembl).'&accession='.$acc_of_chembl_str)) !== false){
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

?>