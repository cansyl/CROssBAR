
$('#layout').change(function () {

	var layout = cy.layout({
		name: $(this).val(),
		fit: 'viewport'
	});

	if($(this).val()=='CrossBarLayout'){
		//layout.options.orderOfNodeTypes = order();
		$('#CrossBarLayout_settings').show();
		$('#changeOrderOfCrossbar').click();
		return true;
	}else
		$('#CrossBarLayout_settings').hide();
	//console.log(layout.options.fit);
	layout.run();
});

$('#style_edit').click(function(){
	var stringStylesheet = 'node[Node_Type="Disease"] { background-color: cyan; }';
	cy.style( stringStylesheet );
	
});

$('#myList a').on('click', function (e) {
  e.preventDefault()
  $(this).tab('show')
});
$('#changeOrderOfCrossbar').click(function () {
	var lsslayer;
	var orders;
	var sizing = 2 - ($('#sizeOfNodes').val() / 100);
	if($('input[name="layersize"]:checked').val() == 'less'){
		lsslayer = 1;
		orders = order();
	}else{
		orders = order_more();
		lsslayer = 0;
	}

	var layout = cy.layout({
		name: 'CrossBarLayout',
		fit: 'viewport',
		orderOfNodeTypes: orders,
		lesslayer: lsslayer,
		spacingFactor: sizing
	});

	layout.run();
});

$('#sizeOfNodes').change(function () {
	var sizing = 2 - ($('#sizeOfNodes').val() / 100);
	if($('#layout').val() == 'CrossBarLayout')
		$('#changeOrderOfCrossbar').trigger( "click" );
	else{
		var layout = cy.layout({
			name: $('#layout').val(),
			fit: 'viewport',
			spacingFactor: sizing
		});
		layout.run();
	}
		
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
	  //switch(items[i].textContent){
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
	//console.log(this.value);
	if(this.value == 'less'){
		$('#orderingComponents_more').addClass('d-none');
		$('#orderingComponents').removeClass('d-none');
	}else{
		$('#orderingComponents_more').removeClass('d-none');
		$('#orderingComponents').addClass('d-none');
	}
});
$('#add_remove_toggle').click(function() {
	if($('#component_list_container').hasClass('d-none'))
		$('#component_list_container').removeClass('d-none');
	else
		$('#component_list_container').addClass('d-none');
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
	//window.open(ss,'width=largeImage.style.width,height=largeImage.style.height,resizable=1');
	var w = window.open("");
	w.document.write('<img src="'+ss+'" height="'+height+'"/>');
});