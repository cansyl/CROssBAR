<?php
$disease_nodes = array();
if(!count($omims_of_prots)){
	#Could not found any OMIM id from collected proteins
}else if( ($genEFOSet = fetch_data('/efo?omimId='.$omims_str.'&limit=1000')) !== false){
	# assumed not bigger than 1000

	foreach($genEFOSet->diseases as $disease){
		# if obo_id starts with CHEBI, we are ignoring since it is compound
		if(substr($disease->obo_id, 0, 5) == 'CHEBI')
			continue;

		# making nodes all diseases which fetched by OMIM ID(s)
		//if (!array_key_exists($disease->obo_id,$disease_nodes)){
		if (!isset($disease_nodes[$disease->obo_id])){
			$disease_nodes[$disease->obo_id]['display_name'] = $disease->label;
			$disease_nodes[$disease->obo_id]['source'] = 'EFO';
			$disease_nodes[$disease->obo_id]['edges'] = array();
			$disease_nodes[$disease->obo_id]['omims'] = array();
		}

		# creating edges between diseases-proteins with nested 3 loops below
		if($disease->omim)
			foreach($disease->omim as $om){
				$disease_nodes[$disease->obo_id]['omims'][] = $om;
				foreach($proteinToOmim as $protein => $omims)
					foreach($omims as $omim)
						if($om === $omim){
							//$disease_nodes[$disease->obo_id]['edges'][] = $protein;
							# duplicate records occur if use the line above
							# api could return same disease with 2 different omim IDs
							# example: https://wwwdev.ebi.ac.uk/crossbar/efo?omimId=OMIM:178500
							#		   https://wwwdev.ebi.ac.uk/crossbar/efo?omimId=OMIM:614742
							if(array_search($protein,$disease_nodes[$disease->obo_id]['edges']) === false)
								$disease_nodes[$disease->obo_id]['edges'][] = $protein;
						}
			}
	}		
}else{
	#Could not found any EFO Disease with OMIM ids: $omims_str
}

?>