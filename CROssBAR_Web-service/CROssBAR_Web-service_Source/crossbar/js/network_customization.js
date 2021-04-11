
$('#layout').change(function () {
	if($(this).val()=='CrossBarLayout'){
		$('#CrossBarLayout_settings').show();
		$('#changeOrderOfCrossbar').click();
		return true;
	}else{
		$('#CrossBarLayout_settings').hide();
		var layout = cy.layout({
			name: $(this).val(),
			fit: 'viewport'
		});
		layout.run();
	}
});

$('#changeOrderOfCrossbar').click(function () {
	var lsslayer;
	var orders;
	var sizing = 1;
	var separated_val = 0;
	if($('input[name="layersize"]:checked').val() == 'less'){
		lsslayer = 1;
		orders = order();
	}else{
		orders = order_more();
		lsslayer = 0;
	}

	if($('input[name="clustered"]:checked').val() == 'separated'){
		separated_val = 1;
	}

	var layout = cy.layout({
		name: 'CrossBarLayout',
		fit: 'viewport',
		orderOfNodeTypes: orders,
		lesslayer: lsslayer,
		separated: separated_val
	});

	layout.run();
});

function order(){
	var orders = new Array(4);;
	var items = $('#orderingComponents').children();
	var i;
	for (i = 0; i < 4; i++) {
	  switch(items[i].id){
		  case "Proteins":
			orders[0] = i+1;
		  break;
		  case "Pathways":
			orders[1] = i+1;
		  break;
		  case "Diseases":
			orders[2] = i+1;
		  break;
		  case "Drugs":
			orders[3] = i+1;
		  break;
	  }
	}
	return orders;
}
function order_more(){
	var orders = new Array(7);;
	var items = $('#orderingComponents_more').children();
	var i;
	for (i = 0; i < 7; i++) {
	  switch(items[i].id){
		  case "proteins":
			orders[0] = i+1;
		  break;
		  case "neighbours":
			orders[1] = i+1;
		  break;
		  case "pathways":
			orders[2] = i+1;
		  break;
		  case "hpo":
			orders[3] = i+1;
		  break;
		  case "drugs":
			orders[4] = i+1;
		  break;
		  case "diseases":
			orders[5] = i+1;
		  break;
		  case "compounds":
			orders[6] = i+1;
		  break;
	  }
	}
	return orders;
}

$('input[type=radio][name=layersize]').change(function() {
	if(this.value == 'less'){
		$('#orderingComponents_more').addClass('d-none');
		$('#orderingComponents').removeClass('d-none');
	}else{

		if($('input[name="clustered"]:checked').val() == 'separated')
			component_list = 'crossbar_7isolated_layer_default.php';
		else
			component_list = 'crossbar_7nested_layer_default.php';
		$.ajax({
			type:"POST",
			url:component_list,
			success: function(res){
				$('#orderingComponents_more').html(res);
			}
		});		
		
		$('#orderingComponents_more').removeClass('d-none');
		$('#orderingComponents').addClass('d-none');
	}
});

$('input[type=radio][name=clustered]').change(function() {
	var layersize = $('input[name="layersize"]:checked').val();
	if(layersize == 'more'){
		if(this.value == 'separated')
			component_list = 'crossbar_7isolated_layer_default.php';
		else
			component_list = 'crossbar_7nested_layer_default.php';
		$.ajax({
			type:"POST",
			url:component_list,
			success: function(res){
				$('#orderingComponents_more').html(res);
			}
		});		
	}
});

$('#add_remove_toggle').click(function() {
	if($('#component_list_container').hasClass('d-none'))
		$('#component_list_container').removeClass('d-none');
	else
		$('#component_list_container').addClass('d-none');
});

$('#network_customization').click(function() {
	if($('#network_customization_panel').hasClass('d-none'))
		$('#network_customization_panel').removeClass('d-none');
	else
		$('#network_customization_panel').addClass('d-none');
});

