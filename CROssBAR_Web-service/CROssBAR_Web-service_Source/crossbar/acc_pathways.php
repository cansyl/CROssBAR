<?php
$pathways_str = implode(',',$pathways);
$kegg_starter_pathways_str = implode(',',$kegg_starter_pathways);
if(count($kegg_starter_pathways)){
	# kegg pathways ekleniyor burda
	fwrite($report, "\nKEGG pathway(s) to be searched: $kegg_starter_pathways_str\n\n");
	$a = microtime(true);
	include('node_kegg_starter_pathway_accessions.php');
	$aa = microtime(true);
	$aaa = $aa - $a;
	fwrite($report,'KEGG pathway operations takes '.$aaa." seconds.\n\n");
	$starter_searchs['kegg_diseases'] = $kegg_starter_diseases;
}else
	fwrite($report,'No pathway found in KEGG database with "'.$pathways_str.'"'."\n\n");

$acc_of_pathways = array();



/*
$protOfPWs = fetch_data('/proteins?limit=100&reactome='.urlencode($pathways_str).'&taxId='.$tax_ids_str));
if(is_object($protOfPWs)){
	foreach($protOfPWs->proteins as $protein)
		$acc_of_pathways[] = $protein->accession;
	for($i=1; $i < $protOfPWs->pageMeta->totalPages; $i++){
		$protOfPWs = json_decode(file_get_contents($url.'/proteins?limit=100&page='.$i.'&reactome='.urlencode($pathways_str).'&taxId='.$tax_ids_str));
		foreach($protOfPWs->proteins as $protein)
			$acc_of_pathways[] = $protein->accession;
	}
}
*/

#var_dump($pathways); die();

# CROssBAR protein collection to be processed.
if( ($prots = fetch_data('/proteins?limit=1000&reactome='.urlencode($pathways_str).'&taxId='.$tax_ids_str)) !== false){
	fwrite($report, 'PATHWAYs to be searched: "'.$pathways_str.'"'."\n");
	$prots = (array)$prots;
	if(isset($prots['proteins'])){
		$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
	}else
		fwrite($report, "\nError occured while fetching proteins with reactomes: \"$pathways_str\"\n".'/proteins?limit=1000&reactome='.$pathways_str.'&taxId='.$tax_ids_str."\n\n");
}

unset($prots);
#var_dump($crossbar_proteins);
?>