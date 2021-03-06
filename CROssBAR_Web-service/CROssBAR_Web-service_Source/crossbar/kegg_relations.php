<?php
include('database.php');
include('functions.php');
$db = new database();

$params = file_get_contents('data/'.$_POST['params'].'.json');
$report_file = $_POST['params'].'.txt';
$report = fopen('data/'.$report_file, "a");

$file 			 = json_decode($params,true);
#$num_of_nodes 	 = (int)$file['options']['num_of_nodes'];
$num_of_diseases_ver2 	 = (int)$file['options']['num_of_diseases'];
$num_of_pathways_ver2 	 = (int)$file['options']['num_of_pathways'];
$starter_searchs = $file['starter_searchs'];
$drugs			 = $file['tonext']['drugs'];
$diseases		 = $file['tonext']['diseases'];
$pathways		 = $file['tonext']['pathways'];
$proteins		 = $file['tonext']['prots'];
$hpos			 = $file['tonext']['hpos'];
$compounds		 = $file['tonext']['compounds'];
$predictions	 = $file['tonext']['predictions'];
$accessions		 = array_keys($proteins);

$nodes			 = $file['nodes'];
$edges			 = $file['edges'];

include('kegg.php');

# $kegg_Pathways
foreach($kegg_Pathways as $id => $pathway){
	$nodes[] = array('data'=>array('id'=>$id,'display_name'=>$pathway['display_name'],'Node_Type'=>'kegg_Pathway','enrichScore'=>$pathway['enrichScore']));
	foreach($pathway['edges'] as $e){
		$edges[] = array('data'=>array('source'=>$e,'target'=>$id,'Edge_Type'=>'kegg_path_prot', 'label'=>'is involved in'));
		$proteins[$e]['pathways'][] = $id;
	}
}

# $kegg_Diseases
foreach($kegg_Diseases as $id => $disease){
	$nodes[] = array('data'=>array('id'=>$id,'display_name'=>$disease['display_name'],'Node_Type'=>'kegg_Disease','enrichScore'=>$disease['enrichScore']));
	foreach($disease['edges'] as $e){
		$edges[] = array('data'=>array('source'=>$e,'target'=>$id, 'Edge_Type'=>'kegg_dis_prot', 'label'=>'is related to'));
		$proteins[$e]['diseases'][] = $id;
	}
}

# create csv report
$pathways = array_merge($pathways,$kegg_Pathways);
$diseases = array_merge($diseases,$kegg_Diseases);
include('csv_report.php');

#$file['tonext']['prots'] 	= $proteins;
#$file['tonext']['diseases'] = array_merge($diseases,$kegg_Diseases);
#$file['tonext']['pathways'] = array_merge($pathways,$kegg_Pathways);
$file['nodes']				= $nodes;
$file['edges']				= $edges;

unset($file['starter_searchs']);
unset($file['tonext']);

$search_start_time = (int)$file['options']['search_start'];
$file['options']['search_finish'] = time();
$file['options']['search_runtime'] = $file['options']['search_finish'] - $search_start_time;

include('report.php');

# bellegi bosalt
unset($proteins);
unset($diseases);
unset($kegg_Diseases);
unset($pathways);
unset($kegg_Pathways);
unset($nodes);
unset($edges);
unset($accessions);

$data = fopen("data/".$_POST['params'].'.json', "w");
fwrite($data, json_encode($file));
fclose($data);

echo json_encode($file);
?>
