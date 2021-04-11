<?php

$fn_str 	= implode('|',array_keys($fn));

$crossbar_proteins = array();
# user can define num_of_nodes parameter as 100 max.
$proteinEntities = fetch_data('/proteins?limit='.count($fn).'&accession='.$fn_str);

if($proteinEntities !== false){
	foreach($proteinEntities->proteins as $p){
		$crossbar_proteins[$p->accession]['pathways'] 	  = $p->crossreferences->reactome;
		$crossbar_proteins[$p->accession]['omims'] 		  = $p->crossreferences->omim;
		$crossbar_proteins[$p->accession]['tax_id'] 	  = $p->tax_id;
	}

	$counter	= 0;
	foreach($fn as $id => $p){
		if(isset($crossbar_proteins[$id])){
			$counter++;
			# protein processing...
			$taxx_id				= $crossbar_proteins[$id]['tax_id'];
			$fn[$id]['tax_id'] 		= $taxx_id;

			# HPOS for first neighbours
			# don't need to add HPOs of proteins which does not match HUMAN (9606)
			# $hpo_nodes // the array which keeps all HPO nodes
			$gene = $fn[$id]['display_name'];
			if($taxx_id === 9606 and $gene != ''){
				$hpo_res = $db->tableWhere('hpoterms',array('gene'=>$gene));
				foreach($hpo_res as $hpo){
					if (!isset($hpo_nodes[$hpo['id']])){
						$hpo_disp = $db->tableWhere('hpo_term_names',array('hpo_id'=>$hpo['id']));
						if(count($hpo_disp) === 1)
							$hpo_nodes[$hpo['id']]['display_name'] = $hpo_disp[0]['term_name'];
						else
							$hpo_nodes[$hpo['id']]['display_name'] = $hpo['id'];
					}
					$hpo_nodes[$hpo['id']]['edges'][] = $id;
				}
			}

			# the loop for finding pathways via neighbouring proteins (FNs)
			foreach($crossbar_proteins[$id]['pathways'] as $react){
				# saving pathways for to make node at next steps
				if (!isset($pathway_nodes[$react->id])){
					$pathway_nodes[$react->id]['display_name'] = $react->pathwayName;
				}
				# we will create edges between proteins-pathways
				$pathway_nodes[$react->id]['edges'][] = $id;
			}

			# Collecting OMIM ids related to main protein set
			foreach($crossbar_proteins[$id]['omims'] as $om){
				if($om->type == 'phenotype'){
					$omims_of_prots[] = 'OMIM:'.$om->_id;
					# protein - omimId relations depolaniyor...
					$proteinToOmim[$id][] = 'OMIM:'.$om->_id;
				}
			}
		}
	}
	unset($crossbar_proteins);
	//unset($fn);
	unset($proteinEntities); # memory rahatlatiliyor.

}

?>