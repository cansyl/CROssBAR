<?php
$kegg_pathways	 		 = array();
$kegg_accessions 		 = array();

foreach($kegg_starter_pathways as $id => $name){
	# Collecting accessions (uniprotids) from KEGG Pathways
	$kegg_acc = $db->tableWhere('kegg_pathway_protein',array('kegg_pathwayid'=>$id));
	foreach($kegg_acc as $acc){
		$kegg_accessions[] = $acc['uniprotid'];
	}
}

# KEGG'den toplanan accession'ları aramayı hızlandırmak için temizliyoruz.
# bu kontrol ayrıca crossbar protein collection'dan çekilen veride de yapılıyor.
if($search_parameters['options']['reviewed_filter'] == 1){
	foreach($kegg_accessions as $i => $acc){
		if(! reviewed_check($acc,$revieweds,'main KEGG accessions',$report))
			unset($kegg_accessions[$i]);
	}
}

if(count($kegg_accessions)){
	fwrite($report, "\nCollected accessions from KEGG pathway search: \n".implode(',',$kegg_accessions)."\n");
	$kegg_accessions_str = implode(',',$kegg_accessions);
	#$prots = (array)json_decode(file_get_contents($url.'/proteins?limit=1000&accession='.$kegg_accessions_str));
	$prots = (array)fetch_data('/proteins?limit=1000&accession='.$kegg_accessions_str);
	if(isset($prots['proteins']))
		$crossbar_proteins = array_merge($crossbar_proteins, $prots['proteins']);
	else
		fwrite($report, "\nError occured while fetching proteins with KEGG accessions : $kegg_accessions_str\n".'/proteins?limit=1000&accession='.$kegg_accessions_str."\n\n");
}else
	fwrite($report, "\nCould not found any accession in KEGG database related to pathway\n");

?>