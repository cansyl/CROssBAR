<?php
$disease_nodes = array();
//echo $url.'/efo?omimId='.$omims_str.'&limit=1000'; die();
if(!count($omims_of_prots)){
	fwrite($report,"Could not found any OMIM id from collected proteins\n");
/*}else if(get_http_response_code($url.'/efo?omimId='.$omims_str.'&limit=1') != "200"){
	fwrite($report, "Error in disease search with OMIM ids: $omims_str\n");
}else{
*/
}else if( ($genEFOSet = fetch_data('/efo?omimId='.$omims_str.'&limit=1000')) !== false){
	# assumed not bigger than 1000
	//$genEFOSet = json_decode(file_get_contents($url.'/efo?omimId='.$omims_str.'&limit=1000'));
	foreach($genEFOSet->diseases as $disease){
		# obo_id'si CHEBI ile baslayanlar aslinda compound. Ignore ediliyor.
		if(substr($disease->obo_id, 0, 5) == 'CHEBI')
			continue;

		# OMIM ID LERI ILE ULASILAN TUM DISEASE LER NODE YAPILIYOR...
		//if (!array_key_exists($disease->obo_id,$disease_nodes)){
		if (!isset($disease_nodes[$disease->obo_id])){
			$disease_nodes[$disease->obo_id]['display_name'] = $disease->label;
			$disease_nodes[$disease->obo_id]['source'] = 'EFO';
			$disease_nodes[$disease->obo_id]['edges'] = array();
			$disease_nodes[$disease->obo_id]['omims'] = array();
		}

		# BU 3 DONGU DISEASE LER ILE PROTEIN LER ARASINDAKI EDGE LERI OLUSTURUYOR...
		if($disease->omim)
			foreach($disease->omim as $om){
				$disease_nodes[$disease->obo_id]['omims'][] = $om;
				foreach($proteinToOmim as $protein => $omims)
					foreach($omims as $omim)
						if($om === $omim){
							//$disease_nodes[$disease->obo_id]['edges'][] = $protein;
							# ustteki satiri kullaninca duplicate olusuyor.
							# crossbarda ayni disease'i donduren 2 farkli omim olabiliyor
							# ornek : https://wwwdev.ebi.ac.uk/crossbar/efo?omimId=OMIM:178500
							#		  https://wwwdev.ebi.ac.uk/crossbar/efo?omimId=OMIM:614742
							if(array_search($protein,$disease_nodes[$disease->obo_id]['edges']) === false)
								$disease_nodes[$disease->obo_id]['edges'][] = $protein;
						}
			}
	}		
}else{
	fwrite($report,"Could not found any EFO Disease with OMIM ids: $omims_str\n");
}

?>