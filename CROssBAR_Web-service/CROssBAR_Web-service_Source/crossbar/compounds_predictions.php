<?php
include('database.php');
include('functions.php');
$db = new database();

$params = file_get_contents('data/'.$_POST['params'].'.json');
$report_file = $_POST['params'].'.txt';
$report = fopen('data/'.$report_file, "a");

$file 			= json_decode($params,true);
#$num_of_nodes 	= (int)$file['options']['num_of_nodes'];
$num_of_nodes 	= (int)$file['options']['num_of_compounds'];
$proteins	 	= $file['tonext']['prots'];
$drugs		 	= $file['tonext']['drugs'];
$nodes		 	= $file['nodes'];
$edges		 	= $file['edges'];
$starter_searchs= $file['starter_searchs'];

#print_r($starter_searchs); die();

$accessions 	= array_keys($proteins);
$accessions_str = implode('|',$accessions);
$pchemblValue 	= 6;

$drug_chembl_ids = array();
foreach($drugs as $id => $drug)
	if($drug['chembl_id'] !== null)
		$drug_chembl_ids[$drug['chembl_id']] = $id;

if($file['options']['chembl_compounds'] == 1){
	# compounds are collecting
	#$t = microtime(TRUE);
	include('node_compounds.php');
	#$ttt = microtime(true) - $t;
	#write_enrichScores($report,$node_compounds,'Compounds');
	#fwrite($report,"\nChembl compound operations takes: $ttt seconds.\n");
}else
	$node_compounds = array();

if($file['options']['predictions'] == 1){
	# predictions are collecting
	#$t = microtime(TRUE);
	include('node_predictions.php');
	#$ttt = microtime(true) - $t;
	#write_enrichScores($report,$predictions,'Predictions');
	#fwrite($report,"Predicted compound operations takes: $ttt seconds.\n\n");
}else
	$predictions = array();

$file['tonext']['prots']	   = $proteins;
$file['tonext']['drugs'] 	   = $drugs;
$file['nodes']		 	 	   = $nodes;
$file['edges']		 	 	   = $edges;
$file['tonext']['compounds']   = $node_compounds;
$file['tonext']['predictions'] = $predictions;

unset($proteins);
unset($drugs);
unset($nodes);
unset($edges);
unset($node_compounds);
unset($predictions);

$to_file = json_encode($file);
fclose($report);
$data = fopen("data/".$_POST['params'].'.json', "w");
fwrite($data, $to_file);
fclose($data);
echo $to_file;
?>