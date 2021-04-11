<?php
include('database.php');
include('functions.php');
$db = new database();

# pre-defined variables.
$url = 'https://www.ebi.ac.uk/tools/crossbar';

# parsing form elements.
$diseases		= form_input($_POST['diseases']);
$drugs			= form_input($_POST['drugs']);
//$compounds 	= form_input($_POST['compounds']);
$compounds		= array();
$pathways 		= form_input($_POST['pathways']);
$proteins 		= form_input($_POST['proteins']);
$hpos 			= form_input($_POST['hpos']);
//$tax_ids 	  	= form_input($_POST['tax_ids']);

$tax_ids = array();
if(!isset($_POST['tax_ids_multi']))
	$tax_ids[] = 9606; # default human choosen.
else
	foreach($_POST['tax_ids_multi'] as $txid)
		$tax_ids = array_merge($tax_ids,form_input($txid));

//if(!count($tax_ids)) $tax_ids[] = 9606; # default human choosen.
$tax_ids_str	= implode('|',$tax_ids );

/*
$num_of_nodes 	= (int)$_POST['num_of_nodes'];
if($num_of_nodes < 1) $num_of_nodes = 10; # if empty, it comes as 0 (zero)
if($num_of_nodes > 50) $num_of_nodes = 50;
*/

$num_of_fn_nodes_ver2 = (int)$_POST['num_of_fn_nodes'];
if($num_of_fn_nodes_ver2 < 1) $num_of_fn_nodes_ver2 = 10; # if empty, it comes as 0 (zero)
if($num_of_fn_nodes_ver2 > 100) $num_of_fn_nodes_ver2 = 100;

$num_of_pathways_ver2 = (int)$_POST['num_of_pathways'];
if($num_of_pathways_ver2 < 1) $num_of_pathways_ver2 = 10; # if empty, it comes as 0 (zero)
if($num_of_pathways_ver2 > 100) $num_of_pathways_ver2 = 100;

$num_of_phenotypes_ver2 = (int)$_POST['num_of_phenotypes'];
if($num_of_phenotypes_ver2 < 1) $num_of_phenotypes_ver2 = 10; # if empty, it comes as 0 (zero)
if($num_of_phenotypes_ver2 > 100) $num_of_phenotypes_ver2 = 100;

$num_of_drugs_ver2 = (int)$_POST['num_of_drugs'];
if($num_of_drugs_ver2 < 1) $num_of_drugs_ver2 = 10; # if empty, it comes as 0 (zero)
if($num_of_drugs_ver2 > 100) $num_of_drugs_ver2 = 100;

$num_of_diseases_ver2 = (int)$_POST['num_of_diseases'];
if($num_of_diseases_ver2 < 1) $num_of_diseases_ver2 = 10; # if empty, it comes as 0 (zero)
if($num_of_diseases_ver2 > 100) $num_of_diseases_ver2 = 100;

$num_of_compounds_ver2 = (int)$_POST['num_of_compounds'];
if($num_of_compounds_ver2 < 1) $num_of_compounds_ver2 = 10; # if empty, it comes as 0 (zero)
if($num_of_compounds_ver2 > 100) $num_of_compounds_ver2 = 100;

$search_parameters = array(); # arama bilgilerinin tutulacağı array
							  # aramanın job id'si de bu bilgilerle oluşturulacak
							  # bu job id ile tum dosyalar yazılacak
$starter_searchs   = array(); # just pathways needed to be define here as starter for their unique structure

$to_json['options'] = array(); # arama opsiyonlarinin kaydedilecegi array
$to_json['options']['search_start'] = time(); #for calculating runtime of the search.
$to_json['options']['tax_ids'] = $tax_ids;
#$to_json['options']['num_of_nodes'] = $num_of_nodes;
$to_json['options']['num_of_fn_nodes'] = $num_of_fn_nodes_ver2;
$to_json['options']['num_of_pathways'] = $num_of_pathways_ver2;
$to_json['options']['num_of_phenotypes'] = $num_of_phenotypes_ver2;
$to_json['options']['num_of_drugs'] = $num_of_drugs_ver2;
$to_json['options']['num_of_diseases'] = $num_of_diseases_ver2;
$to_json['options']['num_of_compounds'] = $num_of_compounds_ver2;

if(isset($_POST['chembl_compounds']))
	$to_json['options']['chembl_compounds'] = 1;
else
	$to_json['options']['chembl_compounds'] = 0;

if(isset($_POST['predictions']))
	$to_json['options']['predictions'] = 1;
else
	$to_json['options']['predictions'] = 0;

if(isset($_POST['only_reviewed']))
	$to_json['options']['reviewed_filter'] = 1;
else
	$to_json['options']['reviewed_filter'] = 0;

if(isset($_POST['first_neighbours']))
	$to_json['options']['fn'] = 1;
else
	$to_json['options']['fn'] = 0;

$to_json['options']['chembl'] = 1;


if(count($proteins)){
	$search_parameters[] = array('Protein' => implode('|',$proteins));
}

