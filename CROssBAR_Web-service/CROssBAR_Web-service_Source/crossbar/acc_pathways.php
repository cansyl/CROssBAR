<?php
if(count($kegg_starter_pathways)){
	$kegg_starter_pathways_str = implode(',',$kegg_starter_pathways);
	# kegg pathways ekleniyor burda
	fwrite($report, "\nQuery terms: $kegg_starter_pathways_str (kegg pathway)\n");
	$a = microtime(true);
	include('node_kegg_starter_pathway_accessions.php');
	$aa = microtime(true);
	$aaa = $aa - $a;
	fwrite($report,'KEGG pathway operations takes '.$aaa." seconds.\n\n");
	$starter_searchs['kegg_diseases'] = $kegg_starter_diseases;
}
/*
else
	fwrite($report,'Could not found any kegg pathway in database with "'.$pathways_str.'"'."\n\n");
*/
#$acc_of_pathways = array();
if(count($pathways)){
	$pathways_str = implode(',',$pathways);
	fwrite($report, 'Query terms: '.$pathways_str.' (Reactome pathway)'."\n");
	# CROssBAR protein collection to be processed.
	if( ($prots = fetch_data('/proteins?limit=100&reactome='.urlencode($pathways_str).'&taxId='.$tax_ids_str)) !== false){
		$prots = (array)$prots;
		if(isset($prots['proteins'])){
			$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
		}else
			fwrite($report, "\nError occurred while fetching proteins with reactomes: \"$pathways_str\"\n".'/proteins?limit=100&reactome='.$pathways_str.'&taxId='.$tax_ids_str."\n\n");
	}else{
			fwrite($report, "\nError occurred while fetching proteins with reactomes: \"$pathways_str\"\n".'/proteins?limit=100&reactome='.$pathways_str.'&taxId='.$tax_ids_str."\n\n");
	}
}

unset($prots);
#var_dump($crossbar_proteins);
?>