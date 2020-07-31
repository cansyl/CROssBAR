<?php
$diseases_str = implode(',',$diseases); # lokalden alıcam artık bu değerleri.
#$diseases_str = implode(',',$starter_searchs['diseases']); # lokal'den alınan disease nameleri
														   # ile arama yapılacak CROssBAR'da.

if(count($kegg_starter_diseases)){
	$kegg_starter_diseases_str = implode(',',$kegg_starter_diseases);
	# kegg drugs ekleniyor burda
	fwrite($report, "\nQuery terms: $kegg_starter_diseases_str (kegg disease)\n");
	$a = microtime(true);
	include('node_kegg_starter_disease_drugs.php');
	$aa = microtime(true);
	$aaa = $aa - $a;
	fwrite($report,'KEGG disease operations takes '.$aaa." seconds.\n\n");
	$starter_searchs['kegg_diseases'] = $kegg_starter_diseases;
}else
	fwrite($report,'No disease found in KEGG database with '.$diseases_str."\n\n");
# echo count($kegg_starter_diseases); # üst satırda include ettiğim dosyadan gelen kegg_starter_diseases

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
	#fwrite($report, "\nOMIM DISEASE(s) to be searched: $diseases_str\n\n");
	fwrite($report, "\nQuery terms: $diseases_str (disease)\n");
	foreach($diseases as $disease){
		$omimIds = array();
		$oboof_starter = '';
		$disease_entities = fetch_data('/efo?limit=1000&synonym='.urlencode($disease));
		if($disease_entities === false)
			$disease_entities = fetch_data('/efo?label='.urlencode($disease));
		if($disease_entities !== false)
		{
			foreach($disease_entities->diseases as $d){
				#if(array_search($d->label,$diseases) !== false){
				if($d->label == $disease){
					# arama adiyla bulunan disease starter olarak kaydediliyor.
					# bu node diseases eklenirken kontrol edilecek, network'e girmediyse force edilip eklenecek.
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
		}else
			fwrite($report, "\nCould not found $disease (disease) in crossbar database.\n\n");
		unset($disease_entities); # memory rahatlatiliyor.

		if(!count($omimIds)){
			fwrite($report, "\nThere is no OMIM record related to $disease in crossbar database.\n\n");
		}else{
			$omimIds = array_unique($omimIds);
			$omimIds_str = implode(',',$omimIds);
			fwrite($report, "OMIM ids collected from $disease (EFO): $omimIds_str\n");

			# CROssBAR protein collection to be processed.
			if( ($prots = fetch_data('/proteins?limit=100&omim='.$omimIds_str)) !== false){
				$prots = (array)$prots;
				
				if(isset($prots['proteins'])){
					if($oboof_starter !== '')
						foreach($prots['proteins'] as $i => $p){
							$starter_searchs['diseases'][$oboof_starter]['proteins'][] = $p->accession;
						}
					$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
				}else
					fwrite($report, "\nError occured while fetching proteins with OMIM ids: $omimIds_str\n".'/proteins?limit=100&omim='.$omimIds_str."\n\n");
			}
		}
	}
}

?>