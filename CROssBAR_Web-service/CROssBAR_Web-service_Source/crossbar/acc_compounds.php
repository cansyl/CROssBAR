<?php

$acc_of_chembl = array();
foreach($compounds as $com){

	for($pv=6; $pv>3; $pv--){
		$chembl_id_to_acc = chembl_id_to_acc($com,$num_of_compounds_ver2,$pv);
		if($chembl_id_to_acc !== false and count($chembl_id_to_acc['proteins']) > 9)
			break;
	}

	if($chembl_id_to_acc !== false){
		$acc_of_chembl = array_merge($acc_of_chembl,$chembl_id_to_acc['proteins']);
		$starter_searchs['compounds'][$com] = array('assay_chembl_ids'=>array_filter($chembl_id_to_acc['assayIds_of_relations']),'pchembl_values'=>array_filter($chembl_id_to_acc['pchemblValues_of_relations']),'edges'=>$chembl_id_to_acc['proteins']);
	}else{
		$check_if_compound_exist = fetch_data('/molecules?moleculeChemblId='.$com);
		if($check_if_compound_exist !== false){
			$starter_searchs['compounds'][$com] = array('edges'=>array());
		}
	}
}

$acc_of_chembl = array_unique($acc_of_chembl);
$acc_of_chembl_str = implode('|',$acc_of_chembl);

if(count($acc_of_chembl)){
	if( ($prots = fetch_data('/proteins?limit=100&accession='.$acc_of_chembl_str)) !== false){
		$prots = (array)$prots;
		if(isset($prots['proteins'])){
			$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
		}else{
			die('Protein Fetch Error');
		}
	}else{
		die('Protein Fetch Error');
	}
}
?>