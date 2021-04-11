<?php
include('database.php');
include('functions.php');
$db = new database();

$params = file_get_contents('data/'.$_POST['params'].'.json');
$report_file = $_POST['params'].'.txt';
$report = fopen('data/'.$report_file, "a");

$file 			= json_decode($params,true);
$tax_ids 		= $file['options']['tax_ids'];
$tax_ids_str	= implode('|',$tax_ids );
$fn_filter 		= (int)$file['options']['fn'];
#$num_of_nodes 	= (int)$file['options']['num_of_nodes'];
$num_of_diseases_ver2 	= (int)$file['options']['num_of_diseases'];
$num_of_drugs_ver2 	= (int)$file['options']['num_of_drugs'];
$num_of_phenotypes_ver2 	= (int)$file['options']['num_of_phenotypes'];
$num_of_pathways_ver2 	= (int)$file['options']['num_of_pathways'];

$proteins	 		= $file['tonext']['prots'];
$hpo_nodes 			= $file['tonext']['hpos'];
$pathway_nodes 		= $file['tonext']['pathways'];
$omims_str			= $file['tonext']['omims'];
if(trim($omims_str) != '')
	$omims_of_prots		= explode('|',$omims_str);
else
	$omims_of_prots = array();
//$omims_of_prots	= array();
$proteinToOmim 		= $file['tonext']['mainomims'];
$starter_searchs 	= $file['starter_searchs'];

# collecting disease nodes
include('node_diseases.php');

# collecting drug nodes
$accessions_str = implode('|',array_keys($proteins));
include('node_drugs.php');

# ENRICHMENT CALCULATIONS STARTING
include('enrichment_disease_drug_hpo_pathway.php');
# ENRICHMENT CALCULATIONS DONE

foreach($disease_nodes as $id => $disease){
	$node_type = 'Disease';
	$file['nodes'][] = array('data'=>array('id'=>$id,'display_name'=>$disease['display_name'],'Node_Type'=>$node_type,'enrichScore'=>$disease['enrichScore']));
	$tmp_edges = array_unique($disease['edges']);
	$disease_nodes[$id]['edges'] = $tmp_edges;
	foreach($tmp_edges as $e){
		$file['edges'][] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'Disease','label'=>'is related to'));
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
		foreach($drug['chembl_edges'] as $e => $pchmblvl){
			#$drug_nodes[$id]['chembl_edges'][$ee] = $pchmbl;
			$file['edges'][] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'Chembl','label'=>'targets', 'pchembl_value'=>$pchmblvl, 'assay_chembl_id'=>$drug['chembl_assays'][$e]));
			$proteins[$e]['drugs'][] = $id;
		}
}
#die();
foreach($pathway_nodes as $id => $pathway){
	$file['nodes'][] = array('data'=>array('id'=>$id,'display_name'=>$pathway['display_name'],'Node_Type'=>'Pathway','enrichScore'=>$pathway['enrichScore']));
	$tmp_edges = array_unique($pathway['edges']);
	$pathway_nodes[$id]['edges'] = $tmp_edges;
	foreach($tmp_edges as $e){
		$file['edges'][] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'Pathway','label'=>'is involved in'));
		$proteins[$e]['pathways'][] = $id;
	}
}
foreach($hpo_nodes as $id => $hpo){
	$file['nodes'][] = array('data'=>array('id'=>$id,'display_name'=>$hpo['display_name'],'Node_Type'=>'HPO','enrichScore'=>$hpo['enrichScore']));
	$tmp_edges = array_unique($hpo['edges']);
	$hpo_nodes[$id]['edges'] = $tmp_edges;
	foreach($tmp_edges as $e){
		$file['edges'][] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'HPO','label'=>'is associated w/'));
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

fclose($report);

$data = fopen("data/".$_POST['params'].'.json', "w");
fwrite($data, json_encode($file));
fclose($data);
echo json_encode($file);

?>