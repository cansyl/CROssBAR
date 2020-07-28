<?php
$hpos_str 		= implode(',',$hpos);
$acc_of_hpos 	= array();
$genes_of_hpos  = array();

if( ($hpos_for_genes = fetch_data('/hpo?limit=100&hpotermname='.urlencode($hpos_str))) !== false){
	#fwrite($report, "HPOs to be searched: $hpos_str\n");
	fwrite($report, "Query terms: $hpos_str (HPO)\n");
	foreach($hpos_for_genes->hpo as $hpo){
		if(array_search($hpo->term_name,$hpos) !== false){
			$starter_searchs['hpos'][$hpo->hpo_id]['display_name'] = $hpo->term_name;
			$starter_searchs['hpos'][$hpo->hpo_id]['edges'] = array();
			foreach($hpo->gene as $gene){
				$genes_of_hpos[] = $gene->symbol;
				$starter_searchs['hpos'][$hpo->hpo_id]['edges'][] = $gene->symbol;
			}
			continue;
		}
		foreach($hpo->gene as $gene)
			$genes_of_hpos[] = $gene->symbol;
	}

	$accg_of_hpos = array();
	foreach($genes_of_hpos as $g){
		$res = $db->fetch_gene_with_tax($g,$tax_ids);
		foreach($res as $r)
			if($r['gene'] != '')
				$accg_of_hpos[] = $r['acc'];
	}
	if($search_parameters['options']['reviewed_filter'] == 1 or count($accg_of_hpos) > 100){
		foreach($accg_of_hpos as $i => $acc)
			if(array_search($acc,$revieweds,true) === false)
				unset($accg_of_hpos[$i]);
	}
	$accg_of_hpos = array_values( array_unique( $accg_of_hpos ) );
	#$accs_from_gene  = json_decode(file_get_contents($url.'/proteins?limit=100&gene='.$proteins_str.'&taxId='.$tax_ids_str));
	#$genes_of_hpos_str = implode(',',$genes_of_hpos);
	$genes_of_hpos_str = implode(',',$accg_of_hpos);
	fwrite($report, 'Collected accessions from HPO term(s):'."\n".$genes_of_hpos_str."\n");
	# CROssBAR protein collection to be processed.
	$accg_of_hpos = array_chunk($accg_of_hpos,100);

	foreach( $accg_of_hpos as $accg_of_hpos_divided ){
		$accg_of_hpos_divided_str = implode(',',$accg_of_hpos_divided);
		if( ($prots = fetch_data('/proteins?limit=100&accession='.$accg_of_hpos_divided_str.'&taxId='.$tax_ids_str)) !== false){
			$prots = (array)$prots;
			if(isset($prots['proteins'])){
				$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
			}else{
				fwrite($report, "\n".'Error occured while fetching proteins with accessions: '.$accg_of_hpos_divided_str."\n".'/proteins?limit=100&accession='.$accg_of_hpos_divided_str.'&taxId='.$tax_ids_str."\n\n");
				die('Protein Fetch Error');
			}
		}else{
			fwrite($report, "\n".'Error occured while fetching proteins with accessions: '.$accg_of_hpos_divided_str."\n".'/proteins?limit=100&gene='.$accg_of_hpos_divided_str."\n\n");
			die('Protein Fetch Error');
		}
	}
}else{
	fwrite($report,'Unexpected error occured while fetching HPO data with HPO(s): '.$hpos_str."\n");
	die('HPO Error');
}

?>