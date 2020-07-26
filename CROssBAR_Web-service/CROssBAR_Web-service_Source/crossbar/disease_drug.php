<?php
include('database.php');
include('functions.php');
$db = new database();

/*
$params = file_get_contents('datas/'.$_GET['params'].'.json');
$report_file = $_GET['params'].'-report.txt';
$report = fopen('datas/'.$report_file, "a");
*/

$params = file_get_contents('datas/'.$_POST['params'].'.json');
$report_file = $_POST['params'].'.txt';
$report = fopen('datas/'.$report_file, "a");

# back-end defined variables.
//$url = "https://wwwdev.ebi.ac.uk/crossbar";
//$pchemblValue = 6;

$file 			= json_decode($params,true);
$tax_ids 		= $file['options']['tax_ids'];
$tax_ids_str	= implode(',',$tax_ids );
$fn_filter 		= (int)$file['options']['fn'];
$num_of_nodes 	= (int)$file['options']['num_of_nodes'];

$proteins	 		= $file['tonext']['prots'];
$hpo_nodes 			= $file['tonext']['hpos'];
$pathway_nodes 		= $file['tonext']['pathways'];
$omims_str			= $file['tonext']['omims'];
if(trim($omims_str) != '')
	$omims_of_prots		= explode(',',$omims_str);
else
	$omims_of_prots = array();
//$omims_of_prots	= array();
$proteinToOmim 		= $file['tonext']['mainomims'];
$starter_searchs 	= $file['starter_searchs'];

# collecting disease nodes
$t = microtime(true);
include('node_diseases.php');
$ttt = microtime(true) - $t;
fwrite($report,"Disease collecting takes $ttt seconds.\n");

# collecting drug nodes
$accessions_str = implode(',',array_keys($proteins));
$t = microtime(true);
include('node_drugs.php');
$ttt = microtime(true) - $t;
fwrite($report,"Drug collecting takes $ttt seconds.\n");

# ENRICHMENT CALCULATIONS STARTING
$t = microtime(TRUE);
include('enrichment_disease_drug_hpo_pathway.php');
$ttt = microtime(true) - $t;
fwrite($report,"\nEnrichment of pathways, drugs, diseases and hpo terms takes: $ttt seconds.\n\n");
# ENRICHMENT CALCULATIONS DONE

foreach($disease_nodes as $id => $disease){
	$node_type = 'Disease';
	$file['nodes'][] = array('data'=>array('id'=>$id,'display_name'=>$disease['display_name'],'Node_Type'=>$node_type,'enrichScore'=>$disease['enrichScore']));
	$tmp_edges = array_unique($disease['edges']);
	$disease_nodes[$id]['edges'] = $tmp_edges;
	foreach($tmp_edges as $e){
		$file['edges'][] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'Disease','label'=>'related to'));
		$proteins[$e]['diseases'][] = $id;
	}
}

foreach($drug_nodes as $id => $drug){
	$file['nodes'][] = array('data'=>array('id'=>$id,'display_name'=>$drug['display_name'],'Node_Type'=>'Drug','enrichScore'=>$drug['enrichScore']));
	$tmp_edges = array_unique($drug['edges']);
	$drug_nodes[$id]['edges'] = $tmp_edges;
	#var_dump($tmp_edges);
	foreach($tmp_edges as $e){
		$file['edges'][] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'Drug','label'=>'targets'));
		$proteins[$e]['drugs'][] = $id;
	}
	if(isset($drug['chembl_edges']))
		foreach($drug['chembl_edges'] as $e){
			$file['edges'][] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'Chembl','label'=>'targets'));
			$proteins[$e]['drugs'][] = $id;
		}
}
#die();
foreach($pathway_nodes as $id => $pathway){
	$file['nodes'][] = array('data'=>array('id'=>$id,'display_name'=>$pathway['display_name'],'Node_Type'=>'Pathway','enrichScore'=>$pathway['enrichScore']));
	$tmp_edges = array_unique($pathway['edges']);
	$pathway_nodes[$id]['edges'] = $tmp_edges;
	foreach($tmp_edges as $e){
		$file['edges'][] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'Pathway','label'=>'involved in'));
		$proteins[$e]['pathways'][] = $id;
	}
}
foreach($hpo_nodes as $id => $hpo){
	$file['nodes'][] = array('data'=>array('id'=>$id,'display_name'=>$hpo['display_name'],'Node_Type'=>'HPO','enrichScore'=>$hpo['enrichScore']));
	$tmp_edges = array_unique($hpo['edges']);
	$hpo_nodes[$id]['edges'] = $tmp_edges;
	foreach($tmp_edges as $e){
		$file['edges'][] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'HPO','label'=>'associated w/'));
		$proteins[$e]['hpos'][] = $id;
	}
}
# output file arrangement
unset($file['tonext']['prots']);
unset($file['tonext']['hpos']);
unset($file['tonext']['pathways']);
unset($file['tonext']['fn']);
unset($file['tonext']['omims']);
unset($file['tonext']['mainomims']);
unset($file['tonext']['kegg_drugs']);
$file['tonext']['prots'] 	= $proteins;
$file['tonext']['hpos'] 	= $hpo_nodes;
$file['tonext']['pathways'] = $pathway_nodes;
$file['tonext']['diseases'] = $disease_nodes;
$file['tonext']['drugs'] 	= $drug_nodes;
# memory relaxing...
unset($proteins);
unset($hpo_nodes);
unset($pathway_nodes);
unset($disease_nodes);
unset($drug_nodes);

#var_dump($proteins);
#var_dump($disease_nodes);
fclose($report);

//$data = fopen('datas/3.json', "w");
//$data = fopen("datas/".$_GET['params'].'.json', "w");
$data = fopen("datas/".$_POST['params'].'.json', "w");
fwrite($data, json_encode($file));
fclose($data);
echo json_encode($file);

?>