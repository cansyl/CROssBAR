
// for removing/adding nodes and edges
var removed_nodes = {};
var removed_edges = {};

var hpodis;
var hpodis_count = 0;
var kegg_dis_drug;
var kegg_dis_drug_count = 0;
var kegg_dis_path;
var kegg_dis_path_count = 0;

$('.removeNodes').click(function(){

	if(!hpodis_count++)
		hpodis = cy.elements('edge[Edge_Type="hpodis"]');

	if(!kegg_dis_drug_count++)
		kegg_dis_drug = cy.elements('edge[Edge_Type="kegg_dis_drug"]');

	if(!kegg_dis_path_count++)
		kegg_dis_path = cy.elements('edge[Edge_Type="kegg_dis_path"]');

	//if($(this).hasClass("btn-primary")){
	if(!$(this).hasClass("list-group-item-secondary")){
		switch($(this).attr('id')){
			case 'Disease':
				removed_edges['Disease'] = cy.elements('edge[Edge_Type="Disease"]');
			break;
			case 'kegg_Disease':
				removed_edges['kegg_dis_prot'] = cy.elements('edge[Edge_Type="kegg_dis_prot"]');
			break;
			case 'HPO':
				removed_edges['HPO'] = cy.elements('edge[Edge_Type="HPO"]');
			break;
			case 'Pathway':
				removed_edges['Pathway'] = cy.elements('edge[Edge_Type="Pathway"]');
			break;
			case 'kegg_Pathway':
				removed_edges['kegg_path_prot'] = cy.elements('edge[Edge_Type="kegg_path_prot"]');
			break;
			case 'Drug':
				removed_edges['Drug'] 		   = cy.elements('edge[Edge_Type="Drug"]');
				removed_edges['drugChembl'] = cy.elements('edge[Edge_Type="drugChembl"]');
				removed_edges['drugPrediction'] = cy.elements('edge[Edge_Type="drugPrediction"]');
			break;
			case 'Compound':
				removed_edges['Chembl'] = cy.elements('edge[Edge_Type="Chembl"]');
				removed_edges['compoundPrediction'] = cy.elements('edge[Edge_Type="compoundPrediction"]');
			break;
			case 'Prediction':
				removed_edges['Prediction'] = cy.elements('edge[Edge_Type="Prediction"]');
				removed_edges['predictionChembl'] = cy.elements('edge[Edge_Type="predictionChembl"]');
			break;
		}

		removed_nodes[$(this).attr('id')] = cy.elements('node[Node_Type="'+$(this).attr('id')+'"]');
		cy.remove( removed_nodes[$(this).attr('id')] );

		$(this).addClass("list-group-item-secondary");
	}else{

		cy.add( removed_nodes[$(this).attr('id')] );

		switch($(this).attr('id')){
			case 'Disease':
				cy.add( removed_edges['Disease'] );
				if(!$('#HPO').hasClass("list-group-item-secondary")){
					cy.add( hpodis );
				}
			break;
			case 'kegg_Disease':
				cy.add( removed_edges['kegg_dis_prot'] );
				if(!$('#Drug').hasClass("list-group-item-secondary"))
					cy.add( kegg_dis_drug );
				if(!$('#Pathway').hasClass("list-group-item-secondary"))
					cy.add( kegg_dis_path );
			break;
			case 'HPO':
				cy.add( removed_edges['HPO'] );
				if(!$('#Disease').hasClass("list-group-item-secondary")){
					cy.add( hpodis );
				}
			break;
			case 'Pathway':
				cy.add( removed_edges['Pathway'] );
				if(!$('#kegg_Disease').hasClass("list-group-item-secondary"))
					cy.add( kegg_dis_path );
			break;
			case 'kegg_Pathway':
				cy.add( removed_edges['kegg_path_prot'] );
			break;
			case 'Drug':
				cy.add( removed_edges['Drug'] );
				cy.add( removed_edges['drugChembl'] );
				cy.add( removed_edges['drugPrediction'] );
				if($('#kegg_Disease').hasClass("btn-primary"))
					cy.add( kegg_dis_drug );
			break;
			case 'Compound':
				cy.add( removed_edges['Chembl'] );
				cy.add( removed_edges['compoundPrediction'] );
			break;
			case 'Prediction':
				cy.add( removed_edges['Prediction'] );
				cy.add( removed_edges['predictionChembl'] );
			break;
		}

		$(this).removeClass("list-group-item-secondary");
	}

	if($('#layout').val()=='CrossBarLayout'){
		$('#changeOrderOfCrossbar').trigger( "click" );
		return true;
	}else{
		var layout = cy.layout({
			name: $('#layout').val(),
			fit: 'viewport'
		});
		layout.run();
	}
});