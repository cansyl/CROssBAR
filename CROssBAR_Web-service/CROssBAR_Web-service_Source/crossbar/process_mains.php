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
		fwrite($report, 'Isoform : '.$p->accession ." converted to $izo[0] in main accesions.\n");
		$p->accession = $izo[0];
	}

	# reviewed filtresi var ise uygulaniyor
	if($search_parameters['options']['reviewed_filter'] == 1){
		//if(array_search($p->accession,$revieweds) === false){
		if(! reviewed_check($p->accession,$revieweds,'main accessions',$report)){
			//fwrite($report, $p->accession ." deleted from main accessions since it is unreviewed\n");
			unset($crossbar_proteins[$i]);
			continue;
		}
	}

	# protein daha once islenmisse tekrar isleme, duplicate control
	//if(array_search($p->accession,$main_uniprots) !== false){
	if(isset($proteins_main[$p->accession])){
		unset($crossbar_proteins[$i]);
		continue;
	}

	# proteinin gene adı yoksa siliyorum. emin degilim sormak gerekebilir bu kısmı.
	if(!count($p->genes)){
		fwrite($report, $p->accession ." deleted from main accessions since it doesn't have gene name\n");
		unset($crossbar_proteins[$i]);
		continue;
	}

	//$main_uniprots[] = $p->accession;
	# protein işleniyor...
	$proteins_main[$p->accession]['display_name'] = $p->genes[0];
	$proteins_main[$p->accession]['Node_Type'] = 'Protein';
	$proteins_main[$p->accession]['tax_id'] = $p->tax_id;
	$proteins_main[$p->accession]['proteins'] = array();

}

