<?php
include('database.php');
include('functions.php');
$db = new database();
/*
$params = file_get_contents('datas/'.$_GET['params'].'.json');
$report_file = $_GET['params'].'-report.txt';
$report = fopen('datas/'.$report_file, "a+");
*/

$params = file_get_contents('datas/'.$_POST['params'].'.json');
$report_file = $_POST['params'].'.txt';
$report = fopen('datas/'.$report_file, "a+");

# back-end defined variables.
//$url = "https://wwwdev.ebi.ac.uk/crossbar";
$pchemblValue = 6;

$remains = json_decode($params,true);
//print_r($search_parameters['options']['tax_ids']); die();
$tax_ids 		= $remains['options']['tax_ids'];
$tax_ids_str	= implode(',',$tax_ids );
$fn_filter 		= (int)$remains['options']['fn'];
$num_of_nodes 	= (int)$remains['options']['num_of_nodes'];

#print_r($remains); die();

/*
$search_parameters['tonext']['prots'] 		= $proteins_main;
$search_parameters['tonext']['hpos']		= $hpo_nodes;
$search_parameters['tonext']['pathways'] 	= $pathway_nodes;
$search_parameters['tonext']['fn'] 			= implode(',',(array_unique($extracted_fn)));
$search_parameters['tonext']['omims'] 		= implode(',',(array_unique($omims_of_mains)));
$search_parameters['tonext']['mainomims'] 	= $main_to_omim;
*/

$proteins_main 		= $remains['tonext']['prots'];
$proteins_fn		= $remains['tonext']['fn'];
$hpo_nodes 			= $remains['tonext']['hpos'];
$pathway_nodes 		= $remains['tonext']['pathways'];
//$omims_str		= $remains['tonext']['omims'];
$omims_of_prots		= explode(',',$remains['tonext']['omims']);
$proteinToOmim 		= $remains['tonext']['mainomims'];

/*
echo implode(',',array_keys($proteins_main));
var_dump($proteins_fn); die();
*/
/*
print_r($proteins_main);
print_r($hpo_nodes);
print_r($pathway_nodes);
print_r($proteins_fn);
print_r($omims_str);
print_r($proteinToOmim);
die();
*/

# collected main accessions as 1-d array
$main_accessions = array_keys($proteins_main);

if($fn_filter){
	fwrite($report,"\nCollecting first neighbours\n");
	$ttt_start = microtime(TRUE); # for calculation of process
	include('node_fn.php');
	$ttt = microtime(TRUE) - $ttt_start;
	fwrite($report,"First neighbours operations takes $ttt second.\n");
}
/*
var_dump($proteins_main);
var_dump($fn_nodes);
*/
$all_proteins = array_merge($proteins_main,$fn_nodes);
//var_dump($all_proteins); die();

$out = protein_to_node($all_proteins);
/*
$remains['nodes'] = array_merge($remains['nodes'],$out['nodes']);
$remains['edges'] = array_merge($remains['edges'],$out['edges']);
*/

$remains['nodes'] 				= $out['nodes'];
$remains['edges'] 				= $out['edges'];
$remains['tonext']['prots'] 	= $all_proteins;
$remains['tonext']['hpos']		= $hpo_nodes;
$remains['tonext']['pathways'] 	= $pathway_nodes;
$remains['tonext']['omims'] 	= implode(',',(array_unique($omims_of_prots)));
$remains['tonext']['mainomims'] = $proteinToOmim;

/*
$search_parameters['tonext']['prots'] 		= $proteins_main;
$search_parameters['tonext']['hpos']		= $hpo_nodes;
$search_parameters['tonext']['pathways'] 	= $pathway_nodes;
//$search_parameters['tonext']['fn'] 			= implode(',',(array_unique($extracted_fn)));
$search_parameters['tonext']['fn'] 			= $fn;
$search_parameters['tonext']['omims'] 		= implode(',',(array_unique($omims_of_mains)));
$search_parameters['tonext']['mainomims'] 	= $main_to_omim;
*/

//$data = fopen('datas/'.$_GET['params'].'.json', "w");
$data = fopen('datas/'.$_POST['params'].'.json', "w");
fwrite($data, json_encode($remains));
fclose($data);

echo json_encode($remains);
//echo json_encode(protein_to_node($all_proteins));
#die();
?>