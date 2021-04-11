<?php
include('database.php');
include('functions.php');
$db = new database();

$params = file_get_contents('data/'.$_POST['params'].'.json');
$report_file = $_POST['params'].'.txt';
$report = fopen('data/'.$report_file, "a+");

# pre-defined variables.
//$url = "https://wwwdev.ebi.ac.uk/crossbar";
$pchemblValue = 6;

$remains = json_decode($params,true);
$tax_ids 		= $remains['options']['tax_ids'];
$tax_ids_str	= implode('|',$tax_ids );
$fn_filter 		= (int)$remains['options']['fn'];
//$num_of_nodes 	= (int)$remains['options']['num_of_nodes'];
$num_of_fn_nodes 	= (int)$remains['options']['num_of_fn_nodes'];

$proteins_main 		= $remains['tonext']['prots'];
#$proteins_fn		= $remains['tonext']['fn'];
$hpo_nodes 			= $remains['tonext']['hpos'];
$pathway_nodes 		= $remains['tonext']['pathways'];
//$omims_str		= $remains['tonext']['omims'];
$omims_of_prots		= explode('|',$remains['tonext']['omims']);
$proteinToOmim 		= $remains['tonext']['mainomims'];

# collected main accessions as 1-d array
$main_accessions = array_keys($proteins_main);

if($fn_filter){
	include('acc_fn.php');
	include('node_fn.php');
}

# cleaning untaken first neighbours from main accession interactions
foreach($proteins_main as $prot_main => $prot_arr){
	foreach($prot_arr['proteins'] as $interaction => $interaction_arr){
		if(!isset($fn[$interaction]) and $prot_main != $interaction){
			unset($proteins_main[$prot_main]['proteins'][$interaction]);
		}
	}
}

$all_proteins = array_merge($proteins_main,$fn);
$out = protein_to_node($all_proteins);

$remains['nodes'] 				= $out['nodes'];
$remains['edges'] 				= $out['edges'];
$remains['tonext']['prots'] 	= $all_proteins;
$remains['tonext']['hpos']		= $hpo_nodes;
$remains['tonext']['pathways'] 	= $pathway_nodes;
$remains['tonext']['omims'] 	= implode('|',(array_unique($omims_of_prots)));
$remains['tonext']['mainomims'] = $proteinToOmim;

$data = fopen('data/'.$_POST['params'].'.json', "w");
fwrite($data, json_encode($remains));
fclose($data);

echo json_encode($remains);

?>
