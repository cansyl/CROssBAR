<?php
include('database.php');
include('functions.php');
$db = new database();

# fetching validated search parameters
/*
$params = file_get_contents('datas/'.$_GET['params'].'.json');
$report_file = $_GET['params'].'.txt';
*/
$params = file_get_contents('datas/'.$_POST['params'].'.json');
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
$tax_ids_str	= implode(',',$tax_ids );
$fn_filter  	= (int)$search_parameters['options']['fn'];
$num_of_nodes   = (int)$search_parameters['options']['num_of_nodes'];
$chembl		    = (int)$search_parameters['options']['chembl'];

$report = fopen('datas/'.$report_file, "w");
fwrite($report, "SEARCH REPORT\n-------------\n");
fwrite($report, 'Only the first '.$num_of_nodes.' enriched nodes from each component will be incorporated to the knowledge graph'."\n");

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
				$drugs[] = $data[0];
			break;
			
			case 'Compound':
				$compounds[] = $data[0];
			break;

			case 'HPO':
				$hpos[] = $data[0];
			break;
			
			case 'Protein':
				$prts = explode(',',$data);
			break;
		}
	}
}

fwrite($report, "\nQuery terms: \n");
if(count($diseases) or count($kegg_starter_diseases)){
	if(count($diseases))
		fwrite($report, implode(',',$diseases)." (disease)\n");
	if(count($kegg_starter_diseases))
		fwrite($report, implode(',',$kegg_starter_diseases)." (kegg disease)\n");
}
if(count($pathways) or count($kegg_starter_pathways)){
	if(count($pathways))
		fwrite($report, implode(',',$pathways).' (pathway)'."\n");
	if(count($kegg_starter_pathways))
		fwrite($report, implode(',',$kegg_starter_pathways)." (kegg pathway)\n");
}
if(count($drugs)){
	fwrite($report, implode(',',$drugs)." (drug)\n");
}
if(count($hpos)){
	fwrite($report, implode(',',$hpos)." (HPO)\n");
}
if(count($compounds)){
	fwrite($report, implode(',',$compounds)." (compound)\n");
}
if(count($prts)){
	fwrite($report, implode(',',$prts)." (gene/protein)\n");
}
fwrite($report, "\n");

if(count($diseases) or count($kegg_starter_diseases)){
	$b1 = microtime(true);
	include('acc_diseases.php');
	$b2 = microtime(true) - $b1;
	fwrite($report,'collection of disease(s) accessions takes '.$b2.' seconds'."\n\n");
}

if(count($pathways) or count($kegg_starter_pathways)){
	$b1 = microtime(true);
	include('acc_pathways.php');
	$b2 = microtime(true) - $b1;
	fwrite($report,'collection of pathway(s) accessions takes '.$b2.' seconds'."\n\n");
}

if(count($drugs)){
	$b1 = microtime(true);
	$drugs_str = implode(',',$drugs);
	fwrite($report, "\nQuery terms: $drugs_str (drug)\n");
	include('node_kegg_starter_drug_diseases.php');
	include('acc_drugs.php');
	$b2 = microtime(true) - $b1;
	fwrite($report,'collection of drug(s) accessions takes '.$b2.' seconds'."\n\n");
}

if(count($hpos)){
	$b1 = microtime(true);
	include('acc_hpos.php');
	$b2 = microtime(true) - $b1;
	fwrite($report,'collection of hpo(s) accessions takes '.$b2.' seconds'."\n\n");
}

if(count($compounds)){
	$b1 = microtime(true);
	include('acc_compounds.php');
	$b2 = microtime(true) - $b1;
	fwrite($report,'collection of compound(s) accessions takes '.$b2.' seconds'."\n\n");
}

if(count($prts)){
	$b1 = microtime(true);
	include('acc_proteins.php');
	$b2 = microtime(true) - $b1;
	fwrite($report,'collection of gene(s) accessions takes '.$b2.' seconds'."\n\n");
}

$taym1 = microtime(TRUE);
# main proteinlerin islendigi dosya
include('process_mains.php');
$taym2 = microtime(TRUE);
$omims_of_mains = array_unique($omims_of_mains);
fwrite($report,"\n".'Collected core proteins: '."\n".implode(',',array_keys($proteins_main))."\n");
fwrite($report,'From core proteins:'."\n");
fwrite($report,'Number of the collected HPOs: '.count($hpo_nodes)."\n");
fwrite($report,'Number of the collected Pathways: '.count($pathway_nodes)."\n");
fwrite($report,'Number of the collected first neighbours: '.count($fn)."\n");
fwrite($report,'Number of the collected OMIMs: '.count($omims_of_mains)."\n");
fwrite($report,'Core proteins processing time: '.($taym2 - $taym1)." seconds\n");

# collected information to json file
$search_parameters['tonext']['prots'] 		= $proteins_main;
$search_parameters['tonext']['hpos']		= $hpo_nodes;
$search_parameters['tonext']['pathways'] 	= $pathway_nodes;
$search_parameters['tonext']['fn'] 			= $fn;
$search_parameters['tonext']['omims'] 		= implode(',',$omims_of_mains);
$search_parameters['tonext']['mainomims'] 	= $main_to_omim;
$search_parameters['starter_searchs']	 	= $starter_searchs;

# giving first small network to user (only main proteins)
$search_parameters = array_merge($search_parameters,protein_to_node($proteins_main));
/*
var_dump($proteins_main); 
print_r($search_parameters); die();
*/
unset($proteins_main);
unset($hpo_nodes);
unset($pathway_nodes);
unset($fn);
unset($omims_of_mains);
unset($main_to_omim);
unset($starter_searchs);

$data = fopen("datas/".$_POST['params'].'.json', "w");
fwrite($data, json_encode($search_parameters));
fclose($data);
echo json_encode($search_parameters);

?>