$('.customizeStyle').click(function(){
	btn = $(this).attr('id');
	nodeType = $('#style_nodeGroup').val();
	if(nodeType == "all")
		node_elements = cy.elements('node');
	else
		node_elements = cy.elements('node[Node_Type="'+nodeType+'"]');
	//node_elements = cy.elements('node[Node_Type="Disease"]');

	switch(btn){
		case 'nodeSize_up':
			current_width_of_nodes = node_elements[0].width();
			current_heigh_of_nodes = node_elements[0].height();
			node_elements.animate({
				style: {width: current_width_of_nodes*1.1,height:current_heigh_of_nodes*1.1}
			});
		break;
		case 'nodeSize_down':
			current_width_of_nodes = node_elements[0].width();
			current_heigh_of_nodes = node_elements[0].height();
			node_elements.animate({
				style: {width: current_width_of_nodes/1.1,height:current_heigh_of_nodes/1.1}
			});		
		break;

		case 'fontSize_up':
			//node_elements.addClass('fontEight');
			if(node_elements.hasClass('fontZero')){
				node_elements.removeClass('fontZero');
				node_elements.addClass('fontOne');
			}else if(node_elements.hasClass('fontOne')){
				node_elements.removeClass('fontOne');
				node_elements.addClass('fontTwo');
			}else if(node_elements.hasClass('fontTwo')){
				node_elements.removeClass('fontTwo');
				//node_elements.addClass('fontThree');				
			}else if(node_elements.hasClass('fontThree')){
				node_elements.removeClass('fontThree');
				node_elements.addClass('fontFour');				
			}else if(node_elements.hasClass('fontFour')){
				node_elements.removeClass('fontFour');
				node_elements.addClass('fontFive');				
			}else if(node_elements.hasClass('fontFive')){
				node_elements.removeClass('fontFive');
				node_elements.addClass('fontSix');				
			}else if(node_elements.hasClass('fontSix')){
				node_elements.removeClass('fontSix');
				node_elements.addClass('fontSeven');				
			}else if(node_elements.hasClass('fontSeven')){
				node_elements.removeClass('fontSeven');
				node_elements.addClass('fontEight');
			}else if(node_elements.hasClass('fontEight')){
				// already has the bigger size...
			}else
				node_elements.addClass('fontThree');
		break;
		case 'fontSize_down':
			if(node_elements.hasClass('fontZero')){
				// already has the min size...
			}else if(node_elements.hasClass('fontOne')){
				node_elements.removeClass('fontOne');
				node_elements.addClass('fontZero');
			}else if(node_elements.hasClass('fontTwo')){
				node_elements.removeClass('fontTwo');
				node_elements.addClass('fontOne');
			}else if(node_elements.hasClass('fontThree')){
				node_elements.removeClass('fontThree');
				//node_elements.addClass('fontTwo');				
			}else if(node_elements.hasClass('fontFour')){
				node_elements.removeClass('fontFour');
				node_elements.addClass('fontThree');				
			}else if(node_elements.hasClass('fontFive')){
				node_elements.removeClass('fontFive');
				node_elements.addClass('fontFour');				
			}else if(node_elements.hasClass('fontSix')){
				node_elements.removeClass('fontSix');
				node_elements.addClass('fontFive');				
			}else if(node_elements.hasClass('fontSeven')){
				node_elements.removeClass('fontSeven');
				node_elements.addClass('fontSix');				
			}else if(node_elements.hasClass('fontEight')){
				node_elements.removeClass('fontEight');
				node_elements.addClass('fontSeven');				
			}else
				node_elements.addClass('fontTwo');
		break;
		
		case 'left':
			node_elements.removeClass('left');
			node_elements.removeClass('right');
			node_elements.removeClass('top');
			node_elements.removeClass('bottom');
			node_elements.removeClass('center');
			node_elements.addClass('left');
		break;
		case 'right':
			node_elements.removeClass('left');
			node_elements.removeClass('right');
			node_elements.removeClass('top');
			node_elements.removeClass('bottom');
			node_elements.removeClass('center');
			node_elements.addClass('right');
		break;
		case 'top':
			node_elements.removeClass('left');
			node_elements.removeClass('right');
			node_elements.removeClass('top');
			node_elements.removeClass('bottom');
			node_elements.removeClass('center');
			node_elements.addClass('top');
		break;
		case 'bottom':
			node_elements.removeClass('left');
			node_elements.removeClass('right');
			node_elements.removeClass('top');
			node_elements.removeClass('bottom');
			node_elements.removeClass('center');
			node_elements.addClass('bottom');
		break;
		case 'center':
			node_elements.removeClass('left');
			node_elements.removeClass('right');
			node_elements.removeClass('top');
			node_elements.removeClass('bottom');
			node_elements.removeClass('center');
			node_elements.addClass('center');
		break;
	}

});

