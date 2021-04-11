<?php

$searcher_prots = array();
foreach($prts as $protein){
	$res = $db->fetch_gene_with_tax($protein,$tax_ids);
	foreach($res as $r)
		if($r['gene'] != '')
			$searcher_prots[] = $r['acc'];
}
if($search_parameters['options']['reviewed_filter'] == 1){
	foreach($searcher_prots as $i => $acc)
		if(array_search($acc,$revieweds,true) === false)
			unset($searcher_prots[$i]);
}

$prts_str = implode('|',$searcher_prots);

# CROssBAR protein collection to be processed.
# assumed to not bigger than 100
if( ($prots = fetch_data('/proteins?limit=100&accession='.urlencode($prts_str).'&taxId='.$tax_ids_str)) !== false){
	$prots = (array)$prots;
	if(isset($prots['proteins'])){
		$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
	}
}

?>