if(count($pathways)){
	$kegg_starter_pathways 	 = array();
	$local_check_of_pathways = array();
	foreach($pathways as $p){
		# check if there is a pathway with given name in CROssBAR
		# if exist, force it to be in network...
		$res = $db->tableWhere('pathways',array('pathwayName'=>$p));
		
		#var_dump($res); die();
		$total_pthwy = count($res);
		if($total_pthwy === 1){
			$local_check_of_pathways[$res[0]['react_id']] = $p;
			$starter_searchs['pathways'][$res[0]['react_id']]['display_name'] = $p;
		}else if($total_pthwy > 1){
			foreach($res as $r){
				#var_dump($r['react_id']);
				if(substr($r['react_id'], 0, 5) === 'R-HSA'){
					$local_check_of_pathways[$r['react_id']] = $p;
					$starter_searchs['pathways'][$r['react_id']]['display_name'] = $p;
					break;
				}
			}
			if(!count($local_check_of_pathways)){
				# if not found any human pathway, take first
				$local_check_of_pathways[$res[0]['react_id']] = $p;
				$starter_searchs['pathways'][$res[0]['react_id']]['display_name'] = $p;
			}
		}
		# KEGG pathways fetching...
		$res = $db->tableWhere('kegg_pathway_protein',array('kegg_pathwayname'=>$p));
		if(count($res))
			$kegg_starter_pathways[$res[0]['kegg_pathwayid']] = $p;
	}
	$numoflocalpath = count($local_check_of_pathways);
	$numofkeggpath = count($kegg_starter_pathways);
	# eğer hem KEGG'den hem de EFO'dan pathway bulunamadıysa arama iptal edilsin.
	if( !$numoflocalpath and !$numofkeggpath){
		die('Pathway Error');
		#die('<div class="mt-3 alert alert-danger" role="alert">Operation terminated! No disease found with name <b>'.$diseases_str.'</b></div>');
	}

	if($numoflocalpath)
		$search_parameters[] = array('Pathway' => $local_check_of_pathways);
	if($numofkeggpath)
		$search_parameters[] = array('KEGG Pathway' => $kegg_starter_pathways);
}

if(count($diseases)){

	$kegg_starter_diseases 	 = array();
	$local_check_of_diseases = array();

	foreach($diseases as $d){
		# KEGG diseases fetching...
		$res = $db->tableWhere('kegg_diseases_alternatives',array('kegg_diseasename'=>$d));
		if(count($res))
			$kegg_starter_diseases[$res[0]['kegg_diseaseid']] = $res[0]['kegg_diseasename'];

		# check if there is a disease with given name in CROssBAR
		# if exist, force it to be in network...
		$res = $db->tableWhere('diseases',array('disease'=>$d));
		if(count($res))
			$local_check_of_diseases[$res[0]['obo_id']] = $res[0]['disease'];
	}

	$numoflocaldis = count($local_check_of_diseases);
	$numofkeggdis = count($kegg_starter_diseases);
	# eğer hem KEGG'den hem de EFO'dan disease bulunamadıysa arama iptal edilsin.
	if( !$numoflocaldis and !$numofkeggdis){
		die('Disease Error');
		#die('<div class="mt-3 alert alert-danger" role="alert">Operation terminated! No disease found with name <b>'.$diseases_str.'</b></div>');
	}

	if($numoflocaldis)
		$search_parameters[] = array('Disease' => $local_check_of_diseases);
	if($numofkeggdis)
		$search_parameters[] = array('KEGG Disease' => $kegg_starter_diseases);

	# $starter_searchs array'inde her bir node tipinin başlangıç araması kaydediliyor.
	# en son bu aramalardan elde edilen node'lar network'de yok ise force edilerek eklenecek.
	# yada node oluşturulurken her seferinde... sanki bu daha mantıklı...
	#$starter_searchs['diseases'] = $local_check_of_diseases;
}

if(count($hpos)){
	$hpos_to_be_searched = array();
	foreach($hpos as $hpo){
		$res = $db->tableWhere('hpo_term_names',array('term_name'=>$hpo));
		#print_r($res);
		foreach($res as $r)
			$hpos_to_be_searched[] = $r['term_name'];
	}
	if( !count($hpos_to_be_searched) ){
		die('HPO Error');
	}	
	$search_parameters[] = array('HPO' => $hpos_to_be_searched);
}


if(count($drugs)){
	# print_r($drugs);
	$drugs_to_be_searched = array();

	foreach($drugs as $drug){
		if(substr($drug,0,6) == 'CHEMBL'){
			$compounds[] = $drug;
			//echo $drug;
			continue;
		}
		if(substr($drug,0,2) == 'DB'){
			$res = $db->tableWhere('drugs',array('drug_id'=>$drug));
		}else
			$res = $db->tableWhere('drugs',array('drug'=>$drug));
		#print_r($res); die();
		foreach($res as $r)
			$drugs_to_be_searched[] = array($r['drug_id'] => $r['drug']);
	}

	if( !count($drugs_to_be_searched) and !count($compounds) ){
		die('Drug Error');
	}
	if( count($drugs_to_be_searched) )
		$search_parameters[] = array('Drug' => $drugs_to_be_searched);
}

if(count($compounds)){
	$compounds_to_be_searched = array();
	foreach($compounds as $compound){
		$compounds_to_be_searched[] = $compound;
	}
	$search_parameters[] = array('Compound' => $compounds_to_be_searched);
}

$to_json['starter_searchs'] = $starter_searchs;
$to_json['search'] = $search_parameters;

//print_r($to_json['options']);

$file_name = time();
$data = fopen("data/".$file_name.'.json', "w");
fwrite($data, json_encode($to_json));
fclose($data);
echo $file_name;
?>
