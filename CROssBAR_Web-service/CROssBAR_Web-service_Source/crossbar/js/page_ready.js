$( function() {
	$('#network').height( screen.height - $('#top_menu').height() - 130 );
    $( "#orderingComponents" ).sortable();
    $( "#orderingComponents" ).disableSelection();
    $( "#orderingComponents_more" ).sortable();
    $( "#orderingComponents_more" ).disableSelection();
});