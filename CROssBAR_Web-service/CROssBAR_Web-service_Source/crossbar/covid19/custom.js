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