$('#makeNewSearchWithSelecteds').click(function() {
	secililer = cy.elements('node.zelected');
	numberOfSelecteds = secililer.length;
	prots = 'proteins=';
	drugs = '&drugs=';
	paths = '&pathways=';
	disea = '&diseases=';
	hpots = '&hpos=';

	for (i = 0; i < numberOfSelecteds; i++) {
	  switch(secililer[i].data().Node_Type){
		  case 'Protein':
			prots += secililer[i].data().display_name + " | ";
		  break;
		  
		  case 'Protein_N':
			prots += secililer[i].data().display_name + " | ";
		  break;
		  
		  case 'Drug':
		    drugs += secililer[i].data().display_name + " | ";
		  break;
		  case 'Compound':
		    drugs += secililer[i].data().display_name + " | ";
		  break;
		  case 'Prediction':
		    drugs += secililer[i].data().display_name + " | ";
		  break;
		  
		  case 'Pathway':
			paths += secililer[i].data().display_name + " | ";
		  break;
		  
		  case 'Disease':
			disea += secililer[i].data().display_name + " | ";
		  break;
		  case 'kegg_Disease':
			disea += secililer[i].data().display_name + " | ";
		  break;
		  
		  case 'HPO':
			hpots += secililer[i].data().display_name + " | ";
		  break;
	  }
	}
	query = prots+drugs+paths+disea+hpots;

	data.then(function(result) {
		if(result.options.fn) // first neighbours active
			query += "&first_neighbours=on";
		if(result.options.reviewed_filter)
			query += "&only_reviewed=on";
		if(result.options.chembl_compounds)
			query += "&chembl_compounds=on";
		if(result.options.predictions)
			query += "&predictions=on";
		//console.log(result.options.tax_ids);

		total_tax_ids = result.options.tax_ids.length;
		for(i=0; i<total_tax_ids; i++){
			//console.log(result.options.tax_ids[i]);
			query += "&tax_ids_multi[]=" + result.options.tax_ids[i];
		}

		query += '&num_of_compounds=' + result.options.num_of_compounds;
		query += '&num_of_diseases=' + result.options.num_of_diseases;
		query += '&num_of_drugs=' + result.options.num_of_drugs;
		query += '&num_of_fn_nodes=' + result.options.num_of_fn_nodes;
		query += '&num_of_pathways=' + result.options.num_of_pathways;
		query += '&num_of_phenotypes=' + result.options.num_of_phenotypes;

		$.ajax({
			type:"POST",
			url:'query_to_file.php',
			data: {query:query},
			success: function(res){
				window.open('index.php?autostart='+res, '_blank');
			}
		});

	});

});

$('#img_export').click(function() {
	var full = $('input[name=img_part]:checked').val();
	var scale = $('input[name=img_resolition]:checked').val();
	if(full == "true")
		if(scale == "1")
			var ss = cy.png({'bg':'white','scale':1,'full':true});
		else
			var ss = cy.png({'bg':'white','scale':2,'full':true});
	else
		if(scale == "1")
			var ss = cy.png({'bg':'white','scale':1,'full':false});
		else
			var ss = cy.png({'bg':'white','scale':2,'full':false});

	var height = window.top.innerHeight - 20;
	var w = window.open("");
	w.document.write('<img src="'+ss+'" height="'+height+'"/>');
});


$('#legend_on_off_btn').click(function() {
	if($('#legend_on_off_btn').hasClass('btn-secondary')){
		$('#canvas_bg').hide();
		$('#legend_on_off_btn').removeClass('btn-secondary');
	}else{
		$('#canvas_bg').show();
		$('#legend_on_off_btn').addClass('btn-secondary');
	}
});
