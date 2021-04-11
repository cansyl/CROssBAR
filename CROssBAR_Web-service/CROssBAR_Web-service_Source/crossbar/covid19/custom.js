$('#add_remove_toggle').click(function() {
	if($('#component_list_container').hasClass('d-none'))
		$('#component_list_container').removeClass('d-none');
	else
		$('#component_list_container').addClass('d-none');
});

function nodeSize_up(el) {
	current_width = el.width();
	current_heigh = el.height();
	el.animate({
		style: {width: current_width*1.1,height:current_heigh*1.1}
	});
}
function nodeSize_down(el) {
	current_width = el.width();
	current_heigh = el.height();
	el.animate({
		style: {width: current_width/1.1,height:current_heigh/1.1}
	});
}
function fontSize_up(el) {
	var current_font_size = el.style('font-size');
	var res = current_font_size.substring(current_font_size.length - 2, current_font_size.length);
	if(res == 'px')
		current_font_size = current_font_size.slice(0,-2);
	current_font_size++;
	current_font_size = current_font_size + 'px';
	el.animate({
		style: {'font-size': current_font_size}
	});
}
function fontSize_down(el) {
	var current_font_size = el.style('font-size');
	var res = current_font_size.substring(current_font_size.length - 2, current_font_size.length);
	if(res == 'px')
		current_font_size = current_font_size.slice(0,-2);
	current_font_size--;
	current_font_size = current_font_size + 'px';
	el.animate({
		style: {'font-size': current_font_size}
	});
}

function removeAlingmentClasses(eles){
	eles.removeClass('left');
	eles.removeClass('right');
	eles.removeClass('top');
	eles.removeClass('bottom');
	eles.removeClass('center');
}

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

	switch(btn){
		case 'nodeSize_up':
			node_elements.forEach(nodeSize_up);
		break;
		case 'nodeSize_down':
			node_elements.forEach(nodeSize_down);	
		break;

		case 'fontSize_up':
			node_elements.forEach(fontSize_up);
		break;
		case 'fontSize_down':
			node_elements.forEach(fontSize_down);
		break;
		
		case 'left':
			removeAlingmentClasses(node_elements);
			node_elements.addClass('left');
		break;
		case 'right':
			removeAlingmentClasses(node_elements);
			node_elements.addClass('right');
		break;
		case 'top':
			removeAlingmentClasses(node_elements);
			node_elements.addClass('top');
		break;
		case 'bottom':
			removeAlingmentClasses(node_elements);
			node_elements.addClass('bottom');
		break;
		case 'center':
			removeAlingmentClasses(node_elements);
			node_elements.addClass('center');
		break;
	}

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