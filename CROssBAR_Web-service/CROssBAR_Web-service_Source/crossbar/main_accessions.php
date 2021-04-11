<?php
include('database.php');
include('functions.php');
$db = new database();

# fetching validated search parameters
$params = file_get_contents('data/'.$_POST['params'].'.json');
$report_file = $_POST['params'].'.txt';

$crossbar_proteins = array(); // crossbardan çekilen protein collection'ların tutulacağı array
$proteins_main	   = array(); // node olacak main proteinlerin tutulacağı array
$proteins_fn	   = array(); // first neighbour proteinlerin tutulacağı array

$omims_of_mains	   = array(); // main proteinler ile toplanacak omim idlerin tutulacağı array
$main_to_omim      = array(); // bu array ile (protein -> OMIM -> disease) relation'ı kurulacak.
$hpo_nodes 		   = array(); // tum hpo'larin tutuldugu array
$pathway_nodes 	   = array(); // tum pathway'lerin tutuldugu array

$search_parameters = json_decode($params,true);

# user'ın arama için girdiği değerleri tutan array => $starter_searchs
# $starter_searchs[Node_Type] => array(id=>display_name) şeklinde tutulacak veri.
# burda toplanan değerler en son networke eklenecek.
# eğer networkde yok ise eklenecek ve 
# networkdeki node'larla bağlantısı kurulacak.
$starter_searchs= $search_parameters['starter_searchs'];

$tax_ids 		= $search_parameters['options']['tax_ids'];
$tax_ids_str	= implode('|',$tax_ids );
$fn_filter  	= (int)$search_parameters['options']['fn'];
//$num_of_nodes   = (int)$search_parameters['options']['num_of_nodes'];
include('num_of_nodes.php'); # all node types' number of nodes variables come from this file

$chembl		    = (int)$search_parameters['options']['chembl'];

$report = fopen('data/'.$report_file, "w");

# reviewed_filter aktif ise local veritabanindan reviewed proteinler cekiliyor
if($search_parameters['options']['reviewed_filter'] == 1){
	$revieweds = $db->selectByColumn('proteins_reviewed',array('accession'),PDO::FETCH_COLUMN);
}

$diseases = array();
$kegg_starter_diseases = array();
$pathways = array();
$kegg_starter_pathways = array();
$drugs = array();
$hpos = array();
$compounds = array();
$prts = array();

# validated starter search nodes fetching
foreach($search_parameters['search'] as $s){
	foreach($s as $type => $data){
		switch($type){
			case 'Disease':
				foreach($data as $id => $name)
					$diseases[] = $name;
			break;
			case 'KEGG Disease':
				$kegg_starter_diseases = array_merge($kegg_starter_diseases,$data);
			break;
			case 'KEGG Pathway':
				$kegg_starter_pathways = array_merge($kegg_starter_pathways,$data);
			break;
			case 'Pathway':
				foreach($data as $id => $name)
					$pathways[] = $name;
			break;
			case 'Drug':

				$drugs = array();
				foreach($data as $d)
					$drugs = array_merge($drugs,$d);

				$names_of_drugs = array_values($drugs);
				$ids_of_drugs = array_keys($drugs);
			break;
			
			case 'Compound':
				$compounds[] = $data[0];
			break;

			case 'HPO':
				foreach($data as $d)
					$hpos[] = $d;
				#$hpos[] = $data[0];
			break;
			
			case 'Protein':
				$prts = explode('|',$data);
			break;
		}
	}
}

if(count($diseases) or count($kegg_starter_diseases)){
	include('acc_diseases.php');
}

if(count($pathways) or count($kegg_starter_pathways)){
	include('acc_pathways.php');
}

if(count($drugs)){
	$drugs_str = implode('|',$names_of_drugs);
	$ids_of_drugs_str = implode('|',$ids_of_drugs);
	include('node_kegg_starter_drug_diseases.php');
	include('acc_drugs.php');
}

if(count($hpos)){
	include('acc_hpos.php');
}

if(count($compounds)){
	include('acc_compounds.php');
}

if(count($prts)){
	include('acc_proteins.php');
}

# main proteinlerin islendigi dosya
include('process_mains.php');
$omims_of_mains = array_unique($omims_of_mains);

# creating temporary core proteins log file
$report_cores = fopen('data/'.$_POST['params'].'_cores', "w");
fwrite($report_cores, 'Total number of collected core proteins: '.count($proteins_main)."\n\n");
fwrite($report_cores, 'UniProt_protein_acc.'."\t".'Gene_name'."\n");
foreach($proteins_main as $pm => $pm_arr){
	fwrite($report_cores, $pm."\t".$pm_arr['display_name']."\n");
}
fclose($report_cores);

# collected information to json file
$search_parameters['tonext']['prots'] 		= $proteins_main;
$search_parameters['tonext']['hpos']		= $hpo_nodes;
$search_parameters['tonext']['pathways'] 	= $pathway_nodes;
#$search_parameters['tonext']['fn'] 			= $fn;
$search_parameters['tonext']['omims'] 		= implode('|',$omims_of_mains);
$search_parameters['tonext']['mainomims'] 	= $main_to_omim;
$search_parameters['starter_searchs']	 	= $starter_searchs;

# giving first small network to user (only main proteins)
$search_parameters = array_merge($search_parameters,protein_to_node($proteins_main));

unset($proteins_main);
unset($hpo_nodes);
unset($pathway_nodes);
unset($fn);
unset($omims_of_mains);
unset($main_to_omim);
unset($starter_searchs);

$data = fopen("data/".$_POST['params'].'.json', "w");
fwrite($data, json_encode($search_parameters));
fclose($data);
echo json_encode($search_parameters);
?>