<?php

foreach($crossbar_proteins as $i => $p){

	# tax_id filtering
	if( array_search($p->tax_id,$tax_ids) === false ){
		unset($crossbar_proteins[$i]);
		fwrite($report,$p->accession .' deleted since tax_id: '.$p->tax_id ."\n");
		continue;
	}

	$izo = explode('-',$p->accession);
	if(count($izo)>1){
		fwrite($report, 'Isoform : '.$p->accession ." converted to $izo[0] in the list of core proteins.\n");
		$p->accession = $izo[0];
	}

	# reviewed filter applying if selected by user
	if($search_parameters['options']['reviewed_filter'] == 1){
		if(! reviewed_check($p->accession,$revieweds,'main accessions',$report)){
			unset($crossbar_proteins[$i]);
			continue;
		}
	}

	# duplicate control, delete if protein already processed
	if(isset($proteins_main[$p->accession])){
		unset($crossbar_proteins[$i]);
		continue;
	}

	# if there is not exist gene name of protein, we are deleting
	if(!count($p->genes)){
		unset($crossbar_proteins[$i]);
		continue;
	}

	# protein processing...
	$proteins_main[$p->accession]['display_name'] = $p->genes[0];
	$proteins_main[$p->accession]['Node_Type'] = 'Protein';
	$proteins_main[$p->accession]['tax_id'] = $p->tax_id;
	$proteins_main[$p->accession]['proteins'] = array();

}

foreach($crossbar_proteins as $p){
	# MAIN PROTEINS ARE PROCESSING

	# HPO nodes for main accessions collecting
	# local hpo entryleri...
	# HUMAN (9606) olmayan HPO'lar eklenmiyor.
	# $hpo_nodes // tüm hpo nodelarının tutulduğu array
	if($p->tax_id === 9606){
		$hpo_res = $db->tableWhere('hpoterms',array('gene'=>$p->genes[0]));
		foreach($hpo_res as $hpo){
			if (!isset($hpo_nodes[$hpo['id']])){
				$hpo_disp = $db->tableWhere('hpo_term_names',array('hpo_id'=>$hpo['id']));
				if(count($hpo_disp) === 1)
					$hpo_nodes[$hpo['id']]['display_name'] = $hpo_disp[0]['term_name'];
				else
					$hpo_nodes[$hpo['id']]['display_name'] = $hpo['id'];
			}
			$hpo_nodes[$hpo['id']]['edges'][] = $p->accession;
		}
	}

	# main proteinler ile pathway lerin bulundugu dongu
	foreach($p->crossreferences->reactome as $react){
		# bulunan pathway ler node yapilmak uzere saklaniyor...
		if (!isset($pathway_nodes[$react->id])){
			$pathway_nodes[$react->id]['display_name'] = $react->pathwayName;
		}
		# burda toplanan accesion degerleriyle pathway arasinda edge olusturulacak.
		$pathway_nodes[$react->id]['edges'][] = $p->accession;
	}

	# Collecting OMIM ids related to main protein set
	foreach($p->crossreferences->omim as $om){
		if($om->type == 'phenotype'){
			$omims_of_mains[] = 'OMIM:'.$om->_id;
			# protein - omimId relations depolaniyor...
			$main_to_omim[$p->accession][] = 'OMIM:'.$om->_id;
		}
	}
}
unset($crossbar_proteins); # bununla isimiz bitti, memory'yi rahatlat

if(!count($proteins_main))
	die('Protein Error');

?>