<?php
if(count($kegg_starter_pathways)){
	$kegg_starter_pathways_str = implode('|',$kegg_starter_pathways);
	# kegg pathways ekleniyor burda
	include('node_kegg_starter_pathway_accessions.php');
	$starter_searchs['kegg_diseases'] = $kegg_starter_diseases;
}

if(count($pathways)){
	$pathways_str = implode('|',$pathways);

	# CROssBAR protein collection to be processed.
	if( ($prots = fetch_data('/proteins?limit=100&reactome='.urlencode($pathways_str).'&taxId='.$tax_ids_str)) !== false){
		$prots = (array)$prots;
		if(isset($prots['proteins'])){
			$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
		}
	}else{
		#Error occurred while fetching proteins with reactomes: $pathways_str
		#/proteins?limit=100&reactome='.$pathways_str.'&taxId='.$tax_ids_str
	}
}

unset($prots);

?>