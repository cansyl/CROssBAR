// for removing/adding nodes and edges
var removed_eles = {};

$('.removeNodes').click(function(){
	if(!$(this).hasClass("list-group-item-secondary")){
		//removed_nodes[$(this).attr('id')] = cy.elements('node[Node_Type="'+$(this).attr('id')+'"]');		
		removed_eles[$(this).attr('id')] = cy.remove( cy.elements('node[Node_Type="'+$(this).attr('id')+'"]') );
		$(this).addClass("list-group-item-secondary");
	}else{
		cy.add( removed_eles[$(this).attr('id')] );
		$(this).removeClass("list-group-item-secondary");
	}
});