foreach($crossbar_proteins as $p){
	# MAIN PROTEINS ARE PROCESSING

	# first neighbours collecting
	# and also edges between main proteins are defining
	//if($fn_filter)
	foreach($p->interactions as $interaction){
		# echo $interaction->id;
		# interaction id'si varsa ve kendisi degilse islem yapilsin.
		if($interaction->id != '' and $interaction->id != $p->accession){
			# main accession değilse ve fn filter aktif ise first neighbours array ine ekleniyor
			# main accession ise edge bilgisi ekleniyor
			//if( array_search($interaction->id, $main_uniprots) === false){
			if(!isset($proteins_main[$interaction->id]) and $fn_filter){
				# echo $interaction->id;
				# eğer $interaction->id first neighbour proteini izoform ise...
				$izo = explode('-',$interaction->id);
				if(count($izo)>1){
					# izoform bulundu
					# main proteinlerde yok ise dönüştürülüp first neighbours a ekleniyor.
					# kanonik hali main protein ise first neighbours a eklenmiyor.
					# kanonik hali kendisine eşit değilse main protein edge bilgisi ekleniyor.
					//if(array_search($izo[0],$main_uniprots) === false){
					if(!isset($proteins_main[$izo[0]])){
						fwrite($report, 'Isoform : '.$interaction->id ." converted to $izo[0] in first neighbours.\n");
						# canonic version of isoform adding to first neighbours
						# reviewed filtresi var ise uygulaniyor
						//if(isset($_POST['only_reviewed'])){
						if($search_parameters['options']['reviewed_filter'] == 1){
							if(! reviewed_check($izo[0],$revieweds,'first neighbours',$report))
								continue;
						}
						# isoform reviewed ise ve main protein degilse, fn yapiliyor.
						# fn daha once eklendiyse sadece edge ekleniyor
						# fn array'i icinde yoksa tanimlaniyor
						if(!isset($proteins_fn[$izo[0]])){
							$proteins_fn[$izo[0]] = array();
						}
						$proteins_fn[$izo[0]][] = $p->accession;
						
					}else{
						# izoformun kanonik hali main protein oldugu icin main PPI eklendi
						fwrite($report, 'Isoform : '.$interaction->id ." deleted from first neighbours since canonical version exist in main accessions.\n");
						if($p->accession != $izo[0])
							$proteins_main[$p->accession]['proteins'][] = $izo[0];
					}
				}else{
					//if(isset($_POST['only_reviewed'])){
					if($search_parameters['options']['reviewed_filter'] == 1){
						if(! reviewed_check($interaction->id,$revieweds,'first neighbours',$report))
							continue;
					}
					# protein adding to first neighbours
					//$extracted_fn[] = $interaction->id;
					if(!isset($proteins_fn[$interaction->id])){
						#echo $interaction->id . ':';
						$proteins_fn[$interaction->id] = array();
					}
					$proteins_fn[$interaction->id][] = $p->accession;
				}
			//}else if(!$fn_filter){
			}else if(!isset($proteins_main[$interaction->id]) and !$fn_filter){
				# main degil ve fn filter aktif degilse izoformlari kacirmayalim.
				$izo = explode('-',$interaction->id);
				if(count($izo)>1){
					if(isset($proteins_main[$izo[0]]) and $p->accession != $izo[0]){
						$proteins_main[$p->accession]['proteins'][] = $izo[0];
					}
				}
			}else
				$proteins_main[$p->accession]['proteins'][] = $interaction->id;
		}else if($interaction->id == $p->accession){
			$proteins_main[$p->accession]['proteins'][] = $p->accession;
		}
	}
	# // first neighbours collecting DONE

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

	/*	$omims_of_mains  = array();   # SAYFANIN BAŞINDA DEFINE EDİLEN ARRAY LER
		$main_to_omim = array(); */
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

# duplicate interaction values are removing
# ask why this is happening
foreach($proteins_main as $id => $proteins)
	$proteins_main[$id]['proteins'] = array_unique($proteins_main[$id]['proteins']);

//var_dump($proteins_main); die();

# removing duplicate edges between main accesions
/*
foreach($proteins_main as $acc_id => $cleanup){
	foreach($cleanup['proteins'] as $k => $p){
		if(($delete_key = array_search($acc_id,$proteins_main[$p]['proteins'])) !== false ){
				unset($proteins_main[$acc_id]['proteins'][$k]);
		}
	}
}
*/
/*
print_r($proteins_main);
print_r($extracted_fn);
print_r($omims_of_mains);
print_r($main_to_omim);
*/

# reviewed filtresi var ise uygulaniyor
/*
if($fn_filter)
	if($search_parameters['options']['reviewed_filter'] == 1){
		foreach($extracted_fn as $k => $fn){
			if(array_search($fn,$revieweds) === false){
				fwrite($report, $fn ." deleted from first neighbours since it is unreviewed\n");
				unset($extracted_fn[$k]);
			}
		}
	}
*/

$fn = array(); # firstNeighbour'ların enrichScore'a gore azaltilmis halinin tutuldugu array

if($fn_filter){
	# fn aktif ise enrichment yapip fn'leri azalt	
	$ppi_M = $db->table('ppi_enrichment',PDO::FETCH_KEY_PAIR);
	$n = count($proteins_main);
	$fn_enrich_scores = array();
	foreach($proteins_fn as $key => $arr)
		# nedense duplicate degerler geliyor. duzeltildi.
		# bi ara bak niye?
		/*
		# niye olmadi ki?
		$uniq = array_unique($proteins_fn[$key]);
		$proteins_fn[$key] = $uniq;
		*/
		if(isset($ppi_M[$key]) and $ppi_M[$key] != 0){
			//$m = count($uniq);
			$m = count(array_unique($arr));
			$fn_enrich_scores[] = array('id'=>$key,'enrichScore'=>($m*$m/$n) / ($ppi_M[$key] / 14078));
		}else{
			$fn_enrich_scores[] = array('id'=>$key,'enrichScore'=>-1);
		}
	unset($ppi_M);
	usort($fn_enrich_scores, 'sortByEnSc');

	fwrite($report,"\nFirst-neighbour proteins enrichment scores:\n");
	$co = 0;
	foreach($fn_enrich_scores as $p){
		if($co>99) break;
		fwrite($report,"$p[id] : $p[enrichScore]\n");
		$co++;
	}
	fwrite($report,"\n");

	#tax_id filtresinden elenenler olabilecegi icin, istenilen node sayisinin 2 kati alindi
	$limit = $num_of_nodes*2;
	# user defined first n*2 protein taking
	for($i=0; $i<$limit; $i++){
		# checking if exist protein with this index
		# yani user 20 tanimladiyse ve 10 protein varsa avoid edelim.
		if(isset($fn_enrich_scores[$i])){
			//$fn[] = $proteins_fn[$fn_enrich_scores[$i]['id']];
			$fn[$fn_enrich_scores[$i]['id']] = array('proteins'=>$proteins_fn[$fn_enrich_scores[$i]['id']],'enrichScore'=>round($fn_enrich_scores[$i]['enrichScore']));
		}else
			break;
	}
	
	unset($proteins_fn);
	unset($fn_enrich_scores);
}



/*
$results = 'results.txt';
$r = fopen('datas/'.$results, "w");
fwrite($r,print_r($proteins_main) );
fwrite($r,print_r($extracted_fn) );
fwrite($r,print_r($omims_of_mains) );
fwrite($r,print_r($main_to_protein) );
*/
?>