<?php

if(count($kegg_starter_diseases)){
	$kegg_starter_diseases_str = implode('|',$kegg_starter_diseases);
	# kegg drugs adding
	include('node_kegg_starter_disease_drugs.php');
	$starter_searchs['kegg_diseases'] = $kegg_starter_diseases;
}

function takeOmimIdsFromEfo($diseases){
	$Ids = array();
	foreach($diseases->diseases as $disease){
		if(substr($disease->obo_id, 0, 5) == 'CHEBI')
			continue;
		if($disease->omim != null)
			foreach($disease->omim as $omim)
				$Ids[] = explode(':',$omim)[1];
	}
	return $Ids;
}

if(count($diseases)){
	foreach($diseases as $disease){
		$omimIds = array();
		$oboof_starter = '';
		$disease_entities = fetch_data('/efo?limit=1000&synonym='.urlencode($disease));

		if($disease_entities === false)
			$disease_entities = fetch_data('/efo?label='.urlencode($disease));
		if($disease_entities !== false)
		{
			foreach($disease_entities->diseases as $d){
				if($d->label == $disease){
					# founded disease saving as starter
					#if this node will not able to join network due to enrichment calculation,
					#it will be forced by giving high value enrichScore by manual
					$starter_searchs['diseases'][$d->obo_id]['display_name'] = $d->label;
					$starter_searchs['diseases'][$d->obo_id]['omim'] 		 = $d->omim;
					$starter_searchs['diseases'][$d->obo_id]['proteins']	 = array();
					$oboof_starter = $d->obo_id;
					
					# if obo_id is an 'Orphanet' id, collect addinitional proteins...
					if(substr($d->obo_id,0,8) == 'Orphanet')
						if(($orphanet_accs = fetch_data('/proteins?limit=100&orphanet='.urlencode($disease))) !== false){
							$prots = (array)$orphanet_accs;
							if(isset($prots['proteins'])){
								$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
								foreach($prots['proteins'] as $i => $p){
									$starter_searchs['diseases'][$d->obo_id]['proteins'][] = $p->accession;
								}
							}
						}
				}
			}
			$omimIds = takeOmimIdsFromEfo($disease_entities);
		}

		unset($disease_entities); # memory rahatlatiliyor.

		if(!count($omimIds)){
			# There is no OMIM record related to $disease in crossbar database.
		}else{
			$omimIds = array_unique($omimIds);
			$omimIds_str = implode('|',$omimIds);

			# CROssBAR protein collection to be processed.
			if( ($prots = fetch_data('/proteins?limit=100&omim='.$omimIds_str)) !== false){
				$prots = (array)$prots;
				
				if(isset($prots['proteins'])){
					if($oboof_starter !== '')
						foreach($prots['proteins'] as $i => $p){
							$starter_searchs['diseases'][$oboof_starter]['proteins'][] = $p->accession;
						}
					$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
				}
			}
		}
	}
}

?>