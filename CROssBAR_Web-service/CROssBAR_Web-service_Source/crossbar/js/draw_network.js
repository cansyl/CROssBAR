var network_file = document.getElementById("graph_starter").getAttribute("data-attr");
data = fetch(network_file+'.json', {mode: 'no-cors'}).then(function(res){return res.json()}).then(function(data){
	return data;
});

my_style = fetch('css/css.json', {mode: 'no-cors'}).then(function(res) { return res.json()}).then(function(style){
	return style;
});

var cy = window.cy = cytoscape({
	container: $('#network'),
	style: my_style,
	elements: data,
	layout: {
		name: 'CrossBarLayout',
		fit: 'viewport',
		//separated: 1,
		//lesslayer: 0,
		orderOfNodeTypes: [1,2,3,5,6,4,7]
		//orderOfNodeTypes: [3,1,2,4,5,6,7]
	}
});

data.then(function(result) {
	$('#search_parameters').append( '<li class="list-group-item"><b>Query Terms:</b></br>');
	result.search.forEach((s) => {
		for (var p in s) {
			if( p == 'Disease' ||  p == 'KEGG Disease' || p == 'KEGG Pathway' || p == 'Pathway' || p == 'HPO' || p == 'Compound'){
				for (var x in s[p]) {
					apply_high(s[p][x], network_file, p);
					var l;
					switch(p){
						case 'Disease':
							l = 'Disease(s) - EFO';
						break;
						case 'KEGG Disease':
							l = 'Disease(s) - KEGG';
						break;
						case 'Pathway':
							l = 'Pathway(s) - Reactome';
						break;
						case 'KEGG Pathway':
							l = 'Pathway(s) - KEGG';
						break;
						case 'HPO':
							l = 'Phenotype(s)';
						break;
						case 'Compound':
							l = 'Compound(s)';
						break;
					}
					$('#search_parameters').append( '<span class="pl-4 font-italic">' +l+': '+s[p][x]+'</span>' );
				}
			}else if( p == 'Drug' ){
				l = 'Drug(s)';
				for (var x in s[p])
					for (var o in s[p][x]){
						apply_high(s[p][x][o], network_file, p);
						$('#search_parameters').append( '<span class="pl-4 font-italic">' +l+': '+s[p][x][o]+'</span>' );
					}
			}else{
				l = 'Gene(s)/Protein(s)';
				$('#search_parameters').append( '<span class="pl-4 font-italic">' +l+': '+s[p]+'</span>' );
				if(p != 'Protein'){
					apply_high(s[p], network_file, p);
				}else{
					var accs = s[p].split(",");
					for (var a in accs){
						apply_high(accs[a], network_file, p);
					}
				}
			}
		}
	});
	$('#search_parameters').append('</li>');

	var check = 0;
	if(result.options.num_of_fn_nodes == result.options.num_of_pathways)
		if(result.options.num_of_fn_nodes == result.options.num_of_phenotypes)
			if(result.options.num_of_fn_nodes == result.options.num_of_drugs)
				if(result.options.num_of_fn_nodes == result.options.num_of_diseases)
					if(result.options.num_of_fn_nodes == result.options.num_of_compounds)
						check = 1;

	if(check)
		$('#search_parameters').append( '<li class="list-group-item">Number of Nodes: '+result.options.num_of_fn_nodes+'</li>' );
	else{
		$('#search_parameters').append( '<li class="list-group-item"><b>Number of Nodes:</b></br>'+
		
		'<span class="pl-4 font-italic">Neighbouring genes/proteins: ' +result.options.num_of_fn_nodes+ '</span></br>'+
		'<span class="pl-4 font-italic">Pathways: ' +result.options.num_of_pathways+ '</span></br>'+
		'<span class="pl-4 font-italic">Diseases: ' +result.options.num_of_diseases+ '</span></br>'+
		'<span class="pl-4 font-italic">Phenotypes: ' +result.options.num_of_phenotypes+ '</span></br>'+
		'<span class="pl-4 font-italic">Drugs: ' +result.options.num_of_drugs+ '</span></br>'+
		'<span class="pl-4 font-italic">Compounds: ' +result.options.num_of_compounds+ '</span></br>'
		
		+'</li>');
	}

	var fn_stat = 'no';
	if(result.options.fn)
		fn_stat = 'yes';
	$('#search_parameters').append( '<li class="list-group-item">Include interacting proteins: '+fn_stat+'</li>' );
	var rw = 'no';
	if(result.options.reviewed_filter)
		rw = 'yes';
	$('#search_parameters').append( '<li class="list-group-item">Only reviewed genes/proteins: '+rw+'</li>' );

	var cc = 'no';
	if(result.options.chembl_compounds)
		cc = 'yes';
	$('#search_parameters').append( '<li class="list-group-item">Include ChEMBL compounds: '+cc+'</li>' );
	
	var pc = 'no';
	if(result.options.predictions)
		pc = 'yes';
	$('#search_parameters').append( '<li class="list-group-item">Include predicted compounds: '+pc+'</li>' );
	
	separator = ',';
	$('#search_parameters').append( '<li class="list-group-item">Included tax Id(s): '+result.options.tax_ids.join(separator)+'</li>' );
	$('#search_parameters').append( '<li class="list-group-item"><i>This query was processed in <b>'+result.options.search_runtime+'</b> seconds</i></li>' );
	
	
});
