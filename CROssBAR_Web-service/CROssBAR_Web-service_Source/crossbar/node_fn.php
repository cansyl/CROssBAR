<?php
$fn_str 	= implode(',',array_keys($proteins_fn));
//$all_acc	= array_merge($main_accessions,$fn_all);
$fn_nodes	= array(); # node yapilacak fn'lerin tutulacagi array

$crossbar_proteins = array();
//fwrite($report, 'Number of first neighbours: '.count($fn_all)."\n");
# user num_of_nodes parametresine maksimum 50 girebiliyor.
# bunun 2 katini aliyoruz crossbar'dan. maksimum 100 protein cekmekte sikinti yok.
$proteinEntities = fetch_data('/proteins?limit='.count($proteins_fn).'&accession='.$fn_str.'&taxId='.$tax_ids_str);
//$proteinEntities = json_decode(file_get_contents($url.'/proteins?limit='.count($proteins_fn).'&accession='.$fn_str.'&taxId='.$tax_ids_str));
//var_dump($proteinEntities); die();
if($proteinEntities !== false){
	foreach($proteinEntities->proteins as $p){
		# proteinin gene adı yoksa siliyorum. emin degilim sormak gerekebilir bu kısmı.
		if(count($p->genes)){
			$crossbar_proteins[$p->accession]['display_name'] = $p->genes[0];
			$crossbar_proteins[$p->accession]['pathways'] 	  = $p->crossreferences->reactome;
			$crossbar_proteins[$p->accession]['omims'] 		  = $p->crossreferences->omim;
			$crossbar_proteins[$p->accession]['tax_id'] 	  = $p->tax_id;
		}
	}

	//unset($proteinEntities); # memory rahatlatiliyor.
	//var_dump($crossbar_proteins); die();

	$counter	= 0;
	foreach($proteins_fn as $id => $p){
		# tax_id filtresinden kalanlara engel olmak icin istenilen node sayisinin 2 kati fn cektik
		# islenen fn sayisi num_of_nodes'a ulastiginda duralim...
		if($counter >= $num_of_nodes)
			break;
		if(isset($crossbar_proteins[$id])){
			$counter++;
			# protein processing...
			$gene 							= $crossbar_proteins[$id]['display_name'];
			$fn_nodes[$id]['display_name']  = $gene;
			$fn_nodes[$id]['Node_Type'] 	= 'Protein_N';
			$taxx_id						= $crossbar_proteins[$id]['tax_id'];
			$fn_nodes[$id]['tax_id'] 		= $taxx_id;
			$fn_nodes[$id]['proteins'] 		= $proteins_fn[$id]['proteins'];
			$fn_nodes[$id]['enrichScore'] 	= $proteins_fn[$id]['enrichScore'];

			# eger PPI'larinda main varsa, bu bilgi main proteins array'ine eklenmeli
			# rapor olustururken ordan aliyorum protein id degerini
			foreach($proteins_fn[$id]['proteins'] as $interactofthis){
				if(isset($proteins_main[$interactofthis]))
					$proteins_main[$interactofthis]['proteins'][] = $id;
			}

			# HPOS for first neighbours
			# HUMAN (9606) olmayan HPO'lar eklenmiyor.
			# $hpo_nodes // tüm hpo nodelarının tutulduğu array
			if($taxx_id === 9606){
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

			# FN proteinler ile pathway lerin bulundugu dongu
			foreach($crossbar_proteins[$id]['pathways'] as $react){
				# bulunan pathway ler node yapilmak uzere saklaniyor...
				if (!isset($pathway_nodes[$react->id])){
					$pathway_nodes[$react->id]['display_name'] = $react->pathwayName;
				}
				# burda toplanan accesion degerleriyle pathway arasinda edge olusturulacak.
				$pathway_nodes[$react->id]['edges'][] = $id;
			}

			# Collecting OMIM ids related to main protein set
			/*
			# bir onceki stepte bu array'in adi omims_of_mains idi,
			# toplanan tum omimlerle tek bir sorgu yapilacagi icin birlestiriliyor
			$omims_of_prots		= explode(','$remains['tonext']['omims']);
			$proteinToOmim 		= $remains['tonext']['mainomims'];
			*/
			#$crossbar_proteins[$p->accession]['omims']
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
	unset($proteins_fn);

	foreach($proteinEntities->proteins as $p){
		if(  isset($fn_nodes[$p->accession]) )
			foreach($p->interactions as $interaction){
				if(  isset($fn_nodes[$interaction->id]) )
					$fn_nodes[$p->accession]['proteins'][] = $interaction->id;
			}
	}
	unset($proteinEntities); # memory rahatlatiliyor.

	# duplicate interaction values are removing
	# ask why this is happening
	foreach($fn_nodes as $id => $proteins)
		$fn_nodes[$id]['proteins'] = array_unique($fn_nodes[$id]['proteins']);

	/*
	print_r($fn_nodes);
	print_r($proteins_main);
	print_r($hpo_nodes);
	print_r($pathway_nodes);
	print_r($omims_of_prots);
	print_r($proteinToOmim);
	die();
	*/

}else
	fwrite($report,"Could not found any neighbour protein!\n");

?>