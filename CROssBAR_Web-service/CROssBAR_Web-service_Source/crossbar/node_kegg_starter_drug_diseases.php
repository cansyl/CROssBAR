<?php
$kegg_diseases = array();
foreach($drugs as $drug){
	# KEGG drug name'ler ile diseases toplanıp edge yapılıyor.
	$res = $db->tableWhere('kegg_disease_drug',array('drugname'=>$drug));
	foreach($res as $r){
		$kegg_diseases[$r['kegg_diseaseid']] = $r['kegg_diseasename'];
	}
}

if(count($kegg_diseases)){
	$search_parameters['tonext']['kegg_diseases'] 	= $kegg_diseases;
}

?>